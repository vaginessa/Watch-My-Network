
  watchMyNetwork v0.0.1
-----------------------------------------------
	*   Veysi Ertekin  <veysi.ertekin123@gmail.com>
	*   http://www.veysiertekin.com

This script detect network devices that connecting to your network, and saves details of these devices to a MySQL database.

Requirements:

1- apache2, web server.
2- php5, hypertext preprocessor
	(optional) : gmp_lib (for storage and processing of IPv6 addresses) [ubuntu package name : php5-gmp]
3- nmap, security scanner.

How to install and configure?

1- Download watchMyNetwork.

	wget http://goo.gl/1vr1c --output-document=watchMyNetwork_last-release.zip

2- Exract files from archive.

	unzip watchMyNetwork_last-release.zip

3- Please check database and nmap configs.

	chmod +x -R watchMyNetwork*/
	cd watchMyNetwork*/
	vi config.php

4- Run "configure.sh".

	sudo configure.sh --install # or -i

5- and Use it!

	-Please open your web browser.
	-Write "http://localhost/watchmynetwork" to address bar and press "enter" key.
	-Watch network devices on your network!

-----------------------------------------------

Remove:

1- Run configure.sh with "-r" parameter:
	
	sudo configure.sh -r # or --remove   (Note: Your MySQL data don't erase.)

2- That's all :) 



