#!/bin/bash

# Here specific Wordpress setup can be done using WP-CLI.
# Note, the commands in this file are run every time docker-compose up runs (regardless of whether the containers are newly created or re-started).

if [[ $(runuser www-data -s /bin/sh -c 'wp option get blogdescription') == *WordPress* ]]
then

	# We are setting up for the first time
	echo 'Starting the container for the first time, do initial setup'
	runuser www-data -s /bin/sh -c 'wp option update blogdescription "Pfadiwebseite"'

	# Remove unnecessary plugins and themes
	runuser www-data -s /bin/sh -c 'wp plugin list --status=inactive --field=name | egrep -v "gloggi" | xargs -0 -d"\n" wp plugin uninstall'
	runuser www-data -s /bin/sh -c 'wp theme list --status=inactive --enabled=no --field=name | egrep -v "gloggi" | xargs -0 -d"\n" wp theme delete'

	# Set a static front page - any page we can find
	runuser www-data -s /bin/sh -c 'wp option update show_on_front page'
	runuser www-data -s /bin/sh -c 'wp option update page_on_front $(wp post list --post_type=page --field=ID | head -n 1)'

else

	echo 'This container has been started before'

fi


# This command must be last in this file, it runs the Wordpress server
apache2-foreground
