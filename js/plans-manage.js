document.addEventListener('DOMContentLoaded', function () {

    // ── Grab form and fields ───────────────────────────────────────────────────
    var form = document.getElementById('planForm');
    var titleInput = document.getElementById('pm_title');
    var subtitleInput = document.getElementById('pm_subtitle');
    var priceInput = document.getElementById('pm_price');
    var featuresInput = document.getElementById('pm_features');

    if (!form) {
        console.warn('plans-manage.js: #planForm not found on this page.');
        return;
    }

    // ── Styling helpers ────────────────────────────────────────────────────────
    function showError(input, message) {
        clearError(input);
        input.style.borderColor = '#ff4444';
        input.style.boxShadow = '0 0 0 2px rgba(255,68,68,0.25)';

        var err = document.createElement('span');
        err.className = 'js-error-msg';
        err.style.cssText = 'display:block;color:#ff4444;font-size:0.78rem;margin-top:5px;font-weight:500;';
        err.textContent = message;
        input.parentNode.appendChild(err);
    }

    function clearError(input) {
        input.style.borderColor = '';
        input.style.boxShadow = '';
        var prev = input.parentNode.querySelector('.js-error-msg');
        if (prev) prev.remove();
    }

    function clearAll() {
        [titleInput, subtitleInput, priceInput, featuresInput].forEach(function (inp) {
            if (inp) clearError(inp);
        });
    }

    // ── Individual validators ──────────────────────────────────────────────────
    function checkTitle() {
        if (!titleInput) return true;
        var val = titleInput.value.trim();
        if (val === '') {
            showError(titleInput, 'Plan title is required.');
            return false;
        }
        if (val.length < 2) {
            showError(titleInput, 'Title must be at least 2 characters.');
            return false;
        }
        if (val.length > 100) {
            showError(titleInput, 'Title must not exceed 100 characters.');
            return false;
        }
        clearError(titleInput);
        return true;
    }

    function checkSubtitle() {
        if (!subtitleInput) return true;
        var val = subtitleInput.value.trim();
        if (val.length > 150) {
            showError(subtitleInput, 'Subtitle must not exceed 150 characters.');
            return false;
        }
        clearError(subtitleInput);
        return true;
    }

    function checkPrice() {
        if (!priceInput) return true;
        var val = priceInput.value.trim();
        if (val === '') {
            showError(priceInput, 'Price is required.');
            return false;
        }
        var num = parseFloat(val);
        if (isNaN(num) || num < 0) {
            showError(priceInput, 'Price must be a positive number.');
            return false;
        }
        if (num > 1000000) {
            showError(priceInput, 'Price seems too high. Please enter a valid amount.');
            return false;
        }
        clearError(priceInput);
        return true;
    }

    function checkFeatures() {
        if (!featuresInput) return true;
        var val = featuresInput.value.trim();
        if (val === '') {
            showError(featuresInput, 'At least one feature is required (comma-separated).');
            return false;
        }
        var list = val.split(',').map(function (f) { return f.trim(); }).filter(function (f) { return f !== ''; });
        if (list.length === 0) {
            showError(featuresInput, 'Please enter at least one valid feature.');
            return false;
        }
        if (list.length > 20) {
            showError(featuresInput, 'You can list a maximum of 20 features.');
            return false;
        }
        clearError(featuresInput);
        return true;
    }

    // ── Live blur validation ───────────────────────────────────────────────────
    if (titleInput) {
        titleInput.addEventListener('blur', checkTitle);
        titleInput.addEventListener('input', function () { clearError(titleInput); });
    }
    if (subtitleInput) {
        subtitleInput.addEventListener('blur', checkSubtitle);
        subtitleInput.addEventListener('input', function () { clearError(subtitleInput); });
    }
    if (priceInput) {
        priceInput.addEventListener('blur', checkPrice);
        priceInput.addEventListener('input', function () { clearError(priceInput); });
    }
    if (featuresInput) {
        featuresInput.addEventListener('blur', checkFeatures);
        featuresInput.addEventListener('input', function () { clearError(featuresInput); });
    }

    // ── Submit handler ─────────────────────────────────────────────────────────
    form.addEventListener('submit', function (e) {
        clearAll();

        var t = checkTitle();
        var s = checkSubtitle();
        var p = checkPrice();
        var f = checkFeatures();

        if (!t || !s || !p || !f) {
            e.preventDefault();
            var firstErr = form.querySelector('[style*="border-color: rgb(255"]');
            if (!firstErr) firstErr = form.querySelector('.js-error-msg');
            if (firstErr) {
                firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // ── Delete confirmation ────────────────────────────────────────────────────
    document.querySelectorAll('.delete-plan-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            var name = this.getAttribute('data-plan-name') || 'this plan';
            if (!confirm('Are you sure you want to permanently delete "' + name + '"?\nThis action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    console.log('plans-manage.js loaded OK. Form:', form.id);
});
