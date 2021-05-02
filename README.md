# SnowTricks Website

**Version 1.0.0** 

:computer: This project was created in the context of OpenClassRooms Symfony path. </br>
:briefcase: It is the 6th project, and the first in which Symfony was used. 
It was a very important project, and it allowed me to understand the framework. 

## Installation of the project

1.  Clone the project
> git clone https://github.com/Ludovicmanga/projetSnowTricks.git

2.  Modify the .env file, according to your own configuration
> MAILER_DSN=smtp://localhost:1025 <br>
> DATABASE_URL="mysql://root:@127.0.0.1:3306/snowTricks?serverVersion=mariadb-10.4.11"

3.  Install the dependencies 
> composer install

4.  Create the database
> php bin/console doctrine:database:create

5.  Generate the migrations files 
> php bin/console make:migration

6.  Execute the migrations files
> php bin/console doctrine:migrations:migrate

7.  Execute the fixtures
> php bin/console doctrine:fixtures:load

--- 

## License  copyright 
:copyright: Copyright Ludovic Manga-jocky 