# DJ's Burger Shop - Web Application

A PHP & HTML-based customer-facing web application that allows customers to view menu items, book reservations, place orders online, and leave feedback.

## 🛠️ Setup Instructions

### 1. Web Server Setup
1. Download and install **XAMPP**.
2. Clone or copy this repository into the `htdocs` directory of your XAMPP installation (typically `C:\xampp\htdocs\TrialWorkIM-main`).
3. Start the **Apache** and **MySQL** services in the XAMPP Control Panel.

### 2. Database Setup
1. Open your browser and go to `http://localhost/phpmyadmin`.
2. Create a new database named `dj_burgershop`.
3. Import the SQL database dump (e.g. from the Admin or POS repositories, or the centralized SQL file).

### 3. Configuration Setup
1. Navigate to the `docs/api/config/` directory.
2. Copy `db_config.ini.example` and rename it to `db_config.ini`.
3. Edit `db_config.ini` with your database credentials:
   ```ini
   server=localhost
   database=dj_burgershop
   username=root
   password=YOUR_PASSWORD_HERE
   port=3306
   ```

### 4. Running the Website
Open your browser and navigate to `http://localhost/TrialWorkIM-main/docs/userInfo.html` to place customer orders and reservations.
