Software Dependencies
=====================

This is the list of software used by Bibdhek. Beware that it might be incomplete
list, but I try to keep as updated as possible.

- CakePHP 2.2.1 (Integreated on the sources. See src/app/lib/Cake/VERSION.txt)

On the server, you need the following software installed. Name of packages based
on Ubuntu 12.04

- Apache (apache2)
- PHP (php5 php-pgsql libapache2-mod-php5)
- Postgresql (postgresql-9.1)
 
You may used or another database, but then you need to change the configuration
on src/app/Config/database.php

For the scripts to insert data into the database

- Python (python2.7)
- Psycopg (python-psycopg2)
