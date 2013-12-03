<?php

namespace lightninghowl\utils\url;

/**
 * Class used by the system. It sends HTTP requests then stores their answers
 * 
 * Esta classe utiliza uma série de métodos para o envio de requisições
 * e armazenamento de respostas HTTP. Ela age como uma camada de abstração
 * aplicada a curl padrão do PHP.
 * 
 * @package lightninghowl\utils\url
 * @author Alison Bento "Lykaios" <alisonlks@outlook.com>
 * @version 1.0.0
 * 
 */
class UrlHandler {

    /**
     * The url address
     * @var string
     */
    private $url;

    /**
     * The HTTP Method. GET, POST and PUT are examples
     * @var string
     */
    private $method;

    /**
     * Contains the request parameters which will be send with it
     * @var array
     */
    private $fields;

    /**
     * Contains the HTTP response's content. It will be empty if the run() 
     * method were not called first.
     * @var string
     */
    private $content;

    /**
     * Contains the HTTP response's header. It will be empty if the run()
     * method were not called first.
     * @var string
     */
    private $header;

    /**
     * The http status field. It will be empty if the run()
     * method were not called first.
     * @var integer
     */
    private $status;

    /**
     * Sets the request timeout. The time value is in seconds
     * @var integer
     */
    private $timeout;

    /**
     * Sets the request timeout. The time value must be in seconds
     * @param int $timeout
     */
    public function setTimeout($timeout = 30) {
        $this->timeout = $timeout;
    }

    /**
     * Retrieves the timeout value
     */
    public function getTimeout() {
        return $this->timeout;
    }

    /**
     * Retrieves the HTTP response content
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Retrieves the HTTP response header
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * Retrieves the HTTP status
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Constructs the object
     * @param string $url The address of the server
     * @param string $method The HTTP method
     */
    public function __construct($url, $method = "GET") {
        $this->url = $url;
        $this->method = $method;
        $this->fields = array();

        $this->content = "";
        $this->header = "";
        $this->setTimeout();
    }

    /**
     * Add a http parameter to the request. 
     * @param string $fieldName The parameter's name 
     * @param string $fieldValue The parameter's value
     */
    public function addField($fieldName, $fieldValue) {
        $this->fields[$fieldName] = urlencode($fieldValue);
    }

    /**
     * Sends the request and then saves it's results
     * @return string The evaluated url
     */
    public function run() {
        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($cURL, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_HEADER, 1);

        if ($this->method == "POST") {
            curl_setopt($cURL, CURLOPT_URL, $this->url);
            curl_setopt($cURL, CURLOPT_POST, count($this->fields));
            curl_setopt($cURL, CURLOPT_POSTFIELDS, $this->urlify());
        } else if ($this->method == "GET") {
            curl_setopt($cURL, CURLOPT_URL, $this->url . "?" . $this->urlify());
            curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        }

        $result = curl_exec($cURL);

        $header_size = curl_getinfo($cURL, CURLINFO_HEADER_SIZE);

        $this->header = substr($result, 0, $header_size);
        $this->content = substr($result, $header_size);

        $url = curl_getinfo($cURL, CURLINFO_EFFECTIVE_URL);
        
        curl_close($cURL);

        $this->retrieveStatus();
        
        return $url;
    }

    private function urlify() {
        $fieldsString = "";
        foreach ($this->fields as $key => $value) {
            $fieldsString .= $key . '=' . $value . '&';
        }
        $result = rtrim($fieldsString, '&');

        return $result;
    }

    private function retrieveStatus() {
        $head = explode(' ', $this->header);
        $this->status = intval(isset($head[1]) ? $head[1] : 404);
    }

}