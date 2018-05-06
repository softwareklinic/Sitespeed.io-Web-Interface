## Sitespeed.io Web Interface - Thanks to sitespeed.io project (Peter & team)

At Verizon we are heavy users of sitespeed.io - providing us with continuous evaluation, monitoring, and coach suggestions related to desktop and mobile web applications. While most tools provide us means to monitor and alert - sitespeed.io is very unique since it gives you insights on how to make the website experience better in terms of response times & rendering.

## ABOUT THE REPO - Thanks to Blackmamba project

This repo is carved out of https://github.com/blackmamba/sitespeediowebinterface and have been enhanced to a great extent to accomodate the needs of IT and Business users to run sitespeed.io on-demand.

With this revision and implementation you can now run sitespeed.io in PRODUCTION, staging, and other QA environments within your organization for the docker implementation. You can also, customize this script to run for non-docker implementations - of course, once you understand the PHP implementation.

## PRE-REQUISITE - Setting up Sitespeed.io Using DOCKER

You will need sitespeed.io installed on your local or QA or PROD environment to test this implementation. For now, let us install it on your local machine.

* Visit https://hub.docker.com/r/sitespeedio/sitespeed.io/
* docker pull sitespeedio/sitespeed.io:6.4.1 (using the TAG)
* OR just docker pull sitespeedio/sitespeed.io (for latest version)

#### SAMPLE - Docker run for Sitespeed.io

* **docker run --shm-size=1g --rm -v "$(pwd)":/sitespeed.io sitespeedio/sitespeed.io http://www.sitespeed.io/ -b chrome --speedIndex --video**

## INSTALL NGINX with PHP enabled

We will install Nginx on Mac, setup the sitespeed.io, and configure sitespeed.io.php to test this implementation.

$ **brew install nginx** (use homebrew to install nginx on Mac)

Following the brew install nginx -- you will notice below message indicating the location of the nginx docroot and the config files

==> Downloading https://homebrew.bintray.com/bottles/nginx-1.13.12.sierra.bottle
######################################################################## 100.0%
==> Pouring nginx-1.13.12.sierra.bottle.tar.gz
==> Caveats

**Docroot is: /usr/local/var/www**

The default port has been set in **/usr/local/etc/nginx/nginx.conf** to 8080 so that
nginx can run without sudo.

nginx will load all files in /usr/local/etc/nginx/servers/.

To have launchd start nginx now and restart at login:
  **brew services start nginx**
Or, if you don't want/need a background service you can just run:
  **nginx**
==> Summary
🍺  /usr/local/Cellar/nginx/1.13.12: 23 files, 1.4MB

#### Modify Nginx conf to enable PHP (assuming you have PHP installed on your Mac)

* Uncomment below lines in the nginx.conf to enable PHP
* Also, I've placed phpinfo.php under the docroot (/usr/local/var/www) 
* phpinfo.php just has a basic PHP code to print PHP info page when called from browser

#### pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
  location ~ \.php$ {
  	root           html;
    fastcgi_pass   127.0.0.1:9000;
    fastcgi_index  index.php;
    fastcgi_param  SCRIPT_FILENAME  /usr/local/var/www/scripts$fastcgi_script_name;
    include        fastcgi_params;
  }

#### Restart nginx to enable the changes

* $ **brew services restart nginx**

## SETTING UP Sitespeed.io.php & dependencies

Clone this REPO in the document root (e.g. /usr/local/var/www) of your Nginx and you are ready to configure it.

#### Key components

Following are the key components of Sitespeed.io.php

* **css** folder (basic css rules - nothing fancy)
* **images** folder (Just 1 image file - preloader.gif) - is an animated GIF that you want to show when sitespeed portal is processing the URLs for performance analysis
* **sitespeed.io.php** - the heart of this application - Its all in the PHP file - I'm planning to make it better by seperating the configuration into some .ini files later
* **.js** file - you will observe some JS files e.g. staging.js or qa1.js -- those are your preScript files containing the pre login scripts that you will provide as argument to sitespeed if you want to test login-based flows




