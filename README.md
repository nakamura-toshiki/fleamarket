# fleamarket

## 環境構築
### Dockerビルド
・git clone git@github.com:nakamura-toshiki/fleamarket.git  
・docker-compose up -d --build
### Laravel環境構築
・docker-compose exec php bash  
・composer install  
・cp .env.example .env,環境変数を変更  
    '$DB_CONNECTION=mysql'
    $DB_HOST=mysql
    $DB_PORT=3306
    $DB_DATABASE=laravel_db
    $DB_USERNAME=laravel_user
    $DB_PASSWORD=laravel_pass
