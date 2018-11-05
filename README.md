# Shopware on Docker

**This project is currently only intended for development purposes (e.g. plugins). A production version will follow.**

# Requirements
 - Currently, only Mac OS is supported (tested on 2015, 2017 and 2018 MBP)
 - Docker CE 18.X
 
# Setup
**Preparation**
 - Set up a new entry in your `/etc/hosts` file for shopware, e.g.:
     `127.0.0.1 docker.shopware.local`
     If you want to use a different hostname, you also need to set it up accordingly in  	  the following files:
	 - `dev-ops/docker/containers/php/entrypoint` lines 18 & 19
	 - `dev-ops/docker/containers/nginx/config/conf.d/default.conf` line 4
	 - `docker-compose.yml` line 33
##
 1. Clone the repository
	 `git clone https://github.com/JanFoerste/shopware-docker`
 2. Open a terminal in the directory that was created
 3. Build the images
    `docker-compose build`
 4. From this step on, you should not have an IDE open in the installation directory, as docker maps the shopware directory to your local system. If an IDE starts to index that directory, it might slow down your computer to the point that docker-compose crashes.
 5. Run the docker composition
    `docker-compose up`
	You can also add the `-d` parameter to run it in detached mode. However, it might be helpful to see the STDOUT on the first run in case of any errors. This first run will probably take ~15 minutes to complete, depending on your machine. It installs all assets and the database.
 6. You can now access the demo shop in your browser by opening `http://docker.shopware.local:8080`. The admin interface can be found at `http://docker.shopware.local:8080/backend`. The default credentials are `demo:demo`.