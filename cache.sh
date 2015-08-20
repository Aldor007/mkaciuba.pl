sudo setfacl -R -m u:www-data:rwX -m u:`whoami`:rwx app/cache app/logs
sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
sudo setfacl -R -m u:www-data:rwX -m u:www-data:rwx app/cache app/logs
sudo setfacl -dR -m u:www-data:rwx -m u:www-data:rwx app/cache app/logs
sudo -u www-data php app/console cache:clear --env=prod
sudo -u www-data php app/console cache:warmup --env=prod

sudo setfacl -R -m u:www-data:rwX -m u:`whoami`:rwx app/cache app/logs
sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
sudo setfacl -R -m u:www-data:rwX -m u:www-data:rwx app/cache app/logs
sudo setfacl -dR -m u:www-data:rwx -m u:www-data:rwx app/cache app/logs
sudo chown -R www-data:www-data app/cache
