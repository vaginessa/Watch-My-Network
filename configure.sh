#!/bin/bash

##### Sabitler

VERSION="0.0.1"
DATE="12/12/2010"
USERNAME=$(id -un)

CRONTAB="* */1 * * * sudo php /var/www/watchmynetwork/cron.php"

USAGE="
   Usage:

      -v               : Show version number.
      -i | --install   : install this script package.
      -r | --remove    : remove this script package.
"

##### Ana kisim

if   [ "$1" = "-v" ]; then

	printf "\n\tVersion : $VERSION\n"
	printf   "\tDate    : $DATE\n\n"

elif [ "$1" = "-i" ] || [ "$1" = "--install" ]; then

	printf "\ninstalling...\n\n"

	if [ -e "/usr/bin/crontab" ]; then

		if [ -e "/var/spool/cron/crontabs/$USERNAME" ]; then
			crontab -l | grep -v "watchmynetwork" >  /tmp/crontab.temp.txt
			echo "$CRONTAB" >> /tmp/crontab.temp.txt
			crontab  /tmp/crontab.temp.txt
			rm /tmp/crontab.temp.txt

		else
			echo "$CRONTAB" | crontab
		fi
################################################################################################

		cp -R watchmynetwork/ /var/www/watchmynetwork
		chmod 755 -R /var/www/watchmynetwork/
		chmod 777 -R /var/www/watchmynetwork/xmls
		
		php import_mysql.php

################################################################################################
	else
		printf "\tplease, install 'crontab'\n\n"
	fi
elif [ "$1" = "-r" ] || [ "$1" = "--remove" ]; then

	crontab -l | grep -v "watchmynetwork" >  /tmp/crontab.temp.txt
	crontab  /tmp/crontab.temp.txt
	rm /tmp/crontab.temp.txt

	rm -rf -R /var/www/watchmynetwork

else
	printf "$USAGE\n"
fi

