<?php
require_once __DIR__ . '/../../../config.php';

$pageTitle = 'Extract Upload Certificate PEM from JKS Keystore';
$pageDescription = 'Free tool to generate upload_certificate.pem from your Android .jks keystore without keytool. Export the upload certificate for Google Play App Signing or reset your upload key in Play Console.';
$canonicalUrl = 'https://webinto.app/tools/jks/upload-certificate';
$uploadCertificateApiUrl = rtrim($apiBaseUrl, '/') . '/key/get/upload-certificate';
$maxKeyBytes = 102400;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/../../../_components/meta.php'; ?>
    <style>
        .cert-spinner {
            border: 3px solid #dbeafe;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: cert-spin 0.75s linear infinite;
        }
        .cert-spinner-lg {
            width: 2.5rem;
            height: 2.5rem;
        }
        .cert-spinner-sm {
            width: 1.5rem;
            height: 1.5rem;
            border-width: 2px;
        }
        @keyframes cert-spin {
            to { transform: rotate(360deg); }
        }
    </style>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebApplication",
        "name": "JKS Upload Certificate Extractor",
        "url": "<?php echo htmlspecialchars($canonicalUrl); ?>",
        "description": <?php echo json_encode($pageDescription, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>,
        "applicationCategory": "DeveloperApplication",
        "operatingSystem": "Web",
        "offers": { "@type": "Offer", "price": "0", "priceCurrency": "USD" }
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "What is the difference between upload key and app signing key?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "The upload key signs bundles you upload to Play. Google re-signs with the app signing key before users install. This tool exports the certificate for your upload key only."
                }
            },
            {
                "@type": "Question",
                "name": "Can I use this PEM to reset my upload key in Google Play Console?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes. Google Play's upload key reset request accepts the PEM exported from your new upload keystore. This tool generates that file from your .jks file."
                }
            },
            {
                "@type": "Question",
                "name": "Do I need Android Studio or the JDK to generate upload_certificate.pem?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "No. If you have the .jks file and passwords, this browser tool returns upload_certificate.pem without installing Java or using keytool."
                }
            }
        ]
    }
    </script>
</head>
<body class="min-h-screen font-body bg-[#fafafa]">
    <?php include __DIR__ . '/../../../_components/header.php'; ?>

    <section class="pt-28 pb-20 relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <svg class="absolute -top-24 -right-24 w-80 h-80 text-brand-100 opacity-60" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path fill="currentColor" d="M44.7,-76.4C58.8,-69.2,71.8,-59.1,81.3,-46.3C90.8,-33.5,96.8,-18,96.5,-2.9C96.2,12.2,89.6,26.9,80.1,39.6C70.6,52.3,58.2,63,44.2,71.4C30.2,79.8,15.1,85.9,0.3,85.4C-14.5,84.9,-29,77.8,-42.6,69.1C-56.2,60.4,-68.9,50.1,-77.4,37.3C-85.9,24.5,-90.2,9.2,-88.4,-5.4C-86.6,-20,-78.7,-33.9,-68.6,-45.1C-58.5,-56.3,-46.3,-64.8,-33.4,-72.5C-20.5,-80.2,-7.4,-87.1,4.4,-94.9C16.2,-102.7,30.6,-83.6,44.7,-76.4Z" transform="translate(100 100)" />
            </svg>
        </div>

        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block py-1 px-3 rounded-full bg-brand-100 text-brand-700 font-bold text-sm tracking-wider uppercase mb-4">Free tool</span>
                <h1 class="text-4xl md:text-5xl font-black font-heading text-gray-900 mb-4 tracking-tight">Extract upload certificate from JKS</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto font-medium">
                    Generate <code class="text-sm bg-gray-100 px-1.5 py-0.5 rounded">upload_certificate.pem</code> from your Android keystore
                    (<code class="text-sm bg-gray-100 px-1.5 py-0.5 rounded">.jks</code>) — no <code class="text-sm bg-gray-100 px-1.5 py-0.5 rounded">keytool</code> or terminal required.
                </p>
            </div>

            <div class="bg-white rounded-[2rem] border-2 border-gray-100 shadow-soft p-8 md:p-10">
                <form id="uploadCertForm" class="space-y-6" novalidate>
                    <div>
                        <label for="keyFile" class="block text-sm font-bold text-gray-700 mb-2">Keystore file (.jks)</label>
                        <input type="file" id="keyFile" name="key" accept=".jks,application/octet-stream"
                               required
                               class="block w-full text-sm text-gray-700 file:mr-4 file:py-3 file:px-4 file:rounded-2xl file:border-0 file:text-sm file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 file:cursor-pointer border-2 border-dashed border-gray-200 rounded-2xl p-4 bg-gray-50/50">
                        <p class="mt-2 text-sm text-gray-500 font-medium">Maximum file size: 100 KB. Your keystore is processed in memory and not stored.</p>
                    </div>

                    <div>
                        <label for="keyStorePassword" class="block text-sm font-bold text-gray-700 mb-2">Keystore password</label>
                        <input type="password" id="keyStorePassword" name="keyStorePassword" required autocomplete="off"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition-colors font-medium"
                               placeholder="Keystore password">
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="keyPasswordDifferent" name="keyPasswordDifferent"
                               class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-400">
                        <label for="keyPasswordDifferent" class="text-sm font-medium text-gray-700 cursor-pointer select-none">
                            Key password is different
                        </label>
                    </div>

                    <div id="keyPasswordField" class="hidden">
                        <label for="keyPassword" class="block text-sm font-bold text-gray-700 mb-2">Key password</label>
                        <input type="password" id="keyPassword" name="keyPassword" autocomplete="off"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition-colors font-medium"
                               placeholder="Key entry password">
                    </div>

                    <div id="formError" class="hidden p-4 rounded-2xl border-2 font-medium bg-red-50 text-red-900 border-red-200" role="alert"></div>

                    <button type="submit" id="submitBtn"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-brand-500 text-white font-bold py-4 px-8 rounded-2xl hover:bg-brand-600 hover:shadow-float hover:-translate-y-0.5 transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                        <span class="material-icons" id="submitIcon">vpn_key</span>
                        <span id="submitLabel">Extract certificate</span>
                    </button>
                </form>
            </div>

            <div class="mt-10 space-y-8 content-auto">
                <article class="rounded-3xl border-2 border-gray-100 bg-white p-6 md:p-8 shadow-soft">
                    <h2 class="text-2xl font-black font-heading text-gray-900 mb-4">What is an upload certificate?</h2>
                    <div class="space-y-4 text-gray-600 font-medium leading-relaxed">
                        <p>
                            When you publish on Google Play with <strong class="text-gray-800">Play App Signing</strong>, Google holds your app signing key and you sign releases with a separate <strong class="text-gray-800">upload key</strong>. That upload key lives in a Java keystore file (<code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">.jks</code> or <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">.keystore</code>).
                        </p>
                        <p>
                            The <strong class="text-gray-800">upload certificate</strong> is the public half of that key, exported as a PEM file (<code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">upload_certificate.pem</code>). Google Play uses it to verify that APKs and AABs you upload were signed with your upload key — without you ever sending the private key to Google.
                        </p>
                    </div>
                </article>

                <article class="rounded-3xl border-2 border-gray-100 bg-white p-6 md:p-8 shadow-soft">
                    <h2 class="text-2xl font-black font-heading text-gray-900 mb-4">When developers need this tool</h2>
                    <div class="space-y-4 text-gray-600 font-medium leading-relaxed">
                        <p>
                            Most indie and low-code developers have a <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">.jks</code> file from their app builder but have never run <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">keytool</code> or opened Android Studio’s signing UI. Play Console then asks for an upload certificate PEM — and the docs assume you know how to export one.
                        </p>
                        <p>Common situations where you need <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">upload_certificate.pem</code>:</p>
                        <ul class="space-y-2 list-disc list-inside">
                            <li><strong class="text-gray-800">First-time Play App Signing setup</strong> — register your upload key with Google.</li>
                            <li><strong class="text-gray-800">Reset upload key in Google Play Console</strong> — if you lost access to your old upload keystore but still have (or recreate) a key, Google asks for the new upload certificate PEM to approve the change.</li>
                            <li><strong class="text-gray-800">Support or agency handoff</strong> — prove which upload key signs your builds without sharing the keystore password in email.</li>
                        </ul>
                    </div>
                </article>

                <article class="rounded-3xl border-2 border-gray-100 bg-white p-6 md:p-8 shadow-soft">
                    <h2 class="text-2xl font-black font-heading text-gray-900 mb-4">Reset upload key in Google Play Console</h2>
                    <div class="space-y-4 text-gray-600 font-medium leading-relaxed">
                        <p>
                            If you cannot sign new releases because the upload key is lost or compromised, Google lets you request an <strong class="text-gray-800">upload key reset</strong> (under <strong class="text-gray-800">Setup → App signing</strong> in Play Console). You generate a new upload keystore, export its certificate as PEM, and submit that file to Google.
                        </p>
                        <ol class="space-y-3 list-decimal list-inside">
                            <li>Create or locate your new upload <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">.jks</code> file.</li>
                            <li>Use this tool to download <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">upload_certificate.pem</code>.</li>
                            <li>In Play Console, open the upload key reset flow and upload the PEM when prompted.</li>
                            <li>After Google approves the reset, sign future AAB/APK builds with the new keystore.</li>
                        </ol>
                        <p class="text-sm">
                            Read more in our guide:
                            <a href="<?php echo $LOCAL_DEV ? '/blogs.php?article=how-to-upload-app-google-play-store' : '/blogs/how-to-upload-app-google-play-store'; ?>" class="text-brand-600 hover:text-brand-700 font-bold">How to upload your app on Google Play</a>.
                        </p>
                    </div>
                </article>

                <article class="rounded-3xl border-2 border-gray-100 bg-white p-6 md:p-8 shadow-soft">
                    <h2 class="text-2xl font-black font-heading text-gray-900 mb-4">How this tool works</h2>
                    <div class="space-y-4 text-gray-600 font-medium leading-relaxed">
                        <p>
                            Upload your keystore and passwords above. The server writes the file temporarily, runs the same <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">keytool</code> export a developer would run locally, returns the PEM, and deletes all temp files. Your keystore is <strong class="text-gray-800">not stored</strong> on our servers.
                        </p>
                        <p>Equivalent manual command (for reference):</p>
                        <pre class="overflow-x-auto p-4 rounded-2xl border-2 border-gray-200 bg-gray-50 text-xs sm:text-sm font-mono text-gray-800"><code>keytool -exportcert -rfc -keystore release.jks -alias your_alias -file upload_certificate.pem</code></pre>
                    </div>
                </article>

                <article class="rounded-3xl border-2 border-gray-100 bg-white p-6 md:p-8 shadow-soft">
                    <h2 class="text-2xl font-black font-heading text-gray-900 mb-4">Frequently asked questions</h2>
                    <dl class="space-y-6">
                        <div>
                            <dt class="text-lg font-bold font-heading text-gray-900 mb-2">What is the difference between upload key and app signing key?</dt>
                            <dd class="text-gray-600 font-medium leading-relaxed">
                                The <strong class="text-gray-800">upload key</strong> signs bundles you upload to Play. Google re-signs with the <strong class="text-gray-800">app signing key</strong> before users install. This tool exports the certificate for your upload key only.
                            </dd>
                        </div>
                        <div>
                            <dt class="text-lg font-bold font-heading text-gray-900 mb-2">Can I use this PEM to reset my upload key?</dt>
                            <dd class="text-gray-600 font-medium leading-relaxed">
                                Yes. Google Play’s upload key reset request accepts the PEM exported from your <strong class="text-gray-800">new</strong> upload keystore. This tool generates that file from your <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">.jks</code>.
                            </dd>
                        </div>
                        <div>
                            <dt class="text-lg font-bold font-heading text-gray-900 mb-2">Do I need Android Studio or the JDK installed?</dt>
                            <dd class="text-gray-600 font-medium leading-relaxed">
                                No. If you only have the <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">.jks</code> file and passwords, this browser tool returns <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">upload_certificate.pem</code> without installing Java or learning <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">keytool</code>.
                            </dd>
                        </div>
                        <div>
                            <dt class="text-lg font-bold font-heading text-gray-900 mb-2">Is my keystore saved?</dt>
                            <dd class="text-gray-600 font-medium leading-relaxed">
                                No. The keystore is processed in a temporary file, the certificate is extracted, and both are removed immediately after the response is sent.
                            </dd>
                        </div>
                    </dl>
                </article>
            </div>
        </div>
    </section>

    <!-- Result / loading dialog -->
    <div id="cert-result-modal"
         class="fixed inset-0 z-[10000] hidden items-center justify-center p-4 bg-gray-900/75 backdrop-blur-sm isolate"
         aria-hidden="true">
        <div id="cert-modal-panel" class="relative z-10 bg-white rounded-3xl shadow-2xl border-2 border-gray-100 max-w-3xl w-full max-h-[92vh] flex flex-col"
             role="dialog" aria-labelledby="cert-result-title" aria-modal="true">
            <div id="cert-modal-header" class="flex items-start justify-between gap-4 p-5 pb-3 border-b-2 border-gray-100">
                <div>
                    <div id="cert-modal-icon-wrap" class="inline-flex items-center justify-center w-10 h-10 bg-green-50 rounded-full mb-2">
                        <span id="cert-modal-icon" class="material-icons text-xl text-green-500">verified</span>
                    </div>
                    <h2 id="cert-result-title" class="text-xl font-black font-heading text-gray-900">Certificate ready</h2>
                    <p id="cert-result-subtitle" class="hidden text-sm text-gray-500 font-medium mt-1"></p>
                </div>
                <button type="button" id="cert-modal-close" class="p-2 text-gray-400 hover:text-gray-700 rounded-lg shrink-0" aria-label="Close">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <div id="cert-modal-body" class="flex-1 overflow-hidden px-5 py-3 min-h-0">
                <div id="cert-modal-loading" class="hidden flex items-center justify-center py-20 px-8 min-h-[12rem]" role="status" aria-live="polite">
                    <div class="cert-spinner cert-spinner-lg" aria-hidden="true"></div>
                </div>

                <div id="cert-modal-result" class="hidden h-full flex flex-col min-h-0">
                    <textarea id="certContent" readonly spellcheck="false" aria-readonly="true" aria-label="upload_certificate.pem"
                              class="w-full h-[26rem] sm:h-[32rem] p-4 rounded-2xl border-2 border-gray-200 bg-gray-50 text-sm font-mono text-gray-800 resize-none overflow-y-auto focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400"></textarea>
                </div>
            </div>

            <div id="cert-modal-actions" class="hidden flex-col sm:flex-row gap-3 p-5 pt-3 border-t-2 border-gray-100">
                <button type="button" id="certDownloadBtn"
                        class="inline-flex items-center justify-center gap-2 flex-1 bg-brand-500 text-white font-bold py-3 px-6 rounded-2xl hover:bg-brand-600 hover:shadow-float transition-all">
                    <span class="material-icons">download</span>
                    Download
                </button>
                <button type="button" id="certCopyBtn"
                        class="inline-flex items-center justify-center gap-2 flex-1 bg-gray-100 text-gray-800 font-bold py-3 px-6 rounded-2xl hover:bg-gray-200 transition-all">
                    <span class="material-icons">content_copy</span>
                    Copy
                </button>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../../_components/footer.php'; ?>

    <script>
    (function () {
        var API_URL = <?php echo json_encode($uploadCertificateApiUrl, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
        var MAX_KEY_BYTES = <?php echo (int) $maxKeyBytes; ?>;

        var form = document.getElementById('uploadCertForm');
        var keyFileInput = document.getElementById('keyFile');
        var keyStorePasswordInput = document.getElementById('keyStorePassword');
        var keyPasswordInput = document.getElementById('keyPassword');
        var keyPasswordDifferent = document.getElementById('keyPasswordDifferent');
        var keyPasswordField = document.getElementById('keyPasswordField');
        var formError = document.getElementById('formError');
        var submitBtn = document.getElementById('submitBtn');
        var submitIcon = document.getElementById('submitIcon');
        var submitLabel = document.getElementById('submitLabel');
        var resultModal = document.getElementById('cert-result-modal');
        var certModalLoading = document.getElementById('cert-modal-loading');
        var certModalResult = document.getElementById('cert-modal-result');
        var certModalActions = document.getElementById('cert-modal-actions');
        var certModalHeader = document.getElementById('cert-modal-header');
        var certModalBody = document.getElementById('cert-modal-body');
        var certModalPanel = document.getElementById('cert-modal-panel');
        var certModalIconWrap = document.getElementById('cert-modal-icon-wrap');
        var certModalIcon = document.getElementById('cert-modal-icon');
        var certResultTitle = document.getElementById('cert-result-title');
        var certResultSubtitle = document.getElementById('cert-result-subtitle');
        var certContent = document.getElementById('certContent');
        var downloadBtn = document.getElementById('certDownloadBtn');
        var copyBtn = document.getElementById('certCopyBtn');
        var closeBtn = document.getElementById('cert-modal-close');

        var pemText = '';

        function getKeyPassword() {
            if (keyPasswordDifferent.checked) {
                return keyPasswordInput.value;
            }
            return keyStorePasswordInput.value;
        }

        keyPasswordDifferent.addEventListener('change', function () {
            if (this.checked) {
                keyPasswordField.classList.remove('hidden');
                keyPasswordInput.focus();
            } else {
                keyPasswordField.classList.add('hidden');
                keyPasswordInput.value = '';
            }
        });

        function showError(message) {
            formError.textContent = message;
            formError.classList.remove('hidden');
        }

        function hideError() {
            formError.classList.add('hidden');
            formError.textContent = '';
        }

        function openModal() {
            resultModal.classList.remove('hidden');
            resultModal.classList.add('flex');
            resultModal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function hideResultModal() {
            resultModal.classList.add('hidden');
            resultModal.classList.remove('flex');
            resultModal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            certModalLoading.classList.add('hidden');
            certModalLoading.classList.remove('flex');
            certModalResult.classList.add('hidden');
            certModalActions.classList.add('hidden');
            certModalActions.classList.remove('flex');
            certContent.value = '';
            certModalHeader.classList.remove('hidden');
            certModalBody.classList.remove('px-8', 'py-8');
            certModalBody.classList.add('px-5', 'py-3');
            certModalPanel.classList.remove('max-w-sm');
        }

        function setModalLoadingState() {
            certModalHeader.classList.add('hidden');
            certModalBody.classList.add('px-8', 'py-8');
            certModalBody.classList.remove('px-5', 'py-3');
            certModalPanel.classList.add('max-w-sm');

            certModalLoading.classList.remove('hidden');
            certModalLoading.classList.add('flex');
            certModalResult.classList.add('hidden');
            certModalActions.classList.add('hidden');
            certModalActions.classList.remove('flex');
            certContent.value = '';
        }

        function setModalResultState(content) {
            pemText = content;
            certContent.value = content;

            certModalHeader.classList.remove('hidden');
            certModalBody.classList.remove('px-8', 'py-8');
            certModalBody.classList.add('px-5', 'py-3');
            certModalPanel.classList.remove('max-w-sm');
            certModalIcon.textContent = 'verified';
            certResultTitle.textContent = 'Certificate ready';
            certResultSubtitle.classList.add('hidden');
            certResultSubtitle.textContent = '';

            certModalLoading.classList.add('hidden');
            certModalLoading.classList.remove('flex');
            certModalResult.classList.remove('hidden');
            certModalActions.classList.remove('hidden');
            certModalActions.classList.add('flex');
        }

        function setSubmitLoading(isLoading) {
            submitBtn.disabled = isLoading;
            submitLabel.textContent = isLoading ? 'Extracting…' : 'Extract certificate';
            submitIcon.textContent = isLoading ? 'hourglass_top' : 'vpn_key';
        }

        function showLoadingModal() {
            setSubmitLoading(true);
            setModalLoadingState();
            openModal();
        }

        function showResultModal(content) {
            setModalResultState(content);
            setSubmitLoading(false);
        }

        function validateForm() {
            hideError();

            var file = keyFileInput.files && keyFileInput.files[0];
            if (!file) {
                showError('Please select a keystore (.jks) file.');
                return false;
            }

            if (file.size > MAX_KEY_BYTES) {
                showError('Keystore file exceeds the 100 KB limit.');
                return false;
            }

            if (!keyStorePasswordInput.value) {
                showError('Please enter the keystore password.');
                return false;
            }

            if (keyPasswordDifferent.checked && !keyPasswordInput.value) {
                showError('Please enter the key password.');
                return false;
            }

            return true;
        }

        async function parseErrorResponse(response) {
            var contentType = response.headers.get('content-type') || '';
            if (contentType.indexOf('application/json') !== -1) {
                try {
                    var data = await response.json();
                    if (data && data.error) {
                        return data.error;
                    }
                } catch (e) {
                    /* fall through */
                }
            }
            return 'Unable to extract upload certificate. Check your file and passwords.';
        }

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (!validateForm()) {
                return;
            }

            var file = keyFileInput.files[0];
            var body = new FormData();
            body.append('key', file, file.name);
            body.append('keyPassword', getKeyPassword());
            body.append('keyStorePassword', keyStorePasswordInput.value);

            showLoadingModal();

            try {
                var response = await fetch(API_URL, {
                    method: 'POST',
                    body: body
                });

                if (!response.ok) {
                    throw new Error(await parseErrorResponse(response));
                }

                var text = await response.text();
                if (!text.trim()) {
                    throw new Error('The server returned an empty certificate.');
                }

                showResultModal(text.trim());
            } catch (err) {
                hideResultModal();
                setSubmitLoading(false);
                showError(err.message || 'Something went wrong. Please try again.');
            }
        });

        downloadBtn.addEventListener('click', function () {
            if (!pemText) {
                return;
            }
            var blob = new Blob([pemText], { type: 'application/x-pem-file' });
            var url = URL.createObjectURL(blob);
            var link = document.createElement('a');
            link.href = url;
            link.download = 'upload_certificate.pem';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        });

        copyBtn.addEventListener('click', async function () {
            if (!pemText) {
                return;
            }
            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(pemText);
                } else {
                    var textArea = document.createElement('textarea');
                    textArea.value = pemText;
                    textArea.style.position = 'fixed';
                    textArea.style.left = '-9999px';
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                }
                copyBtn.innerHTML = '<span class="material-icons">check</span> Copied';
                setTimeout(function () {
                    copyBtn.innerHTML = '<span class="material-icons">content_copy</span> Copy';
                }, 2000);
            } catch (err) {
                showError('Could not copy to clipboard. Use Download instead.');
            }
        });

        closeBtn.addEventListener('click', function () {
            hideResultModal();
            setSubmitLoading(false);
        });
        resultModal.addEventListener('click', function (e) {
            if (e.target === resultModal) {
                hideResultModal();
                setSubmitLoading(false);
            }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                hideResultModal();
                setSubmitLoading(false);
            }
        });
    })();
    </script>
</body>
</html>
