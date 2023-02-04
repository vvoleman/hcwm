php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
php bin/console app:zotero:load --no-interaction

/usr/sbin/apache2ctl -D FOREGROUND
