# SubFox - Modern App Landing Page

A beautiful, modern, and professional landing page template built with PHP and Tailwind CSS. Perfect for app developers who want to showcase their mobile applications with a clean, responsive design.

## 🌟 Features

- **Modern Design**: Clean, professional layout with smooth gradients and beautiful typography
- **Fully Responsive**: Optimized for desktop, tablet, and mobile devices
- **Component-Based**: Modular PHP components for easy customization
- **Tailwind CSS**: Modern utility-first CSS framework
- **Material Icons**: Beautiful icons from Google Material Design
- **SEO Optimized**: Proper meta tags and semantic HTML structure
- **Easy Configuration**: Everything customizable through `config.php`

## 📁 Project Structure

```
App/
├── _components/
│   ├── header.php                    # Sticky header with blurred background
│   ├── footer.php                    # Footer with links and social media
│   ├── feature_card_with_icon.php    # Feature cards with Material Icons
│   ├── feature_card_with_screenshot.php # Feature cards with app screenshots
│   ├── review_card.php               # User reviews and app store ratings
│   └── bottom_download_cta.php       # Call-to-action section before footer
├── assets/
│   └── images/                       # Place your images here
├── src/
│   └── input.css                     # Tailwind CSS source file
├── dist/
│   └── output.css                    # Compiled CSS (auto-generated)
├── config.php                        # Main configuration file
├── index.php                         # Homepage with all components
├── faq.php                          # FAQ page with accordion
├── contact.php                       # Contact page with form
├── privacy-policy-android.php       # Android privacy policy
├── privacy-policy-ios.php           # iOS privacy policy
├── terms-of-use.php                 # Terms of service
├── tailwind.config.js               # Tailwind configuration
├── package.json                     # Node.js dependencies
└── README.md                        # This file
```

## 🚀 Quick Start

### Prerequisites
- Node.js and npm installed
- PHP server (optional, for testing)

### Installation

1. **Install dependencies**:
   ```bash
   npm install
   ```

2. **Build CSS** (first time):
   ```bash
   npm run build
   ```

3. **Start development** (with auto-rebuild):
   ```bash
   npm run build-css
   ```

4. **Run PHP server** (optional):
   ```bash
   php -S localhost:8000
   ```

## ⚙️ Configuration

Everything is configurable through the `config.php` file. Simply update the values to customize your website:

### App Information
```php
$common = [
    'appName' => "Your App Name",
    'appTitle' => "Your App Tagline",
    'appDescription' => "Your app description",
    'appIcon' => "/assets/images/app_icon.webp",
    'supportEmail' => "support@yourapp.com",
    'appStoreUrl' => "https://apps.apple.com/...",
    'googlePlayUrl' => "https://play.google.com/...",
];
```

### Features with Icons
```php
$featuresIcons = [
    "featuresList" => [
        [
            "title" => "Feature Title",
            "description" => "Feature description",
            "icon" => "star", // Material Design icon name
        ],
    ]
];
```

### Features with Screenshots
```php
$featuresScreenshots = [
    "featuresList" => [
        [
            "title" => "Feature Title",
            "description" => "Feature description",
            "image" => "/assets/images/feature_1.webp",
        ],
    ]
];
```

## 🎨 Design Features

### Color Scheme
- **Primary**: Blue tones (#2563eb, #1d4ed8)
- **Secondary**: Green for Android (#16a34a, #15803d)
- **Accent**: Purple highlights (#7c3aed, #6d28d9)
- **Neutral**: Gray scales for text and backgrounds

### Typography
- **System Fonts**: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto
- **Headings**: Bold, modern hierarchy
- **Body Text**: Readable line height and spacing

### Layout
- **Maximum Width**: 7xl (1280px) for main content
- **Responsive Grid**: Automatic adaptation to screen sizes
- **Spacing**: Consistent padding and margins using Tailwind scale

## 📱 Components

### Header
- Sticky positioning with backdrop blur
- App icon and name on the left
- Download buttons on the right
- Responsive design

### Hero Section
- Large, attention-grabbing headline
- App screenshot showcase
- Prominent CTA buttons
- Trust indicators (ratings, user count)

### Feature Cards
- **With Icons**: Perfect for listing app capabilities
- **With Screenshots**: Great for showing app interface
- Alternating layout for visual interest

### Reviews Section
- User testimonials with star ratings
- App Store and Google Play ratings display
- Profile pictures with fallback

### Footer
- App information and description
- Navigation links
- Social media links
- Legal pages links

## 🔧 Customization

### Adding New Pages
1. Create a new PHP file in the root directory
2. Include the config file: `require_once 'config.php';`
3. Use components: `<?php include '_components/header.php'; ?>`
4. Follow the existing structure for consistency

### Modifying Styles
1. Edit classes directly in PHP files (recommended)
2. Or add custom CSS to `src/input.css`
3. Run `npm run build` to compile changes

### Adding Images
1. Place images in the `assets/images/` folder
2. Update paths in `config.php`
3. Recommended formats: WebP for best performance

## 📄 Legal Pages

The template includes ready-to-use legal pages with custom CSS that mimics Tailwind styling:

- **Privacy Policy (Android)**: Google Play Store compliant
- **Privacy Policy (iOS)**: App Store compliant with Apple-specific sections
- **Terms of Service**: Comprehensive terms covering both platforms

These pages are designed to be easily customizable while maintaining professional appearance.

## 🌐 SEO & Performance

- **Meta Tags**: Proper title, description, and social media tags
- **Semantic HTML**: Proper heading hierarchy and structure
- **Fast Loading**: Optimized CSS and minimal dependencies
- **Mobile First**: Responsive design approach
- **Accessibility**: ARIA labels and keyboard navigation support

## 🔄 Development Workflow

1. **Development Mode**:
   ```bash
   npm run build-css
   ```
   Watches for changes and rebuilds automatically

2. **Production Build**:
   ```bash
   npm run build
   ```
   Creates minified CSS for deployment

3. **Testing**:
   ```bash
   php -S localhost:8000
   ```
   Local PHP server for testing

## 📋 Browser Support

- **Modern Browsers**: Chrome, Firefox, Safari, Edge (latest versions)
- **Mobile Browsers**: iOS Safari, Chrome Mobile, Samsung Internet
- **Fallbacks**: Graceful degradation for older browsers

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

## 🆘 Support

For support and questions:
- **Email**: support@yourapp.com
- **Issues**: Create an issue on GitHub
- **Documentation**: Check this README and code comments

---

**Built with ❤️ using PHP and Tailwind CSS**
