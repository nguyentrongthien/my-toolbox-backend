#!/bin/bash

echo 'Running git pull...'
sudo -u root git pull

echo 'Updating owner and permissions...'
sudo chown -R www-data:www-data .
sudo find . -type f -exec chmod 644 {} \;
sudo find . -type d -exec chmod 755 {} \;
sudo find . -iname deploy.sh -exec chmod a+x {} \;
