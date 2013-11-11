<VirtualHost *:80>
  ServerAdmin webmaster@wondernetwork.com
  DocumentRoot /var/www/aphpidoc/webroot/
  ServerName jsonapi.weballison.com

  <Directory /var/www/aphpidoc/>
    AllowOverride all
    Order Allow,Deny
    Allow from all
  </Directory>

  php_value display_errors "E_ALL & ~E_NOTICE"

  AddDefaultCharset utf-8
</VirtualHost>
