#NGINX
 - client_max_body_size 100M;
 - max_input_vars = 3000 

#NOTE
 - git clone git@github.com:winex01/hris.git
 - cp .env.example .env
 - composer install
 - sudo chown -R www-data:www-data storage/ 
 - sudo chown -R www-data:www-data bootstrap/cache/
 - php artisan migrate:fresh
 - php artisan db:seed
 - php artisan storage:link 
 - run factories: 
    - php artisan winex:factories 50 --priority=Employee
- generate Dtr logs:
    - php artisan winex:make-dtrlogs