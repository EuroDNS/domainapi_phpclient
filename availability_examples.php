<?php

require_once 'DomainAPI_class.inc.php';

$USERNAME = 'YOUR_USERNAME';
$PASSWORD = 'YOUR_PASSWORD';

// Simple request to know either a domain (domain + tld) is available or not
$response = DomainAPI::from("availability", $configuration, $USERNAME, $PASSWORD)
    ->withType("json")->get("example.com");
var_dump($response);


// Single request returning the list domain name and if there are available or not.
// You can filter them by
// - region GEN, EU, ASIA, AEU, PREREG (ngtld), AMERICAS, AFRICA
$response = DomainAPI::from("availability", $configuration, $USERNAME, $PASSWORD)
    ->withType("json")->where(array('regions' => array('GEN', 'EU')))->get("example");
var_dump($response);


// The client does't support streams, it has to be done by your own hand.
// However this kind of call must allow you to build reactive availability result
// When selecting your returning format, instead 'json' or 'xml', just use 'rt'
$response = DomainAPI::from("availability", $configuration, $USERNAME, $PASSWORD)->withType("rt")->get("example");

//You can inspire yourself using the following code
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'api.domainapi.com:80/v1/availability/rt/example',
    CURLOPT_USERPWD => "$USERNAME:$PASSWORD",
    CURLOPT_VERBOSE => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_VERBOSE => 0,
    CURLOPT_FOLLOWLOCATION => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_TIMEOUT => 10,

));
curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($curl, $data) {
    echo $data;
    ob_flush();
    flush();
    return strlen($data);
});
curl_exec($ch);


