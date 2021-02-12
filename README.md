# Persona

![Continuous Integration](https://github.com/JorgeHRJ/persona/workflows/Continuous%20Integration/badge.svg)

Persona is a small CMS developed to have a personal portfolio + blog combination. In the portfolio you can show 
your personal information in addition to your education, work experience, projects made and skills. The blog is very
simple but powerful in order to have a small place where you may write your pieces.

Stack:
- Symfony 5.2
- PHP 7.4 + mariaDB + nginx

## Requirements

You can download or fork this project if you want.
You can install it locally. In order to do that, please make sure you have the following software installed. If not, 
please, install them:

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

## Qualitify
[Qualitify](https://github.com/JorgeHRJ/qualitify) is a bash script which uses a set of code quality tools
for PHP and Symfony projects like this.

```bash
docker exec -it persona-php bin/qualitify.sh
```

## Manager
[Manager](bin/manager) is a bash script in order to perform some common tasks, such as up and down your dockers, build assets, etc.
```bash
bin/manager <task>
```

## GitHub Actions
Project has a GitHub Action for Continuous Integration. You can look at it in the following file:
[continuous-integration.yml](.github/workflows/continuous-integration.yml)

#### That's all!
 
Open your browser and go to:
* http://persona.loc
