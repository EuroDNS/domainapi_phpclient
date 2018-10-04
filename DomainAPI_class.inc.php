<?php

/*
* This file is part of the domainAPI_php_wrapper package.
*
* Copyright (C) 2011 by domainAPI.com - EuroDNS S.A.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

require("DomainAPIConfiguration_class.php");
require("lib/REST_Service/curl_rest_service_class.inc.php");
require_once("exceptions/ServiceException.class.php");
require_once("exceptions/ServiceUnavailableException.class.php");
require_once("exceptions/NotFoundException.class.php");
require_once("exceptions/NotAuthorizedException.class.php");
require_once("exceptions/InternalServerErrorException.class.php");
require_once("exceptions/BadRequestException.class.php");

/*
 * This class allow to call any service of the DomainAPI
 * Example of call with default values :
 *      $response = DomainAPI::from("info")->withType("json")->get("example.com");
 *
 * Example of call with somes settings changes on the fly :
 *      $configuration = new DomainAPIConfiguration();
 *      $configuration->set('username','anotherUsername')->set('password','anotherPassword');
 *      $response = DomainAPI::from("info",$configuration)->withType("json")->get("example.com");
 */

class DomainAPI {

    /*
     * Configuration (credentials, host,...)
     */
    private $configuration;
    /*
    * Name of the service to call
    */
    private $serviceName;
    /**
     * Type of the return
     */
    private $returnType;
    /**
     * Url of the ressource to call
     */
    private $url;
    /**
     * An array of options
     */
    private $options;

    /**
     * Array of service authorized to use the return type availability
     **/
    private static $authorizedServiceForRT = array ("availability");

    /**
     * Construct of the class, init the service name, build the url and init options
     * @param $serviceName
     */
    public function __construct($serviceName, $configuration = false, $username, $password) {
        if (!$configuration) {
            $configuration = new DomainAPIConfiguration($username, $password);
        }
        $this->configuration = $configuration;
        $this->serviceName = $serviceName;
        $this->url = $this->configuration->get('baseUrl') . "/" . $serviceName;
        $this->options = array ();
    }

    /**
     * Specified the name of the service to call. Build an object DomainAPI
     * @param $serviceName name of the service
     * @param $configuration configuration object, if change needed from default settings
     * @return this
     */
    public static function from($serviceName, $configuration = false, $username, $password) {
        $me = __CLASS__;
        return new $me($serviceName, $configuration, $username, $password);
    }

    /**
     * This function allows you to specify the return type of the service
     * @param $returnType return type (json, xml, rt)
     * @return this
     */
    public function withType($returnType) {
        $this->returnType = $returnType;
        return $this;
    }

    /**
     * Make the request on the service, and return the response
     * Realtime request was removed as it was simply not working
     * @param $domainName
     * @param $decode not totally implements
     * @return the response of the service
     */
    public function get($domainName) {
        $response = "";
        $returnType = $this->getReturnType();
        //allow access to multiple values for the same GET/POST parameter without the use of the brace ([]) notation
        $query_string = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', http_build_query($this->options));
            $url = $this->url . "/" . $returnType . "/" . $domainName . "?" . $query_string;
            $response = $this->request($url);
        return $response;
    }

    /**
     * This function allows you to specify an array of options
     * @param $options an array of options
     * @return this
     */
    public function where($options) {
        $this->options = $options;
        return $this;
    }

    /**
     * Make a curl request on the service, and return the response if the http status code is 200,
     * else return an exception.
     * @param $url url to call
     * @return response of the service
     */
    private function request($url) {
        $curlRestService = new CurlRestService();
        $content_Type = 'application/json';
        $curlRestService->setOption('CURLOPT_TIMEOUT', 10);
        $curlRestService->setOption('CURLOPT_CUSTOM_HTTPHEADER', 'Content-Type: ' . $content_Type);
        $curlRestService->setOption('CURLOPT_USERPWD',
            $this->configuration->get('username') . ":" . $this->configuration->get('password'));
        $exception = null;
        try {
            $response = $curlRestService->get($url);
        } catch (Exception $e) {
            $exception = $e;
        }
        $info = $curlRestService->getInfo();
        if ($info['http_code'] != null) {
            switch ($info["http_code"]) {
                case 200:
                    return $response;
                case 400:
                    throw new BadRequestException();
                case 401:
                    throw new NotAuthorizedException("Please check your username/password and that you come from an authorized IP.");
                case 404:
                    throw new NotFoundException();
                case 500:
                    throw new InternalServerErrorException();
                case 503:
                    throw new ServiceUnavailableException();
                default:
                    throw new ServiceException();
            }
        } else {
            $message = "Response is empty";
            if (!empty($exception)) {
                $message = $exception->getMessage();
            }
            throw new ServiceException($message);
        }
    }


    /**
     * Getter of the return type
     * @return return type
     */
    private function getReturnType() {
        if ($this->returnType != null) {
            $returnType = $this->returnType;
        } else {
            $returnType = $this->configuration->get('returnType');
        }
        return $returnType;
    }
}

?>
