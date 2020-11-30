<?php 

namespace app\logs;

use app\interfaces\Logs;

class SystemLogs extends Logs {
	static function writeSystemLog($log) {
		self::$fileName = 'system_log.txt';
		$log = date("h:i:s", time()) . ' ' . $log . "\n\n";
		self::writeLog($log);
	}
}