<?php
namespace env\format;

class json implements \env\format\iformat {
	public function format(array $line) {
		return json_encode($line);
	}
}
