<?php
require_once __DIR__ . '/vendor/autoload.php';

$organizationFrom = $argv[1];
$organizationTo = $argv[2];

$client = new \Github\Client();
$client->authenticate(getenv('GITHUB_ACCESS_TOKEN'), Github\Client::AUTH_ACCESS_TOKEN);

$organizationApi = $client->api('organization');

$paginator  = new Github\ResultPager($client);
$results    = $paginator->fetchAll($organizationApi, 'repositories', [$organizationFrom]);

foreach ($results as $result) {
    $client->api('repo')->forks()->create($organizationFrom, $result['name'], ['organization' => $organizationTo]);
}
