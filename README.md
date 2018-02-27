# Laravel Best-Practices


This repository is based on [Carpoolear](https://github.com/STS-Rosario/carpoolear_backend). Following a pattern of architectural  of model, repository, service and presentation


There is still much to improve :)

### Packages
 	-laravel/framework": "5.5.*"
    - DINGO v2.0.0-alpha1
    - JWT 0.5.*


### Start coding

    Install dependencies
    composer install
    
    Generate laravel key
    php artisan key:generate
    
    Configure the database access in the .env file
   
    Generate the database
    php artisan migrate --seed
    
    
 ### Endpoints
 
 **POST api/login**
 ```
 {
  "email": "gonzah@helloworld.com",
  "password": 123456,
 }
  ```
**GET api/users** user list

**GET api/users/{id}** get user by id

**POST api/users** Create a user

```
{
  "name": "Eric McLaughlin",
  "password": 123456,
  "email": "lawrence.fay@example.org",
  "profiles": [1, 2]
 }

 ```
 
 **POST api/users** Update a user
 ```
{
  "id": 1,
  "name": "Eric McLaughlin",
  "password": 123456,  
  "profiles": [1, 2]
 }
  ```

### To refactor

1. BaseRepository.php
2. Handling http responses

**Note:**

> To declare an **appService** or **repository** remember to register it in the Providers / AppServiceProvider.php to make use of the dependency injection

 

