# PHP-Rest-API-with-routes-and-controllers

.htaccess file in root folder<br>
-----------------------------------<br>
RewriteEngine On<br>
RewriteRule ^(.*)$ index.php?url=$1 [L,QSA]<br>
