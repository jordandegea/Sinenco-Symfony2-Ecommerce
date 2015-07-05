Sinenco development repository
========================

That is the personnal repository of Sinenco of Jordan DE GEA

Important : 
----
There is an important parameters in app/parameters.yml named web_dir. 
When you install Sinenco, edit this parameter. 

First Installation. 
ln ...php.ini /etc/php.ini
php app/console doctrine:database:create
php app/console doctrine:schema:create

Application to be installed : 
http://wkhtmltopdf.org/downloads.html 
for html2Pdf conversion
apt-get install wkhtmltopdf
apt-get install xvfb
echo 'xvfb-run --server-args="-screen 0, 1024x768x24" /usr/bin/wkhtmltopdf $*' > /usr/bin/wkhtmltopdf.sh
chmod a+rx /usr/bin/wkhtmltopdf.sh
ln -s /usr/bin/wkhtmltopdf.sh /usr/local/bin/wkhtmltopdf

Cron Requirements :
---- 
Send mail
php app/console swiftmailer:spool:send --time-limit=10 --env=prod
Mail Reminder for service : 
php app/console services:reminder --send



Usefull command :
---- 
php app/console assets:install web/
php app/console assetic:dump --env=prod

Creer une tache cron pour envoyer les mails : 

php app/console swiftmailer:spool:send
c'est la commande de base, il y a des options

Creer une licence avec ionCube dans un projet
make_ioncube_license --passphrase <pass> --header-line '<?php exit(0); ?>' --property "UserName='Chuck Norris'" --allowed-server dev.sinenco.com