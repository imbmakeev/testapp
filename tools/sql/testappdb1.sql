create database testapp;
CREATE USER 'testapp'@'%' identified by '1qazxsw2';
CREATE USER 'testapp'@'localhost' identified by '1qazxsw2';
grant all privileges on testapp.* to 'testapp'@'%';
grant all privileges on testapp.* to 'testapp'@'localhost';

