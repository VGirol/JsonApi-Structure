#!/bin/bash

echo "Running phpcs..."
phpcs --encoding=utf-8 --report-full=./build/phpcs/phpcs.txt --report-xml=./build/phpcs/phpcs.xml
