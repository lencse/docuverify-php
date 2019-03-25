.PHONY: test coverage min-coverage clean cs cs-fix dev coveralls
.PHONY: mnd psalm phpmetrics infection verify require phpmd

vendor: composer.json composer.lock
	composer install

verify: coverage min-coverage cs phpstan phpmd psalm require mnd phpmetrics

dev: cs-fix verify

infection: vendor
	vendor/bin/infection --threads=8

coveralls: logs/phpunit
	mkdir -p build/logs && \
	cp logs/phpunit/clover.xml build/logs/ && \
	vendor/bin/php-coveralls -v

clean:
	rm -rf logs vendor

test: vendor
	vendor/bin/phpunit

coverage: vendor
	vendor/bin/phpunit \
		--coverage-html logs/phpunit/coverage-report \
		--coverage-clover logs/phpunit/clover.xml \
		--log-junit logs/phpunit/test-report.xml

min-coverage: vendor coverage
	vendor/bin/min-coverage --min-coverage 5 --clover-file logs/phpunit/clover.xml

cs: vendor
	vendor/bin/phpcs --standard=Omar -s src test

cs-fix: vendor
	vendor/bin/phpcbf --standard=Omar --report=diff src test || :

phpstan: vendor
	vendor/bin/phpstan analyse --level 7 src

mnd: vendor
	vendor/bin/phpmnd --non-zero-exit-on-violation src

psalm: vendor
	vendor/bin/psalm

phpmetrics: vendor logs/phpunit
	vendor/bin/phpmetrics \
		--report-html=logs/phpmetrics \
		--junit=logs/phpunit/test-report.xml \
		.

require: vendor
	vendor/bin/composer-require-checker check ./composer.json

phpmd: vendor
	vendor/bin/phpmd src/ text phpmd.xml
