#!/bin/sh

cd /var/www/webs
echo ${FORCE_REINSTALL}
if [ ! -f /var/www/webs/vendor/autoload.php ] || [ "${FORCE_REINSTALL}" = true ]; then
    echo "Installing dependencies . . ."
    composer install  >> /var/www/webs/log/setup.log
    npm install --include=dev  >> /var/www/webs/log/setup.log

    if [ "${FORCE_REINSTALL}" != true ]; then
        echo "Copying config . . ."
        cp app/config/config.local.neon.example app/config/config.fof.local.neon
        cp app/config/config.local.neon.example app/config/config.fol.local.neon
        cp app/config/config.local.neon.example app/config/config.dsef.local.neon
        cp app/config/config.local.neon.example app/config/config.fykos.local.neon
        cp app/config/config.local.neon.example app/config/config.vyfuk.local.neon
    fi
fi
service apache2 restart
npm run dev