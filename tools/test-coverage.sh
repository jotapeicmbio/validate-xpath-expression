#!/bin/sh
set -e

if php -m | grep -qi '^pcov$'; then
  php -d pcov.enabled=1 vendor/bin/phpunit --coverage-clover coverage/clover.xml --coverage-text
  exit 0
fi

if php -m | grep -qi '^xdebug$'; then
  XDEBUG_MODE=coverage php vendor/bin/phpunit --coverage-clover coverage/clover.xml --coverage-text
  exit 0
fi

echo "No coverage driver available. Install pcov or xdebug to run local coverage."
exit 1
