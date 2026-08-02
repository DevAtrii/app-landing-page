<?php
/**
 * Content arrays for niche landing pages.
 * Each entry is keyed by slug used in root PHP files.
 */

return [
    'wordpress' => [
        'slug' => 'wordpress',
        'badge' => 'WordPress to Android App',
        'h1' => 'Convert Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-500 to-accent-500">WordPress Site</span> Into a Mobile App',
        'heroDescription' => 'Turn your WordPress blog, WooCommerce store, or membership site into a Play Store-ready Android app. No PHP plugins to hack together, no agency quotes. Paste your URL and build in minutes with WebInto.app.',
        'heroImage' => '/assets/hero.webp',
        'urlPlaceholder' => 'https://yourblog.wordpress.com',
        'platformName' => 'WordPress',
        'loadingMessages' => [
            'Checking WordPress site URL…',
            'Detecting theme and mobile layout…',
            'Preparing Android WebView shell…',
            'Adding splash screen and navigation…',
            'Preview ready. Download the app to finalize.',
        ],
        'stats' => [
            ['label' => 'Average time to first build', 'value' => '15 min', 'percent' => 92, 'color' => 'bg-brand-500'],
            ['label' => 'Cost vs custom native app', 'value' => '95% lower', 'percent' => 95, 'color' => 'bg-secondary-500'],
            ['label' => 'Repeat visit lift (app icon)', 'value' => '3.2×', 'percent' => 78, 'color' => 'bg-accent-500'],
            ['label' => 'Play Store readiness', 'value' => 'APK + AAB', 'percent' => 100, 'color' => 'bg-brand-600'],
        ],
        'benefits' => [
            ['icon' => 'store', 'title' => 'Play Store presence', 'text' => 'Customers install from Google Play instead of bookmarking your WordPress URL in Chrome.'],
            ['icon' => 'notifications_active', 'title' => 'Push for new posts', 'text' => 'Notify readers when you publish. Pair with OneSignal inside WebInto.app for reliable delivery.'],
            ['icon' => 'shopping_cart', 'title' => 'WooCommerce friendly', 'text' => 'Checkout, cart, and product pages run inside a native shell with controlled links and downloads.'],
            ['icon' => 'speed', 'title' => 'Keep one codebase', 'text' => 'Update content in WordPress as usual. The app loads your live site automatically.'],
        ],
        'useCases' => [
            'News and magazine sites that want home-screen icons',
            'WooCommerce shops targeting mobile-first buyers',
            'Course and membership sites built on WordPress',
            'Local business sites with booking or contact forms',
        ],
        'comparison' => [
            ['feature' => 'Install from Play Store', 'plugin' => 'Limited / wrapper plugins', 'webinto' => 'Yes'],
            ['feature' => 'Signed APK + AAB + keystore', 'plugin' => 'Rare', 'webinto' => 'Yes'],
            ['feature' => 'Remote URL & config updates', 'plugin' => 'Varies', 'webinto' => 'Yes'],
            ['feature' => 'Custom splash & navigation', 'plugin' => 'Basic', 'webinto' => 'Advanced'],
        ],
        'faq' => [
            ['q' => 'Can I convert WordPress to app without coding?', 'a' => 'Yes. WebInto.app wraps your live WordPress URL in a signed Android shell. You configure branding and features in the builder app, then download APK and AAB files for testing and Play Store upload.'],
            ['q' => 'Will WooCommerce checkout work inside the app?', 'a' => 'Most WooCommerce flows work in a WebView when your theme is mobile-responsive. You can allow external payment tabs, control downloads, and inject CSS if checkout needs padding under the status bar.'],
            ['q' => 'Do I need to rebuild when I publish a new blog post?', 'a' => 'No for content. New posts appear automatically because the app loads your site from the server. Rebuild only when you change native settings, permissions, or store binaries.'],
            ['q' => 'How do I publish the WordPress app on Google Play?', 'a' => 'Follow our Play Console guide after you download the AAB from WebInto.app. You will need store listings, a privacy policy, and data safety answers.'],
        ],
        'bottomCta' => [
            'title' => 'Launch your WordPress app today',
            'description' => 'Download WebInto.app, paste your WordPress URL, and ship a Play Store-ready Android app without hiring developers.',
        ],
        'sections' => [
            'stats' => [
                'eyebrow' => 'Outcomes',
                'title' => 'Why WordPress publishers ship an app',
                'subtitle' => 'Blogs, WooCommerce stores, and membership sites see stronger return visits when readers install instead of bookmark.',
            ],
            'benefits' => [
                'eyebrow' => 'Benefits',
                'title' => 'Built for WordPress, not generic wrappers',
            ],
            'useCases' => [
                'eyebrow' => 'Use cases',
                'title' => 'Who this is for',
                'subtitle' => 'WordPress site owners who want Play Store distribution without maintaining a separate mobile codebase.',
            ],
            'compare' => [
                'eyebrow' => 'Compare',
                'title' => 'WebInto.app vs WordPress app plugins',
                'subtitle' => 'Skip wrapper plugins that charge monthly fees and still struggle with Play Store signing.',
            ],
            'faq' => [
                'eyebrow' => 'FAQ',
                'title' => 'WordPress to app questions',
                'subtitle' => 'Answers for WooCommerce merchants, bloggers, and membership site owners.',
            ],
        ],
        'tutorialsIntro' => 'WordPress-specific guides plus Play Store upload, push notifications, and signing keys.',
        'tutorials' => [
            ['slug' => 'wordpress-site-to-android-app', 'title' => 'WordPress to app guide', 'icon' => 'article'],
            ['slug' => 'how-to-convert-website-to-android-app-without-coding', 'title' => 'Web to app step-by-step', 'icon' => 'rocket_launch'],
            ['slug' => 'how-to-upload-app-google-play-store', 'title' => 'Upload to Google Play', 'icon' => 'store'],
            ['slug' => 'onesignal-sdk-javascript-webinto-app', 'title' => 'Push notifications setup', 'icon' => 'notifications_active'],
        ],
    ],

    'shopify' => [
        'slug' => 'shopify',
        'badge' => 'Shopify to Android App',
        'h1' => 'Turn Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-500 to-accent-500">Shopify Store</span> Into an Android App',
        'heroDescription' => 'Give mobile shoppers a dedicated app icon on their home screen. Convert your Shopify storefront to a native Android wrapper with push notifications, faster return visits, and Play Store distribution.',
        'heroImage' => '/assets/hero.webp',
        'urlPlaceholder' => 'https://yourstore.myshopify.com',
        'platformName' => 'Shopify',
        'loadingMessages' => [
            'Validating Shopify storefront URL…',
            'Checking mobile theme compatibility…',
            'Building Android app shell…',
            'Configuring cart and checkout behavior…',
            'Preview ready. Download the app to finalize.',
        ],
        'stats' => [
            ['label' => 'Mobile cart completion lift', 'value' => '+18%', 'percent' => 72, 'color' => 'bg-brand-500'],
            ['label' => 'Launch vs native agency', 'value' => 'Days not months', 'percent' => 88, 'color' => 'bg-secondary-500'],
            ['label' => 'Return customer opens', 'value' => '2.8×', 'percent' => 70, 'color' => 'bg-accent-500'],
            ['label' => 'Store policy compliance', 'value' => 'You control listings', 'percent' => 100, 'color' => 'bg-brand-600'],
        ],
        'benefits' => [
            ['icon' => 'loyalty', 'title' => 'Brand beyond the browser', 'text' => 'Your store lives next to Instagram and WhatsApp on the home screen, not buried in browser tabs.'],
            ['icon' => 'campaign', 'title' => 'Push for sales', 'text' => 'Flash sales, abandoned cart reminders, and new drops reach installed users with push notifications.'],
            ['icon' => 'payments', 'title' => 'Checkout that you control', 'text' => 'Keep Shopify checkout in-app or open payment providers externally with link rules.'],
            ['icon' => 'inventory_2', 'title' => 'One Shopify admin', 'text' => 'Inventory, products, and discounts stay in Shopify. The app always loads your live storefront.'],
        ],
        'useCases' => [
            'DTC brands scaling mobile repeat purchases',
            'Fashion and beauty stores with loyal customers',
            'Single-product brands running Shopify landing pages',
            'Merchants who outgrew mobile web conversion rates',
        ],
        'comparison' => [
            ['feature' => 'Uses your live Shopify theme', 'shopifyApp' => 'Separate builder', 'webinto' => 'Yes'],
            ['feature' => 'No monthly Shopify App fee for shell', 'shopifyApp' => 'Often $49+/mo', 'webinto' => 'One-time per app'],
            ['feature' => 'APK sideload + Play Store AAB', 'shopifyApp' => 'Varies', 'webinto' => 'Yes'],
            ['feature' => 'Link rules for external checkout', 'shopifyApp' => 'Limited', 'webinto' => 'Per-URL rules'],
        ],
        'comparisonHeaders' => ['feature' => 'Feature', 'other' => 'Typical Shopify app', 'webinto' => 'WebInto.app'],
        'faq' => [
            ['q' => 'Can I convert Shopify to an Android app without rebuilding my store?', 'a' => 'Yes. WebInto.app loads your existing myshopify.com or custom domain storefront inside a native Android WebView. You do not duplicate products or rebuild the theme.'],
            ['q' => 'Will Shopify Pay and third-party checkout work?', 'a' => 'Most Shopify checkout flows work on mobile web. If a payment provider must open in Chrome, set an external link rule so the gateway opens in the browser while the rest of the shop stays in-app.'],
            ['q' => 'Is this better than a Shopify mobile app plugin?', 'a' => 'Plugins can work but often charge recurring fees and lock you into their templates. WebInto.app gives you signed binaries, deeper link control, and optional push via OneSignal.'],
            ['q' => 'How do I get the app on Google Play?', 'a' => 'Download the AAB from WebInto.app after building, then follow our step-by-step Play Console upload guide for store listings and compliance.'],
        ],
        'bottomCta' => [
            'title' => 'Put your Shopify store in the app drawer',
            'description' => 'Stop losing mobile sales to browser friction. Build a Shopify Android app in one sitting with WebInto.app.',
        ],
        'sections' => [
            'stats' => [
                'eyebrow' => 'Outcomes',
                'title' => 'Why Shopify merchants choose an app',
                'subtitle' => 'Mobile shoppers who install your store return more often than those who rely on browser bookmarks.',
            ],
            'benefits' => [
                'eyebrow' => 'Benefits',
                'title' => 'Built for Shopify storefronts',
            ],
            'useCases' => [
                'eyebrow' => 'Use cases',
                'title' => 'Who this is for',
                'subtitle' => 'DTC brands and Shopify merchants ready to move beyond mobile web conversion limits.',
            ],
            'compare' => [
                'eyebrow' => 'Compare',
                'title' => 'WebInto.app vs Shopify app builders',
                'subtitle' => 'Keep your live theme and checkout while avoiding recurring Shopify App marketplace fees.',
            ],
            'faq' => [
                'eyebrow' => 'FAQ',
                'title' => 'Shopify to app questions',
                'subtitle' => 'Checkout, Shopify Pay, and Play Store publishing for ecommerce founders.',
            ],
        ],
        'tutorialsIntro' => 'Ecommerce-focused tutorials for wrapping your storefront and publishing to Google Play.',
        'tutorials' => [
            ['slug' => 'how-to-convert-website-to-android-app-without-coding', 'title' => 'Web to app step-by-step', 'icon' => 'rocket_launch'],
            ['slug' => 'how-to-upload-app-google-play-store', 'title' => 'Upload to Google Play', 'icon' => 'store'],
            ['slug' => 'onesignal-sdk-javascript-webinto-app', 'title' => 'Push for flash sales', 'icon' => 'notifications_active'],
        ],
    ],

    'lovable' => [
        'slug' => 'lovable',
        'badge' => 'Lovable to Android App',
        'h1' => 'Ship Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-500 to-accent-500">Lovable Project</span> as an Android App',
        'heroDescription' => 'You built a slick MVP with Lovable in a weekend. Now investors and early users ask for an app. Wrap your deployed URL in a real Android package without rewriting the stack in Kotlin.',
        'heroImage' => '/assets/hero.webp',
        'urlPlaceholder' => 'https://your-app.lovable.app',
        'platformName' => 'Lovable',
        'loadingMessages' => [
            'Connecting to your Lovable deployment…',
            'Analyzing responsive layout…',
            'Generating Android wrapper…',
            'Applying icon and splash placeholders…',
            'Preview ready. Download the app to finalize.',
        ],
        'stats' => [
            ['label' => 'MVP to installable APK', 'value' => '< 1 day', 'percent' => 90, 'color' => 'bg-brand-500'],
            ['label' => 'Native rewrite avoided', 'value' => '$15k+ saved', 'percent' => 85, 'color' => 'bg-secondary-500'],
            ['label' => 'Demo-ready for investors', 'value' => 'Same week', 'percent' => 80, 'color' => 'bg-accent-500'],
            ['label' => 'Push & deep links', 'value' => 'Configurable', 'percent' => 75, 'color' => 'bg-brand-600'],
        ],
        'benefits' => [
            ['icon' => 'rocket_launch', 'title' => 'Validate on real devices', 'text' => 'Hand testers an APK before you commit to a full native rewrite.'],
            ['icon' => 'code', 'title' => 'Keep shipping in Lovable', 'text' => 'Deploy updates to your URL. Content changes flow to the app automatically.'],
            ['icon' => 'groups', 'title' => 'Founder-friendly workflow', 'text' => 'Configure splash, theme, and nav from your phone with the WebInto.app builder.'],
            ['icon' => 'verified', 'title' => 'Play Store path included', 'text' => 'Download AAB and keystore files when you are ready for public listing.'],
        ],
        'useCases' => [
            'AI-built SaaS dashboards seeking mobile distribution',
            'Startup MVPs demoing to angels and accelerators',
            'Internal tools your team wants on phones',
            'Client projects delivered from Lovable prototypes',
        ],
        'comparison' => [
            ['feature' => 'Time to first APK', 'rewrite' => 'Weeks to months', 'webinto' => 'Same day'],
            ['feature' => 'Reuse Lovable frontend', 'rewrite' => 'No', 'webinto' => 'Yes'],
            ['feature' => 'Signed release binaries', 'rewrite' => 'After dev hire', 'webinto' => 'Included'],
            ['feature' => 'Custom CSS/JS injection', 'rewrite' => 'Full control', 'webinto' => 'URL-scoped'],
        ],
        'comparisonHeaders' => ['feature' => 'Feature', 'other' => 'Native rewrite', 'webinto' => 'WebInto.app'],
        'faq' => [
            ['q' => 'Can I turn a Lovable site into an Android app?', 'a' => 'Yes. Deploy your Lovable project to a public HTTPS URL, then paste that URL into WebInto.app. The builder creates a signed Android shell around your existing React-based site.'],
            ['q' => 'Do I need to export code from Lovable first?', 'a' => 'No. As long as your deployment is live over HTTPS, the app loads it directly. Export code only if you plan a separate native codebase later.'],
            ['q' => 'Will auth and APIs still work?', 'a' => 'Most Lovable stacks use standard web auth and APIs. Test login flows on a real device and adjust link rules or injected CSS if the status bar overlaps your header.'],
            ['q' => 'When should I rebuild the APK?', 'a' => 'Rebuild when you change native plugins, permissions, or Play-required settings. Routine Lovable deploys update automatically inside the WebView.'],
        ],
        'bottomCta' => [
            'title' => 'Your Lovable MVP deserves an app icon',
            'description' => 'Download WebInto.app and convert your deployed Lovable URL into an Android app before your next investor call.',
        ],
        'sections' => [
            'stats' => [
                'eyebrow' => 'Outcomes',
                'title' => 'Why Lovable founders ship an APK first',
                'subtitle' => 'Validate on real devices before you budget a Kotlin rewrite or hire mobile contractors.',
            ],
            'benefits' => [
                'eyebrow' => 'Benefits',
                'title' => 'Built for Lovable MVPs',
            ],
            'useCases' => [
                'eyebrow' => 'Use cases',
                'title' => 'Who this is for',
                'subtitle' => 'Founders and agencies turning AI-built prototypes into installable Android demos.',
            ],
            'compare' => [
                'eyebrow' => 'Compare',
                'title' => 'WebInto.app vs a native rewrite',
                'subtitle' => 'Ship this week with your existing Lovable deployment instead of restarting in Android Studio.',
            ],
            'faq' => [
                'eyebrow' => 'FAQ',
                'title' => 'Lovable to app questions',
                'subtitle' => 'Deployment URLs, auth flows, and when to rebuild your APK.',
            ],
        ],
        'tutorialsIntro' => 'Founder-friendly guides for shipping an APK from your Lovable deployment and passing Play Console checks.',
        'tutorials' => [
            ['slug' => 'how-to-convert-website-to-android-app-without-coding', 'title' => 'Web to app step-by-step', 'icon' => 'rocket_launch'],
            ['slug' => 'how-to-upload-app-google-play-store', 'title' => 'Upload to Google Play', 'icon' => 'store'],
            ['slug' => 'how-to-get-sha1-sha256-google-play-console', 'title' => 'SHA keys for Play Console', 'icon' => 'vpn_key'],
        ],
    ],

    'base44' => [
        'slug' => 'base44',
        'badge' => 'Base44 to Android App',
        'h1' => 'Convert Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-500 to-accent-500">Base44 App</span> to Android',
        'heroDescription' => 'Base44 helps you spin up AI-powered web apps fast. WebInto.app gets that same project onto Android with a home-screen icon, push-ready shell, and Play Store signing files.',
        'heroImage' => '/assets/hero.webp',
        'urlPlaceholder' => 'https://your-project.base44.app',
        'platformName' => 'Base44',
        'loadingMessages' => [
            'Fetching Base44 project URL…',
            'Verifying HTTPS and mobile viewport…',
            'Wrapping UI in Android WebView…',
            'Preparing store-ready signing package…',
            'Preview ready. Download the app to finalize.',
        ],
        'stats' => [
            ['label' => 'AI app to Android', 'value' => '1 session', 'percent' => 88, 'color' => 'bg-brand-500'],
            ['label' => 'Client demo readiness', 'value' => 'Hours', 'percent' => 82, 'color' => 'bg-secondary-500'],
            ['label' => 'Maintenance overhead', 'value' => 'Low', 'percent' => 65, 'color' => 'bg-accent-500'],
            ['label' => 'Distribution options', 'value' => 'APK + Play', 'percent' => 100, 'color' => 'bg-brand-600'],
        ],
        'benefits' => [
            ['icon' => 'smartphone', 'title' => 'Mobile distribution', 'text' => 'Share an installable APK with beta users or publish on Google Play when you are ready.'],
            ['icon' => 'sync', 'title' => 'Live project URL', 'text' => 'Point the app at your Base44 deployment. Updates on the web reflect in the app without resubmitting every copy change.'],
            ['icon' => 'tune', 'title' => 'Native polish', 'text' => 'Add splash screens, bottom navigation, and progress bars so the wrapper feels intentional.'],
            ['icon' => 'share', 'title' => 'Shareable with clients', 'text' => 'Agencies can deliver Android packages alongside Base44 web deliverables.'],
        ],
        'useCases' => [
            'Base44 prototypes shown to paying clients on phones',
            'AI-generated internal dashboards for field teams',
            'Hackathon projects moving to beta testers',
            'Creators monetizing tools built on Base44',
        ],
        'comparison' => [
            ['feature' => 'Android APK without Java/Kotlin', 'pwa' => 'Bookmark only', 'webinto' => 'Yes'],
            ['feature' => 'Play Store AAB + keystore', 'pwa' => 'No', 'webinto' => 'Yes'],
            ['feature' => 'Push notifications', 'pwa' => 'Unreliable', 'webinto' => 'OneSignal ready'],
            ['feature' => 'Per-URL link control', 'pwa' => 'Browser limited', 'webinto' => 'Advanced rules'],
        ],
        'comparisonHeaders' => ['feature' => 'Feature', 'other' => 'Mobile bookmark / PWA', 'webinto' => 'WebInto.app'],
        'faq' => [
            ['q' => 'How do I convert Base44 to an Android app?', 'a' => 'Publish your Base44 project to a public URL, open WebInto.app on Android, paste the link in the wizard, customize branding, and build. Download APK for testing or AAB for Play Store.'],
            ['q' => 'Will my Base44 app look native?', 'a' => 'The UI is your web project inside a native shell with splash, optional bottom nav, and system integrations. It feels like an app because users install it from Play or sideload an APK.'],
            ['q' => 'Can I update the app after Base44 changes?', 'a' => 'Yes for web content and many dashboard settings via remote updates. Rebuild when you change native permissions or need a new signed binary for Play.'],
            ['q' => 'Does this work worldwide?', 'a' => 'Yes. WebInto.app and Google Play support developers globally. Use HTTPS deployments and follow Play policy for your target markets.'],
        ],
        'bottomCta' => [
            'title' => 'Take your Base44 build mobile',
            'description' => 'Download WebInto.app and turn your Base44 deployment into a shareable Android app today.',
        ],
        'sections' => [
            'stats' => [
                'eyebrow' => 'Outcomes',
                'title' => 'Why Base44 builders add Android',
                'subtitle' => 'Client demos and beta tests land better when stakeholders install an APK instead of opening a browser tab.',
            ],
            'benefits' => [
                'eyebrow' => 'Benefits',
                'title' => 'Built for Base44 projects',
            ],
            'useCases' => [
                'eyebrow' => 'Use cases',
                'title' => 'Who this is for',
                'subtitle' => 'Agencies, creators, and teams distributing AI-generated web apps to mobile users.',
            ],
            'compare' => [
                'eyebrow' => 'Compare',
                'title' => 'WebInto.app vs mobile bookmarks',
                'subtitle' => 'Real APK and Play Store binaries beat PWA shortcuts that users forget to keep.',
            ],
            'faq' => [
                'eyebrow' => 'FAQ',
                'title' => 'Base44 to app questions',
                'subtitle' => 'Publishing, updates, and Play Store signing for AI-built projects.',
            ],
        ],
        'tutorialsIntro' => 'Guides for distributing Base44 projects as installable Android apps instead of browser bookmarks.',
        'tutorials' => [
            ['slug' => 'how-to-convert-website-to-android-app-without-coding', 'title' => 'Web to app step-by-step', 'icon' => 'rocket_launch'],
            ['slug' => 'pwa-to-android-app', 'title' => 'PWA vs native app', 'icon' => 'compare'],
            ['slug' => 'how-to-upload-app-google-play-store', 'title' => 'Upload to Google Play', 'icon' => 'store'],
        ],
    ],
];
