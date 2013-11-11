<VirtualHost *:80>
  ServerAdmin webmaster@wondernetwork.com
  DocumentRoot /var/www/jsonapi/webroot/
  ServerName jsonapi.webpaul.com

  <Directory /var/www/jsonapi/>
    AllowOverride all
    Order Allow,Deny
    Allow from all
  </Directory>

  php_value display_errors "E_ALL & ~E_NOTICE"

  <Location />
    SetOutputFilter DEFLATE
    BrowserMatch ^Mozilla/4 gzip-only-text/html
    BrowserMatch ^Mozilla/4\.0[678] no-gzip
    BrowserMatch \bMSI[E] !no-gzip !gzip-only-text/html
    SetEnvIfNoCase Request_URI .(?:gif|jpe?g|png)$ no-gzip dont-vary
  </Location>
        
  ExpiresActive On
  ExpiresByType image/png "access plus 1 month"
  ExpiresByType text/css "access plus 1 month"
  ExpiresByType image/jpeg "acces plus 1 month"
  ExpiresByType application/javascript "access plus 1 month"
  ExpiresByType image/x-icon "access plus 1 month"
  AddDefaultCharset utf-8
</VirtualHost>
