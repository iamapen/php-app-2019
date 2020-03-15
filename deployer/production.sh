#!/bin/bash
BASE_DIR=$(cd $(dirname $0);pwd)

DEPLOYER=./deployer.phar

cd "$BASE_DIR"
echo "$DEPLOYER" deploy --parallel production "$@"
"$DEPLOYER" deploy --parallel production "$@"
