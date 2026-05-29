## Run application
```
docker compose down && docker compose up -d --build
```

## Run Laravel commands
```
docker compose exec php bash
php artisan short-schedule:run
php artisan queue:work
```