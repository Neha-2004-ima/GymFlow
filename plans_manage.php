<?php
// ════════════════════════════════════════════════════════════════════════════
//  plans_manage.php  –  Manage Membership Plans (Admin)
//  ALL PHP logic runs first, then HTML is rendered.
// ════════════════════════════════════════════════════════════════════════════
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'includes/Database.php';

// Auth guard
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ── 1. Initialise state ───────────────────────────────────────────────────────
$errors      = [];
$success_msg = '';
$edit        = false;
$edit_data   = ['id' => '', 'title' => '', 'subtitle' => '', 'price' => '', 'features' => '', 'is_popular' => 0];
$form_data   = $edit_data;

// ── 2. Handle DELETE ──────────────────────────────────────────────────────────
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $del_id = (int) $_GET['delete'];
    $stmt   = $conn->prepare("DELETE FROM membership_plans WHERE id = ?");
    $stmt->bind_param("i", $del_id);
    if ($stmt->execute()) {
        header("Location: plans_manage.php?deleted=1");
        exit();
    } else {
        $errors['general'] = 'Error deleting plan: ' . $stmt->error;
    }
}

// ── 3. Load plan for editing ──────────────────────────────────────────────────
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = (int) $_GET['edit'];
    $res     = $conn->prepare("SELECT * FROM membership_plans WHERE id = ?");
    $res->bind_param("i", $edit_id);
    $res->execute();
    $row = $res->get_result()->fetch_assoc();
    if ($row) {
        $edit      = true;
        $edit_data = $row;
        $form_data = $row;
    } else {
        $errors['general'] = 'Plan not found.';
    }
}

// ── 4. Handle POST (Add or Update) ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title      = trim($_POST['title']    ?? '');
    $subtitle   = trim($_POST['subtitle'] ?? '');
    $price      = trim($_POST['price']    ?? '');
    $features   = trim($_POST['features'] ?? '');
    $is_popular = isset($_POST['is_popular']) ? 1 : 0;
    $post_id    = isset($_POST['id']) && is_numeric($_POST['id']) ? (int)$_POST['id'] : 0;
    $is_edit    = ($post_id > 0);

    // Keep values in form on error
    $form_data = [
        'id'         => $post_id,
        'title'      => $title,
        'subtitle'   => $subtitle,
        'price'      => $price,
        'features'   => $features,
        'is_popular' => $is_popular,
    ];
    if ($is_edit) { $edit = true; }

    // ── Validation ────────────────────────────────────────────────────────────
    // Title
    if ($title === '') {
        $errors['title'] = 'Plan title is required.';
    } elseif (strlen($title) < 2) {
        $errors['title'] = 'Title must be at least 2 characters.';
    } elseif (strlen($title) > 100) {
        $errors['title'] = 'Title must not exceed 100 characters.';
    }

    // Subtitle (optional)
    if (strlen($subtitle) > 150) {
        $errors['subtitle'] = 'Subtitle must not exceed 150 characters.';
    }

    // Price
    if ($price === '') {
        $errors['price'] = 'Price is required.';
    } elseif (!is_numeric($price) || (float)$price < 0) {
        $errors['price'] = 'Price must be a positive number.';
    } elseif ((float)$price > 1000000) {
        $errors['price'] = 'Price seems too high. Please enter a valid amount.';
    } elseif (!preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
        $errors['price'] = 'Price can have at most 2 decimal places.';
    }

    // Features
    if ($features === '') {
        $errors['features'] = 'At least one feature is required (comma-separated).';
    } else {
        $feat_list = array_filter(array_map('trim', explode(',', $features)));
        if (count($feat_list) === 0) {
            $errors['features'] = 'Please enter at least one valid feature.';
        } elseif (count($feat_list) > 20) {
            $errors['features'] = 'You can list a maximum of 20 features.';
        }
    }

    // ── Persist to DB if valid ────────────────────────────────────────────────
    if (empty($errors)) {
        if ($is_edit) {
            $sql  = "UPDATE membership_plans SET title=?, subtitle=?, price=?, features=?, is_popular=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssdii", $title, $subtitle, $price, $features, $is_popular, $post_id);
        } else {
            $sql  = "INSERT INTO membership_plans (title, subtitle, price, features, is_popular) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssdi", $title, $subtitle, $price, $features, $is_popular);
        }

        if ($stmt->execute()) {
            $action = $is_edit ? 'updated' : 'created';
            header("Location: plans_manage.php?{$action}=1");
            exit();
        } else {
            $errors['general'] = 'Database error: ' . $stmt->error;
        }
    }
}

// ── 5. Flash messages from redirects ─────────────────────────────────────────
if (isset($_GET['created'])) $success_msg = '✅ Plan successfully created!';
if (isset($_GET['updated'])) $success_msg = '✅ Plan successfully updated!';
if (isset($_GET['deleted'])) $success_msg = '🗑️ Plan successfully deleted!';

// ── 6. Render page ────────────────────────────────────────────────────────────
$pageTitle    = "Manage Membership Plans - Admin";
$currentPage  = "manage_plans";
$extraStyles  = ["admin-dashboard.css"];
$extraScripts = ["plans-manage.js"];
include 'includes/header.php';
?>

<style>
/* ── Validation styles ──────────────────────────────────────────────────────── */
.input-error {
    border-color: #ff4444 !important;
    box-shadow: 0 0 0 2px rgba(255, 68, 68, 0.25) !important;
}
.field-error-static {
    display: block;
    color: #ff4444;
    font-size: 0.78rem;
    margin-top: 5px;
    font-weight: 500;
}
.form-flash {
    padding: 12px 18px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}
.flash-success {
    background: rgba(0, 255, 0, 0.08);
    border: 1px solid rgba(0, 255, 0, 0.3);
    color: #00dd00;
}
.flash-error {
    background: rgba(255, 68, 68, 0.1);
    border: 1px solid rgba(255, 68, 68, 0.3);
    color: #ff6666;
}
.required-star { color: #ff4444; }
.plan-input {
    width: 100%;
    padding: 12px;
    background: #111;
    border: 1px solid #333;
    color: white;
    border-radius: 5px;
    font-size: 0.95rem;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box;
}
.plan-input:focus {
    outline: none;
    border-color: rgba(0, 255, 0, 0.5);
    box-shadow: 0 0 0 2px rgba(0, 255, 0, 0.1);
}
</style>

<div class="container" style="padding: 120px 5% 50px;">
    <h1 style="color: var(--neon-green); margin-bottom: 30px;">
        <?php echo $edit ? '✏️ Update Plan' : '➕ Add New Plan'; ?>
    </h1>

    <!-- Flash messages -->
    <?php if ($success_msg): ?>
        <div class="form-flash flash-success"><?php echo htmlspecialchars($success_msg); ?></div>
    <?php endif; ?>

    <?php if (isset($errors['general'])): ?>
        <div class="form-flash flash-error">⚠️ <?php echo htmlspecialchars($errors['general']); ?></div>
    <?php endif; ?>

    <?php if (!empty($errors) && !isset($errors['general'])): ?>
        <div class="form-flash flash-error">⚠️ Please fix the highlighted errors below before submitting.</div>
    <?php endif; ?>

    <!-- ── Add / Edit Form ─────────────────────────────────────────────────── -->
    <div class="card" style="background: var(--card-bg); padding: 30px; border-radius: 12px; border: 1px solid rgba(0,255,0,0.1); margin-bottom: 50px;">
        <form method="POST" id="planForm" novalidate>
            <?php if ($edit): ?>
                <input type="hidden" name="id" value="<?php echo (int)$form_data['id']; ?>">
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 20px;">

                <!-- Plan Title -->
                <div class="input-group">
                    <label for="pm_title">Plan Title <span class="required-star">*</span></label>
                    <input
                        type="text"
                        id="pm_title"
                        name="title"
                        class="plan-input<?php echo isset($errors['title']) ? ' input-error' : ''; ?>"
                        value="<?php echo htmlspecialchars($form_data['title']); ?>"
                        maxlength="100"
                        placeholder="e.g. Premium Monthly"
                    >
                    <?php if (isset($errors['title'])): ?>
                        <span class="field-error-static"><?php echo htmlspecialchars($errors['title']); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Subtitle -->
                <div class="input-group">
                    <label for="pm_subtitle">Subtitle <small style="color:#888;">(optional)</small></label>
                    <input
                        type="text"
                        id="pm_subtitle"
                        name="subtitle"
                        class="plan-input<?php echo isset($errors['subtitle']) ? ' input-error' : ''; ?>"
                        value="<?php echo htmlspecialchars($form_data['subtitle']); ?>"
                        maxlength="150"
                        placeholder="e.g. Best value for enthusiasts"
                    >
                    <?php if (isset($errors['subtitle'])): ?>
                        <span class="field-error-static"><?php echo htmlspecialchars($errors['subtitle']); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Price -->
                <div class="input-group">
                    <label for="pm_price">Price (LKR) <span class="required-star">*</span></label>
                    <input
                        type="number"
                        id="pm_price"
                        name="price"
                        class="plan-input<?php echo isset($errors['price']) ? ' input-error' : ''; ?>"
                        value="<?php echo htmlspecialchars($form_data['price']); ?>"
                        step="0.01"
                        min="0"
                        max="1000000"
                        placeholder="e.g. 2500.00"
                    >
                    <?php if (isset($errors['price'])): ?>
                        <span class="field-error-static"><?php echo htmlspecialchars($errors['price']); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Features -->
                <div class="input-group" style="grid-column: span 2;">
                    <label for="pm_features">
                        Features <span class="required-star">*</span>
                        <small style="color:#888;">(comma-separated, max 20 items)</small>
                    </label>
                    <textarea
                        id="pm_features"
                        name="features"
                        class="plan-input<?php echo isset($errors['features']) ? ' input-error' : ''; ?>"
                        rows="3"
                        placeholder="e.g. Unlimited access, Pool, Personal trainer"
                        style="height: 85px; resize: vertical;"
                    ><?php echo htmlspecialchars($form_data['features']); ?></textarea>
                    <?php if (isset($errors['features'])): ?>
                        <span class="field-error-static"><?php echo htmlspecialchars($errors['features']); ?></span>
                    <?php endif; ?>
                    <div id="feat_count" style="font-size:0.75rem;color:#666;margin-top:3px;">0 features</div>
                </div>

                <!-- Is Popular -->
                <div class="input-group" style="display: flex; align-items: center; gap: 10px;">
                    <input
                        type="checkbox"
                        id="pm_popular"
                        name="is_popular"
                        style="width:18px;height:18px;cursor:pointer;"
                        <?php echo $form_data['is_popular'] ? 'checked' : ''; ?>
                    >
                    <label for="pm_popular" style="margin: 0; cursor:pointer;">⭐ Mark as Popular</label>
                </div>

            </div><!-- /grid -->

            <div style="margin-top: 25px; display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                <button type="submit" class="btn"><?php echo $edit ? '💾 Update Plan' : '✅ Create Plan'; ?></button>
                <?php if ($edit): ?>
                    <a href="plans_manage.php" style="color: #666; text-decoration: none;">✕ Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- ── Plans table ────────────────────────────────────────────────────── -->
    <h2 style="color: white; margin-bottom: 20px;">Current Membership Plans</h2>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; background: var(--card-bg); border-radius: 12px; overflow: hidden;">
            <thead style="background: rgba(0,255,0,0.1);">
                <tr>
                    <th style="padding: 15px; text-align: left;">Title</th>
                    <th style="padding: 15px; text-align: left;">Subtitle</th>
                    <th style="padding: 15px; text-align: left;">Price</th>
                    <th style="padding: 15px; text-align: left;">Popular</th>
                    <th style="padding: 15px; text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $plans = $conn->query("SELECT * FROM membership_plans ORDER BY price ASC");
                if ($plans && $plans->num_rows > 0):
                    while ($p = $plans->fetch_assoc()):
                ?>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 15px; font-weight: bold; color: var(--neon-green);"><?php echo htmlspecialchars($p['title']); ?></td>
                        <td style="padding: 15px; color: #aaa;"><?php echo htmlspecialchars($p['subtitle']); ?></td>
                        <td style="padding: 15px;">LKR <?php echo number_format((float)$p['price'], 2); ?></td>
                        <td style="padding: 15px;">
                            <?php echo $p['is_popular']
                                ? '<span style="color:#00ff00;">⭐ Yes</span>'
                                : '<span style="color:#666;">No</span>';
                            ?>
                        </td>
                        <td style="padding: 15px;">
                            <a href="?edit=<?php echo (int)$p['id']; ?>" style="color: #00ff00; text-decoration: none; margin-right: 15px;">✏️ Update</a>
                            <a
                                href="?delete=<?php echo (int)$p['id']; ?>"
                                class="delete-plan-btn"
                                data-plan-name="<?php echo htmlspecialchars($p['title']); ?>"
                                style="color: #ff3333; text-decoration: none;"
                            >🗑️ Delete</a>
                        </td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="5" style="padding: 20px; text-align: center; color: #666;">No membership plans found. Add one above.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 30px;">
        <a href="dashboard.php" class="btn-login" style="text-decoration: none; padding: 12px 25px;">← Return to Dashboard</a>
    </div>
</div>

<!-- Feature counter script (inline, no dependency on external JS) -->
<script>
(function() {
    var featuresInput = document.getElementById('pm_features');
    var featCount     = document.getElementById('feat_count');
    if (!featuresInput || !featCount) return;

    function updateCount() {
        var list = featuresInput.value.split(',').map(function(f){ return f.trim(); }).filter(function(f){ return f !== ''; });
        var n = list.length;
        featCount.textContent = n + ' feature' + (n !== 1 ? 's' : '') + ' (max 20)';
        featCount.style.color = n > 20 ? '#ff4444' : (n >= 18 ? '#ffaa00' : '#666');
    }
    featuresInput.addEventListener('input', updateCount);
    updateCount();
})();
</script>

<?php include 'includes/footer.php'; ?>
