# EduSync - Multi-tenant School Management SaaS System

## 📋 প্রজেক্ট পরিচয়

EduSync হলো একটি **মাল্টি-টেন্যান্ট SaaS স্কুল ম্যানেজমেন্ট সিস্টেম** যা Laravel দিয়ে তৈরি এবং বাংলাদেশের সকল স্কুলের জন্য ডিজাইন করা হয়েছে।

## 🚀 ফিচার

- ✅ মাল্টি-টেন্যান্ট আর্কিটেকচার
- ✅ রোল-বেসড এক্সেস কন্ট্রোল (RBAC)
- ✅ bKash পেমেন্ট ইন্টিগ্রেশন
- ✅ স্টুডেন্ট ম্যানেজমেন্ট
- ✅ অ্যাটেন্ডেন্স ট্র্যাকিং
- ✅ এক্সাম এবং রেজাল্ট
- ✅ ফি কালেকশন
- ✅ এইচআর এবং পেরোল
- ✅ নোটিস বোর্ড

## 📦 ইনস্টলেশন

### প্রয়োজনীয় সফটওয়্যার
- PHP 8.2 বা তার উপরে
- MySQL 8.0 বা তার উপরে
- Composer
- Node.js এবং NPM

### ইনস্টলেশন ধাপ

```bash
# ১. রিপোজিটরি ক্লোন করুন
git clone https://github.com/raiyanf171-design/EduSync.git
cd EduSync

# ২. ডিপেন্ডেন্সি ইনস্টল করুন
composer install
npm install

# ३. .env ফাইল তৈরি করুন
cp .env.example .env

# ४. Application Key জেনারেট করুন
php artisan key:generate

# ५. ডাটাবেস কনফিগারেশন
# .env ফাইলে আপনার ডাটাবেস ডিটেইলস দিন

# ६. মাইগ্রেশন চালান
php artisan migrate

# ७. বীজ ডাটা (Seeding) চালান
php artisan db:seed

# ८. ফ্রন্টএন্ড বিল্ড করুন
npm run build

# ९. সার্ভার চালান
php artisan serve
```

## 🔐 bKash ইন্টিগ্রেশন

### bKash Sandbox সেটআপ

1. [bKash Developer Portal](https://developer.bkash.com/) এ রেজিস্টার করুন
2. Sandbox পরিবেশে আপনার অ্যাপ তৈরি করুন
3. `.env` ফাইলে নিম্নলিখিত ডিটেইলস যোগ করুন:

```env
BKASH_APP_KEY=your_app_key
BKASH_APP_SECRET=your_app_secret
BKASH_USERNAME=your_username
BKASH_PASSWORD=your_password
BKASH_SANDBOX_URL=https://sandbox.bkash.com
BKASH_CALLBACK_URL=http://localhost:8000/api/subscriptions/verify-payment
```

## 📖 ডকুমেন্টেশন

বিস্তারিত আর্কিটেকচার ডকুমেন্টেশনের জন্য দেখুন: [ARCHITECTURE.md](./ARCHITECTURE.md)

## 📁 ডিরেক্টরি স্ট্রাকচার

```
EduSync/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   ├── Services/
│   └── Traits/
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── resources/
│   ├── views/
│   └── css/
├── routes/
├── tests/
└── config/
```

## 👥 ব্যবহারকারী রোল

1. **Super Admin** - সিস্টেম অ্যাডমিনিস্ট্রেটর
2. **School Admin** - স্কুল পরিচালক
3. **Teacher** - শিক্ষক
4. **Student** - শিক্ষার্থী
5. **Parent** - অভিভাবক

## 💰 সাবস্ক্রিপশন মডেল

### প্যাকেজ
- **স্টার্টার প্যাকেজ**: ৫০০ শিক্ষার্থী পর্যন্ত
- **প্রফেশনাল প্যাকেজ**: ১০০০ শিক্ষার্থী পর্যন্ত
- **এন্টারপ্রাইজ প্যাকেজ**: সীমাহীন

### মূল্য নির্ধারণ
- ১ বছর সাবস্ক্রিপশন
- ২ বছর সাবস্ক্রিপশন

## 🔐 নিরাপত্তা

- Laravel Sanctum দিয়ে API সুরক্ষা
- Role-based Access Control (RBAC)
- Password encryption (bcrypt)
- CSRF Protection
- SQL Injection প্রতিরোধ
- XSS Protection

## 📞 সাপোর্ট

যেকোনো সমস্যার জন্য GitHub Issues এ রিপোর্ট করুন।

**ডেভেলপার**: Raiyan  
**ইমেইল**: raiyanf171@gmail.com  
**লাইসেন্স**: MIT

---

**Made with ❤️ for Education**
