<?php

$instance = \Magium\Configuration\File\Configuration\ConfigurationFileRepository::getInstance();
$instance->addSecureBase(__DIR__ . '/settings');
$instance->registerConfigurationFile(
    new \Magium\Configuration\File\Configuration\XmlFile(__DIR__ . '/settings/settings.xml')
);
