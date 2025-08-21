<?php
// Include config for global variables
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
    $pageTitle = "Contact Us";
    $pageDescription = "Get in touch with the " . $common['appName'] . " team";
    include '_components/meta.php'; 
    ?>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-50 min-h-screen font-body">
    <!-- Header -->
    <?php include '_components/header.php'; ?>
    
    <!-- Contact Section -->
    <section class="pt-24 pb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h1>
                <p class="text-xl text-gray-600">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Information -->
                <div class="space-y-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Get in Touch</h2>
                        <p class="text-gray-600 mb-8">Have questions about <?php echo $common['appName']; ?>? We're here to help!</p>
                    </div>
                    
                    <!-- Contact Methods -->
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="material-icons text-blue-600">email</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Email</h3>
                                <p class="text-gray-600">Send us an email and we'll get back to you within 24 hours.</p>
                                <a href="mailto:<?php echo $common['supportEmail']; ?>" 
                                   class="text-blue-600 hover:text-blue-700 font-medium"><?php echo $common['supportEmail']; ?></a>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="material-icons text-green-600">chat</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Community</h3>
                                <p class="text-gray-600">Join our community for discussions and updates.</p>
                                <div class="flex gap-3 mt-2">
                                    <?php foreach ($footer['socials'] as $social): ?>
                                        <a href="<?php echo $social['link']; ?>" 
                                           target="_blank" 
                                           class="text-blue-600 hover:text-blue-700 font-medium"><?php echo $social['title']; ?></a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="material-icons text-purple-600">help</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Support</h3>
                                <p class="text-gray-600">Check our FAQ section for quick answers.</p>
                                <a href="/faq" class="text-blue-600 hover:text-blue-700 font-medium">Visit FAQ</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- App Download -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Download <?php echo $common['appName']; ?></h3>
                        <p class="text-gray-600 mb-4">Get the app and start managing your subscriptions today.</p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <?php if ($common['appStoreUrl']): ?>
                                <a href="<?php echo $common['appStoreUrl']; ?>" 
                                   target="_blank" 
                                   class="block w-32 h-10 hover:opacity-80 transition-opacity duration-200">
                                    <img src="./assets/app-store-download.svg" alt="Download on the App Store" class="w-full h-full">
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($common['googlePlayUrl']): ?>
                                <a href="<?php echo $common['googlePlayUrl']; ?>" 
                                   target="_blank" 
                                   class="block w-32 h-10 hover:opacity-80 transition-opacity duration-200">
                                    <img src="./assets/google-play-download.svg" alt="Get it on Google Play" class="w-full h-full">
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="bg-white rounded-xl border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                    
                    <form id="contactForm" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input type="text" id="firstName" name="firstName" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                                       placeholder="John">
                            </div>
                            <div>
                                <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input type="text" id="lastName" name="lastName" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                                       placeholder="Doe">
                            </div>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                                   placeholder="john@example.com">
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <select id="subject" name="subject" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                                <option value="">Select a subject</option>
                                <option value="general">General Inquiry</option>
                                <option value="support">Technical Support</option>
                                <option value="feature">Feature Request</option>
                                <option value="bug">Bug Report</option>
                                <option value="partnership">Partnership</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea id="message" name="message" rows="5" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                                      placeholder="Tell us how we can help you..."></textarea>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" id="submitBtn"
                                    class="flex-1 bg-blue-600 text-white font-medium py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center">
                                <span class="material-icons mr-2">send</span>
                                Send via Email
                            </button>
                            <!-- <button type="button" id="copyBtn"
                                    class="flex-1 bg-gray-600 text-white font-medium py-3 px-6 rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center justify-center">
                                <span class="material-icons mr-2">content_copy</span>
                                Copy Message
                            </button> -->
                        </div>
                        
                        <!-- Success/Error Messages -->
                        <div id="formMessage" class="hidden p-4 rounded-lg"></div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <?php include '_components/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('contactForm');
            const submitBtn = document.getElementById('submitBtn');
            const copyBtn = document.getElementById('copyBtn');
            const formMessage = document.getElementById('formMessage');
            const supportEmail = '<?php echo $common['supportEmail']; ?>';

            // Form submission handler
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (validateForm()) {
                    sendEmail();
                }
            });

            // Copy message handler
            copyBtn.addEventListener('click', function() {
                if (validateForm()) {
                    copyToClipboard();
                }
            });

            function validateForm() {
                const email = document.getElementById('email').value.trim();
                const subject = document.getElementById('subject').value;
                const message = document.getElementById('message').value.trim();

                // Clear previous messages
                hideMessage();

                // Validate required fields
                if (!email || !subject || !message) {
                    showMessage('Please fill in all required fields.', 'error');
                    return false;
                }

                // Validate email format
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showMessage('Please enter a valid email address.', 'error');
                    return false;
                }

                return true;
            }

            function sendEmail() {
                const formData = getFormData();
                
                // Create mailto URL
                const mailtoUrl = `mailto:${supportEmail}?subject=${encodeURIComponent(formData.subject)}&body=${encodeURIComponent(formData.body)}`;
                
                // Try to open mailto
                try {
                    const mailtoLink = document.createElement('a');
                    mailtoLink.href = mailtoUrl;
                    mailtoLink.click();
                    
                    showMessage('Opening your email client... If it doesn\'t open automatically, please copy the message using the "Copy Message" button.', 'success');
                    
                    // Reset form after successful attempt
                    setTimeout(() => {
                        form.reset();
                        hideMessage();
                    }, 5000);
                    
                } catch (error) {
                    console.error('Error opening email client:', error);
                    showMessage('Unable to open email client. Please use the "Copy Message" button to copy your message.', 'error');
                }
            }

            function copyToClipboard() {
                const formData = getFormData();
                const textToCopy = `To: ${supportEmail}\nSubject: ${formData.subject}\n\n${formData.body}`;
                
                // Try modern clipboard API first
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(textToCopy).then(() => {
                        showMessage('Message copied to clipboard! You can now paste it in your email client.', 'success');
                        form.reset();
                        setTimeout(hideMessage, 5000);
                    }).catch(() => {
                        fallbackCopyToClipboard(textToCopy);
                    });
                } else {
                    fallbackCopyToClipboard(textToCopy);
                }
            }

            function fallbackCopyToClipboard(text) {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                
                try {
                    document.execCommand('copy');
                    showMessage('Message copied to clipboard! You can now paste it in your email client.', 'success');
                    form.reset();
                    setTimeout(hideMessage, 5000);
                } catch (err) {
                    showMessage('Unable to copy to clipboard. Please manually copy the email details.', 'error');
                } finally {
                    document.body.removeChild(textArea);
                }
            }

            function getFormData() {
                const firstName = document.getElementById('firstName').value.trim();
                const lastName = document.getElementById('lastName').value.trim();
                const email = document.getElementById('email').value.trim();
                const subject = document.getElementById('subject').value;
                const message = document.getElementById('message').value.trim();

                const fullName = [firstName, lastName].filter(Boolean).join(' ');
                const subjectText = subject ? document.querySelector(`#subject option[value="${subject}"]`).textContent : '';
                
                const emailBody = `Hello,

${message}

---
Contact Details:
Name: ${fullName || 'Not provided'}
Email: ${email}
Subject: ${subjectText}

Best regards,
${fullName || 'Contact Form User'}`;

                return {
                    subject: `[Contact Form] ${subjectText}`,
                    body: emailBody
                };
            }

            function showMessage(text, type) {
                formMessage.textContent = text;
                formMessage.className = `p-4 rounded-lg ${type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'}`;
                formMessage.classList.remove('hidden');
            }

            function hideMessage() {
                formMessage.classList.add('hidden');
            }

            // Add some visual feedback for form interactions
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('transform', 'scale-[1.02]');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('transform', 'scale-[1.02]');
                });
            });
        });
    </script>
</body>
</html>
