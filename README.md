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
