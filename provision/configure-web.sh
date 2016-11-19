#!/usr/bin/env bash

echo "Configuring Web folders..."

ln -s /vagrant/web /var/www/public

echo "Creating Database..."
mysql -u root -proot -e "create database blogaggregator";
echo "Migrate database..."
mysql -u root -proot blogaggregator < /vagrant/migration.sql

echo "Updating Server"
sudo apt-get update

echo "Installing PHP extensions..."
apt-get install php5-imap -y
php5enmod imap
service apache2 restart

echo "Installing dependencies"
cd /vagrant
composer install

echo "Web configured!"
