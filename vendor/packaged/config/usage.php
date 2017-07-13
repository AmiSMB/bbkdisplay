<?php
/**
 * @author Brooke Bryan @bajbnet
 */

include_once('vendor/autoload.php');

echo "\n";
$configProvider = new \Packaged\Config\Provider\Test\TestConfigProvider();

$configProvider->addItem("database", "hostname", "tester.local");
$configProvider->addItem("database", "username", "root");

//Retrieve the section and then pull the item specifically
// This method is great if you want to pass the whole section into an object to
// configure it
$section  = $configProvider->getSection("database");
$hostname = $section->getItem("hostname", "localhost");
echo "Located '$hostname' as the hostname from a section item get\n";

//Retrieve a single config item directly from the provider
// This method is useful for one off retrievals of an item
$username = $configProvider->getItem("database", "username", "brooke");
echo "Located '$username' as the username from a single item get\n";