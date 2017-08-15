<?php

namespace Magium\ConfigurationManager\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use Magium\Configuration\Config\Repository\ConfigInterface;

class TwitterFactory
{

    const ENABLED = 'twitter/config/enabled';
    const CONSUMER_KEY = 'twitter/config/consumer_key';
    const CONSUMER_SECRET = 'twitter/config/consumer_secret';
    const OAUTH_TOKEN = 'twitter/config/oauth_token';
    const OAUTH_TOKEN_SECRET = 'twitter/config/oauth_token_secret';

    private $config;

    private static $self;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        self::$self = $this;
    }

    public function isEnabled()
    {
        return $this->config->getValueFlag(self::ENABLED);
    }

    public function factory()
    {
        if (!$this->isEnabled()) {
            throw new TwitterDisabledException('Twitter client has been disabled');
        }
        return new TwitterOAuth(
            $this->config->getValue(self::CONSUMER_KEY),
            $this->config->getValue(self::CONSUMER_SECRET),
            $this->config->getValue(self::OAUTH_TOKEN),
            $this->config->getValue(self::OAUTH_TOKEN_SECRET)
        );
    }

    public static function factoryStatic(ConfigInterface $config)
    {
        if (!self::$self instanceof self) {
            self::$self = new self($config);
        }
        return self::$self->factory();
    }

}
