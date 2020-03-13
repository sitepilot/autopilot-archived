# Autopilot

![Test](https://github.com/sitepilot/autopilot/workflows/Test/badge.svg?branch=master)

A tool for provisioning and maintaining WordPress sites and servers using Ansible and Laravel.

![screenshot](screenshot.png)

## Requirements

Ubuntu 18.04 is the only supported operating system (for the master and slave hosts). Autopilot uses Ansible to update WordPress sites and provision servers, users and vhosts. To use Autopilot you need:

* A valid [Laravel Nova](https://nova.laravel.com/) license.
* [Docker](https://www.docker.com/) and [Docker Compose](https://docs.docker.com/compose/install/) installed on the master node.

*NOTE: The master host requires root access to the slave hosts.*

## Installation

* Create a directory: `mkdir ~/autopilot && cd ~/autopilot`.
* Download Autopilot script: `curl -o ./autopilot https://raw.githubusercontent.com/sitepilot/autopilot/master/autopilot && chmod +x ./autopilot`.
* Download environment file and modify it to your needs: `curl -o ./.env https://raw.githubusercontent.com/sitepilot/autopilot/master/.env.example && nano ./.env`.
* Run `./autopilot install` to start the containers, install packages and migrate the database. *NOTE: This will prompt for your Laravel Nova username and password.*
* Navigate to `https://<SERVER IP>:<APP_HTTPS_PORT>` and login (default user: `admin@sitepilot.io`, default pass: `supersecret`).

## Update

* Navigate to the Autopilot installation folder: `cd ~/autopilot`.
* Update Autopilot script: `curl -o ./autopilot https://raw.githubusercontent.com/sitepilot/autopilot/master/autopilot && chmod +x ./autopilot`.
* Run `./autopilot update` to update the containers, packages and migrate the database.

## Commands

* `./autopilot server:inventory`: Outputs the inventory in JSON format.
* `./autopilot server:provision`: Provision a server.
* `./autopilot server:test`: Test a server.

## Server Configuration

### Packages & Services

The following packages/services will be installed and configured on servers (together with dependencies):

* OpenLitespeed
* LSPHP 7.4
* Composer
* WPCLI
* UFW
* Fail2Ban
* OpenSSH (SFTP)
* Docker
* Docker Compose
* Docker Redis 5
* Docker MariaDB 10.4
* phpMyAdmin 5

Users are isolated and allowed to use SFTP with password authentication (chroot directory `/opt/sitepilot/users/%u`).

### Tools

* phpMyAdmin: `http://example.com/.sitepilot/pma/`.
* Health check: `http://example.com/.sitepilot/health/`.

### Filesystem

* Users folder: `/opt/sitepilot/users`.
* App document root folder: `/opt/sitepilot/users/{{ user.name }}/{{ app.name }}/live`.
* App logs folder: `/opt/sitepilot/users/{{ user.name }}/{{ app.name }}/logs`.
* OpenLitespeed logs folder: `/opt/sitepilot/services/olsws/logs`.
* OpenLitespeed temp folder: `/opt/sitepilot/services/olsws/tmp`.
* Docker MySQL data folder: `/opt/sitepilot/services/mysql/data`.
* Docker MySQL logs folder: `/opt/sitepilot/services/mysql/logs`.
* Docker Redis data folder: `/opt/sitepilot/services/redis/data`.

## Development

* Clone this repository.
* Copy environment file and modify it to your needs: `cp .env.example .env`.
* Start the containers, install packages and migrate the database: `./autopilot install-dev`. The Autopilot source files are mounted to the the container. *NOTE: This will prompt for your Laravel Nova username and password.*
* Optional for testing Ansible roles and playbooks: install [Vagrant](https://www.vagrantup.com/) and run `vagrant up` to start a clean Ubuntu 18.04 virtual machine (ip: `192.168.25.100`, ssh port: `<localhost>:7685`). The Autopilot Docker container has access to this VM using a test private key file located at `/var/www/html/vagrant/ssh/test_key`.
* Navigate to `https://<SERVER IP>:<APP_HTTPS_PORT>` and login (default user: `admin@sitepilot.io`, default pass: `supersecret`).

## License

MIT / BSD

## Author

Autopilot was created in 2020 by [Nick Jansen](https://nbejansen.com/).
