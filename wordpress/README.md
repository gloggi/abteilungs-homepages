#Running locally

1. Clone the git repository

    ```git clone https://github.com/gloggi/abteilungs-homepages.git```

2. Go into the wordpress subdirectory

    ```cd abteilungs-homepages/wordpress```

3. Start the docker containers

    ```docker-compose up```

4. Once the containers have started, access the site at ```localhost``` (can be changed in .env.local). You can log in at localhost/wp-admin with user 'AL' and password 'gloggi'. If you change something in the plugin or theme directories, refresh the page in your browser to see the changes take action immediately.


#Installing on a hoster

1. Set up a WordPress instance at your hoster

2. Using SSH, log into the server and install WP-CLI (see https://gist.github.com/neverything/851778304fd730b468fe).

3. Using FTP, copy the current version of the plugin and theme to the respective directories in wp-content at your hoster

4. Using FTP, copy post-install.sh to your wp-content folder and make it executable using ```chmod +x post-install.sh```

5. Execute the install script:

    ```./post-install.sh ~/bin/wp```