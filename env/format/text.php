<?php
namespace env\format;

class text implements \env\format\iformat {
	public function format(array $line) {
		return json_encode($line);
	}
}
