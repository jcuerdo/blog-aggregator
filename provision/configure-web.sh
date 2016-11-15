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

echo "Web configured!"
