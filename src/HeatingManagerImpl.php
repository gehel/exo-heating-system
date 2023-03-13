<?php

declare( strict_types=1 );

namespace exo\heating;

class HeatingManagerImpl {

	private readonly HomeSocketManager $homeSocketManager;

	public function setHomeSocketManager( HomeSocketManager $homeSocketManager ): void {
		$this->homeSocketManager = $homeSocketManager;
	}
	public function manageHeating( float $t, float $threshold ): void {
		if ( $t < $threshold ) {
			$this->homeSocketManager->send( 'heater.home', 9999, 'on' );
		} elseif ( $t > $threshold ) {
			$this->homeSocketManager->send( 'heater.home', 9999, 'off' );
		}
	}
}
