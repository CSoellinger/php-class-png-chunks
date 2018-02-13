install: vendor

vendor: composer.phar
	@php ./composer.phar install

composer.phar:
	@curl -sS https://getcomposer.org/installer | php

phpunit.phar:
	@wget -c https://phar.phpunit.de/phpunit-5.phar && chmod +x phpunit-5.phar

phpmd.phar:
	@wget -c http://static.phpmd.org/php/latest/phpmd.phar && chmod +x phpmd.phar

lint: phpmd.phar
	./phpmd.phar src text cleancode,codesize,controversial,design,naming,unusedcode
	@vendor/bin/phpcs --standard=PSR2 ./src ./tests &> /dev/null

lint.fix:
	@vendor/bin/phpcbf --standard=PSR2 ./src ./tests

test: install phpunit.phar lint
	./phpunit-5.phar
	@php ./composer.phar validate

release:
	@printf "releasing ${VERSION}..."
	@node -e "var fs = require('fs'), pkg = require('./composer'); pkg.version = '${VERSION}'; fs.writeFileSync('./composer.json', JSON.stringify(pkg, null, '\t'));"
	@git changelog -t ${VERSION}
	@git release ${VERSION}

clean:
	rm -rf \
		composer.phar \
		phpmd.phar \
		phpunit-5.phar \
		vendor \
		composer.lock

.PHONY: test release
