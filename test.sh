vendor/bin/phpspec run
vendor/bin/codecept run --debug
vendor/bin/phpmd src/ text codesize,unusedcode,design
vendor/bin/phpcs src/ --standard="PSR2"

