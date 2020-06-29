Symfony test blog
=================

# Installation
```bash
git clone git@github.com:Spell6inder/symfony-test-blog.git
cd symfony-test-blog/docker
docker-compose up
```

# For develop frontend

```bash
docker-compose run --rm nodejs npm run watch
```

# Tests

## Init doctrine schema

```bash
docker-compose exec php-fpm php bin/console doctrine:schema:create -e test -n
```

## Load fixtures

```bash
docker-compose exec php-fpm php bin/console doctrine:fixtures:load -e test -n
```

## Run

```bash
docker-compose exec php-fpm php bin/phpunit
```
