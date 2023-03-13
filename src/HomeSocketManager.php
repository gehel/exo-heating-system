<?php

declare( strict_types=1 );

namespace exo\heating;

class HomeSocketManager {

	public function turnOn(): void
	{
		$this->send('heater.home', 9999, 'on');
	}

	public function turnOff(): void
	{
		$this->send('heater.home', 9999, 'off');
	}

	private function send( string $address, int $port, string $message ): void {
		try {
			if ( !( $s = socket_create( AF_INET, SOCK_STREAM, 0 ) ) ) {
				die( 'could not create socket' );
			}
			if ( !socket_connect( $s, $address, $port ) ) {
				die( 'could not connect!' );
			}
			socket_send( $s, $message, strlen( $message ), 0 );
			socket_close( $s );
		} catch ( Exception $e ) {
			echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
}
