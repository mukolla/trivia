**Install**

1. docker exec -it trivia_api_db bash
2. mysql -u root -p123123 
   - show databases;
   - GRANT ALL ON trivia_db.* TO 'trivia_user'@'%' IDENTIFIED BY '123123'; 
   - FLUSH PRIVILEGES;
   - EXIT;
3. exit


Check app DB connection

1. docker exec -it trivia_api_app bash
   - php artisan tinker
   - php artisan migrate


**Install new package composer**

1. Go to app folder. (as example: cd /home/mukolla/trivia)
2. docker run --rm -v $(pwd):/app composer require darkaonline/l5-swagger

**Update API Documentation with OpenAPI/Swagger**

1. docker exec -it trivia_api_app bash
2. php artisan l5-swagger:generate
    

**Command List**
- php artisan make:middleware JsonMiddleware
