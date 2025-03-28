## Running docker

```bash
cd docker
docker compose up -d
```

## Manual tests
1. Connect to container
```bash
sudo docker compose exec app bash
```
2. Wait a while for database connection

3. In container run migrations
```
bin/console doctrine:migrations:migrate --no-interaction
```

4. Sync meals from api
```
bin/console app:sync-meals
```

5. Go to http://localhost:8000/

## PHPUnit
1. Connect to container
```bash
sudo docker compose exec app bash
```

2. Run migrations
```bash
bin/console doctrine:database:create --env=test --if-not-exists
bin/console doctrine:migrations:migrate --env=test --no-interaction
```

3. Run tests
```bash
bin/phpunit
```
