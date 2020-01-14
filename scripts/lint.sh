#!/bin/bash

BASE_PATH="./scripts"
PHPCS_PATH="$BASE_PATH/phpcs.sh"
PHPMD_PATH="$BASE_PATH/phpmd.sh"
PHPCPD_PATH="$BASE_PATH/phpcpd.sh"
PHPMND_PATH="$BASE_PATH/phpmnd.sh"
PHPSTAN_PATH="$BASE_PATH/phpstan.sh"
PHAN_PATH="$BASE_PATH/phan.sh"

with_phan=1

while [ "$1" != "" ]; do
    case $1 in
        -no-phan )      with_phan=0
                        ;;
        -h | --help )   usage
                        exit
                        ;;
        * )             usage
                        exit 1
    esac
    shift
done

. "$PHPCS_PATH"
. "$PHPMD_PATH"
. "$PHPCPD_PATH"
. "$PHPMND_PATH"
. "$PHPSTAN_PATH"
if [ "$with_phan" = "1" ]; then
	. "$PHAN_PATH"
fi
