
## User experience API

This is a test task covering creation of REST API in Laravel. The API gets user experience, and takes some fake data generated by [RandomUser API](https://randomuser.me/api/),
then combines it with user experience and saves this data to the database. API also allows to search for employee by ID or first/last name, and can display all employees.

# Installation

- Clone the repository
- Create the database
- Create your own *.env* file from *.env.example*
- Set up database connection parameters in  *.env*
- Run `composer update`
- Run `php artisan migrate`
- Run `php artisan serve`, the API will be available at [http://localhost:8000/api/](http://localhost:8000/api/)

# Database

The database is quite simple and contains two tables: `employees` and `experiences`. The API operates `Employee` model which has one-to-many relation to `Experiences` model.

# API endpoints

The documentation can be found in `docs.yaml`. Briefly, the API supports four endpoints:

`GET /employees`

Retrieves list of all employees

`POST /employees`

Creates new employee with experiences

`GET /employees/:id`

Retrieves employee with given ID

`GET /search`

Searches for employee by first or last name

# Technology and approach

This is a simple API, therefore all logic is kept in controllers.

Since we are using a 3rd party API, failures can happen, therefore I catch the exception it can throw.

All requests and responses to the API are logged via Logger class. The logs are stored under `storage/logs/api.log`. If you wish to change the name of the log file, you can do this by adjusting settings in `/config/logging.php`

# What can be improved

+ Move all logic from controller into service containers
+ Adjust the logs to keep only certain data instead of handling the whole response
+ Create another middleware which would format the API response, thus avoiding duplicate code in the methods of controller
+ Add Laravel passport authentication
+ ...and many more