<?php
namespace env\lib;
class io {
	private $stream;
	private $format;
	private $line_delimiter = "\n";
	public function __construct( \env\stream\istream $stream, \env\format\iformat $format) {
		$this->stream = $stream;
		$this->format = $format;
		$this->line_delimiter = "\n";
	}

	public function write($line) {
		if (is_array($line)) {
			$this->stream->write($this->format->format($line) . $this->line_delimiter);
		}else{
			$this->stream->write($line . $this->line_delimiter);
		}
	}
}
