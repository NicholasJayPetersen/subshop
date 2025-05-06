# subshop

## Site Installation with Docker on MacOS
### Step 1 (MacOS / Docker)
Configure Git and clone the repository to your preffered directory.
I will use ~/docker/subshop in this example

### Step 2 (MacOS / Docker)
cd ~/docker/subshop to get into the project directory.
export NGINX_PORT=80 to set the web server to port 80. (See notes below.)
docker compose up -d to start the project running in Docker.
./fixperms.sh
docker exec -it app_php bash to open a shell inside the Docker container.
composer install inside the container to install Symfony components and dependencies.
Note: You should only need to run ./fixperms.sh the first time, not on every startup.

Setting NGINX_PORT
The NGINX_PORT environment variable sets the port for the Nginx web server in your project. If this is not set, the project will default to port 8080, which you'll need to use in your browser. If port 80 is not available, you'll need to use the default or another port.

(In my Windows screenshots below, port 80 did not work, so you'll see 8080 in my browser's address bar there.)

If port 80 works for your initial test, add the line export NGINX_PORT=80 to your ~/.bashrc file so it will be set automatically every time.

### Step 3 (MacOS / Docker)
composer dump-env dev inside the container to create your own local configuration file.
Open your project folder in Visual Studio Code (or the editor of your choice.)
Edit the .env.local.php to adjust your settings. The defaults should mostly be fine.
Add settings for ADMIN_PASSWORD and JWT_SECRET if they don't exist. You will need these later to provide an initial password for the admin user and for REST API security.

### Step 4 (MacOS / Docker)
bin/console doctrine:migrations:list to list available database migrations.
bin/console doctrine:migrations:migrate to execute database migrations.
Use phpMyAdmin on http://localhost:8081/ to verify table creation. (Log in as root/root)
Screenshot 4 - Running Database Migrations
Step 5 (MacOS / Docker)
View your site at http://localhost/
(You may need to try a different port as noted in step 2.)

### Step 6 (MacOS / Docker)
When you are done working, you can use the following steps to exit from the container shell and shut down all running containers.

exit (or ctrl+d) from the container shell.
docker compose down to stop and remove all containers.
Containers are temporary! It's okay to remove them. Your code is still in your ~/docker/subshop folder and your database is saved in a Docker volume for next time. You can shut down Docker if you need to free up resources for other work. It's also fine to leave your Docker containers running all the time if your computer can handle it, or simply pause them for a quick restart.


## Site Installation with Docker on Windows
### Step 1 (Windows / Docker)
Configure Git and clone the repository to your preffered directory.
I will use ~/docker/subshop in this example

mkdir ~/docker to create a place for Docker projects.
mkdir ~/docker/.ssh to store a copy of your ssh keys that can be accessed by the Docker container.
cp ~/.ssh/id_ed25519* ~/docker/.ssh to copy your keys into the new directory.
Clone the project into the target directory.
For Winter 2025: git clone git@gitlab.hfcc.edu:cis-294-25wi/website.git ~/docker/subshop

### Step 2 (Windows / Docker)
cd ~/docker/subshop to get into the project directory.
export NGINX_PORT=80 to set the web server to port 80. (See notes below.)
docker compose up -d to start the project running in Docker.
./fixperms.sh
docker exec -it app_php bash to open a shell inside the Docker container.
composer install inside the container to install Symfony components and dependencies.
Note: If the docker exec command fails with a message about winpty, try this instead:
winpty docker exec -it app_php bash

Note: You should only need to run ./fixperms.sh the first time, not on every startup.

Setting NGINX_PORT
The NGINX_PORT environment variable sets the port for the Nginx web server in your project. If this is not set, the project will default to port 8080, which you'll need to use in your browser. If port 80 is not available, you'll need to use the default or another port.
If port 80 works for your initial test, add the line export NGINX_PORT=80 to your ~/.bashrc file so it will be set automatically every time.

### Step 3 (Windows / Docker)
composer dump-env dev inside the container to create your own local configuration file.
Open your project folder in Visual Studio Code (or the editor of your choice.)
Edit the .env.local.php to adjust your settings. The defaults should mostly be fine.
Add settings for ADMIN_PASSWORD and JWT_SECRET if they don't exist. You will need these later to provide an initial password for the admin user and for REST API security.

### Step 4 (Windows / Docker)
bin/console doctrine:migrations:list to list available database migrations.
bin/console doctrine:migrations:migrate to execute database migrations.
Use phpMyAdmin on http://localhost:8081/ to verify table creation. (Log in as root/root)

### Step 5 (Windows / Docker)
View your site at http://localhost/
(You may need to try a different port as noted in step 2.)


### Step 6 (Windows / Docker)
When you are done working, you can use the following steps to exit from the container shell and shut down all running containers.

exit (or ctrl+d) from the container shell.
docker compose down to stop and remove all containers.
Containers are temporary! It's okay to remove them. Your code is still in your ~/docker/subshop folder and your database is saved in a Docker volume for next time. You can shut down Docker if you need to free up resources for other work. It's also fine to leave your Docker containers running all the time if your computer can handle it, or simply pause them for a quick restart.
