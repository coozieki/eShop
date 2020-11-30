<?php 

namespace app\exceptions;

use app\logs\ErrorLogs;

class RouteException extends \Exception {
    protected $exception;
    protected $log_data;

    public function __construct($message='', $code=0, $log_data='')
    {
        $this->exception = new \Exception($message, $code);
        $this->log_data = $log_data;
    }
    public function errorMessage() {
        switch ($this->exception->getCode()){
            case 1: 
                $error = date('d.m.y h:i:s', time()) . "\n" . $this->exception->getMessage() . ($this->log_data ? "\nLog data: " . $this->log_data : '') . "\nOn line: " . $this->exception->getLine() . "\nIn file: " . $this->exception->getFile() . "\n\n";
                ErrorLogs::writeErrorLog($error);
            break;
        }
        return $this->exception->getMessage();
    }
}