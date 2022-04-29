#NGINX
 - client_max_body_size 100M;
 - max_input_vars = 3000 

#REMINDER
 - schedule 
 - php artisan queue:work --queue=high,medium,default 
   - use supervisor
 - check backup in dropbox
 - test scan chapter
 - test queue
 - supervisorctl restart all // restart supervisor if queue doesnt work

#INSTALLATION
 - git clone git@github.com:winex01/mangachapters.git
 - cp .env.example .env
 - composer install
 - sudo chown -R www-data:www-data storage/ 
 - sudo chown -R www-data:www-data bootstrap/cache/
 - php artisan migrate:fresh
 - php artisan db:seed
 - php artisan storage:link 
 - run factories: 
    - php artisan winex:factories 50 --priority=ModelName
