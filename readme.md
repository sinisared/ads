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


## Depolying in AWS Elastic Beanstalk 
Use the script provided, prepare database instance on AWS manually before deployment and update the script.
```
# The following script will deploy a Laravel 5 applicaion on AWS Elastic Beanstalk.
# Add to .ebextensions at the root of your application and name your commands file (e.g., commands.config)

# -------------------------------- Commands ------------------------------------
# Use "commands" key to execute commands on the EC2 instance. The commands are 
# processed in alphabetical order by name, and they run before the application 
# and web server are set up and the application version file is extracted. 
# ------------------------------------------------------------------------------
commands:
  01updateComposer:
    command: export COMPOSER_HOME=/root && /usr/bin/composer.phar self-update

option_settings:
  - namespace: aws:elasticbeanstalk:application:environment
    option_name: COMPOSER_HOME
    value: /root

  - namespace: aws:elasticbeanstalk:container:php:phpini
    option_name: document_root
    value: /public

  - namespace: aws:elasticbeanstalk:container:php:phpini
    option_name: memory_limit
    value: 512M
    
# Create RDS database, requires adding env variables.  
#  Resources:
#  AWSEBRDSDatabase:
#    Type: AWS::RDS::DBInstance
#    Properties:
#      AllocatedStorage: 5
#      DBInstanceClass: db.t1.micro
#      DBName: #insert db name
#      Engine: mysql
#      EngineVersion: 5.6
#      MasterUsername: #insert name
#      MasterUserPassword: #insert pass
    
# ---------------------------- Container Commands ------------------------------
# You can use the container_commands key to execute commands for your container. 
# The commands in container_commands are processed in alphabetical order by 
# name. They run after the application and web server have been set up and the 
# application version file has been extracted, but before the application 
# version is deployed. They also have access to environment variables such as
# your AWS security credentials. Additionally, you can use leader_only. One 
# instance is chosen to be the leader in an Auto Scaling group. If the 
# leader_only value is set to true, the command runs only on the instance 
# that is marked as the leader.
#
# Artisan commands include environment flag for production. If you are not
# deploying to a production environment, update the flag. 
# ------------------------------------------------------------------------------

container_commands:
  01express:
    command: "echo AWS Container Commands started, starting Composer install."
  02installComposer:
    command: "php /opt/elasticbeanstalk/support/composer.phar install"
    cwd: "/var/app/ondeck"
  03express:
    command: "echo Composer install completed, starting Laravel migration"
  04migrations:
    command: "php artisan migrate --env=production"
    cwd: "/var/app/ondeck"
  05express:
    command: "echo Completed Laravel migration, starting Laravel database seeding"
  06seeds:
    command: "php artisan db:seed --env=production"
    cwd: "/var/app/ondeck"
    leader_only: true
  07express:
    command: "echo Completed database seeting, Container Commands complete."
```
