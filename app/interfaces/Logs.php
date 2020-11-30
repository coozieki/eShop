<?php 

namespace app\interfaces;

abstract class Logs {
	protected static $dir = "log";
	protected static $fileName = 'log.txt';

	static protected function writeLog(string $log) {
		file_put_contents(static::$dir . '/' . date('d.m.y', time()) . ' ' . static::$fileName, $log, FILE_APPEND);
	}

	static public function nextLog() {
		file_put_contents(static::$dir . '/' . date('d.m.y', time()) . ' ' . static::$fileName, "\n\n", FILE_APPEND);
	}
}