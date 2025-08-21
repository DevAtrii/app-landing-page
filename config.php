<?php

// header


// index
$home = [
    "title" => "Stay on top of your <span class='text-blue-600'>subscriptions</span> effortlessly",
    "description" => "SubFox is your go-to app for managing subscriptions, helping you keep track and save money.",
    "screenshot" => "/assets/hero.webp",
];

// common
$common = [
    'appStoreUrl' => "https://apps.apple.com/us/app/subfox/id6754495902",
    'googlePlayUrl' => "https://play.google.com/store/apps/details?id=com.app.app", // could be null
    'appName' => "SubFox",
    'appVersion' => "1.0.0",
    'appTitle' => "Never lose track of your subscriptions again",
    'appDescription' => "SubFox is a subscription management app that helps you track your subscriptions and save money.",
    'appRatingAppStore' => [
        'totalReviews' => 10,
        'rating' => 4.5,
    ],
    'appRatingGooglePlay' => [
        'totalReviews' => 10,
        'rating' => 4.5,
    ], // could be null
    'appIcon' => "/assets/app_icon.webp",
    'supportEmail' => "support@subfox.app",
    'screenshotRoundedCorners' => true, // Set to false for sharp corners
];


// features with screenshots
$featuresScreenshots = [
    "title" => "Powerful Features to Enhance Your Experience",
    "description" => "Discover the tools that make managing your subscriptions a breeze.",
    "featuresList" => [
        [
            "title" => "Custom Services",
            "description" => "Easily add and manage custom services tailored to your needs.",
            "image" => "/assets/feature_services.webp",
        ],
        [
            "title" => "Payment Dates",
            "description" => "Select and track your subscription payment dates effortlessly.",
            "image" => "/assets/feature_payment_date.webp",
        ],
        [
            "title" => "Dark Mode",
            "description" => "Switch to dark mode for a comfortable viewing experience at night.",
            "image" => "/assets/feature_darkmode.webp",
        ],
    ]
];


$featuresIcons = [
    "title" => "Comprehensive Subscription Management",
    "description" => "Explore a wide range of features designed to enhance your subscription management experience.",
    "featuresList" => [
        [
            "title" => "Offline Access",
            "description" => "Access your subscriptions even when you're offline.",
            "icon" => "cloud_off",
        ],
        [
            "title" => "Material 3 with iOS Feel",
            "description" => "Enjoy a seamless experience with Material 3 design and Cupertino iOS aesthetics.",
            "icon" => "phone_iphone",
        ],
        [
            "title" => "Payment Reminders",
            "description" => "Never miss a payment with timely reminders.",
            "icon" => "notifications_active",
        ],
        [
            "title" => "Widgets",
            "description" => "Add widgets to your home screen for quick access.",
            "icon" => "widgets",
        ],
        [
            "title" => "Custom Service",
            "description" => "Tailor your subscription management to your needs.",
            "icon" => "build",
        ],
        [
            "title" => "Categories",
            "description" => "Organize your subscriptions into categories.",
            "icon" => "category",
        ],
        [
            "title" => "Payment Methods",
            "description" => "Manage multiple payment methods with ease.",
            "icon" => "credit_card",
        ],
        [
            "title" => "Trial Subscriptions",
            "description" => "Keep track of your trial subscriptions and never get charged unexpectedly.",
            "icon" => "hourglass_empty",
        ],
    ]
];

$ratings = [
    "title" => "What our users say",
    "description" => "Join thousands of satisfied users who have taken control of their subscriptions and saved money.",
    "ratingsList" => [
        [
            "title" => "Kurosaki",
            "description" => "i tried another subscription manager before but this one looks so easy to use and better UI.. thanks to the develper👌",
            "rating" => 5,
            "image" => null,
        ],
        [
            "title" => "Faji",
            "description" => "great app for tracking subscriptions",
            "rating" => 5,
            "image" => null,
        ],
        [
            "title" => "Rizwan Devid",
            "description" => "Very useful and simple to use. Highly recommend! ✨",
            "rating" => 4,
            "image" => null,
        ],
        [
            "title" => "Aldy Iqman Nur Furqon",
            "description" => "I think it's the best digital service subscription manager and reminder, clean user experience, light ⭐",
            "rating" => 5,
            "image" => null,
        ],
    ]
];

$bottomCta = [
    "title" => "Get the app",
    "description" => "SubFox is a subscription management app that helps you track your subscriptions and save money.",
];


// footer
$footer = [
    'description' => "SubFox is a subscription management app that helps you track your subscriptions and save money.",
    'navigation' => [
        [
            "title" => "FAQs",
            "link" => "/faq.php",
            "isExternal" => false,
        ],
        [
            "title" => "Contact",
            "link" => "/contact.php",
            "isExternal" => false,
        ],
        [
            "title" => "Feature Request",
            "link" => "https://subfox.canny.io/feature-requests",
            "isExternal" => true,
        ],
        [
            "title" => "Press Kit",
            "link" => "/assets/press-kit.zip",
            "isExternal" => true,
        ],
    ],
    'socials' => [
        [
            "title" => "Reddit",
            "link" => "https://www.reddit.com/r/subfox",
            "isExternal" => true,
        ],
        [
            "title" => "X",
            "link" => "https://x.com/subfoxapp",
            "isExternal" => true,
        ],
        [
            "title" => "GitHub",
            "link" => "https://github.com/subfoxapp",
            "isExternal" => true,
        ],
        [
            "title" => "Youtube",
            "link" => "https://youtube.com/@subfoxapp",
            "isExternal" => true,
        ],


    ],
    "legal" => [
        [
            "title" => "Privacy Policy (Android)",
            "link" => "/privacy-policy-android.php",
            "isExternal" => false,
        ],
        [
            "title" => "Privacy Policy (iOS)",
            "link" => "/privacy-policy-ios.php",
            "isExternal" => false,
        ],

        [
            "title" => "Terms of Service",
            "link" => "/terms-of-services.php",
            "isExternal" => false,
        ],


    ],
    "copyright" => "© 2025 SubFox. All rights reserved.",
    "message" => "Made with ❤️ in Pakistan",
];


// faqs
$faqs = [
    "title" => "Frequently Asked Questions",
    "description" => "Find answers to common questions about SubFox and subscription management.",
    "faqsList" => [
        [
            "title" => "Getting Started",
            "faqs" => [
                [
                    "title" => "What is SubFox?",
                    "description" => "SubFox is a comprehensive subscription management app that helps you track all your subscriptions, manage payment dates, and save money by avoiding unwanted charges.",
                ],
                [
                    "title" => "How much does SubFox cost?",
                    "description" => "SubFox works on freemium model. All features are available at no cost with limited recurring tasks to help you manage your subscriptions effectively.",
                ],
                [
                    "title" => "Which devices does SubFox support?",
                    "description" => "SubFox is available for iOS devices through the App Store and Android devices through Google Play Store. The app works on phones and tablets.",
                ]
            ]
        ],
        [
            "title" => "Managing Subscriptions",
            "faqs" => [
                [
                    "title" => "How do I add a subscription?",
                    "description" => "Tap the '+' button, select a service from our extensive list or create a custom one, enter your subscription details like cost and billing cycle, and set your payment date.",
                ],
                [
                    "title" => "Can I add custom services not in your list?",
                    "description" => "Yes! SubFox allows you to create custom services for any subscription. Just tap 'Add Custom Service' and enter the service name, logo, and other details.",
                ],
                [
                    "title" => "How do I edit or cancel a subscription?",
                    "description" => "Tap on any subscription to view details, then use the edit button to modify information or mark it as cancelled to stop tracking future payments.",
                ]
            ]
        ],
        [
            "title" => "Features & Notifications",
            "faqs" => [
                [
                    "title" => "Will I get reminders for upcoming payments?",
                    "description" => "Yes, SubFox sends push notifications before your subscription payments are due. You can customize when you receive these reminders in the app settings.",
                ],
                [
                    "title" => "Does SubFox work offline?",
                    "description" => "Yes, SubFox works completely offline. All your subscription data is stored locally on your device, so you can access and manage your subscriptions anytime.",
                ],
                [
                    "title" => "Can I organize subscriptions into categories?",
                    "description" => "Absolutely! You can categorize your subscriptions into groups like Entertainment, Productivity, Health, etc., to keep them organized and easy to find.",
                ]
            ]
        ],
        [
            "title" => "Data & Privacy",
            "faqs" => [
                [
                    "title" => "Is my subscription data secure?",
                    "description" => "Yes, all your data is stored securely on your device. SubFox doesn't collect or store your personal subscription information on external servers.",
                ],
                [
                    "title" => "Can I export my subscription data?",
                    "description" => "Currently, SubFox stores all data locally on your device. We're working on adding export features in future updates to help you backup your subscription data.",
                ],
                [
                    "title" => "What happens if I delete the app?",
                    "description" => "If you delete SubFox, all your subscription data will be lost since it's stored locally. We recommend keeping the app installed or waiting for our upcoming backup feature.",
                ]
            ]
        ]
    ]
];