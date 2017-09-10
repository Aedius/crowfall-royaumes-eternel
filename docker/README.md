base on https://github.com/maxpou/docker-symfony



> docker-compose -f [...] exec php composer install
> docker-compose -f [...] exec php php bin/console doctrine:migrations:migrate

##### for https : 

> docker-compose -f [...] exec nginx certbot certonly --webroot -w /var/www/symfony/web -d royaumes-eternels.net
