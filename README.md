# fleamarket

## 環境構築
### Dockerビルド
1. git clone git@github.com:nakamura-toshiki/fleamarket.git  
2. docker-compose up -d --build
### Laravel環境構築
1. docker-compose exec php bash  
2. composer install  
3. cp .env.example .env,環境変数を変更  
``` text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
``` text
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=test@example.com
MAIL_FROM_NAME="${APP_NAME}"
```
``` text
STRIPE_KEY=pk_test_51QWaayK7mVSKK9zQmpmJSm7AjfEvINznQGXoBEnokHOcWj7pYwC5HoPJa8Q7O6hPTjIEyA8G6yyNsnOleiaGXhNN007XU5vnmw
STRIPE_SECRET=sk_test_51QWaayK7mVSKK9zQZs1gi0EcjT15RQo0mcrkw1mdoCXrUKFqmEw2LXg5KZz75LaYZaMNDeeFRPZkLgkRJBJLswpp002RuTqNgO
```
4. php artisan key:generate  
5. php artisan migrate  
6. php artisan db:seed  
## URL
・開発環境：http://localhost/  
・phpMyAdmin:：http://localhost:8080/
## 使用技術
・php 7.4.9  
・Laravel 8  
・mysql 8.0.26  
・nginx 1.21.1
## ER図
![drowio](
