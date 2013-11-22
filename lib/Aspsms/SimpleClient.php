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
        'getVersion'  => 'soap'
    );
    
    public function __construct($options = array()) {
        parent::__construct($options);
        
        $this->drivers = new \stdClass();
    }
    
    /**
     * Loads and returns the correct driver for the assigned request type.
     * 
     * @param Request $requestType
     * @return AbstractClient
     * @throws AspsmsException
     */
    public function driver(&$request)
    {
        $requestName = $request->getRequestName();
        
        if ( ! isset($this->request2driver[$requestName]))
        {
            throw new AspsmsException('Request type not recognized: '.$requestName);
        }
        
        // Get driver name
        $driverName = strtolower($this->request2driver[$requestName]);
        
        // If driver not loaded, well, load.
        $this->loadDriver($driverName, FALSE);
        
        return $this->drivers->$d;
    }
    
    public function loadDriver($driverName, $return = FALSE)
    {
        if ( ! isset($this->drivers->$obj_name) or $return)
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

            if ($return)
            {
                return new $class($options);
            }
            else
            {
                $this->drivers->$obj_name = new $class($options);
            }
        }
    }
    
}