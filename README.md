# CasinoGameLobby

This project includes a casino game lobby built using **PHP** with the **Symfony Framework**, using **Bootstrap** and **Twig**. 

Caching has been implemented for faster retrieval during the runtime of the application, where the games and game categories are loaded from their respective json file and saved in the cache.

## Features
- View all games in casino game lobby
- View a specific game details
- Search games by name
- Filter games by game category

## Extras
- The entire application has been **Dockerized**
- Doctrine migrations can be generated and executed so that the necessary DB entities are created in the DB.

## Pre-requisities
- PHP version 7.3.28
- Composer
- Nodejs
- Docker

## Installation
1.  Download the project
2.  In the root folder run the following commands:
  - Run `npm install` to install the required JS dependencies
  - Run `composer install` to install the required PHP dependencies
  - Run `npm run dev` to generate the public build folder
  - Finally, run `docker-compose up -d --build` to build the application and host it in a Docker container 
3.  Access the application by navigating to `http://localhost:8000`

## Running the Doctrine migrations
The Doctrine migrations can be run through the following commands (after hosting on Docker):
- `symfony console make:migration`
- `symfony console doctrine:migrations:migrate`
