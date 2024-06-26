# bilemo API

## Requirements

| Requirements |
| ------------ |
| PHP          |
| Composer     |
| Symfony cli  |
| MySql        |

## Set Up

To setup the environnement run :

```shell
$ git clone https://github.com/Davidouu/bilemo.git
```

```shell
$ cd bilemo
```

```shell
$ composer install
```

<hr>

### Config .env

In the project folder run this command :

```shell
$ cp .env .env.local
```

Then fill the this variable :

```
###> doctrine/doctrine-bundle ###
DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"
###< doctrine/doctrine-bundle ###
```

<hr>

### DB setup

To create the database and all the tables execute this command :

```
$ php bin/console doctrine:database:create
```

```
$ php bin/console doctrine:schema:update --force
```

To load dataFixtures in the database run :

```
$ php bin/console doctrine:fixtures:load
```

<hr>

### Config JWT

#### Generate keys

To create the public and the private keys execute :

```
$ php bin/console lexik:jwt:generate-keypair
```

Then fill your .env.local with your passphrase

```
###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=********************************
###< lexik/jwt-authentication-bundle ###
```

<hr>

### API Documentation access

To test the api and read the api documentation :

> Documentation : https://yourdomainadress.xx/doc/api
