## Sitespeed.io Web Interface


This repo is carved out of https://github.com/blackmamba/sitespeediowebinterface and have been enhanced to a great extent to accomodate the needs of IT and Business users to run sitespeed.io on-demand.

With this revision and implementation you can now run sitespeed.io in PRODUCTION, staging, and other QA environments within your organization for the docker implementation. You can also, customize this to run for non-docker implementations - once you understand the PHP implementation.

## PRE-REQUISITE - Setting up Sitespeed.io Using DOCKER

* Visit https://hub.docker.com/r/sitespeedio/sitespeed.io/
* docker pull sitespeedio/sitespeed.io:6.4.1 (using the TAG)
* OR just docker pull sitespeedio/sitespeed.io (for latest version)

#### SAMPLE - Docker run for Sitespeed.io

* docker run --shm-size=1g --rm -v "$(pwd)":/sitespeed.io sitespeedio/sitespeed.io http://www.sitespeed.io/ -b chrome --speedIndex --video

## INSTALL NGINX with PHP enabled

We will install Nginx on Mac, setup the sitespeed.io, and configure sitespeed.io.php to test this implementation.

$ brew install nginx (use homebrew to install nginx on Mac)

