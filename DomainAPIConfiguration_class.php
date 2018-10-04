<?php

/*
* This file is part of the domainAPI_php_wrapper package.
*
* Copyright (C) 2011 by domainAPI.com - EuroDNS S.A.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

class DomainAPIConfiguration {

    /*
     * Server Host
     */
    private $host;
    /*
     * Server Port
     */
    private $port;
    /*
     * Sub URL (version)
     */
    private $subUrl;
    /*
     * Complete URL
     */
    private $baseUrl;
    /*
     * Domain API Username
     */
    private $username;
    /*
     * Domain API Password
     */
    private $password;
    /*
     * Default return type (json/xml)
     */
    private $returnType;
    /*
     * name of the callback javascript function: call when the availability of a domain is return
     * the function must take two parameters: first the name of the domain, second the status of the domain
     * (taken, sedo, free...)
     */
    private $availabilityCallback;
    /*
     * name of the callback javascript function: call when the availability request is finish
     * this function doesn't take parameters
     */
    private $endAvailabilityCallback;

    /**
     * Construct of the class and initiliaze with default values
     * @param $serviceName
     */
    public function __construct($username, $password) {
        $this->host = 'api.domainapi.com';
        $this->port = 80;
        $this->subUrl = 'v1';
        $this->baseUrl = $this->host . ':' . $this->port . '/' . $this->subUrl;
        $this->username = $username;
        $this->password = $password;
        $this->returnType = 'json';
        $this->availabilityCallback = 'callbackAvailability';
        $this->endAvailabilityCallback = 'callbackAvailabilityFinished';
    }

    public function get($var) {
        return $this->$var;
    }

    public function set($var, $val) {
        $this->$var = $val;
        return $this;
    }

}

?>