#!/bin/bash

echo -e "\n\e[94m DEPLOY! \e[0m\n"

git checkout master
git pull origin master

cd infra/docker/prod
cp dist.env .env
docker-compose down
docker-compose up -d --build
cd ../../../

docker exec persona-php composer dump-env prod
docker exec persona-php composer install --no-dev --optimize-autoloader
docker exec persona-php bin/console doctrine:schema:update --force
docker run -v $(pwd):/home/app persona-node yarn install
docker run -v $(pwd):/home/app persona-node yarn encore production

docker exec persona-php bin/console cache:clear
docker exec persona-php bin/console cache:warmup
