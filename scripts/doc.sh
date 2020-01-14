#!/bin/bash

BASE_PATH="./scripts"
PHPCS_PATH="$BASE_PATH/phpcs.sh"
PHPMD_PATH="$BASE_PATH/phpmd.sh"
PHPUNIT_PATH="$BASE_PATH/phpunit.sh"
PHPLOC_PATH="$BASE_PATH/phploc.sh"
PHPDOX_PATH="$BASE_PATH/phpdox.sh"

. "$PHPCS_PATH"
. "$PHPMD_PATH"
. "$PHPLOC_PATH"
. "$PHPUNIT_PATH"
. "$PHPDOX_PATH"
