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

	# Remove unneccessary example content
	runuser www-data -s /bin/sh -c 'wp site empty --yes'

	# Create a new page and set it as the static front page
	mitmachen=$(runuser www-data -s /bin/sh -c 'wp post create --post_type=page --post_title="Mitmachen" --post_status=publish --menu_order=0 --porcelain')
	runuser www-data -s /bin/sh -c 'wp option update show_on_front page'
	runuser www-data -s /bin/sh -c "wp option update page_on_front \"$mitmachen\""

	# Set some options in options-general.php
	runuser www-data -s /bin/sh -c 'wp option update users_can_register 0'
	runuser www-data -s /bin/sh -c 'wp option update timezone_string Europe/Zurich'
	runuser www-data -s /bin/sh -c 'wp option update date_format d.m.Y'
	runuser www-data -s /bin/sh -c 'wp option update time_format H:i'

	# Set some options in options-discussion.php
	runuser www-data -s /bin/sh -c 'wp option update default_pingback_flag 0'
	runuser www-data -s /bin/sh -c 'wp option update default_ping_status 0'
	runuser www-data -s /bin/sh -c 'wp option update default_comment_status 0'
	runuser www-data -s /bin/sh -c 'wp option update comment_registration 1'
	runuser www-data -s /bin/sh -c 'wp option update close_comments_for_old_posts 1'
	runuser www-data -s /bin/sh -c 'wp option update close_comments_days_old 0'
	runuser www-data -s /bin/sh -c 'wp option update thread_comments 0'
	runuser www-data -s /bin/sh -c 'wp option update comments_notify 0'
	runuser www-data -s /bin/sh -c 'wp option update moderation_notify 0'
	runuser www-data -s /bin/sh -c 'wp option update comment_moderation 1'
	runuser www-data -s /bin/sh -c 'wp option update comment_whitelist 0'

	# Set some options in options-media.php
	runuser www-data -s /bin/sh -c 'wp option update uploads_use_yearmonth_folders 0'

else

	echo 'This container has been started before'

fi


# This command must be last in this file, it runs the Wordpress server
apache2-foreground
