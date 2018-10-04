# DomainAPI PHP Client #

## Presentation ##

The domainAPI PHP Wrapper is a simple connector to access all webservices of [domainapi.com](http://domainapi.com "domainapi.com").

## Getting started ##

1- Clone the project with Git by running:

    $ git clone git://github.com/EuroDNS/domainapi_phpclient

    Or download the project in either [zip](https://github.com/domainapi/domainAPI_php_wrapper/zipball/master "Download in zip format") or [tar](https://github.com/domainapi/domainAPI_php_wrapper/tarball/master "Download in tar format") formats.

2- Change the **username** and **password** values in the configuration file **DomainAPIConfiguration_class.php** with your domainAPI credentials:

    $this->username					= 'yourusername';
	$this->password					= 'yourpassword';

3-A Create a short PHP file which requires the project domainAPI PHP wrapper and makes a simple call to a webservice (**whois** for example):

    <?php
      // Require domainAPI PHP wrapper
      require 'domainapi_phpclient/DomainAPI_class.inc.php';

      //Make a call to the webservice whois with a xml return
      //type for the domain name : example.com
      $response = DomainAPI::from("whois")-> //name of the service (whois, info, availability)
                             withType("xml")-> // Return type (JSON or XML)
                             get("example.com"); //Domain name

    //Display the response
      echo $response;
    ?>

3-B If required yoy can also override on the fly your configuration :

    <?php
	// Require domainAPI PHP wrapper
      require 'domainapi_phpclient/DomainAPI_class.inc.php';

    // we get the default configuration
	$configuration = new DomainAPIConfiguration();
	// we change some values
	$configuration->set('username','anotherUsername')->
					set('password','anotherPassword');
	//Make a call to the webservice whois with a xml return
	//type for the domain name : example.com
	$response = DomainAPI::from("whois",$configuration)->
							withType("json")->
							get("example.com");
	//Display the response
	echo $response;
	?>

4- Execute the PHP script:

    $ php testDomainAPI.php

    If everything works fine, you should have a display like this:

    <?xml version="1.0" encoding="UTF-8"?>
    <Response>
      <service>whois</service>
      <domain>example.com</domain>
      <timestamp>1296054220</timestamp>
      <content>
        <whois>&lt;![CDATA[
    Whois Server Version 2.0

    Domain names in the .com and .net domains can now be registered
    with many different competing registrars. Go to http://www.internic.net
    for detailed information.

    EXAMPLE.COM.AU
    EXAMPLE.COM

    To single out one record, look it up with "xxx", where xxx is one of the
    of the records displayed above. If the records are the same, look them up
    with "=xxx" to receive a full display for each record.

    &gt;&gt;&gt; Last update of whois database: Tue, 25 Jan 2011 14:37:03 UTC &lt;&lt;&lt;

    NOTICE: The expiration date displayed in this record is the date the
    registrar's sponsorship of the domain name registration in the registry is
    currently set to expire. This date does not necessarily reflect the expiration
    date of the domain name registrant's agreement with the sponsoring
    registrar.  Users may consult the sponsoring registrar's Whois database to
    view the registrar's reported date of expiration for this registration.

    The Registry database contains ONLY .COM, .NET, .EDU domains and
    Registrars.
    ]]&gt;</whois>
      </content>
    </Response>

5- Read the documentation to learn more, and visit [domainapi.com](http://domainapi.com "domainapi.com") to know the list of available services.

## Documentation ##

The domainAPI PHP Wrapper is a fluent API implemented by using method chaining.

The simplest call you can do is:

    $whoisResponse = DomainAPI::from("whois")->get("example.com");

You can combine methods to specify return type or options:

    $thumbnailResponse = DomainAPI::from("thumbnail")->where(array("return" => "fullsize"))->withType("xml")->get("example.com");

### Choose service to call - from ###

    $domainAPIObject = DomainAPI::from("whois");

The method **from** is the first method to call to init a DomainAPIObject. It takes only one parameter, the **name** of the service you want to request.

You can find the list of available services on [domainapi.com](http://domainapi.com "domainapi.com") .

### Call the service - get ###

    $whoisResponse = DomainAPI::from("whois")->get("example.com");

To call the service use the method **get** which takes only one parameter, the domain name, and return the response.

The response is a string with the format of the specify return type (JSON or XML for example).

### Specify options - where ###

    $domainAPIObject = DomainAPI::from("thumbnail")->where(array("return" => "fullsize"));

The method **where** allows to specify options of the service. It takes only on parameter, an array of options where the key is the name of the option and value is the value of the option.

Some services of domainAPI allow you to set multiple values for one option. In this case you can use an array of values. Example:

    $domainAPIObject = DomainAPI::from("availability")->where(array("regions" => array("eu", "gen")));

The list of options for each service is available on the [domainAPI documentation](http://domainapi.com/documentation.html "domainAPI documentation") .

### Specify return type - withType ###

    $domainAPIObject = DomainAPI::from("whois")->withType("json");

The method **withType** allows to specify the return type of the response. It takes only one parameter, the **name** of the return type.

The list of return types is available on the [domainAPI documentation](http://domainapi.com/documentation.html "domainAPI documentation") .

By default (If you don't call the method withType) the return type used is the value set in the **config.inc.php** file.

    define("RETURN_TYPE", "json");

## Changelog ##

See the CHANGELOG.rdoc file for details.

## License ##

Copyright (C) 2011 by domainAPI.com - EuroDNS S.A, DomainAPI PHP Wrapper is released under the MIT license.
See the LICENSE.md file for details.
