<?php
// Include config for global variables
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $pageTitle = "Terms of Service";
    $pageDescription = "Terms of Service for " . $common['appName'] . " App";
    include '_components/meta.php';
    ?>
    <style>
        /* Modern Privacy Policy CSS - Universal Design */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.7;
            color: #2d3748;
            background: #f7fafc;
            min-height: 100vh;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 1rem;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header p {
            font-size: 1.25rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .content {
            background: white;
            border-radius: 1rem;
            padding: 3rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            margin-bottom: 2rem;
        }

        /* Section Styling */
        .content>strong {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin: 2.5rem 0 1.5rem 0;
            padding: 1rem 0 0.75rem 0;
            border-bottom: 3px solid #667eea;
            position: relative;
        }

        .content>strong:first-child {
            margin-top: 0;
            font-size: 1.75rem;
            color: #667eea;
            text-align: center;
            border-bottom: none;
            padding-bottom: 1.5rem;
        }

        .content>strong::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .content>strong:first-child::after {
            display: none;
        }

        /* Paragraph Styling */
        .content p {
            margin-bottom: 1.25rem;
            line-height: 1.8;
            font-size: 1rem;
            color: #4a5568;
        }

        /* List Styling */
        .content ul,
        .content ol {
            margin: 1.5rem 0;
            padding-left: 0;
        }

        .content li {
            margin-bottom: 0.75rem;
            line-height: 1.8;
            position: relative;
            padding-left: 2rem;
            color: #4a5568;
        }

        .content ul li::before {
            content: '•';
            color: #667eea;
            font-weight: bold;
            position: absolute;
            left: 0;
            font-size: 1.2rem;
        }

        .content ol {
            counter-reset: item;
        }

        .content ol li {
            counter-increment: item;
        }

        .content ol li::before {
            content: counter(item) '.';
            color: #667eea;
            font-weight: 600;
            position: absolute;
            left: 0;
        }

        /* Link Styling */
        .content a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            border-bottom: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .content a:hover {
            color: #5a67d8;
            border-bottom-color: #5a67d8;
        }

        /* Special Boxes */
        .info-box {
            background: linear-gradient(135deg, #ebf8ff 0%, #f0fff4 100%);
            border: 1px solid #bee3f8;
            border-left: 4px solid #667eea;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin: 2rem 0;
        }

        .warning-box {
            background: linear-gradient(135deg, #fffbeb 0%, #fef5e7 100%);
            border: 1px solid #fed7aa;
            border-left: 4px solid #f59e0b;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin: 2rem 0;
        }

        .contact-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #ecfeff 100%);
            border: 1px solid #bae6fd;
            border-radius: 1rem;
            padding: 2rem;
            margin: 2rem 0;
            text-align: center;
        }

        .contact-box strong {
            color: #0369a1;
            font-size: 1.25rem;
        }

        /* Effective Date Styling */
        .effective-date {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: #718096;
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 1rem 0.5rem;
            }

            .content {
                padding: 2rem 1.5rem;
            }

            .header h1 {
                font-size: 2.25rem;
            }

            .header p {
                font-size: 1.125rem;
            }

            .content>strong {
                font-size: 1.25rem;
            }

            .content>strong:first-child {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .content {
                padding: 1.5rem 1rem;
            }

            .content li {
                padding-left: 1.5rem;
            }
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
            }

            .header {
                background: #667eea !important;
                -webkit-print-color-adjust: exact;
            }

            .content {
                box-shadow: none;
                border: 1px solid #ccc;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <?php include '_components/header.php'; ?>

    <div class="container">
        <div class="header">
            <h1>Terms of Service</h1>
            <p><?php echo $common['appName']; ?> Application</p>
        </div>

        <div class="content">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md"> <a id="generate-button"
                    style="color: white!important;" class="text-white   font-bold"
                    href="https://app-privacy-policy-generator.firebaseapp.com/" target="_blank">Generate </a>
            </button>
            <p>After Generating the terms of service, you can copy the terms of service and paste it here.</p>
        
        </div>
    </div>

    <!-- Footer -->
    <?php include '_components/footer.php'; ?>
</body>

</html>