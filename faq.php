<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
    $pageTitle = "FAQs";
    $pageDescription = $faqs['description'];
    include '_components/meta.php'; 
    ?>
</head>
<body class="page">
    <?php include '_components/header.php'; ?>
    
    <section class="faq-page">
        <div class="container container--narrow">
            <div class="faq-page__header">
                <h1 class="faq-page__title"><?php echo $faqs['title']; ?></h1>
                <p class="faq-page__desc"><?php echo $faqs['description']; ?></p>
            </div>
            
            <?php foreach ($faqs['faqsList'] as $categoryIndex => $category): ?>
                <div class="faq-category">
                    <h2 class="faq-category__title"><?php echo $category['title']; ?></h2>
                    <div class="faq-list">
                        <?php foreach ($category['faqs'] as $faqIndex => $faq): ?>
                            <div class="faq-item">
                                <button class="faq-item__trigger"
                                        onclick="toggleFaq('faq-<?php echo $categoryIndex; ?>-<?php echo $faqIndex; ?>')">
                                    <span class="faq-item__question"><?php echo $faq['title']; ?></span>
                                    <span class="material-icons faq-item__icon" id="icon-faq-<?php echo $categoryIndex; ?>-<?php echo $faqIndex; ?>">expand_more</span>
                                </button>
                                <div class="faq-item__answer is-hidden" id="faq-<?php echo $categoryIndex; ?>-<?php echo $faqIndex; ?>">
                                    <p><?php echo $faq['description']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="faq-support">
                <h3 class="faq-support__title">Still have questions?</h3>
                <p class="faq-support__desc">Can't find the answer you're looking for? Please chat with our friendly team.</p>
                <a href="mailto:<?php echo $common['supportEmail']; ?>" class="btn btn--primary btn--lg">
                    <span class="material-icons">email</span>
                    Contact Support
                </a>
            </div>
        </div>
    </section>
    
    <?php include '_components/footer.php'; ?>
    
    <script>
        function toggleFaq(faqId) {
            const faqContent = document.getElementById(faqId);
            const icon = document.getElementById('icon-' + faqId);
            
            if (faqContent.classList.contains('is-hidden')) {
                faqContent.classList.remove('is-hidden');
                icon.textContent = 'expand_less';
            } else {
                faqContent.classList.add('is-hidden');
                icon.textContent = 'expand_more';
            }
        }
    </script>
</body>
</html>
