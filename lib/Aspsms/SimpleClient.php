<?php

namespace Aspsms;

class SimpleClient extends AbstractSimpleClient
{
    /**
     * Loaded drivers
     * 
     * @var AbstractClient[]
     */
    var $drivers = NULL;
    
    /**
     * Mapping of request names to driver names
     * 
     * @var string[]
     */
    var $request2driver = array(
        'text'  => 'soap'
    );
    
    public function __construct($options = array()) {
        parent::__construct($options);
        
        $this->drivers = new \stdClass();
    }
    
    /**
     * Loads and returns the correct driver for the assigned request type.
     * 
     * @param string $requestType
     * @return AbstractClient
     * @throws AspsmsException
     */
    public function driver($requestType)
    {
        if ( ! isset($this->request2driver[$requestType]))
        {
            throw new AspsmsException('Request type not recognized: '.$requestType);
        }
        
        // Get driver
        $obj_name = strtolower($this->request2driver[$requestType]);
        
        // If driver not loaded, well, load.
        if ( ! isset($this->drivers->$obj_name))
        {
            // Look for class XyzClient in file Xyz/XyzClient.php
            $class = ucfirst($obj_name) . 'Client';
            $path = dirname(__FILE__) . '/' . ucfirst($obj_name) . '/'.$class.'php';
            
            if ( ! file_exists($path))
            {
                throw new AspsmsException('Could not load driver file '.$path.' for driver '.$d);
            }
            
            // Load file
            require_once $path;
            
            // Are there any options
            if (isset($this->options[$obj_name]))
            {
                $options = $this->options[$obj_name];
            }
            else
            {
                $options = array();
            }
            
            $this->drivers->$obj_name = new $class($options);
        }
        
        return $this->drivers->$d;
    }
    
    public function send($options = array())
    {
        $this->set($options);
        
        
    }
}