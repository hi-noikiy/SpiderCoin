<?php
namespace App\Jobs\BCoin\CoinRpc;

class CoinException extends \Exception
{
    private $http_code;
    private $response;

    public function __construct($message, $http_code=null, $response=null)
    {
        parent::__construct($message);
        $this->http_code = $http_code;
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getHttpCode()
    {
        return $this->http_code;
    }
}
