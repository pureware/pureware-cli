#!/usr/bin/php

cd /var/www/html || exit;

/usr/bin/php bin/console plugin:refresh;

/usr/bin/php bin/console plugin:install -a -c {{pluginName}};
