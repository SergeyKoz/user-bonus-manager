Project 
==================
The web application allows: register new users, transfer money between registered users.

Installation
==================
As the project is dockerized. Yo need to install [docker](https://www.docker.com/)

- Download project from repository
```bash
git clone git@github.com:SergeyKoz/user-balance-manager.git
```

Configuration

Config file `config/web.php`

```php
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '<the-key>',
        ],
    ],
```

- Run containers 
```bash
docker-compose up
docker exec user-bonus-manager-php /bin/sh -lc "composer install"
```
- Database initialization
```bash
docker exec user-bonus-manager-php /bin/sh -lc "php yii migrate"
```

The application is allowed by address [http://127.0.0.1:80](http://127.0.0.1:80/)
