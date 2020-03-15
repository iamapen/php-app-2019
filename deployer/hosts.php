<?php
/**
 * Servers
 */

namespace Deployer;

$prodDeployPath = '/home/pen/work/deployTest/app2019';

// 本番web1
host('xxx.xxx.xxx.xxx')->stage('production')->user('pen')
    ->forwardAgent(true)
    ->set('deploy_path', $prodDeployPath)
    ->set('branch', 'dev-batch')
    ->roles('production_web');

// 本番web2
host('xxx.xxx.xxx.xxx')->stage('production')->user('pen')
    ->forwardAgent(true)
    ->set('deploy_path', $prodDeployPath)
    ->set('branch', 'dev-batch')
    ->roles('production_web');
