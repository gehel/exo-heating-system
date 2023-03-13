<?php

class HeatingManagerImpl {

	private readonly HomeSocketManager $homeSocketManager;

	public function setHomeSocketManager( HomeSocketManager $homeSocketManager ): void {
		$this->homeSocketManager = $homeSocketManager;
	}
	function manageHeating( string $t, string $threshold, bool $active ): void {
		$dt = floatval( $t );
		$dThreshold = floatval( $threshold );
		if ( $dt < $dThreshold && $active ) {
			$this->homeSocketManager->send( 'heater.home', 9999, 'on' );
		} elseif ( $dt > $dThreshold && $active ) {
			$this->homeSocketManager->send( 'heater.home', 9999, 'off' );
		}
	}
}
