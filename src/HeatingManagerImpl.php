<?php

class HeatingManagerImpl {
	function manageHeating( string $t, string $threshold, bool $active ): void {
		$dt = floatval( $t );
		$dThreshold = floatval( $threshold );
		if ( $dt < $dThreshold && $active ) {
			try {
				if ( !( $s = socket_create( AF_INET, SOCK_STREAM, 0 ) ) ) {
					die( 'could not create socket' );
				}
				if ( !socket_connect( $s, 'heater.home', 9999 ) ) {
					die( 'could not connect!' );
				}
				$m = "on";
				socket_send( $s, $m, strlen( $m ), 0 );
				socket_close( $s );
			} catch ( Exception $e ) {
				echo 'Caught exception: ', $e->getMessage(), "\n";
			}
		} elseif ( $dt > $dThreshold && $active ) {
			try {
				if ( !( $s = socket_create( AF_INET, SOCK_STREAM, 0 ) ) ) {
					die( 'could not create socket' );
				}
				if ( !socket_connect( $s, 'heater.home', 9999 ) ) {
					die( 'could not connect!' );
				}
				$m = "off";
				socket_send( $s, $m, strlen( $m ), 0 );
				socket_close( $s );
			} catch ( Exception $e ) {
				echo 'Caught exception: ', $e->getMessage(), "\n";
			}
		}
	}
}
