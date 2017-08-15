# Magium Configuration Manager Adapter for abraham/twitteroauth

This library provides an interface for the abraham/twitteroauth so you can use it with the [Magium Configuration Manager](https://magiumlib.com/components/configuration).  Often applications will have some kind of static configuration mechanism, such as XML files, JSON files, YAML files, or PHP files.  There's nothing necessarily wrong with that, but what it does is merge your deployment and configuration concerns.  The Magium Configuration Manager (MCM) breaks that dependency so you can manage configuration separately from your deployment.

This library uses the MCM to provide a configured abraham/twitteroauth instance.

## Getting Started

```
composer require magium/mcm-twitter
```

## Configuring the MCM

### Initialization

If you have not done so already, initialize the MCM.

```
$ vendor/bin/magium-configuration

Could not find a magium-configuration.xml file.  Where would you like me to put it?
  [0] C:\Projects\magium-configuration.xml
  [1] C:\Projects\mcm-twitter\magium-configuration.xml
 > 1
Wrote XML configuration file to: C:\Projects\mcm-twitter\magium-configuration.xml
The context file C:\Projects\mcm-twitter\contexts.xml does not exist next to the magium-configuration.xml file.  Create it? y
{ 
```

Then, make sure that the remote configuration table is created

```
vendor/bin/magium-configuration  magium:configuration:create-table
```

Then configure the magium-configuration.xml file.  It will look something like this:

```
<?xml version="1.0" encoding="UTF-8" ?>
<magiumBase xmlns="http://www.magiumlib.com/BaseConfiguration">
    <persistenceConfiguration>
        <driver>pdo_sqlite</driver>
        <database>/tmp/twitter</database>
    </persistenceConfiguration>
    <contextConfigurationFile file="contexts.xml" type="xml"/>
    <cache>
        <adapter>filesystem</adapter>
        <options>
            <cache_dir>/tmp</cache_dir>
        </options>
    </cache>
</magiumBase>
```

## Setting the Configuration

First list the configuration keys.  These are the configuration endpoints that he library understands.

```
$ vendor/bin/magium-configuration magium:configuration:list-keys
Valid configuration keys
twitter/config/enabled (default: 1)
        (Set if the adapter is enabled.)

twitter/config/consumer_key
        (The consumer key from Twitter)

twitter/config/consumer_secret
        (The consumer secret from Twitter)

twitter/config/oauth_token
        (The Oauth token for the twitter account.  Only necessary if you are binding to a specific Twitter user)

twitter/config/oauth_token_secret
        (The Oauth token secret for the twitter account.  Only necessary if you are binding to a specific Twitter user)
```

Then create a custom Twitter application at [https://dev.twitter.com/](https://dev.twitter.com/).  You will need the consumer key and consumer secret at a minimum.  If you intend to interact with your account and not use it for authentication you can generate your access token and token secret from the application page.

Now you need to configure the MCM.  (`magium-configuration` is in vendor/bin/ or `vendor/magium/configuration-manager/bin`)

```
$ magium-configuration magium:configuration:set twitter/config/consumer_key xxxxxxxxxxxxxxxx  
Set twitter/config/consumer_key to xxxxxxxxxxxxxxxx (context: default)
Don't forget to rebuild your configuration cache with magium:configuration:build

$ magium-configuration magium:configuration:set twitter/config/consumer_secret xxxxxxxxxxxxxxxx
Set twitter/config/consumer_secret to xxxxxxxxxxxxxxxx (context: default)
Don't forget to rebuild your configuration cache with magium:configuration:build

$ magium-configuration magium:configuration:set twitter/config/oauth_token xxxxxxxxxxxxxxxx
Set twitter/config/oauth_token to xxxxxxxxxxxxxxxx (context: default)
Don't forget to rebuild your configuration cache with magium:configuration:build

$ magium-configuration magium:configuration:set twitter/config/oauth_token_secret xxxxxxxxxxxxxxxx
Set twitter/config/oauth_token_secret to xxxxxxxxxxxxxxxx (context: default)
Don't forget to rebuild your configuration cache with magium:configuration:build
```

Then you need to build the configuration to compile it and push it to the storage location.

```
$ magium-configuration magium:configuration:build                                                     

Building context: default
Building context: production
```

## Usage

Using the MCM requires the use of the Magium Configuration Manager Factory which will receive the correct configuration object which you then pass to the Twitter Factory.  That's a lot of words to explain this:

```
require_once __DIR__ . '/../vendor/autoload.php';

$magiumFactory = new \Magium\Configuration\MagiumConfigurationFactory();
$twitterFactory = new \Magium\ConfigurationManager\Twitter\TwitterFactory($magiumFactory->getConfiguration());

$twitter = $twitterFactory->factory();
$timeline = $twitter->get(
    \Magium\ConfigurationManager\Twitter\Constants::GET_STATUSES_USER_TIMELINE
);

foreach ($timeline as $tweet) {
    echo $tweet->text . "\n";
}

```

If you want to post an update:

```
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
```
