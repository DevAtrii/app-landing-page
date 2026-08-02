<?php
include_once 'config.php';

$DB_PATH_BASE = __DIR__ . '/__secrets__/';
$DB_PATH_REDEEM_CODES = $DB_PATH_BASE . 'redeem-codes.json';
$DB_PATH_CLAIMED_REDEEM_CODES = $DB_PATH_BASE . 'claimed-redeem-codes.json';

// No initial codes - codes should be manually added to the JSON file

// Thread-safe file operations
function readJsonFile($filePath) {
    $lockFile = $filePath . '.lock';
    $fp = fopen($lockFile, 'w');
    if (flock($fp, LOCK_EX)) {
        $data = [];
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            if ($content !== false) {
                $decoded = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data = $decoded;
                }
            }
        }
        flock($fp, LOCK_UN);
        fclose($fp);
        return $data;
    }
    fclose($fp);
    return [];
}

function writeJsonFile($filePath, $data) {
    $lockFile = $filePath . '.lock';
    $fp = fopen($lockFile, 'w');
    if (flock($fp, LOCK_EX)) {
        $success = file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
        flock($fp, LOCK_UN);
        fclose($fp);
        return $success !== false;
    }
    fclose($fp);
    return false;
}

// Initialize JSON files if they don't exist (but don't populate with codes)
$redeemCodes = readJsonFile($DB_PATH_REDEEM_CODES);

$claimedCodes = readJsonFile($DB_PATH_CLAIMED_REDEEM_CODES);

// Form submission handling
$showSuccess = false;
$showError = false;
$showNoCode = false;
$claimedCode = '';
$errorMessage = '';

// Check if user was redirected after successful claim
if (isset($_GET['claimed']) && !empty($_GET['claimed'])) {
    $showSuccess = true;
    $claimedCode = $_GET['claimed'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action']) && !isset($_GET['claimed'])) {
    // Read current codes
    $currentCodes = readJsonFile($DB_PATH_REDEEM_CODES);
    $currentClaimed = readJsonFile($DB_PATH_CLAIMED_REDEEM_CODES);
    
    if (empty($currentCodes)) {
        $showNoCode = true;
    } else {
        // Validate form fields
        $isValid = true;
        foreach ($redeemCodesCampaign['formFields'] as $fieldName => $field) {
            if ($field['required'] && empty($_POST[$fieldName])) {
                $isValid = false;
                $errorMessage = "Please fill in all required fields.";
                break;
            }
        }
        
        if ($isValid) {
            // Get the first available code
            $claimedCode = array_shift($currentCodes);
            
            // Prepare user data
            $userData = [
                'code' => $claimedCode,
                'timestamp' => date('Y-m-d H:i:s'),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ];
            
            // Add form field data
            foreach ($redeemCodesCampaign['formFields'] as $field => $config) {
                if (isset($_POST[$field])) {
                    $userData[$field] = $_POST[$field];
                }
            }
            
            // Update files
            $currentClaimed[] = $userData;
            
            if (writeJsonFile($DB_PATH_REDEEM_CODES, $currentCodes) && 
                writeJsonFile($DB_PATH_CLAIMED_REDEEM_CODES, $currentClaimed)) {
                // Redirect to prevent form resubmission on refresh (Post-Redirect-Get pattern)
                header('Location: ' . $_SERVER['PHP_SELF'] . '?claimed=' . urlencode($claimedCode));
                exit;
            } else {
                $showError = true;
                $errorMessage = 'Failed to process claim. Please try again.';
            }
        } else {
            $showError = true;
        }
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'claim') {
    header('Content-Type: application/json');
    
    // Read current codes
    $currentCodes = readJsonFile($DB_PATH_REDEEM_CODES);
    $currentClaimed = readJsonFile($DB_PATH_CLAIMED_REDEEM_CODES);
    
    if (empty($currentCodes)) {
        echo json_encode(['success' => false, 'message' => $redeemCodesCampaign['messages']['noCode']]);
        exit;
    }
    
    // Get the first available code
    $claimedCode = array_shift($currentCodes);
    
    // Prepare user data
    $userData = [
        'code' => $claimedCode,
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ];
    
    // Add form field data
    foreach ($redeemCodesCampaign['formFields'] as $field => $config) {
        if (isset($_POST[$field])) {
            $userData[$field] = $_POST[$field];
        }
    }
    
    // Update files
    $currentClaimed[] = $userData;
    
    if (writeJsonFile($DB_PATH_REDEEM_CODES, $currentCodes) && 
        writeJsonFile($DB_PATH_CLAIMED_REDEEM_CODES, $currentClaimed)) {
        echo json_encode([
            'success' => true, 
            'message' => $redeemCodesCampaign['messages']['success'],
            'code' => $claimedCode
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to process claim. Please try again.']);
    }
    exit;
}

// Get current stats from database files
$availableCodes = readJsonFile($DB_PATH_REDEEM_CODES);
$claimedCodes = readJsonFile($DB_PATH_CLAIMED_REDEEM_CODES);
$totalClaimed = count($claimedCodes);
$totalAvailable = count($availableCodes);

// Set page meta data
$pageTitle = $redeemCodesCampaign['title'];
$pageDescription = $redeemCodesCampaign['description'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '_components/meta.php'; ?>
</head>
<body class="page">
    <?php include '_components/header.php'; ?>
    
    <?php if (!$redeemCodesCampaign['isActive']): ?>
        <section class="claim-page">
            <div class="claim-page__bg"></div>
            <div class="claim-page__content">
                <div class="claim-page__header animate-fade-in-up animation-delay-300">
                    <div class="claim-page__emoji">⏰</div>
                    <h1 class="claim-page__title"><?php echo $redeemCodesCampaign['nonActiveProps']['title']; ?></h1>
                    <p class="claim-page__desc"><?php echo $redeemCodesCampaign['nonActiveProps']['description']; ?></p>
                </div>
            </div>
        </section>
        
    <?php else: ?>
        <section class="claim-page">
            <div class="claim-page__bg"></div>
            <div class="claim-page__blob claim-page__blob--1 animate-pulse"></div>
            <div class="claim-page__blob claim-page__blob--2 animate-pulse animation-delay-2000"></div>
            
            <div class="claim-page__content">
                <div class="claim-page__header animate-fade-in-up animation-delay-300">
                    <h1 class="claim-page__title"><?php echo $redeemCodesCampaign['title']; ?></h1>
                    <p class="claim-page__desc"><?php echo $redeemCodesCampaign['description']; ?></p>
                    
                    <?php if (isset($redeemCodesCampaign['showStats']) && $redeemCodesCampaign['showStats']): ?>
                    <div class="claim-stats">
                        <div class="claim-stat">
                            <div class="claim-stat__value claim-stat__value--blue"><?php echo $totalAvailable; ?></div>
                            <div class="claim-stat__label">Available</div>
                        </div>
                        <div class="claim-stat">
                            <div class="claim-stat__value claim-stat__value--green"><?php echo $totalClaimed; ?></div>
                            <div class="claim-stat__label">Claimed</div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="claim-card animate-fade-in-up animation-delay-500">
                    
                    <?php if ($showError): ?>
                        <!-- Error Message -->
                        <div class="claim-state">
                            <div class="claim-state__emoji">❌</div>
                            <h2 class="claim-state__title claim-state__title--error">Error</h2>
                            <p class="claim-state__desc" style="color:var(--color-error)"><?php echo $errorMessage; ?></p>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn--primary">Try Again</a>
                        </div>
                    <?php elseif ($showNoCode): ?>
                        <div class="claim-state">
                            <div class="claim-state__emoji claim-state__emoji--lg">😔</div>
                            <h2 class="claim-state__title">All Gone!</h2>
                            <p class="claim-state__desc"><?php echo $redeemCodesCampaign['messages']['noCode']; ?></p>
                        </div>
                    <?php elseif ($showSuccess): ?>
                        <div class="claim-state success-animation">
                            <div class="claim-state__emoji claim-state__emoji--lg">🎉</div>
                            <h2 class="claim-state__title">Congratulations!</h2>
                            <div class="claim-card--success">
                                <p class="claim-state__desc"><?php echo $redeemCodesCampaign['messages']['success']; ?></p>
                                <div class="claim-code">
                                    <p class="claim-code__text"><?php echo $claimedCode; ?></p>
                                </div>
                                    
                                    <!-- Download App Section -->
                                    <div class="pt-4 border-t border-blue-200">
                                        <p class="text-gray-700 mb-4 font-body font-medium">Download the app to redeem your code:</p>
                                        <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                                            <?php if ($common['appStoreUrl']): ?>
                                                <a href="<?php echo $common['appStoreUrl']; ?>" 
                                                   target="_blank" 
                                                   class="block w-48 h-14 hover:scale-105 hover:-translate-y-1 transition-all duration-300 transform shadow-lg hover:shadow-2xl group">
                                                    <img src="./assets/app-store-download.svg" alt="Download on the App Store" class="w-full h-full group-hover:brightness-110 transition-all duration-300">
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ($common['googlePlayUrl']): ?>
                                                <a href="<?php echo $common['googlePlayUrl']; ?>" 
                                                   target="_blank" 
                                                   class="block w-48 h-14 hover:scale-105 hover:-translate-y-1 transition-all duration-300 transform shadow-lg hover:shadow-2xl group">
                                                    <img src="./assets/google-play-download.svg" alt="Get it on Google Play" class="w-full h-full group-hover:brightness-110 transition-all duration-300">
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Already Claimed Message (Hidden by default, shown by JS) -->
                        <div id="alreadyClaimedMessage" class="claim-state is-hidden">
                            <div class="claim-state__emoji">✅</div>
                            <h2 class="claim-state__title claim-state__title--success">Already Claimed!</h2>
                            <p class="claim-state__desc">You have already claimed your free month for this campaign.</p>
                            <div class="claim-card--success">
                                <p class="claim-state__desc">Your redeem code:</p>
                                <div class="claim-code">
                                    <p class="claim-code__text" id="existingRedeemCode"></p>
                                </div>
                                <div style="padding-top:1rem;border-top:1px solid #bbf7d0;margin-top:1rem">
                                    <p class="claim-state__desc">Download the app to redeem your code:</p>
                                    <div class="hero__actions">
                                        <?php if ($common['appStoreUrl']): ?>
                                            <a href="<?php echo $common['appStoreUrl']; ?>" target="_blank" class="store-badge">
                                                <img src="./assets/app-store-download.svg" alt="Download on the App Store">
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($common['googlePlayUrl']): ?>
                                            <a href="<?php echo $common['googlePlayUrl']; ?>" target="_blank" class="store-badge">
                                                <img src="./assets/google-play-download.svg" alt="Get it on Google Play">
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Max Claims Reached Message (Hidden by default, shown by JS) -->
                        <div id="maxClaimsMessage" class="claim-state is-hidden">
                            <div class="claim-state__emoji">🚫</div>
                            <h2 class="claim-state__title claim-state__title--error">Claim Limit Reached</h2>
                            <p class="claim-state__desc">You have reached the maximum number of claims allowed for this campaign.</p>
                        </div>

                        <form id="claimForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="claim-form">
                                <?php foreach ($redeemCodesCampaign['formFields'] as $fieldName => $field): ?>
                                    <div class="form-field">
                                        <label><?php echo $field['label']; ?></label>
                                        <?php if ($field['type'] === 'select'): ?>
                                            <select name="<?php echo $fieldName; ?>" <?php echo $field['required'] ? 'required' : ''; ?> class="form-select">
                                                <option value="">Select an option</option>
                                                <?php foreach ($field['options'] as $option): ?>
                                                    <option value="<?php echo $option; ?>" <?php echo (isset($_POST[$fieldName]) && $_POST[$fieldName] === $option) ? 'selected' : ''; ?>><?php echo $option; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php else: ?>
                                            <input type="<?php echo $field['type']; ?>" name="<?php echo $fieldName; ?>" 
                                                   <?php echo $field['required'] ? 'required' : ''; ?>
                                                   value="<?php echo isset($_POST[$fieldName]) ? htmlspecialchars($_POST[$fieldName]) : ''; ?>"
                                                   placeholder="Enter your <?php echo strtolower($field['label']); ?>"
                                                   class="form-input">
                                        <?php endif; ?>
                                        <p style="color:var(--color-gray-500);font-size:0.875rem;margin-top:0.25rem"><?php echo $field['description']; ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <button type="submit" class="btn btn--claim">
                                <?php echo $redeemCodesCampaign['buttonText']; ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

                <!-- Guide Section -->
                <?php if ($showSuccess): ?>
                <div class="claim-guide">
                    <h2 class="claim-guide__title"><?php echo $redeemCodesCampaign['guide']['title']; ?></h2>
                    <p class="claim-guide__desc"><?php echo $redeemCodesCampaign['guide']['description']; ?></p>
                    <?php foreach ($redeemCodesCampaign['guide']['steps'] as $index => $step): ?>
                        <div class="claim-step">
                            <div class="claim-step__num"><?php echo $index + 1; ?></div>
                            <div>
                                <h3 style="font-weight:600;margin-bottom:0.5rem"><?php echo $step['title']; ?></h3>
                                <p style="color:var(--color-gray-600)"><?php echo $step['description']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="claim-rules animate-fade-in-up animation-delay-700">
                    <h2 class="claim-rules__title"><?php echo $redeemCodesCampaign['rules']['title']; ?></h2>
                    <p class="claim-rules__desc"><?php echo $redeemCodesCampaign['rules']['description']; ?></p>
                    <?php foreach ($redeemCodesCampaign['rules']['rules'] as $rule): ?>
                        <div class="claim-rule">
                            <span class="material-icons">check_circle</span>
                            <div>
                                <h3 style="font-weight:600;margin-bottom:0.5rem"><?php echo $rule['title']; ?></h3>
                                <?php if (!empty($rule['description'])): ?>
                                    <p style="color:var(--color-gray-600)"><?php echo $rule['description']; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Footer -->
    <?php include '_components/footer.php'; ?>

    <script>
        // Campaign configuration from PHP
        const campaignConfig = {
            id: '<?php echo $redeemCodesCampaign['id']; ?>',
            maxClaimLimits: <?php echo $redeemCodesCampaign['maxClaimLimits']; ?>,
            claimedCode: '<?php echo $showSuccess ? htmlspecialchars($claimedCode, ENT_QUOTES) : ''; ?>'
        };

        // LocalStorage key for this campaign
        const storageKey = `claim_${campaignConfig.id}`;

        // Check claim status on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkClaimStatus();
            
            // If user just successfully claimed, save to localStorage and clean URL
            if (campaignConfig.claimedCode) {
                saveClaimToLocalStorage(campaignConfig.claimedCode);
                cleanUrlAfterClaim();
            }
        });

        function checkClaimStatus() {
            const claimData = getClaimDataFromLocalStorage();
            
            if (claimData) {
                const claimCount = claimData.claimCount || 0;
                
                if (claimCount >= campaignConfig.maxClaimLimits) {
                    if (claimData.codes && claimData.codes.length > 0) {
                        // Show already claimed message with existing code
                        showAlreadyClaimedMessage(claimData.codes[claimData.codes.length - 1]);
                    } else {
                        // Show max claims reached message
                        showMaxClaimsMessage();
                    }
                    hideClaimForm();
                }
            }
        }

        function getClaimDataFromLocalStorage() {
            try {
                const data = localStorage.getItem(storageKey);
                return data ? JSON.parse(data) : null;
            } catch (error) {
                console.error('Error reading from localStorage:', error);
                return null;
            }
        }

        function saveClaimToLocalStorage(code) {
            try {
                let claimData = getClaimDataFromLocalStorage() || {
                    claimCount: 0,
                    codes: [],
                    timestamps: []
                };

                claimData.claimCount += 1;
                claimData.codes.push(code);
                claimData.timestamps.push(new Date().toISOString());

                localStorage.setItem(storageKey, JSON.stringify(claimData));
                console.log('Claim saved to localStorage:', claimData);
            } catch (error) {
                console.error('Error saving to localStorage:', error);
            }
        }

        function showAlreadyClaimedMessage(code) {
            document.getElementById('alreadyClaimedMessage').classList.remove('is-hidden');
            document.getElementById('existingRedeemCode').textContent = code;
        }

        function showMaxClaimsMessage() {
            document.getElementById('maxClaimsMessage').classList.remove('is-hidden');
        }

        function hideClaimForm() {
            const form = document.getElementById('claimForm');
            if (form) form.classList.add('is-hidden');
        }

        function cleanUrlAfterClaim() {
            // Remove the 'claimed' parameter from URL without refreshing the page
            if (window.history && window.history.replaceState) {
                const url = new URL(window.location);
                url.searchParams.delete('claimed');
                window.history.replaceState({}, '', url.pathname);
            }
        }

        // Add form submission handler to prevent multiple submissions
        document.getElementById('claimForm').addEventListener('submit', function(e) {
            const claimData = getClaimDataFromLocalStorage();
            if (claimData && claimData.claimCount >= campaignConfig.maxClaimLimits) {
                e.preventDefault();
                alert('You have already reached the maximum number of claims for this campaign.');
                return false;
            }
        });

        // Debug function (can be removed in production)
        function debugClearClaims() {
            localStorage.removeItem(storageKey);
            location.reload();
        }

        // Add debug info to console (can be removed in production)
        console.log('Campaign ID:', campaignConfig.id);
        console.log('Max Claims:', campaignConfig.maxClaimLimits);
        console.log('Current Claim Data:', getClaimDataFromLocalStorage());
    </script>

</body>
</html>