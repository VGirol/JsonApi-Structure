#!/bin/bash

echo "Running infection ..."
infection --coverage=./build/coverage --threads=$(nproc) --configuration=./infection.json
