# 🌿 Online Nursery Plant Shopping System

An academic e-commerce-based project developed as part of the **6th Semester BIM Curriculum**. The platform enables users to buy nursery plants online and integrates **Khalti** payment gateway for secure transactions.

---

## 📚 Academic Context

- 📘 **Course:** Bachelor of Information Management (6th Semester)
- 🏫 **Institution:** Nepal Commerce Campus, TU
- 🧑‍🏫 **Supervisor:** Mr. Niraj Khadka
- 👨‍💻 **Submitted by:**
  - Mridul Khanal 
  - Nabin Neupane 
  
---

## 🎯 Key Features

- 👤 User registration & login
- 🔍 Browse plants by category
- 🛒 Cart & checkout functionality
- 💳 **Khalti payment** integration (success/fail handlers)
- 📦 Order confirmation & tracking
- 👨‍💼 Admin login and product management
- 📱 Fully responsive frontend (HTML, CSS, JS)
- 📂 Backup options (`backup.sql`, `backup.tar.gz`)

---

## 🛠️ Technologies Used

| Layer         | Technologies                    |
|---------------|----------------------------------|
| Frontend      | HTML5, CSS3, JavaScript          |
| Backend       | PHP                              |
| Database      | MySQL (`backup.sql`)             |
| Server        | Apache (via XAMPP)               |
| Payment       | [Khalti API](https://khalti.com) |

---

## 💸 Payment Flow

- `checkout.php` handles cart totals
- `khalti_verify.log` logs the payment verification
- On success: `payment_successful.php`
- On failure: `payment_failed.php`
- Final redirect: `thank_you.php`

---

## 🧪 Testing Overview

| Feature                | Status |
|------------------------|--------|
| User Auth              | ✅ Pass |
| Cart Operations        | ✅ Pass |
| Khalti Integration     | ✅ Pass |
| Admin Management       | ✅ Pass |
| Mobile Responsiveness  | ✅ Pass |
| Error Handling         | ✅ Pass |

---

## 🧑‍💼 User Roles

- **Customer**
  - Login via `login.php`
  - Browse, cart, checkout, order history
- **Admin**
  - Login via `admin_login.php`
  - Manage product listings & inventory

---
### 🔐 Login Page
![Login Page](images/screenshots/login.png)

### 🛒 Product Listings
![Products Page](images/screenshots/products.png)

### 📦 Checkout & Payment
![Checkout Page](images/screenshots/checkout.png)

### 🧑‍💼 Admin Panel
![Admin Panel](images/screenshots/admin.png)


## 📥 Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone https://github.com/Mridulkhanal/online-nursery-plant-shopping-system.git

2. **Import the Database**
    Open XAMPP → phpMyAdmin
    Create a database (e.g., nursery_db)
    Import backup.sql   

3. **Run the App Locally**
    Place folder in htdocs
    Start Apache & MySQL in XAMPP
    Visit: http://localhost/ONLINENURSERYSYSTEM/

📃 License
    This project is submitted as an academic requirement.
    Free to reuse for learning and educational purposes.

    © 2025 Mridul Khanal
    GitHub: Mridulkhanal