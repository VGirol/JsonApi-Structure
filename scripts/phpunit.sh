#!/bin/bash

echo "Running phpunit..."
# phpunit ./tests --coverage-html=./build/coverage/html --coverage-xml=build/coverage/xml --log-junit=build/coverage/phpunit.junit.xml
phpunit ./tests
