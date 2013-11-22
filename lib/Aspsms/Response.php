<?php

namespace Aspsms;

class Response
{
    const STAT_OK = 1;
    
    /**
     * @var string
     * @access protected
     */
    var $requestName = '';
    
    /**
     * @var int
     * @access protected
     */
    var $statusCode = 0;
    
    /**
     * @var string
     * @access protected
     */
    var $statusDescription = '';
    
    
    /**
     * @var mixed
     * @access protected
     */
    var $result;
    
    public function __construct($request)
    {
        if ($request instanceof Request)
        {
            $this->requestName = $request->getRequestName();
        }
        else
        {
            $this->requestName = strval($requestName);
        }
    }
    
    public function requestName()
    {
        return $this->requestName;
    }
    
    public function statusCode($code = NULL)
    {
        if ($code !== NULL)
        {
            $this->statusCode = intval($code);
        }
        return $this->statusCode;
    }
    
    public function statusDescription($description = NULL)
    {
        if ($description !== NULL)
        {
            $this->statusDescription = $description;
        }
        return $this->statusDescription;
    }
    
    public function result()
    {
        return $this->result;
    }
}
