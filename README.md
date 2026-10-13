# ♻️ EcoRecycle Hub — Web-Based Recycling Activity and Awareness System

Graduation project: a web platform that encourages recycling by letting users log their recycling activities, earn points and rewards, compete on a leaderboard, find recycling centers, and chat with an AI assistant.

## ✨ Features

**Users**
- Register / login with account verification
- Log recycling activities and track history
- Earn points, ranks and rewards
- Personal dashboard and profile
- Leaderboard to compete with the community
- Map of recycling centers
- **EcoBot** — AI chatbot and recommendations (DeepSeek API)

**Admin**
- Manage users and activities
- Reports and statistics
- System settings

## 🛠️ Tech Stack
- PHP
- MySQL
- HTML / CSS / JavaScript
- DeepSeek API (AI assistant)

## 🚀 How to Run Locally
1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. Copy this folder into `C:\xampp\htdocs\` and rename it to `Recycling System`.
3. Open **phpMyAdmin** → create a database named `recycling_system` → **Import** → choose `database/recycling_db.sql`.
4. Check the database settings in `config/config.php` (default: user `root`, empty password).
5. (Optional, for the chatbot) Open `ai/deepseek_config.php` and replace `PUT_YOUR_DEEPSEEK_API_KEY_HERE` with your own key.
6. Visit `http://localhost/Recycling%20System`

## 🔑 Demo Accounts
The sample data includes an admin account (`admin@ecorecycle.com`) and sample users. The sample password hash is the standard demo hash — change all passwords before any real deployment.



## 📄 Documentation
Project report: *Web-Based Recycling Activity and Awareness System* (Chapters 1–5).

## 👥 Team

- Hala Khalil Al-huzali ([GitHub](https://github.com/hala777khalil))
- Mshael
- Aryaf
- Sara
