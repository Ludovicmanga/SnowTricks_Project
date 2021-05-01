# SnowTricks Website

**Version 1.0.0** 

This project was created in the context of OpenClassRooms Symfony path. 

**Installation of the project**

1. Clone the project
2. Install the dependencies 
composer install
3. Create the database
php bin/console doctrine:database:create
4. Generate the migrations files 
php bin/console make:migration
5. Execute tje migrations files
php bin/console doctrine:migrations:migrate
6. Execute the fixtures
php bin/console doctrine:fixtures:load

--- 

## License  copyright 
&copy Ludovic Manga-jocky 