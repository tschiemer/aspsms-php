<?php

namespace tschiemer\Aspsms;

/**
 * Shared response object used for request abstraction
 * 
 * @version 1.1.0
 * @package aspsms
 * @license LGPL v3 http://www.gnu.org/licenses/lgpl-3.0.txt 
 * @copyright 2013 Philip Tschiemer, <tschiemer@filou.se>
 * @link https://github.com/tschiemer/aspsms-php
 */
class Response
{
    const STAT_OK = 1;
    
    /**
     * Request name for which this is a response
     * @var string
     * @access protected
     */
    var $requestName = '';
    
    /**
     * Status code
     * @var int
     * @access protected
     */
    var $statusCode = 0;
    
    /**
     * Status Description
     * @var string
     * @access protected
     */
    var $statusDescription = '';
    
    
    /**
     * Essential request result
     * @var mixed
     * @access protected
     */
    var $result;
    
    /**
     * Constructor
     * 
     * @param \Aspsms\Request,string $request
     */
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
    
    /**
     * Getter
     * @return string
     */
    public function requestName()
    {
        return $this->requestName;
    }
    
    /**
     * Getter/setter. Sets new code iff a value is passed.
     * @param NULL,int $code
     * @return int
     */
    public function statusCode($code = NULL)
    {
        if ($code !== NULL)
        {
            $this->statusCode = intval($code);
        }
        return $this->statusCode;
    }
    
    /**
     * Getter/setter. Sets new description iff a value is passed.
     * @param NULL,string $description
     * @return string
     */
    public function statusDescription($description = NULL)
    {
        if ($description !== NULL)
        {
            $this->statusDescription = $description;
        }
        return $this->statusDescription;
    }
    
    /**
     * Getter
     * @return mixed
     */
    public function result()
    {
        return $this->result;
    }
}
