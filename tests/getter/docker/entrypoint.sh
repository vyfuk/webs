#!/bin/bash

CONFIG=/var/www/html/app/config
LOCAL=$CONFIG/local

if [ ! -d $LOCAL ]; then
    mkdir $LOCAL
fi

# check neon configs existance and if not found, create it
WEBS="dsef fof fol fykos vyfuk"
for web in $WEBS; do
    if [ ! -f $LOCAL/$web.neon ]; then
        cp "$CONFIG/config.local.neon.example" "$LOCAL/$web.neon"
    fi
done

service apache2 start

composer install --no-progress --prefer-dist &> /dev/null
npm install --no-save vnu-jar &> /dev/null
npm run build &> /dev/null
rm -rf temp/DownloadedPages
echo -e "_________________________________________________________\nstarting test\n_________________________________________________________"
python3 tests/getter/getAll.py 2> >(tee log/HTMLlinter.log >&2)