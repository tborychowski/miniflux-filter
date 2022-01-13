<?php
require_once 'utils.php';

class LOG_LEVEL {
	const FATAL = 0;
	const ERROR = 1;
	const WARNING = 2;
	const WARN = 2;
	const SUCCESS = 3;
	const INFO = 4;
	const DEBUG = 5;
}


class Logger {
	private $log_level;
	private $date_format;
	private $log_file;

	function __construct ($log_level = LOG_LEVEL::INFO, $path = 'store/filters.log', $format = 'Y-m-d G:i') {
		if (getenv('LOG_LEVEL')) {
			$lvl = strtoupper(getenv('LOG_LEVEL'));
			$log_level = constant("LOG_LEVEL::$lvl");
		}
		$this->log_level = $log_level ?? LOG_LEVEL::INFO;
		$this->log_file = $path;
		$this->date_format = $format;
	}

	public function last_log_time () {
		if (!file_exists($this->log_file)) return 'never';
		$log_file_time = filemtime($this->log_file);
		$log_file_datetime = date($this->date_format, $log_file_time);
		return time_ago($log_file_datetime);
	}

	public function touch () {
		touch($this->log_file);
	}

	private function log ($msg = '', $type = 'DEBUG') {
		if ($this->log_level < constant("LOG_LEVEL::$type")) return;
		$path = $this->log_file;
		$time = date($this->date_format);
		$line = "[$time][$type] $msg\n";
		echo $line;
		file_put_contents($path, $line, FILE_APPEND);
	}

	public function debug   ($msg) { $this->log($msg, 'DEBUG'); }
	public function info    ($msg) { $this->log($msg, 'INFO'); }
	public function success ($msg) { $this->log($msg, 'SUCCESS'); }
	public function warning ($msg) { $this->log($msg, 'WARNING'); }
	public function warn    ($msg) { $this->log($msg, 'WARNING'); }
	public function error   ($msg) { $this->log($msg, 'ERROR'); }
	public function fatal   ($msg) { $this->log($msg, 'FATAL'); }
}
