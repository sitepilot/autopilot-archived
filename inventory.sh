#!/bin/bash
BASEDIR=$(dirname "$0")
/usr/local/bin/php $BASEDIR/artisan server:inventory $@