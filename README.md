# Hangman
For anyone wanting to play a game of hangman, either with a UI, or just based on API responses.

# Requirements

- [Composer](https://getcomposer.org/)
- [Docker](https://docs.docker.com/install/)
- [Docker compose](https://docs.docker.com/compose/)

# Installation

## Getting all dependencies
To gather all dependencies, please run the following:

```
composer install;
npm install;
```

## Docker
Docker requires an environment file, in which 2 variables to be set. To do this, copy the sample file by running the following command in the root folder of our project.

```
cp docker/.env.sample docker/.env
```

Open up the newly created .env file, and add the missing variable values.

### Finding your local IP
If you're having trouble finding your local IP, run `ifconfig | grep inet`.
You will most likely see a list of results. The correct result should *NOT* be `127.0.0.1`, but does follow the same format `x.x.x.x`

### Finding your CLI username
Running `whoami` will show you your username

### Host File
We will also need to add the following entry to our host file so that we can access our docker containers, and our site using our local url.
The host file can usually be found under `/etc/hosts`. In this file, please add the following line (*sudo is probably required*)

```
127.0.0.1	db web mailhog redis hangman.local
```

## Laravel
This project was made with the Laravel Framework, which also needs an environment file. We can use the provided example file again, by running the following command in the root folder of our project.

```
cp .env.example .env
```

### API authentication
For the API authentication a package called [jwt-auth](https://github.com/tymondesigns/jwt-auth) is used.
In order to get this working we will need to run the following commands.

```
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider";
php artisan jwt:secret
```

## All good?
If the above steps have been taken, we can start running our application!

To do this we'll fire up docker, by going to the docker directory `cd docker`, and running the following command.

```
docker-compose up --build
```

*Note: the `--build` parameter is only required on the first run.*

One all docker images have been downloaded, and set up, we can initialize our database, by runnning the following command.

```
php artisan migrate
```

After this, we should be able to see our app running on the following url: http://hangman.local/


## API
In order to use the api, you will need an account. This account will be the same account that's used on the normal website. Simply register an account on the website, and use the same credentials for accessing the API.


### API endpoints

| Method        | Path            | Note                      |
| ------------- | --------------- | ------------------------- |
| `POST`        | /api/login      | - Send a login request to the API, which will return a Bearer token when succesfull |
| `GET`         | /api/games      | - Lists all available games for the authenticated user |
| `GET`         | /api/games/:id  | - Retrieves data for an existing game, if the user owns that game |
| `POST`        | /api/games/new  | - Creates a new game object |
| `POST`        | /api/games/:id/guess/:char | - Update the game by guessing if :char is in the solution |

### Parameters

| Parameter  | Value |
| ------------- | ------------- |
| :id  | Integer - Id of the game  |
| :char  | String - A single character within the a-z range (case insensitive)  |

### Authentication

All endpoints require authentication. An account can be created though the interface.
In order to receive an authentication token, perform a POST call to /api/login, and provide the following body

```
{
"email": "your_account_email@example.com",
"password": "your_chosen_password"
}
```

The response will return a bearer token, which can be used for your other requests, by adding the following header

```
Authorization: Bearer YOUR_TOKEN
```

*Please note that the generated token is only valid for 1 hour*

### Response data

**Errors**

```
{
    "error": "ERROR MESSAGE"
}
```

**Login request**

```
{
    "access_token": "YOUR GENERATED ACCESS TOKEN",
    "token_type": "bearer",
    "expires_in": 3600
}
```

**Example game data**

```
{
    "data": {
        "id": 10,
        "status": 2,
        "solution": "word",
        "guessed_letters": "E, A, R, W, O, D",
        "created_at": "2018-05-02 01:37:23",
        "updated_at": "2018-05-02 01:37:50",
        "user": {
            "id": 1,
            "name": "Martijn",
            "email": "martiniswink@gmail.com",
            "created_at": "2018-05-01 17:45:06",
            "updated_at": "2018-05-01 17:45:06"
        }
    }
}
```
