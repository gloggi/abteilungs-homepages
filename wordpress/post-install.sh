#!/bin/bash

# Here specific Wordpress setup can be done using WP-CLI.
# Note, the commands in this file are run every time docker-compose up runs (regardless of whether the containers are newly created or re-started).

# Remove unnecessary plugins and themes
runuser www-data -s /bin/sh -c 'wp plugin list --status=inactive --field=name | egrep -v "gloggi" | xargs -0 -d"\n" wp plugin uninstall'
runuser www-data -s /bin/sh -c 'wp theme list --status=inactive --enabled=no --field=name | egrep -v "gloggi" | xargs -0 -d"\n" wp theme delete'

# This command must be last in this file, it runs the Wordpress server
apache2-foreground
