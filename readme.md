## Challenge

create restful api for reading and creating and updating ads

## Database setup

Provided are migration files and Users table seeder.

## Models

* User - describing user, with cascade deletion of ads
* Ad - describing ad

## Controller methods

* AdsController@show($id?) - show all sorted by created_at desc, or show by id (route /api/ads , /api/ads/{x})
* AdsController@create - post rest api to create a new ad, requires api_token in uri for authentication, post json object with all fields required to create an Ad except the id. (route post:api/ad/{x})
* AdsController@update($adId) - put rest api to update an existing ad ad, requires api_token in uri for authentication, post json object with all fields required to create an Ad except the id. (route put:api/ad/{x})

## Validator

* AdsController@validateAd

## Error codes
* 200 all is ok
* 403 Access denied
* 422 You are trying to edit an ad that is not yours

## Test cases

* Features/AdsControllerTest

# Installation

use **git clone**, and **composer install** to setup required components

# Docker support

Use laradock.io development setup, within the project directory install submodule

**git submodule add https://github.com/Laradock/laradock.git**

copy **.env-example** to **.env** and edit MYSQL settings:

```
MYSQL_VERSION=5.7
MYSQL_DATABASE=homestead
MYSQL_USER=homestead
MYSQL_PASSWORD=secret
MYSQL_PORT=3306
MYSQL_ROOT_PASSWORD=root
MYSQL_ENTRYPOINT_INITDB=./mysql/docker-entrypoint-initdb.d
```
Be sure MYSQL version is 5.7 and not latest as there are are issues with version 8.

create docker images using **docker-compose up -d nginx mysql workspace**

Enjoy.
