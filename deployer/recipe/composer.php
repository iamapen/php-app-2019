<?php declare(strict_types=1);

namespace Deployer;

desc('install global plugin hirak/prestissimo');
task('composer:prestissimo', function () {
    run(
        '({{bin/composer}} global show | grep hirak/prestissimo) '
        . '|| {{bin/composer}} global require hirak/prestissimo'
    );
});
