<br>

<h1 align="center">⭐️ TraddingApp  ⭐️</h1>

<h3 align="center">This repository is a php exercise carried out during Becode training   </h3> <br>

<h2> Overview</h2> 

>TraddingApp is a Laravel-based API for a trading simulation application. The API allows users to manage their profiles, open and close trades, and perform various trading-related actions.

The url of the API will be for example: http://localhost:8000/

<h2 align="left">📦 Prerequisites</h2> 

>Before getting started with this project, you'll need the following installed on your system:

- PHP (>= 7.4)
- Composer (Dependency Manager for PHP)
- MySql or other server sql
- Web Server (e.g., Apache, Nginx)

>Make sure you have these dependencies installed and properly configured on your machine before proceeding with the installation.

<h2 align="left">🚀 Installation</h2>

* Clone the Repository
  ```sh
    git clone https://github.com/your-username/Blog_Api.git
  ```
  
 * Navigate to the project directory:
	```sh
	  cd tradding-app
	```

* Create a database and update the .env file with your database credentials.
	```
	  cp .env.example .env
	```

* Install Composer dependencies using composer install.
	```
		composer install
	```

* Generate a new application key by running php artisan key:generate.
	```
		php artisan key:generate
	```

* Run Laravel migrations to set up the required database tables using php artisan migrate.
	```
		php artisan migrate
	```

* Optionally, seed the database with initial data:
	```
		php artisan db:seed
	```

<br><br>
<h2>JWT Authentication  </h2>

>To implement an authentication system using JWT (JSON Web Tokens), you can follow these steps:

* Install the required package:
	```
	composer require tymon/jwt-auth
	```
* Publish the package configuration file:
	```
	php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
	```
* Generate a secret key for JWT:
	```
	php artisan jwt:secret
	```
* Update the .env file to configure JWT:
	```
	Update the .env file to configure JWT:
	```
* Create a new controller for handling authentication:
	```
	php artisan make:controller AuthController
	```
In this controller, implement methods for registration, login, logout, etc., using JWT for token-based authentication.

* Define routes for authentication in the routes/api.php file.
<br>
<h2>Objectives </h2>
<h3>Endpoints :</h3>
	
- GET	/api/login	Login
- POST	/api/signup	Signup
- POST	/api/wire	Make a wire (deposit OR withdraw money)
- GET	/api/profile	Fetch all the profile data, including the user's balance
- PATCH	/api/update	Update user's profile (except balance)
- GET	/api/trades/index	Fetch all our trades
- GET	/api/trades/:id	Fetch one trade info
- GET	/api/trades/index/open	Fetch all our open trades
- GET	/api/trades/index/closed	Fetch all our closed trades
- POST	/api/openTrade/	Open a long position (buy), the amount and the stock is specified in the body of the request
- POST	/api/closeTrade/:id	Close the position
- GET	/api/closedPNL	Return the total closed PNL (all closed trades)
- GET	/api/openPNL	Return the total open PNL (all open trades)
- GET	/api/currentBalance	Return current balance (all the money that is NOT in an open position)

<h2> Features</h2>

- User authentication using JWT (JSON Web Tokens)
- User registration and profile creation
- Opening and closing trades
- Fetching open and closed trades
- Wire transactions (deposits and withdrawals)
<br>
<h2 align="left">🏗️ Project Structure</h2>

```
	/traddingApp
	|-- app/                 # Laravel application files
	|-- config/              # Configuration files
	|-- database/            # Database migrations and seeds
	|-- public/              # Publicly accessible files
	|-- resources/           # Views, language files, and other resources (not used in this API)
	|-- routes/              # Route definitions and mapping
	|-- storage/             # Storage for logs and other temporary files
	|-- tests/               # Test cases (if implemented)
	|-- .env                 # Environment configuration file
	|-- .htaccess            # Apache configuration for URL rewriting (if needed)
	|-- artisan              # Laravel command-line utility
	|-- composer.json        # Composer package manager configuration
	|-- README.md            # Project README (this file)
```
<br>

* Laravel already includes its routing system, so there's no need to install a separate router package.
* Use Thunder Client (VSCode extension), cURL or Postman to test the API endpoints and verify responses.
<br>

<!-- <img src="https://github.com/DelphineLecorney/photos-images-readme/blob/main/images/http.JPG"
https://developer.mozilla.org/en-US/docs/Web/HTTP/Status alt="http" height="50" width="50" /> -->

<h2 align="left">💻 Tech Stack</h2>  

<p align='left'>
 <img src="https://github.com/DelphineLecorney/photos-images-readme/blob/main/images/Laravel.JPG" alt="laravel" height="50" width="50" />
 <img src="https://raw.githubusercontent.com/bablubambal/All_logo_and_pictures/1ac69ce5fbc389725f16f989fa53c62d6e1b4883/social%20icons/php.svg" alt="php" height="50" width="50" />
<img src="https://raw.githubusercontent.com/bablubambal/All_logo_and_pictures/62487087dc4f4f5efee637addbc67a16dd374bf6/text%20editors/vscode.svg" alt="vsCode" height="50" width="50" /> 
</p>

[<h2 align="left">Contact me</h2>](https://www.linkedin.com/in/delphine-lecorney/)

