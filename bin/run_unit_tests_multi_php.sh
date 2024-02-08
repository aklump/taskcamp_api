#!/usr/bin/env bash
s="${BASH_SOURCE[0]}";[[ "$s" ]] || s="${(%):-%N}";while [ -h "$s" ];do d="$(cd -P "$(dirname "$s")" && pwd)";s="$(readlink "$s")";[[ $s != /* ]] && s="$d/$s";done;__DIR__=$(cd -P "$(dirname "$s")" && pwd)

cd "$__DIR__/.."

error=false
message="                                                                        "
if ! [ -e ./vendor/bin/phpswap ]; then
  error=true
  message="$message\n     You seem to be missing this: https://github.com/aklump/phpswap     "
  message="$message\n     Try running: composer require --dev aklump/phpswap                 "
fi
if ! [ -e ./vendor/bin/phpunit ]; then
  error=true
  message="$message\n     You seem to be missing this: PHPUnit                               "
  message="$message\n     Try running: composer require --dev phpunit/phpunit                "
fi
if [[ "$error" == true ]]; then
    message="$message\n                                                                        "
    NO_FORMAT="\033[0m"
    F_BOLD="\033[1m"
    C_YELLOW="\033[48;5;226m"
    echo -e "${F_BOLD}${C_YELLOW}$message${NO_FORMAT}"
    exit 1
fi

verbose=''
if [[ "${*}" == *'-v'* ]]; then
  verbose='-v'
fi
./vendor/bin/phpswap use 7.3 $verbose './vendor/bin/phpunit -c ./tests_unit'
./vendor/bin/phpswap use 7.4 $verbose './vendor/bin/phpunit -c ./tests_unit'
./vendor/bin/phpswap use 8.0 $verbose './vendor/bin/phpunit -c ./tests_unit'
./vendor/bin/phpswap use 8.1 $verbose './vendor/bin/phpunit -c ./tests_unit'
./vendor/bin/phpswap use 8.2 $verbose './vendor/bin/phpunit -c ./tests_unit'
