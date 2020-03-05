# Autopilot

![Test](https://github.com/sitepilot/autopilot/workflows/Test/badge.svg?branch=master)

A tool for provisioning and maintaining WordPress sites and servers using Ansible and Laravel.

## Requirements

Ubuntu 18.04 is the only supported operating system (for the master and slave hosts). The CLI uses Ansible to provision servers, users and vhosts. To use the CLI you need to install the following packages on the master host:

* Coming soon...

The master host requires root access to the slave hosts.

## Installation

* Coming soon...

## Commands

* `./php artisan sp:server:inventory`: Outputs the server inventory.
* `./php artisan sp:server:provision`: Provision a server.

## Installed Software

The following packages/services will be installed and configured on the server (together with dependencies):

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
* Docker MySQL 8
* phpMyAdmin 5

Users are isolated and allowed to use SFTP with password authentication (chroot directory `/opt/sitepilot/users/%u`).
  
## Filesystem

* Users folder: `/opt/sitepilot/users`.
* App document root folder: `/opt/sitepilot/users/{{ user.name }}/{{ app.name }}/live`.
* App logs folder: `/opt/sitepilot/users/{{ user.name }}/{{ app.name }}/logs`.
* OpenLitespeed logs folder: `/opt/sitepilot/services/olsws/logs`.
* OpenLitespeed temp folder: `/opt/sitepilot/services/olsws/tmp`.
* Docker MySQL data folder: `/opt/sitepilot/services/mysql/data`.
* Docker MySQL logs folder: `/opt/sitepilot/services/mysql/logs`.
* Docker Redis data folder: `/opt/sitepilot/services/redis/data`.

## Tools

* phpMyAdmin: `http://example.com/.sitepilot/pma/`.
* Health check: `http://example.com/.sitepilot/health/`.

## License

MIT / BSD

## Author Information

This role was created in 2020 by [Nick Jansen](https://nbejansen.com/).
