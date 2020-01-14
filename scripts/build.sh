#!/bin/bash

BASE_PATH="./scripts"
LINT_PATH="$BASE_PATH/lint.sh"
METRICS_PATH="$BASE_PATH/metrics.sh"
PHPDOX_PATH="$BASE_PATH/phpdox.sh"

. "$LINT_PATH" "$@"
. "$METRICS_PATH"
. "$PHPDOX_PATH"
