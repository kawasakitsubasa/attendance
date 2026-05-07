# 勤怠管理アプリ

## 環境構築


```bash
https://github.com/kawasakitsubasa/attendance.git
docker compose up -d --build
```

Laravel環境構築

```bash
docker compose exec php bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## 使用技術（実行環境）

- PHP 8.1
- Laravel 8.83.8
- MySQL 8.0.26

## ER図
![ER図](public/image/erd.png)

## URL

- 開発環境：http://localhost/
- phpMyAdmin：http://localhost:8080/
