<?php
namespace InMotivClient\Exception;

use Exception;

class SoapException extends InMotivException
{
    /**
     * InMotivException constructor.
     * @param string $url
     * @param string $method
     */
    public function __construct($url, $method, Exception $prev)
    {
        $this->url = $url;
        $this->method = $method;

        parent::__construct(sprintf('InMotiv SOAP call fail, url %s method %s', $url, $method), 0, $prev);
    }
}
