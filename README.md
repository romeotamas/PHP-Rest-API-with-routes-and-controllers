# PHP-Rest-API-with-routes-and-controllers

.htaccess file in root folder
++++++++++++++++++++++++++++++++++++++++++++
RewriteEngine On
RewriteRule ^(.*)$ index.php?url=$1 [L,QSA]
