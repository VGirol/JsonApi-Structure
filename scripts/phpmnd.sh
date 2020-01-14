#!/bin/bash

echo "Running phpmnd..."
phpmnd ./src/ --extensions=all --strings --include-numeric-string --allow-array-mapping --xml-output=./build/phpmnd/phpmnd.xml --progress | tee ./build/phpmnd/phpmnd.txt
