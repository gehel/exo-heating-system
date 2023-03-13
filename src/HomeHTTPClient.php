<?php

declare(strict_types=1);

namespace exo\heating;

class HomeHTTPClient
{
	public function stringFromURL( string $urlString, int $s ) {
		$o = file_get_contents( $urlString );

		return substr( $o, 0, $s );
	}
}