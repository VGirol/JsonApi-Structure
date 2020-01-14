#!/bin/bash

echo "Running phpmd..."
phpmd ./src text ./phpmd-src.xml --reportfile ./build/phpmd/phpmd_src.txt --ignore-violations-on-exit
phpmd ./src xml ./phpmd-src.xml --reportfile ./build/phpmd/phpmd_src.xml --ignore-violations-on-exit
phpmd ./tests text ./phpmd-tests.xml --reportfile ./build/phpmd/phpmd_tests.txt --ignore-violations-on-exit
