#!/bin/bash

echo "Running phan..."
# ./build/scripts/php-no-xdebug.sh phan -m text -o ./build/phan/phan.txt
phan -m text -o ./build/phan/phan.txt
