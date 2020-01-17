# ZeroFood

####requirements:

- PHP 7.2 or higher
- Composer
- MySQL

####Installation:

- Download the code by clone the repository from github
 ``` 
$ git clone https://github.com/husseinferas/ZeroFood.git  
```

- Inside the project folder and install the dependencies via composer
```
$ cd ZeroFood

$ composer install 
```
- Add .env file and add your database connection credentials 
``` 
$ nano .env

#inside .env:

APP_NAME=ZeroFood
APP_ENV=local // for development
APP_DEBUG=true // false in prodection
APP_URL=[app_url]
APP_TIMEZONE=UTC

DB_CONNECTION=mysql
DB_HOST=[db_host]
DB_PORT=3306
DB_DATABASE=[db_name]
DB_USERNAME=[db_user]
DB_PASSWORD=[db_pass]

```

- we use JWT for api authentication so we need to generate jwt key by running this command :
``` 
$ php artisan jwt:secret 
 ```

also, we need to set token expire time inside .env file
``` 
 JWT_TTL=36000
```

- Migrate the database by running:
``` 
$ php artisan migrate 
```

####Usage:

This project represent the backend side of ZeroFood Project you can use check the endpoints by import this Postman collection: URL:

https://www.getpostman.com/collections/e0aa476e6b3da68aa048

 
