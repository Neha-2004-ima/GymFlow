<?php
// ════════════════════════════════════════════════════════════════════════════
//  plans_add.php  –  Add a new Membership Plan (Admin)
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

// ── Initialise state ──────────────────────────────────────────────────────────
$errors    = [];
$form_data = ['title' => '', 'subtitle' => '', 'price' => '', 'features' => '', 'is_popular' => 0];

// ── Handle POST ───────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title      = trim($_POST['title']    ?? '');
    $subtitle   = trim($_POST['subtitle'] ?? '');
    $price      = trim($_POST['price']    ?? '');
    $features   = trim($_POST['features'] ?? '');
    $is_popular = isset($_POST['is_popular']) ? 1 : 0;

    $form_data = compact('title', 'subtitle', 'price', 'features', 'is_popular');

    // ── Server-side validation ────────────────────────────────────────────────
    if ($title === '') {
        $errors['title'] = 'Plan title is required.';
    } elseif (strlen($title) < 2) {
        $errors['title'] = 'Title must be at least 2 characters.';
    } elseif (strlen($title) > 100) {
        $errors['title'] = 'Title must not exceed 100 characters.';
    }

    if (strlen($subtitle) > 150) {
        $errors['subtitle'] = 'Subtitle must not exceed 150 characters.';
    }

    if ($price === '') {
        $errors['price'] = 'Price is required.';
    } elseif (!is_numeric($price) || (float)$price < 0) {
        $errors['price'] = 'Price must be a positive number.';
    } elseif ((float)$price > 1000000) {
        $errors['price'] = 'Price seems too high. Please enter a valid amount.';
    } elseif (!preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
        $errors['price'] = 'Price can have at most 2 decimal places.';
    }

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

    // ── Insert if valid ───────────────────────────────────────────────────────
    if (empty($errors)) {
        $sql  = "INSERT INTO membership_plans (title, subtitle, price, features, is_popular) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdi", $title, $subtitle, $price, $features, $is_popular);

        if ($stmt->execute()) {
            header("Location: plans_manage.php?created=1");
            exit();
        } else {
            $errors['general'] = 'Database error: ' . $stmt->error;
        }
    }
}

// ── Render page ───────────────────────────────────────────────────────────────
$pageTitle    = "Add Membership Plan - Admin";
$currentPage  = "manage_plans";
$extraStyles  = ["admin-dashboard.css"];
$extraScripts = ["plans-manage.js"];
include 'includes/header.php';
?>

<style>
/* ── Validation styles ────────────────────────────────────────────────────── */
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
.flash-error {
    background: rgba(255, 68, 68, 0.1);
    border: 1px solid rgba(255, 68, 68, 0.3);
    color: #ff6666;
}
.required-star { color: #ff4444; }
.char-counter { font-size: 0.75rem; color: #666; text-align: right; margin-top: 3px; }
.char-counter.near-limit { color: #ffaa00; }
.char-counter.at-limit   { color: #ff4444; }
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
    <h1 style="color: var(--neon-green); margin-bottom: 8px;">➕ Add New Membership Plan</h1>
    <p style="color: #888; margin-bottom: 30px;">Fill in the details to create a new plan.</p>

    <?php if (isset($errors['general'])): ?>
        <div class="form-flash flash-error">⚠️ <?php echo htmlspecialchars($errors['general']); ?></div>
    <?php elseif (!empty($errors)): ?>
        <div class="form-flash flash-error">⚠️ Please fix the highlighted errors below before submitting.</div>
    <?php endif; ?>

    <div class="card" style="background: var(--card-bg); padding: 30px; border-radius: 12px; border: 1px solid rgba(0,255,0,0.1); margin-bottom: 50px;">
        <form method="POST" id="planForm" novalidate>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 22px;">

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
                    <div class="char-counter" id="title_counter">0 / 100</div>
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
                    <div class="char-counter" id="subtitle_counter">0 / 150</div>
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
                        rows="4"
                        placeholder="e.g. Unlimited access, Pool, Personal trainer"
                        style="height: 100px; resize: vertical;"
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
                    <label for="pm_popular" style="margin: 0; cursor:pointer;">⭐ Mark as Popular Plan</label>
                </div>

            </div><!-- /grid -->

            <div style="margin-top: 30px; display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                <button type="submit" class="btn" style="padding: 12px 30px;">✅ Create Plan</button>
                <a href="plans_manage.php" style="color: #666; text-decoration: none;">✕ Cancel</a>
            </div>
        </form>
    </div>

    <div style="margin-top: 10px;">
        <a href="dashboard.php" class="btn-login" style="text-decoration:none; padding:12px 25px;">← Return to Dashboard</a>
    </div>
</div>

<!-- Character + feature counters (inline) -->
<script>
(function() {
    function initCounter(inputId, counterId, max) {
        var input   = document.getElementById(inputId);
        var counter = document.getElementById(counterId);
        if (!input || !counter) return;
        function update() {
            var len = input.value.length;
            counter.textContent = len + ' / ' + max;
            counter.className   = 'char-counter';
            if (len >= max)             counter.classList.add('at-limit');
            else if (len >= max * 0.85) counter.classList.add('near-limit');
        }
        input.addEventListener('input', update);
        update();
    }
    initCounter('pm_title',    'title_counter',    100);
    initCounter('pm_subtitle', 'subtitle_counter', 150);

    var featEl  = document.getElementById('pm_features');
    var featCnt = document.getElementById('feat_count');
    if (featEl && featCnt) {
        function updateFeat() {
            var list = featEl.value.split(',').map(function(f){ return f.trim(); }).filter(function(f){ return f!==''; });
            var n    = list.length;
            featCnt.textContent  = n + ' feature' + (n !== 1 ? 's' : '') + ' (max 20)';
            featCnt.style.color  = n > 20 ? '#ff4444' : (n >= 18 ? '#ffaa00' : '#666');
        }
        featEl.addEventListener('input', updateFeat);
        updateFeat();
    }
})();
</script>

<?php include 'includes/footer.php'; ?>
