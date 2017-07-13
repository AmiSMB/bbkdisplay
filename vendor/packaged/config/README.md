Configuration Package
======

[![Latest Stable Version](https://poser.pugx.org/packaged/config/version.png)](https://packagist.org/packages/packaged/config)
[![Total Downloads](https://poser.pugx.org/packaged/config/d/total.png)](https://packagist.org/packages/packaged/config)
[![Build Status](https://travis-ci.org/packaged/config.png)](https://travis-ci.org/packaged/config)
[![Dependency Status](https://www.versioneye.com/php/packaged:config/badge.png)](https://www.versioneye.com/php/packaged:config)
[![HHVM Status](http://hhvm.h4cc.de/badge/packaged/config.png)](http://hhvm.h4cc.de/package/packaged/config)
[![Coverage Status](https://coveralls.io/repos/packaged/config/badge.png)](https://coveralls.io/r/packaged/config)

General Usage


    $configProvider = new \Packaged\Config\Provider\Test\TestConfigProvider();

    $configProvider->addItem("database", "hostname", "tester.local");
    $configProvider->addItem("database", "username", "root");

    // Retrieve the section and then pull the item specifically
    // This method is great if you want to pass the whole section
    // into an object to configure it
    $section  = $configProvider->getSection("database");
    $hostname = $section->getItem("hostname", "localhost");
    echo "Located '$hostname' as the hostname from a section item get\n";

    //Retrieve a single config item directly from the provider
    // This method is useful for one off retrievals of an item
    $username = $configProvider->getItem("database", "username", "brooke");
    echo "Located '$username' as the username from a single item get\n";
