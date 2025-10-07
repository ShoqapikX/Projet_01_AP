# Nike E-Commerce Project

Welcome to the Nike E-Commerce project! This application is a fully functional online store built with **PHP**, **JavaScript**, **MySQL**, and **Docker**. The project simulates a real-world e-commerce platform, allowing users to browse Nike products, manage their shopping cart, and complete purchases seamlessly.

## Features

- **User Authentication**: Secure registration and login system for customers.
- **Product Catalog**: Browse a wide range of Nike products with detailed descriptions and images.
- **Shopping Cart**: Add, update, or remove items from your cart and view a summary before checkout.
- **Order Management**: Place orders and view order history.
- **Admin Panel**: Manage products, orders, and users through an intuitive admin dashboard.
- **Responsive Design**: Fully responsive UI for both desktop and mobile devices.
- **Database Integration**: MySQL for storing user, product, and order information.
- **Dockerized Environment**: Easily run the entire application using Docker for simplified setup and deployment.

## Tech Stack Used

- **Backend**: PHP
- **Frontend**: JavaScript, HTML, CSS
- **Database**: MySQL
- **Containerization**: Docker

## Getting Started

### Prerequisites

- [Docker](https://www.docker.com/get-started) installed on your machine

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/ShoqapikX/Projet_01_AP.git
   cd Projet_01_AP
   ```

2. **Start the application with Docker**
   ```bash
   docker-compose up --build
   ```

3. **Access the application**
   - The web application will be available at [http://localhost:8080](http://localhost:8080)

## Folder Structure

- `/public` - Web-accessible files and the main entry point (index.php, CSS, JS, images).
- `/src` - PHP source code for business logic, controllers, and models.
- `/templates` - HTML templates and views.
- `/config` - Configuration files (database, environment settings).
- `/docker` - Docker and Docker Compose configuration files.
- `/sql` - Database schema and sample data scripts.
- `/assets` - Static assets (images, fonts, icons, etc.).
