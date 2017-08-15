<?php

require_once __DIR__ . '/../vendor/autoload.php';

$magiumFactory = new \Magium\Configuration\MagiumConfigurationFactory();
$twitterFactory = new \Magium\ConfigurationManager\Twitter\TwitterFactory($magiumFactory->getConfiguration());

$twitter = $twitterFactory->factory();
$timeline = $twitter->get(\Magium\ConfigurationManager\Twitter\Constants::GET_STATUSES_USER_TIMELINE);
foreach ($timeline as $tweet) {
    echo $tweet->text . "\n";
}
