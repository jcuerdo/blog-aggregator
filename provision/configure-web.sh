#!/usr/bin/env bash

echo "Configuring Web folders..."

ln -s /vagrant/web /var/www/public

echo "Creating Database..."
mysql -u root -proot -e "create database blogaggregator";
apt-get update

echo "Installing PHP extensions..."
apt-get install php5-imap -y
php5enmod imap
service apache2 restart
cd /vagrant
echo "Installing dependencies"
composer install

echo "Create or update database and fixtures"
sudo chmod 777 app/console
app/console doctrine:schema:create
app/console doctrine:schema:update
app/console doctrine:fixtures:load
app/console cache:clear

echo "Web configured!"
