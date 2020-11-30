<?php 

namespace app\interfaces;

abstract class Singletone {
	protected static $instance;
    protected function __construct()
    {
    }
    protected function __clone()
    {
	}
	
    static function getInstance() {
        if (!static::$instance) {
            static::$instance = new static;
        }
        return static::$instance;
    }
}