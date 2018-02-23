 #!/bin/sh

# Start
docker-compose up -d && docker-compose ps

# Start and Remove orphans
docker-compose up -d --remove-orphans && docker-compose ps

# Start and Rebuild Docker images
docker-compose up -d --build && docker-compose ps

# Remove database container and restart (wait 10sec after failure)
rm -rf ./mariadb/ &&
docker-compose stop &&
docker-compose rm database &&
docker-compose up -d --build &&
docker-compose ps