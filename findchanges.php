<?php
require_once __DIR__ . '/vendor/autoload.php';

$organizationChild = $argv[1];
$organizationParent = $argv[2];

$client = new \Github\Client();
$client->authenticate(getenv('GITHUB_ACCESS_TOKEN'), Github\Client::AUTH_ACCESS_TOKEN);

$organizationApi = $client->api('organization');

$paginator  = new Github\ResultPager($client);
$results    = $paginator->fetchAll($organizationApi, 'repositories', [$organizationChild]);

foreach ($results as $result) {
    $info = $client->api('repo')->showById($result['id']);
    if ($info['parent']['owner']['login'] == $organizationParent) {
        if ($info['pushed_at'] != $info['parent']['pushed_at']) {
            echo($result['name']."\n");
        }
    }
}
