<?php

require_once __DIR__ . '/../vendor/autoload.php';

$opts = getopt('', [
    'tweet::'
]);
if (!isset($opts['tweet']) || !$opts['tweet']) {
    echo "Please provide a tweet with --tweet\n";
    exit;
}

$magiumFactory = new \Magium\Configuration\MagiumConfigurationFactory();
$twitterFactory = new \Magium\ConfigurationManager\Twitter\TwitterFactory($magiumFactory->getConfiguration());

$twitter = $twitterFactory->factory();
$result = $twitter->post(
    \Magium\ConfigurationManager\Twitter\Constants::POST_STATUSES_UPDATE, ['status' => $opts['tweet']]
);

var_dump($result);
