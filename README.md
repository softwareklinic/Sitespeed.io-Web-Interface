## NOTE: If you have Nginx & PHP working - this setup should not take more than 10-15 mins to bring the UI in action

## Sitespeed.io Web Interface

At Verizon we are heavy users of sitespeed.io - providing us with continuous evaluation, monitoring, and coach suggestions related to desktop and mobile web applications. While most tools provide us means to monitor and alert - sitespeed.io is very unique since it gives you insights on how to make the website experience better in terms of response times & rendering.

![Sitespeed.io Web Interface](https://github.com/softwareklinic/Sitespeed.io-Web-Interface/blob/master/images/sitespeed-web-interface.png "Sitespeed.io Web Interface")

## ABOUT THE REPO

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
################################################################## 100.0%
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

#### Pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
```
  location ~ \.php$ {
  	root           html;
    fastcgi_pass   127.0.0.1:9000;
    fastcgi_index  index.php;
    fastcgi_param  SCRIPT_FILENAME  /usr/local/var/www$fastcgi_script_name;
    include        fastcgi_params;
  }
```

#### Restart nginx to enable the changes

* $ **brew services restart nginx**

#### Test PHP Info

* http://localhost:8080/phpinfo.php

![Php info](https://github.com/softwareklinic/Sitespeed.io-Web-Interface/blob/master/images/phpinfo-www-folder.png "Php info")

## SETTING UP Sitespeed.io.php & dependencies

Clone this REPO in the document root (e.g. /usr/local/var/www) of your Nginx and you are ready to configure it.

#### Key components

Following are the key components of Sitespeed.io.php

* **css** folder (basic css rules - nothing fancy)
* **images** folder (Just 1 image file - preloader.gif) - is an animated GIF that you want to show when sitespeed portal is processing the URLs for performance analysis
* **sitespeed.io.php** - the heart of this application - Its all in the PHP file - I'm planning to make it better by seperating the configuration into some .ini files later
* **.js** file - you will observe some JS files e.g. staging.js or qa1.js -- those are your preScript files containing the pre login scripts that you will provide as argument to sitespeed if you want to test login-based flows

![Sitespeed.io.php Folder structure](https://github.com/softwareklinic/Sitespeed.io-Web-Interface/blob/master/images/sitespeed-www-folder.png "Sitespeed.io.php Folder structure")

#### MOST IMPORTANT SECTION - the PHP script contains few configurable elements that you need to change to suit your needs

Below sections of code must be configured before giving this tool a spin:

* Replace www.yourdomain.com with "your own domain" e.g. www.verizonwireless.com

```php
$pageUrl = "https://www.yourdomain.com";
```

```html
<textarea class="form-control" rows="5" id="multipleurl" name="multipleurl">https://www.yourdomain.com</textarea>
```

* Customize this section to add your choice of environments

```html
<ul class="dropdown-menu" name="environment">
    <li><a href="#">www</a></li>
    <li class="divider"></li>
    <li><a href="#">staging</a></li>
    <li class="divider"></li>
    <li><a href="#">qa1</a></li>
    <li><a href="#">qa2</a></li>
    <li><a href="#">qa3</a></li>
    <li><a href="#">qa4</a></li>
    <li><a href="#">qa5</a></li>
    <li><a href="#">qa6</a></li>
    <li><a href="#">qa7</a></li>
    <li><a href="#">qa8</a></li>
    <li><a href="#">qa9</a></li>
    <li><a href="#">qa10</a></li>
</ul>
```

* This section has sitespeed.io defaults for your environment
* While running on local machine you might not need proxy - so maybe you can choose to comment out this line and replace with ```$proxysetting = '';```
* firstParty - I've configured here to just show case how to add one or more of your domains as 1st party - everything else is 3rd party

```php
// Sitespeed setting (default)

// use for testing URLS via PROXY
$proxysetting = ' --browsertime.proxy.http=proxy.yourdomain.com:80 --browsertime.proxy.https=proxy.yourcomain.com:80';

// use for testing on local or if no PROXY involved for internet traffic
$proxysetting = '';

$resultBaseUrl = ' --resultBaseURL http://sitespeed.yourdomain.com:80/'.$environment;
$firstParty = ' --firstParty ".*(vzw|verizonwireless).*"';
$resultsUrl = ' --outputFolder sitespeed-result/'.$environment.'/$(date +\%Y-\%m-\%d-\%H-\%M-\%S)';
$videoIndex = ' --video --speedIndex';
```


* All the URLs that you enter in the TEXT AREA in the UI will be 1st saved to a TEXT file under the folder assigned to $fileHandle
* The text file is named using the random number generated in the php code to support multiple users trying to use this feature at same time - this might be subject to improvement
* Feel free to change this to any other folder that you think will be used as the sitespeed.io root folder
* In our production we are using /app/sitespeed.io

```php
if (strlen(trim($_POST['multipleurl']))>0) {
    $fileName = $randomNumber.'.txt';
    $fileHandle = fopen('/usr/local/var/www/'.$fileName, "a");
    fwrite($fileHandle,$_POST['multipleurl']."\r\n");
    fclose($fileHandle);
}
```

![Random number file names](https://github.com/softwareklinic/Sitespeed.io-Web-Interface/blob/master/images/random-numbers.png "Random number file names")

* Mapping the /usr/local/var/www or ANY other folder as sitespeed mount folder
* Feel free to map this to any other folder that you think will be used as the sitespeed.io root folder
* In our production we are using /app/sitespeed.io

```php
$dockerCommand = 'docker run --shm-size=1g --rm -v /usr/local/var/www:/sitespeed.io sitespeedio/sitespeed.io';

// for Naveen - pls add sitespeed version below
if (isset($_POST["submit"])){
     // Analyze button clicked
     $dockerCommand = 'docker run --shm-size=1g --rm -v /usr/local/var/www:/sitespeed.io sitespeedio/sitespeed.io -b chrome '.$pageUrl.$proxysetting.$resultBaseUrl.$firstParty.$resultsUrl.$videoIndex.$preLoginScript;
} else if (isset($_POST["homepage"])){
     // Analyze home page button clicked
     $pageUrl = "https://www.yourdomain.com";
     $dockerCommand = 'docker run --shm-size=1g --rm -v /usr/local/var/www:/sitespeed.io sitespeedio/sitespeed.io -b chrome '.$pageUrl.$proxysetting.$resultBaseUrl.$firstParty.$resultsUrl.$videoIndex.$preLoginScript;
} else if (isset($_POST["top25"])){
    // Analyze top 25 urls
     $pageUrl = "top25.txt";
     $dockerCommand = 'docker run --shm-size=1g --rm -v /usr/local/var/www:/sitespeed.io sitespeedio/sitespeed.io -b chrome '.$pageUrl.$proxysetting.$resultBaseUrl.$firstParty.$resultsUrl.$videoIndex.$preLoginScript;

}
```

## VIEW RESULTS - customize it

* Below code is used to PARSE the response returned by the sitespeed.io docker command and extract the URL where we are posting the sitespeed-result - hence making the RESULT link clickable and openable in new window
* Cool stuff

```php
if (count($output)>0)
{
    $urlToParse = $output[count($output)-1];
    $strPos = strpos($urlToParse, "http");
    $urlToClick = substr($urlToParse, $strPos);
}
```

## NOTE: SOME MORE TODO 
// COMING SOON

## NEXT STEPS

* Externalize all settings from PHP to ini 
* Dockerize this application - once all variables are externalized
* Enhancements welcome 
* Pull requests welcome

## VOTE OF THANKS

* Many thanks to my colleagues 
  * Somasekhar Nekkalapudi (https://www.linkedin.com/in/somasekharnekkalapudi)
  * Naveen Indurti (https://linkedin.com/in/naveen-indurti-ba1aabb3)
  * Mark Redder (https://www.linkedin.com/in/markredder/)
* Sitespeed.io team (https://github.com/sitespeedio/sitespeed.io)
* Blackmamba project (https://github.com/blackmamba/sitespeediowebinterface)

## Contact

* You can reach me at https://www.linkedin.com/in/keyurkshah/
* More than welcome if you have questions on sitespeed.io or run into any issues making this work


