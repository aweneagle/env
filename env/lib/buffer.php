<?php
namespace env\lib;
class io_buffer {
	private $format = null;
	private $stream = null;
	private $data = array();
	
	public function __construct(\env\stream\istream $s, \env\format\iformat $f) {
		$this->stream = $s;
		$this->format = $f;
	}

	public function write (array $line) {
		$this->data[] = $line;
	}

	public function __destruct(){
		$this->stream->write($this->format->format($this->data));
	}
}
