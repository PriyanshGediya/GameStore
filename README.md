# 🎮 GameStore  
A full-featured **Laravel-based digital game marketplace** where users can browse games, purchase them securely using Razorpay, download installers, and manage their profile and purchase history.  
Includes a powerful **Admin Panel**, **Customer Area**, and **Guest Mode** with full role-based access.

---

## ⭐ Key Features

### 🔥 Customer Features
- **Browse Games** with detailed genre, description, screenshots, price, and install size.  
- **Add to Cart & Checkout** with secure Razorpay payment gateway integration.  
- **Purchase History** is stored in a dedicated `purchase_history` table.  
- **Download Purchased Games** (installer stored in `storage/app/public/installers`).  
- **Write Reviews** — only customers who purchased the game can submit reviews.  
- **Profile Management** including profile picture upload.

### 🛠 Admin Features
- Full CRUD for **Games**, **Genres**, **Users**, **Memberships**, and **Reviews**.  
- Upload **game installers (.zip/.exe)** using Laravel Storage.  
- Manage **users**, reset passwords, assign roles.  
- View and manage **purchase history**, transactions, and customer reports.

### 📧 Email & OTP Integration
- Integrated **PHPMailer SMTP** for:
  - OTP verification  
  - Forgot password  
  - Account verification  
  - Purchase receipt email  

### 💳 Payment Gateway
- **Razorpay Payment Integration** with:
  - Order creation  
  - Payment verification  
  - Secure server-side validation  
  - Webhook-ready structure  

### 📁 Storage & Uploads
- Image uploads stored in `storage/app/public/images`.  
- Installer uploads stored in `storage/app/public/installers`.  
- Public profile pictures stored in `public/uploads/profile`.  

---

## 🚀 Tech Stack

- **Laravel 10**
- **PHP 8+**
- **MySQL**
- **Blade Templates**
- **Bootstrap / Tailwind**
- **Razorpay API**
- **PHPMailer SMTP**
- **Composer & NPM**
- **Laravel Storage**

---

## 🛠 Installation & Setup

### 1️⃣ Clone this repository  
```bash
git clone https://github.com/PriyanshGediya/GameStore.git
cd GameStore
2️⃣ Install backend dependencies
composer install
3️⃣ Install frontend dependencies
npm install
npm run dev
4️⃣ Create environment file
cp .env.example .env


Then update:

DB_DATABASE

DB_USERNAME

DB_PASSWORD

RAZORPAY_KEY

RAZORPAY_SECRET

MAIL_USERNAME

MAIL_PASSWORD

5️⃣ Generate app key
php artisan key:generate

6️⃣ Run migrations
php artisan migrate

7️⃣ (Optional) Seed sample data
php artisan db:seed

8️⃣ Link storage
php artisan storage:link

9️⃣ Start server
php artisan serve


Access the site at http://localhost:8000

🧑‍💼 User Roles
👑 Admin

Manage games, genres, users

Upload installers

Manage purchases, memberships, reviews

View all transactions

👤 Customer

Browse games

Add to cart

Secure checkout (Razorpay)

Download games after purchase

Submit reviews

Manage profile

👀 Guest User

Browse games

View details

Register / login
🤝 Contribution

Contributions are welcome!
To contribute:

Fork the repo

Create a feature branch

Commit your changes

Create a pull request

🐞 Support / Issues

If you find any issues, feel free to open a GitHub issue:
👉 https://github.com/PriyanshGediya/GameStore/issues
