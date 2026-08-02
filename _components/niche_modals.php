<?php
/**
 * Loading overlay + success modal for niche pages.
 * Rendered at end of <body> so fixed positioning stacks above page content.
 * Expects: $nichePage, $common, $downloadUrl, $platform
 */
if (empty($nichePage)) {
    return;
}
?>

<!-- Mock loading overlay -->
<div id="niche-loading-overlay"
     class="fixed inset-0 z-[9999] hidden items-center justify-center p-4 bg-gray-900/70 backdrop-blur-sm isolate"
     aria-hidden="true">
    <div class="relative z-10 bg-white rounded-3xl shadow-2xl border-2 border-gray-100 max-w-md w-full p-8"
         role="dialog" aria-labelledby="niche-loading-title" aria-modal="true">
        <div class="flex items-center gap-4 mb-6">
            <img src="<?php echo htmlspecialchars($common['appIcon']); ?>" alt="" class="w-12 h-12 rounded-xl" width="48" height="48">
            <div class="min-w-0">
                <h2 id="niche-loading-title" class="text-lg font-black font-heading text-gray-900">Building your preview</h2>
                <p class="text-sm text-gray-500 font-medium truncate" id="niche-loading-url"></p>
            </div>
        </div>
        <div class="h-2 bg-gray-100 rounded-full overflow-hidden mb-6">
            <div id="niche-loading-bar" class="h-full bg-brand-500 rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
        </div>
        <ul id="niche-loading-steps" class="space-y-3 text-sm font-medium text-gray-600"></ul>
    </div>
</div>

<!-- Success modal -->
<div id="niche-success-modal"
     class="fixed inset-0 z-[10000] hidden items-center justify-center p-4 bg-gray-900/75 backdrop-blur-sm isolate"
     aria-hidden="true">
    <div class="relative z-10 bg-white rounded-3xl shadow-2xl border-2 border-gray-100 max-w-md w-full p-8 text-center"
         role="dialog" aria-labelledby="niche-success-title" aria-modal="true">
        <button type="button" id="niche-modal-close" class="absolute top-4 right-4 p-2 text-gray-400 hover:text-gray-700 rounded-lg" aria-label="Close">
            <span class="material-icons">close</span>
        </button>
        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-50 rounded-full mb-6">
            <span class="material-icons text-4xl text-green-500">check_circle</span>
        </div>
        <h2 id="niche-success-title" class="text-2xl font-black font-heading text-gray-900 mb-3">Your <?php echo $platform; ?> app is ready to finalize</h2>
        <p class="text-gray-600 font-medium mb-8 leading-relaxed">Download <strong><?php echo htmlspecialchars($common['appName']); ?></strong> on Android to paste your URL, customize the shell, and get your signed APK and AAB.</p>
        <a href="<?php echo htmlspecialchars($downloadUrl); ?>"
            class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-8 py-4 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-lg shadow-float hover:scale-[1.02] transition-all mb-4">
            <span class="material-icons">download</span>
            Download the app
        </a>
        <?php if (!empty($common['googlePlayUrl'])): ?>
            <a href="<?php echo htmlspecialchars($downloadUrl); ?>" class="block w-44 mx-auto hover:scale-105 transition-transform">
                <img src="/assets/google-play-download.svg" alt="Get it on Google Play" class="w-full h-auto" width="192" height="57">
            </a>
        <?php endif; ?>
    </div>
</div>

<script>
(function () {
    var form = document.getElementById('niche-convert-form');
    var urlInput = document.getElementById('niche-site-url');
    var errorEl = document.getElementById('niche-url-error');
    var overlay = document.getElementById('niche-loading-overlay');
    var modal = document.getElementById('niche-success-modal');
    var loadingBar = document.getElementById('niche-loading-bar');
    var loadingSteps = document.getElementById('niche-loading-steps');
    var loadingUrl = document.getElementById('niche-loading-url');
    var closeBtn = document.getElementById('niche-modal-close');
    var messages = <?php echo json_encode($nichePage['loadingMessages'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

    if (!form || !overlay || !modal) {
        return;
    }

    function isValidUrl(value) {
        try {
            var u = new URL(value.trim());
            return u.protocol === 'http:' || u.protocol === 'https:';
        } catch (e) {
            return false;
        }
    }

    function showOverlay() {
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function hideOverlay() {
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
        overlay.setAttribute('aria-hidden', 'true');
    }

    function showModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function hideModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    function runMockBuild(url) {
        showOverlay();
        loadingUrl.textContent = url;
        loadingSteps.innerHTML = '';
        loadingBar.style.width = '0%';

        var stepIndex = 0;
        var total = messages.length;

        function addStep(text, done) {
            var li = document.createElement('li');
            li.className = 'flex items-center gap-2 ' + (done ? 'text-green-600' : 'text-gray-400');
            li.innerHTML = '<span class="material-icons text-lg">' + (done ? 'check_circle' : 'hourglass_empty') + '</span><span>' + text + '</span>';
            loadingSteps.appendChild(li);
            return li;
        }

        function tick() {
            if (stepIndex > 0) {
                var prev = loadingSteps.children[stepIndex - 1];
                if (prev) {
                    prev.className = 'flex items-center gap-2 text-green-600';
                    prev.querySelector('.material-icons').textContent = 'check_circle';
                }
            }
            if (stepIndex < total) {
                addStep(messages[stepIndex], false);
                loadingBar.style.width = Math.round(((stepIndex + 1) / total) * 100) + '%';
                stepIndex++;
                setTimeout(tick, 900 + Math.random() * 400);
            } else {
                loadingBar.style.width = '100%';
                setTimeout(function () {
                    hideOverlay();
                    showModal();
                }, 500);
            }
        }

        tick();
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var url = urlInput.value.trim();
        if (!isValidUrl(url)) {
            errorEl.textContent = 'Enter a valid URL starting with https://';
            errorEl.classList.remove('hidden');
            urlInput.focus();
            return;
        }
        errorEl.classList.add('hidden');
        runMockBuild(url);
    });

    closeBtn.addEventListener('click', hideModal);
    modal.addEventListener('click', function (e) {
        if (e.target === modal) hideModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            hideOverlay();
            hideModal();
        }
    });
})();
</script>
