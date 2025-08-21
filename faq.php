<?php
// Include config for global variables
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
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-50 min-h-screen font-body">
    <!-- Header -->
    <?php include '_components/header.php'; ?>
    
    <!-- FAQ Section -->
    <section class="pt-24 pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4"><?php echo $faqs['title']; ?></h1>
                <p class="text-xl text-gray-600"><?php echo $faqs['description']; ?></p>
            </div>
            
            <!-- FAQ Categories -->
            <?php foreach ($faqs['faqsList'] as $categoryIndex => $category): ?>
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6"><?php echo $category['title']; ?></h2>
                    
                    <div class="space-y-4">
                        <?php foreach ($category['faqs'] as $faqIndex => $faq): ?>
                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                                <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                        onclick="toggleFaq('faq-<?php echo $categoryIndex; ?>-<?php echo $faqIndex; ?>')">
                                    <span class="text-lg font-medium text-gray-900"><?php echo $faq['title']; ?></span>
                                    <span class="material-icons transform transition-transform duration-200" id="icon-faq-<?php echo $categoryIndex; ?>-<?php echo $faqIndex; ?>">expand_more</span>
                                </button>
                                <div class="hidden px-6 pb-4" id="faq-<?php echo $categoryIndex; ?>-<?php echo $faqIndex; ?>">
                                    <p class="text-gray-600 leading-relaxed"><?php echo $faq['description']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <!-- Contact Support -->
            <div class="mt-16 text-center bg-white rounded-xl border border-gray-200 p-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Still have questions?</h3>
                <p class="text-gray-600 mb-6">Can't find the answer you're looking for? Please chat with our friendly team.</p>
                <a href="mailto:<?php echo $common['supportEmail']; ?>" 
                   class="inline-flex items-center px-6 py-3 text-lg font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <span class="material-icons mr-2">email</span>
                    Contact Support
                </a>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <?php include '_components/footer.php'; ?>
    
    <script>
        function toggleFaq(faqId) {
            const faqContent = document.getElementById(faqId);
            const icon = document.getElementById('icon-' + faqId);
            
            if (faqContent.classList.contains('hidden')) {
                faqContent.classList.remove('hidden');
                icon.textContent = 'expand_less';
            } else {
                faqContent.classList.add('hidden');
                icon.textContent = 'expand_more';
            }
        }
    </script>
</body>
</html>
