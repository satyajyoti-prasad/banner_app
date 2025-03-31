# Banner App

This is a simple project in PHP to display custom banners dynamically in third party web-app.
The goal of this app is to develop an integration script that enables third-party websites to display a banner served by the host system.

## 🚀 Technology Stack

- **Backend:** CodeIgniter 4
- **Database:** MySQL
- **Frontend:** JavaScript (for embeddable script)
- **Styling:** Tailwind CSS

---

## 📋 Functional Requirements

- The script should be embeddable in any third-party website using:
  ```html
  <script src="https://yourdomain.com/banner.js"></script>
  ```
- The script should fetch banner details (image URL, link, alt text) from the backend.
- The script should dynamically insert the banner into the webpage.
- The backend should provide banner details via a public API endpoint.
- The script should allow optional parameters (e.g., width, height, position).
- The banner should be clickable, leading to a provided URL.
- An **Admin Panel** should be available where users can:
  - Add customers.
  - Assign respective banners to customers.
  - Generate a **custom banner.js** endpoint for each customer.

---

## ⚙️ Setup Instructions

### Prerequisites

- PHP 8.x
- Composer installed
- MySQL database setup

### Installation Steps

1️⃣ **Clone the Repository**

```sh
  git clone https://github.com/satyajyoti-prasad/banner-app.git
  cd banner-app
```

2️⃣ **Install Dependencies**

```sh
  composer install
```

3️⃣ **Set Up Environment**

- Update database credentials in .env:

  ```
  database.default.hostname = localhost
  database.default.database = your_database_name
  database.default.username = your_db_user
  database.default.password = your_db_password
  database.default.DBDriver = MySQLi

  ```

  **OR** you can directly modify `database.php` in `Config/Database.php` folder.

4️⃣ **Run Database Migrations**

```sh
  php spark migrate
```

**OR** use the provided SQL file directly.

5️⃣ **Start the Development Server**

```sh
  php spark serve
```

The application will be accessible at

```
http://localhost:8080
```

## 📂 Admin Panel Features

- **Add Customers:** Register customers who will use the banner system.
- **Manage Banners:** Upload and assign banners to specific customers.
- **Generate Custom Banner.js URL:** Each customer gets a unique `banner.js` endpoint to embed in their websites.
- **Dashboard:** View statistics on banner usage and performance.

---

## 🔗 API Endpoint for Banner Details

The custom banner.js will automatically resolve the banner details from the provided API endpoint.
The backend exposes a public API endpoint to retrieve banner details:

```
GET /api/banner/CLIENT_ID
```

#### Example API Response:

```json
{
  "image_url": "http://localhost:8080/assets/uploads/banners/1743403099_bf376cab008d8b02ea8e.png",
  "link_url": "https://banner-destination-domain.com",
  "alt_text": "Banner Alt Text",
  "width": "100%",
  "height": "120px",
  "position": "bottom",
  "zIndex": "9999"
}
```

---

## 🎯 Embedding the Banner Script

To display the banner on a third-party website, client will include the following script tag:

```
<script>
  window.bannerConfig = {
    position: 'top',
    width: '100%',
    height: '120px'
  };
</script>
```

```
<script async src="http://localhost:8080/banner.js/CLIENT_ID"></script>
```

- This window.bannerConfig object is optional,it is meant for customizing the banner positioning and size.

- **Implementation Tips:**
- Place this code just before the closing body tag.
- For best performance, load asynchronously by adding async attribute
- No refresh needed as the script will automatically fetch the updated banner in realtime

## 🌍 Deployment

- Configure the production environment in `.env`
- Use `php spark serve --host yourdomain.com` for local testing
- Deploy using Apache/Nginx with proper routing to `public/index.php`
