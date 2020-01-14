#!/bin/bash

echo "Running phpmetrics..."
phpmetrics --report-html=./build/phpmetrics --junit=./build/coverage/phpunit.junit.xml ./src,./tests
