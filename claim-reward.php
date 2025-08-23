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
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 40px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .success-animation {
            animation: bounceIn 0.6s ease;
        }
        
        .animation-delay-300 { animation-delay: 0.3s; }
        .animation-delay-500 { animation-delay: 0.5s; }
        .animation-delay-700 { animation-delay: 0.7s; }
        
        .claim-button {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px 0 rgba(37, 99, 235, 0.4);
        }
        
        .claim-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px 0 rgba(37, 99, 235, 0.6);
        }
        
        .claim-button:active {
            transform: translateY(0);
        }
        
        .claim-button:disabled {
            transform: none;
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-50 min-h-screen font-body">
    <!-- Header -->
    <?php include '_components/header.php'; ?>
    
    <?php if (!$redeemCodesCampaign['isActive']): ?>
        <!-- Campaign Inactive -->
        <section class="pt-16 pb-20 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-100/50 via-white/30 to-blue-100/50"></div>
            
            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="animate-fade-in-up opacity-0 animation-delay-300">
                    <div class="text-8xl mb-8">⏰</div>
                    <h1 class="text-4xl md:text-5xl font-bold font-heading text-gray-900 mb-6">
                        <?php echo $redeemCodesCampaign['nonActiveProps']['title']; ?>
                    </h1>
                    <p class="text-xl text-gray-600 leading-relaxed font-body max-w-2xl mx-auto">
                        <?php echo $redeemCodesCampaign['nonActiveProps']['description']; ?>
                    </p>
                </div>
            </div>
        </section>
        
    <?php else: ?>
        <!-- Active Campaign -->
        <section class="pt-16 pb-20 relative overflow-hidden">
            <!-- Background Gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-100/50 via-white/30 to-blue-100/50"></div>
            
            <!-- Animated Blurred Blobs -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-20 -left-32 w-96 h-96 bg-blue-400/30 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-20 -right-32 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
            </div>
            
            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-12 animate-fade-in-up opacity-0 animation-delay-300">
                    <h1 class="text-4xl md:text-5xl font-bold font-heading text-gray-900 mb-6">
                        <?php echo $redeemCodesCampaign['title']; ?>
                    </h1>
                    <p class="text-xl text-gray-600 leading-relaxed font-body max-w-2xl mx-auto mb-8">
                        <?php echo $redeemCodesCampaign['description']; ?>
                    </p>
                    
                    <!-- Stats (Optional) -->
                    <?php if (isset($redeemCodesCampaign['showStats']) && $redeemCodesCampaign['showStats']): ?>
                    <div class="flex justify-center gap-8">
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-gray-200/50">
                            <div class="text-3xl font-bold text-blue-600 font-heading"><?php echo $totalAvailable; ?></div>
                            <div class="text-gray-600 font-body">Available</div>
                        </div>
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-gray-200/50">
                            <div class="text-3xl font-bold text-green-600 font-heading"><?php echo $totalClaimed; ?></div>
                            <div class="text-gray-600 font-body">Claimed</div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Main Claim Card -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-xl border border-gray-200/50 mb-12 animate-fade-in-up opacity-0 animation-delay-500">
                    
                    <?php if ($showError): ?>
                        <!-- Error Message -->
                        <div class="text-center mb-8">
                            <div class="text-6xl mb-4">❌</div>
                            <h2 class="text-2xl font-bold text-red-600 mb-4 font-heading">Error</h2>
                            <p class="text-red-600 font-body"><?php echo $errorMessage; ?></p>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Try Again</a>
                        </div>
                    <?php elseif ($showNoCode): ?>
                        <!-- No Codes Message -->
                        <div class="text-center">
                            <div class="text-8xl mb-6">😔</div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-4 font-heading">All Gone!</h2>
                            <p class="text-gray-600 font-body"><?php echo $redeemCodesCampaign['messages']['noCode']; ?></p>
                        </div>
                    <?php elseif ($showSuccess): ?>
                        <!-- Success Message -->
                        <div class="text-center">
                            <div class="success-animation">
                                <div class="text-8xl mb-6">🎉</div>
                                <h2 class="text-3xl font-bold text-gray-900 mb-4 font-heading">Congratulations!</h2>
                                <div class="bg-blue-50 rounded-2xl p-6 mb-6 border border-blue-200">
                                    <p class="text-gray-700 mb-4 font-body"><?php echo $redeemCodesCampaign['messages']['success']; ?></p>
                                    <div class="bg-white rounded-xl p-4 border border-blue-300 mb-6">
                                        <p class="text-blue-800 font-mono text-xl font-bold"><?php echo $claimedCode; ?></p>
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
                        <div id="alreadyClaimedMessage" class="hidden text-center">
                            <div class="text-6xl mb-4">✅</div>
                            <h2 class="text-2xl font-bold text-green-600 mb-4 font-heading">Already Claimed!</h2>
                            <p class="text-gray-600 mb-4 font-body">You have already claimed your free month for this campaign.</p>
                            <div class="bg-green-50 rounded-2xl p-6 border border-green-200">
                                <p class="text-gray-700 mb-2 font-body">Your redeem code:</p>
                                <div class="bg-white rounded-xl p-4 border border-green-300 mb-6">
                                    <p class="text-green-800 font-mono text-xl font-bold" id="existingRedeemCode"></p>
                                </div>
                                
                                <!-- Download App Section -->
                                <div class="pt-4 border-t border-green-200">
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

                        <!-- Max Claims Reached Message (Hidden by default, shown by JS) -->
                        <div id="maxClaimsMessage" class="hidden text-center">
                            <div class="text-6xl mb-4">🚫</div>
                            <h2 class="text-2xl font-bold text-red-600 mb-4 font-heading">Claim Limit Reached</h2>
                            <p class="text-gray-600 font-body">You have reached the maximum number of claims allowed for this campaign.</p>
                        </div>

                        <!-- Claim Form -->
                        <form id="claimForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="space-y-6 mb-8">
                                <?php foreach ($redeemCodesCampaign['formFields'] as $fieldName => $field): ?>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-2 font-body"><?php echo $field['label']; ?></label>
                                        <?php if ($field['type'] === 'select'): ?>
                                            <select name="<?php echo $fieldName; ?>" <?php echo $field['required'] ? 'required' : ''; ?> 
                                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 font-body">
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
                                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 font-body">
                                        <?php endif; ?>
                                        <p class="text-gray-500 text-sm mt-1 font-body"><?php echo $field['description']; ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <button type="submit" class="w-full claim-button text-white font-bold py-4 px-8 rounded-xl text-lg font-heading">
                                <?php echo $redeemCodesCampaign['buttonText']; ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

                <!-- Guide Section -->
                <?php if ($showSuccess): ?>
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-xl border border-gray-200/50 mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center font-heading"><?php echo $redeemCodesCampaign['guide']['title']; ?></h2>
                    <p class="text-gray-600 text-center mb-8 font-body"><?php echo $redeemCodesCampaign['guide']['description']; ?></p>
                    
                    <div class="grid gap-6">
                        <?php foreach ($redeemCodesCampaign['guide']['steps'] as $index => $step): ?>
                            <div class="flex items-start gap-4 bg-blue-50 rounded-2xl p-6 border border-blue-200">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold font-heading">
                                    <?php echo $index + 1; ?>
                                </div>
                                <div>
                                    <h3 class="text-gray-900 font-semibold mb-2 font-heading"><?php echo $step['title']; ?></h3>
                                    <p class="text-gray-600 font-body"><?php echo $step['description']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Rules Section -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-xl border border-gray-200/50 animate-fade-in-up opacity-0 animation-delay-700">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center font-heading"><?php echo $redeemCodesCampaign['rules']['title']; ?></h2>
                    <p class="text-gray-600 text-center mb-8 font-body"><?php echo $redeemCodesCampaign['rules']['description']; ?></p>
                    
                    <div class="grid gap-4">
                        <?php foreach ($redeemCodesCampaign['rules']['rules'] as $rule): ?>
                            <div class="flex items-start gap-4 bg-gray-50 rounded-2xl p-6 border border-gray-200">
                                <div class="flex-shrink-0 text-green-600">
                                    <span class="material-icons">check_circle</span>
                                </div>
                                <div>
                                    <h3 class="text-gray-900 font-semibold mb-2 font-heading"><?php echo $rule['title']; ?></h3>
                            
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
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
            document.getElementById('alreadyClaimedMessage').classList.remove('hidden');
            document.getElementById('existingRedeemCode').textContent = code;
        }

        function showMaxClaimsMessage() {
            document.getElementById('maxClaimsMessage').classList.remove('hidden');
        }

        function hideClaimForm() {
            document.getElementById('claimForm').classList.add('hidden');
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