# Symfony final task for internship

This is a explanation of basic setup that needs to be done for the project.

## Setup 📃

Below we will setup the symfony project

## Get the project
### First we need to get the project from this git

Open your designated terminal and go to your www, htdocs, html ... etc folder and run the below command.

```bash
git clone git@github.com:Slokyy/docker-symfony.git
```

## Installing dependencies
### Installing the needed dependencies for the project

After running the clone you need to move into the newly pulled project
Once inside run these basic commands to install needed dependencies 

Install symfony dependencies:
```bash
composer install
```

Install node modules:
```bash
npm install
```

Install Yarn if you do not have it ( for webpack):
```bash
npm install -g yarn
```
## Setting the database
### .env setup

Go into .env file on the line where mysql is configured and enter needed info to connect to your local database.
Swap *db_user* with *database user*, *db_password* with *database password* and *db_name* with *database name*. Make sure the db_name is unique and non existant inside mysql.

```dotenv
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7&charset=utf8mb4"
```

### Running doctrine commands that create the database

**IMPORTANT**: Make sure you have your local webserver running at this point

Run below commands to create the database:
```bash
symfony console doctrine:database:create
```
After this verify that the database is created in your mysql database environment

### Next we need to run the migrations
**Sanity Check:** Check the migrations folder and if for some reason its missing Version files you will need to run:
```bash
symfony console make:migration
```
Run this command only if the above notice is true and you don't have the migration files. 
If you have the migration files run:
```bash
symfony console doctrine:migrations:migrate
```
After this you should check your database and confirm that you have sucessfully migrated.
You should see 5 tables, doctrines doctrine_migration_versions and messenger_messages and most importantly: users, clients and user_client tables

These tables should have their structure setup.
While you are here it would be wise to enter an admin user here so you can log into the app.
Admin is a user with the role of: ["ROLE_ADMIN"].
If you want you can grab the 
