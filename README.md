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

### Optional: Setting up data
If you want you can grab the dummy files you can find it in *symfony_project_main.sql* file at the base of the project.

Here you have every table with some data and an admin with email: admin@mail.com and password: secret

### If you did not do the above option
While you are here it would be wise to enter an admin user here so you can log into the app.
Admin is a user with the role of: ["ROLE_ADMIN"].

## Starting the project
To start the project you need to run two commands (symfony server and yarn watcher for webpack)

### Symfony server
```bash
symfony serve -d
```
This should run the server on localhost:8000 by default, if the port is in use it will tell you

If the port is somewhere else or unknown just find it by running:
```bash
symfony console server:list
```

### Webpack
Open a new terminal tab or window inside the project and run this command for webpack encore
```bash
yarn watch
```

## Final ( I promise!)
Now you should be good to go... almost!
Finally you should open your browser and go to the hashed route 
```text
localhost:8000/$2a$12$99iZHSovZPM6xvwAMeFeoONS69pt45Udgplt4DAdT7fDQvX12nBte/login
```

Thats right, the routes are hashed, what of it 😏

## THATS IT, ENJOY 🥳🥳🥳🥳🥳🥳


# DISCLAIMER ⚡⚡⚡

This is a project that I made while learning symfony and this markdown as well, please contact me if you see some problems, bad practices etc.
I'm still learning and I would appreciate your help in getting my skills to the next level.




