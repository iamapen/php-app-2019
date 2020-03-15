<?php
/**
 * デフォルトの deploy:sharedタスクは 0バイトのファイルを自動的に作るが、
 * これは事故につながるため、ファイルがないものはリンク切れのリンクを作るよう変更したもの
 * @uses deployer-6.7.3
 */

namespace Deployer;

use Deployer\Exception\Exception;

desc('Creating symlinks for shared files and dirs');
task('deploy:shared', function () {
    $sharedPath = "{{deploy_path}}/shared";

    // Validate shared_dir, find duplicates
    foreach (get('shared_dirs') as $a) {
        foreach (get('shared_dirs') as $b) {
            if ($a !== $b && strpos(rtrim($a, '/') . '/', rtrim($b, '/') . '/') === 0) {
                throw new Exception("Can not share same dirs `$a` and `$b`.");
            }
        }
    }

    foreach (get('shared_dirs') as $dir) {
        // Check if shared dir does not exist.
        if (!test("[ -d $sharedPath/$dir ]")) {
            if (!test("[ -d $sharedPath/" . dirname(parse($dir)) . " ]")) {
                // Create shared parent dir if it does not exist.
                run("mkdir -p $sharedPath/" . dirname(parse($dir)));
            }

            // If release contains shared dir, copy that dir from release to shared.
            if (test("[ -d $(echo {{release_path}}/$dir) ]")) {
                run("mv {{release_path}}/$dir $sharedPath/$dir");
            }
        }

        // Remove from source.
        run("rm -rf {{release_path}}/$dir");

        // Symlink shared dir to release dir
        run("{{bin/symlink}} $sharedPath/$dir {{release_path}}/$dir");
    }

    foreach (get('shared_files') as $file) {
        $dirname = dirname(parse($file));

        // Create dir of shared file
        run("mkdir -p $sharedPath/" . $dirname);

        // Remove from source.
        run("if [ -f $(echo {{release_path}}/$file) ]; then rm -rf {{release_path}}/$file; fi");

        // Ensure dir is available in release
        run("if [ ! -d $(echo {{release_path}}/$dirname) ]; then mkdir -p {{release_path}}/$dirname;fi");

        // Symlink shared dir to release dir
        run("{{bin/symlink}} $sharedPath/$file {{release_path}}/$file");
    }
});
