#!/bin/sh
set -e

chmod 777 /var/www/html/app/var

/root/.symfony5/bin/symfony server:start --allow-all-ip
