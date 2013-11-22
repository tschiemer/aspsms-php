<?php

namespace Aspsms;

/**
 */
abstract class AbstractClient
{
    public function __construct($options=array());
    
    public function __call($name,$param=array());
}