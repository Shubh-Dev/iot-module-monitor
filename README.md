# Iot Module Monitor

An IoT module monitoring website built using Laravel and other technologies.

<!-- ![screenshot](./desktop.png) -->

## Built With

-   PhP
-   JavaScript
-   Laravel
-   Bootstrap
-   Css3

## Getting Started

To get a local copy up and running follow these simple steps.

## Prerequisites

-   PhP(version 8.3 or higher)
-   Composer(Dependency manager for PHP)
-   MySQL
-   Have VSCode or other text editor installed. [Link to download VSCode](https://code.visualstudio.com/download)
-   Install node package. [Link to download node](https://nodejs.org/en/download/)
-   Have git installed.[Link to download git](https://git-scm.com/downloads)

## Setup

Step-1: Clone the repository to your local machine

```javascript
 git clone git@github.com:Shubh-Dev/iot-module-monitor.git
 cd iot-module-monitor
```

Step-2: Run Composer to install all required PHP dependencies:

```javascript
 composer install
```

Step-3: Set Up the Environment - Duplicate the .env.example file to create .env file

```javascript
cp.env.example.env;
```

Step-4: Update the .env file with your database credentiald and any other necessary configurations
Example .env Settings

```javascript
 DB_CONNECTION=mysql
 DB_HOST=127.0.0.1
 DB_PORT=3306
 DB_DATABASE=your_database_name
 DB_USERNAME=your_database_user
 DB_PASSWORD=your_database_password
```

Step-5: Generate Application Key - Run the following command to generate the Laravel application key

```javascript
php artisan key:generate
```

Step 6: Run Database Migrations - Set up your database schema and tables by running migrations

```javascript
php artisan migrate
```

If you have any seeders, you can also run

```javascript
php artisan db:seed

```

Step 6: Start the Simulator - Run the simulator to generate and update module data

```javascript
php artisan simulate:module-data

```

Step 7: Start the Development Server

```javascript
php artisan serve

```

## Authors

👤 **Shubh M**

-   GitHub: [@Shubh-Dev](https://github.com/Shubh-Dev)
-   LinkedIn: [LinkedIn](https://www.linkedin.com/in/shubhscb/)

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

Feel free to check the [issues page](../../issues/).

## Show your support

Give a ⭐️ if you liked this project!

## Acknowledgments

-   Not available right now

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
