vendor/bin/phpspec run
vendor/bin/codecept run --debug -vvv
vendor/bin/phpmd src/ text codesize,unusedcode,design
vendor/bin/phpcs src/ --standard="PSR2"

