<?php

declare(strict_types=1);

namespace exo\heating;

class HomeHTTPClient
{
	public function getStartHour(): float {
		return (float) $this->stringFromURL('http://timer.home:9990/start', 5 );
	}

	public function getEndHour(): float {
		return (float) $this->stringFromURL('http://timer.home:9990/end', 5 );
	}

	public function getTemperature(): float {
		return (float) $this->stringFromURL('http://probe.home:9999/temp', 4 );
	}

	private function stringFromURL( string $urlString, int $s ) {
		$o = file_get_contents( $urlString );

		return substr( $o, 0, $s );
	}
}