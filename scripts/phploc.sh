#!/bin/bash

echo "Running phploc..."
phploc --log-xml="./build/phploc/phploc.xml" -vvv ./src
