# Vending Machine System

A complete Laravel-based vending machine management system with user-facing product browsing, purchasing, and admin management capabilities.

## Features

### 🛒 **User Features**
- Product browsing with search and filtering
- Product detail pages with images and descriptions
- Secure purchase system with real-time stock management
- Transaction history and order tracking
- Role-based authentication (user/admin)

### 👨‍💼 **Admin Features**
- Complete product CRUD operations
- Transaction management and reporting
- User management and analytics
- Real-time statistics dashboard
- Advanced filtering and search capabilities

### 🔧 **Technical Stack**
- **Backend**: Laravel 11
- **Frontend**: Vue 3 with Composition API
- **UI Framework**: Tailwind CSS
- **State Management**: Inertia.js
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **Testing**: PHPUnit

## Installation

### 📋 **Prerequisites**
- PHP 8.2 or higher
- Composer
- Node.js 18 or higher
- MySQL 8.0 or higher
- Git

### 🚀 **Quick Installation**

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd vending-machine
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Database**
   Edit your `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=vending_machine
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

5. **Database Setup**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Start Development Server**
   ```bash
   npm run dev
   ```

### 🔄 **Complete Setup Commands**
   ```bash
   # Clone and setup
   git clone <repository-url> && cd vending-machine
   composer install
   npm install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate:fresh --seed
   npm run dev
   ```

### 🌐 **Access the Application**
- **Development Server**: http://localhost:8000
- **Login Credentials**:
  - **Admin**: admin@gmail.com / password
  - **User**: john@gmail.com / password

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
