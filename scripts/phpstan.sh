#!/bin/bash

echo "Running phpstan..."
phpstan analyse ./src --level=7 | tee ./build/phpstan/phpstan.txt
