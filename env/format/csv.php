<?php
namespace env\format;

class csv implements \env\format\iformat {
	private $delimiter = "|";
	public function __construct($delimiter = "|") {
		$this->delimiter = $delimiter;
	}
	public function format(array $line) {
		return implode($this->delimiter, $line);
	}
}
