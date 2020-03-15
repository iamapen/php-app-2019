<?php
/**
 * deployer設定
 *
 * phpとsshのみで完結するのがメリット。
 * CodeBuild, CodeDeploy, Beanstalk も検討した方がいい
 */

namespace Deployer;

require_once 'recipe/common.php';
require_once __DIR__ . '/recipe/composer.php';
require_once __DIR__ . '/recipe/deploy/shared.php';

// Project name
set('application', 'app2019');

// Project repository
set('repository', 'https://github.com/iamapen/php-app-2019');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys
set('shared_files', ['.env']);
set('shared_dirs', ['storage']);

// Writable dirs by web server
set('writable_dirs', []);
set('writable_use_sudo', false);
set('allow_anonymous_stats', false);

set('keep_releases', 5);
set('ssh_multiplexing', true);

// Hosts
require_once __DIR__ . '/hosts.php';

// Tasks

desc('Deploy your project');
task('deploy', function () {
    invoke('deploy:info');
    invoke('deploy:prepare');   // {{deploy_path}}作成
    invoke('deploy:lock');      // {{deploy_path}}/.dep/deploy.lock
    invoke('deploy:release');
    invoke('deploy:update_code');
    invoke('deploy:shared');
    invoke('deploy:writable');
    invoke('composer:prestissimo');
    invoke('deploy:vendors');
    invoke('deploy:clear_paths');
    invoke('deploy:symlink');
    invoke('deploy:unlock');
    invoke('cleanup');
    invoke('success');
});

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
