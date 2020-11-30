<?php 

namespace app\logs;

use app\interfaces\Logs;

class ErrorLogs extends Logs {
	static function writeErrorLog($log) {
		self::$fileName = 'error_log.txt';
		self::writeLog($log);
	}
}