# JanBhasha 🇮🇳
### *Bridging Government Communication, One Word at a Time*

JanBhasha is an AI-powered English to Hindi translation tool built for Indian government organizations. It enables government websites to convert official content into Hindi, ensuring accessibility for all citizens.

---

## 🛠️ Tech Stack

- **Backend:** PHP 8.x / Laravel 11
- **Frontend:** Blade Templates / TailwindCSS
- **Translation API:** Google Translate API / LibreTranslate
- **Database:** MySQL
- **Auth:** Laravel Breeze

---

## ⚙️ Installation

```bash
# Clone the repository
git clone https://github.com/your-org/janbhasha.git
cd janbhasha

# Install dependencies
composer install
npm install && npm run build

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate

# Start the server
php artisan serve
```

---

## 🔑 Environment Variables

Add the following to your `.env` file:

```env
APP_NAME=JanBhasha
APP_URL=http://localhost

DB_DATABASE=janbhasha
DB_USERNAME=root
DB_PASSWORD=

TRANSLATION_API_KEY=your_api_key_here
```

---

## ✨ Features

- 🌐 English → Hindi translation for official government content
- 📋 Supports bulk text, notices, and policy documents
- 🔒 Secure API access for government organizations
- 📱 Mobile-friendly responsive UI
- 📝 Translation history and logs

---

## 📁 Project Structure

```
janbhasha/
├── app/
│   ├── Http/Controllers/TranslatorController.php
│   └── Services/TranslationService.php
├── resources/views/
│   ├── layouts/app.blade.php
│   └── translator/index.blade.php
├── routes/web.php
└── .env
```

---

## 📄 License

This project is developed for use by Indian Government Organizations.  
© 2025 JanBhasha. All rights reserved.