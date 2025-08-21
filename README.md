# Modern App Landing Page Template

A professional, ready-to-use landing page template for mobile apps. Built with PHP and Tailwind CSS, this template is designed for developers who want to quickly create a beautiful website for their mobile application.

## 🚀 Quick Setup (5 Minutes)

1. **Install dependencies**:
   ```bash
   npm install
   ```

2. **Build CSS**:
   ```bash
   npm run build
   ```

3. **Customize your app** (see below)

4. **Launch**:
   ```bash
   php -S localhost:8000
   ```

## 📝 How to Customize for Your App

### Step 1: Update App Information
Edit `config.php` and update these basic details:

```php
$common = [
    'appName' => "Your App Name",                    // Replace with your app name
    'appTitle' => "Your app tagline here",           // Main headline
    'appDescription' => "Describe what your app does", // App description
    'appIcon' => "/assets/app_icon.webp",            // Your app icon
    'supportEmail' => "support@yourapp.com",         // Your support email
    'appStoreUrl' => "https://apps.apple.com/...",   // Your App Store link
    'googlePlayUrl' => "https://play.google.com/...", // Your Google Play link
    'screenshotRoundedCorners' => true,              // true = rounded, false = sharp corners
];
```

### Step 2: Add Your App Screenshots
1. Place your screenshots in the `/assets/` folder
2. Update the paths in `config.php`:

```php
// Hero section screenshot
$home = [
    "screenshot" => "/assets/your_main_screenshot.png",
];

// Feature screenshots
$featuresScreenshots = [
    "featuresList" => [
        [
            "title" => "Easy Setup",
            "description" => "Get started in minutes",
            "image" => "/assets/feature_1.png",      // Your screenshot here
        ],
        [
            "title" => "Smart Notifications", 
            "description" => "Never miss important updates",
            "image" => "/assets/feature_2.png",      // Your screenshot here
        ],
        // Add more features...
    ]
];
```

### Step 3: Customize App Features
Update the features that appear on your homepage:

```php
// Features with icons (no screenshots needed)
$featuresIcons = [
    "featuresList" => [
        [
            "title" => "Fast & Reliable",
            "description" => "Lightning fast performance",
            "icon" => "speed",                       // Material Design icon name
        ],
        [
            "title" => "Secure",
            "description" => "Your data is always protected", 
            "icon" => "security",
        ],
    ]
];
```

### Step 4: Add Customer Reviews
```php
$ratings = [
    "ratingsList" => [
        [
            "title" => "John Smith",
            "description" => "This app changed how I work!",
            "rating" => 5,
            "image" => "/assets/user_1.jpg",         // User photo (optional)
        ],
        // Add more reviews...
    ]
];
```

### Step 5: Update Footer & Contact Info
```php
$footer = [
    'navigation' => [
        ['title' => 'About', 'link' => '/about'],
        ['title' => 'Features', 'link' => '/features'],
        ['title' => 'Support', 'link' => '/contact'],
    ],
    'socials' => [
        ['title' => 'Twitter', 'link' => 'https://twitter.com/yourapp'],
        ['title' => 'Instagram', 'link' => 'https://instagram.com/yourapp'],
    ],
];
```

## 🎨 Design Options

### Screenshot Corners
Set `'screenshotRoundedCorners' => false` in config.php for sharp, modern corners, or `true` for friendly rounded corners.

### Colors
The template uses a clean blue color scheme that works well for most apps. All colors are customizable through Tailwind CSS classes.

## 📱 What You Get

- **Homepage**: Hero section, features, reviews, download CTA
- **Contact Page**: Contact form and information
- **FAQ Page**: Expandable questions and answers
- **Legal Pages**: Privacy policies and terms (required for app stores)
- **Smart Downloads**: Automatically detects user's device and shows correct download link

## 🚀 Going Live

1. **Build for production**:
   ```bash
   npm run build
   ```

2. **Upload files** to your web server

3. **Update app store links** in config.php with your real URLs

4. **Test on mobile** to ensure everything works correctly

## 💡 Tips for Success

- **Use high-quality screenshots** - they're the most important element
- **Keep descriptions short** and focused on benefits
- **Add real customer reviews** - they build trust
- **Test on mobile devices** - most visitors will be on phones
- **Update app store ratings** regularly in config.php

## 🆘 Need Help?

- Check `config.php` - 90% of customization happens there
- All images go in `/assets/` folder
- Run `npm run build` after making changes
- Test with `php -S localhost:8000`

---

**Ready to showcase your app? Start customizing config.php! 🚀**
