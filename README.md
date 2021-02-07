# Persona

Stack:
- Symfony 5.2
- PHP 7.4 + mariaDB + nginx

## Requirements

Please make sure you have the following software installed. If not, please, install them:

* [Docker](https://docs.docker.com/install/)
* [Docker Compose](https://docs.docker.com/compose/install/)

## Docker

Create .env file for Docker and modify it in case you need it:
```bash
cd infra/docker/local
cp dist.env .env
```

Build and start containers:
```bash
docker-compose build
docker-compose up -d
```

Build node container:
```bash
docker build -t persona-node node/ --no-cache
```

## Database

Let's create the database for this project:
```bash
docker exec -t persona-db mysql -e "CREATE DATABASE IF NOT EXISTS persona"
docker exec -t persona-db mysql -e "GRANT ALL ON persona.* TO 'persona'@'%' IDENTIFIED BY 'persona'"
```

## App

Create Symfony .env file for your local environment:
```bash
cd </project/root>
cp .env .env.local
```

Install vendors via composer
```bash
docker exec -it persona-php composer install
```

Install node dependencies
```bash
cd </project/root>
docker run -it -v $(pwd):/home/app persona-node yarn install --force
```

To access node container
```bash
docker run -it -v $(pwd):/home/app persona-node bash
```

Create database schema
```bash
docker exec -it persona-php bin/console doctrine:schema:create
```

Update your /etc/host file adding the following entry
```bash
127.0.0.1       persona.loc
```

## Assets 

Build assets 
```bash
docker run -it -v $(pwd):/home/app persona-node yarn encore dev
```

Build assets for specific part of the project
```bash
docker run -it -v $(pwd):/home/app persona-node yarn encore dev-cms
docker run -it -v $(pwd):/home/app persona-node yarn encore dev-site
```

Watch mode
```bash
docker run -it -v $(pwd):/home/app persona-node yarn encore watch
```

Watch mode for specific part of the project
```bash
docker run -it -v $(pwd):/home/app persona-node yarn encore watch-cms
docker run -it -v $(pwd):/home/app persona-node yarn encore watch-site
```

## Code quality
```bash
docker exec -it persona-php bin/qualitify.sh
```


#### That's all!
 
Open your browser and go to:
* http://persona.loc
