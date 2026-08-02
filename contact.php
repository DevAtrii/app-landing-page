<?php
require_once 'config.php';
$schemaPageType = 'contact';
$schemaContext = [
    'title' => t('contact_title'),
    'description' => t('contact_subtitle'),
    'url' => 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . i18n_locale_url('/contact' . $EXTENSION),
];
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars(i18n_current_locale()); ?>">
<head>
    <?php 
    $pageTitle = t('contact_title');
    $pageDescription = t('contact_subtitle');
    include '_components/meta.php'; 
    ?>
</head>
<body class="page">
    <?php include '_components/header.php'; ?>
    
    <section class="contact-page">
        <div class="container container--wide">
            <div class="section-header">
                <h1 class="faq-page__title"><?php echo htmlspecialchars(t('contact_title')); ?></h1>
                <p class="faq-page__desc"><?php echo htmlspecialchars(t('contact_subtitle')); ?></p>
            </div>
            
            <div class="contact-page__grid">
                <div>
                    <h2 class="contact-info__title">Get in Touch</h2>
                    <p class="contact-info__desc">Have questions about <?php echo $common['appName']; ?>? We're here to help!</p>
                    
                    <div class="contact-methods">
                        <div class="contact-method">
                            <div class="contact-method__icon contact-method__icon--blue">
                                <span class="material-icons">email</span>
                            </div>
                            <div>
                                <h3 class="contact-method__title">Email</h3>
                                <p class="contact-method__desc">Send us an email and we'll get back to you within 24 hours.</p>
                                <a href="mailto:<?php echo $common['supportEmail']; ?>" class="contact-method__link"><?php echo $common['supportEmail']; ?></a>
                            </div>
                        </div>
                        
                        <div class="contact-method">
                            <div class="contact-method__icon contact-method__icon--green">
                                <span class="material-icons">chat</span>
                            </div>
                            <div>
                                <h3 class="contact-method__title">Community</h3>
                                <p class="contact-method__desc">Join our community for discussions and updates.</p>
                                <div class="contact-method__socials">
                                    <?php foreach ($footer['socials'] as $social): ?>
                                        <a href="<?php echo $social['link']; ?>" target="_blank" class="contact-method__link"><?php echo $social['title']; ?></a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-method">
                            <div class="contact-method__icon contact-method__icon--purple">
                                <span class="material-icons">help</span>
                            </div>
                            <div>
                                <h3 class="contact-method__title">Support</h3>
                                <p class="contact-method__desc">Check our FAQ section for quick answers.</p>
                                <a href="<?php echo i18n_locale_url('/faq' . $EXTENSION); ?>" class="contact-method__link">Visit FAQ</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-download">
                        <h3 class="contact-download__title">Download <?php echo $common['appName']; ?></h3>
                        <p class="contact-download__desc">Get the app and start managing your subscriptions today.</p>
                        <div class="contact-download__badges">
                            <?php if ($common['appStoreUrl']): ?>
                                <a href="<?php echo $common['appStoreUrl']; ?>" target="_blank" class="store-badge store-badge--sm">
                                    <img src="./assets/app-store-download.svg" alt="Download on the App Store">
                                </a>
                            <?php endif; ?>
                            <?php if ($common['googlePlayUrl']): ?>
                                <a href="<?php echo $common['googlePlayUrl']; ?>" target="_blank" class="store-badge store-badge--sm">
                                    <img src="./assets/google-play-download.svg" alt="Get it on Google Play">
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form-card">
                    <h2 class="contact-form-card__title">Send us a Message</h2>
                    <form id="contactForm" class="form">
                        <div class="form-row">
                            <div class="form-field">
                                <label for="firstName">First Name</label>
                                <input type="text" id="firstName" name="firstName" class="form-input" placeholder="John">
                            </div>
                            <div class="form-field">
                                <label for="lastName">Last Name</label>
                                <input type="text" id="lastName" name="lastName" class="form-input" placeholder="Doe">
                            </div>
                        </div>
                        <div class="form-field">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required class="form-input" placeholder="john@example.com">
                        </div>
                        <div class="form-field">
                            <label for="subject">Subject</label>
                            <select id="subject" name="subject" required class="form-select">
                                <option value="">Select a subject</option>
                                <option value="general">General Inquiry</option>
                                <option value="support">Technical Support</option>
                                <option value="feature">Feature Request</option>
                                <option value="bug">Bug Report</option>
                                <option value="partnership">Partnership</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="5" required class="form-textarea" placeholder="Tell us how we can help you..."></textarea>
                        </div>
                        <button type="submit" id="submitBtn" class="btn btn--primary btn--lg" style="width:100%">
                            <span class="material-icons">send</span>
                            Send via Email
                        </button>
                        <div id="formMessage" class="form-message is-hidden"></div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <?php include '_components/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('contactForm');
            const formMessage = document.getElementById('formMessage');
            const supportEmail = '<?php echo $common['supportEmail']; ?>';

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (validateForm()) sendEmail();
            });

            function validateForm() {
                const email = document.getElementById('email').value.trim();
                const subject = document.getElementById('subject').value;
                const message = document.getElementById('message').value.trim();
                hideMessage();
                if (!email || !subject || !message) {
                    showMessage('Please fill in all required fields.', 'error');
                    return false;
                }
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    showMessage('Please enter a valid email address.', 'error');
                    return false;
                }
                return true;
            }

            function sendEmail() {
                const formData = getFormData();
                const mailtoUrl = `mailto:${supportEmail}?subject=${encodeURIComponent(formData.subject)}&body=${encodeURIComponent(formData.body)}`;
                try {
                    const mailtoLink = document.createElement('a');
                    mailtoLink.href = mailtoUrl;
                    mailtoLink.click();
                    showMessage('Opening your email client...', 'success');
                    setTimeout(() => { form.reset(); hideMessage(); }, 5000);
                } catch (error) {
                    showMessage('Unable to open email client.', 'error');
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
                const emailBody = `Hello,\n\n${message}\n\n---\nContact Details:\nName: ${fullName || 'Not provided'}\nEmail: ${email}\nSubject: ${subjectText}\n\nBest regards,\n${fullName || 'Contact Form User'}`;
                return { subject: `[Contact Form] ${subjectText}`, body: emailBody };
            }

            function showMessage(text, type) {
                formMessage.textContent = text;
                formMessage.className = 'form-message form-message--' + type;
            }

            function hideMessage() {
                formMessage.className = 'form-message is-hidden';
            }
        });
    </script>
</body>
</html>
