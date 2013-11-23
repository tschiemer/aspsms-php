<?php

namespace Aspsms;

/**
 * Shared response object used for request abstraction
 * 
 * @version 1
 * @package aspsms
 * @license LGPL v3 http://www.gnu.org/licenses/lgpl-3.0.txt 
 * @copyright 2013 Philip Tschiemer, <tschiemer@filou.se>
 * @link https://github.com/tschiemer/aspsms-php
 */
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
