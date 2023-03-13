<?php

namespace exo\heating;

class HeatingManagerImpl {

	private readonly HomeSocketManager $homeSocketManager;

	public function setHomeSocketManager( HomeSocketManager $homeSocketManager ): void {
		$this->homeSocketManager = $homeSocketManager;
	}
	function manageHeating( float $t, float $threshold, bool $active ): void {
		if (!$active) {
			return;
		}
		if ( $t < $threshold ) {
			$this->homeSocketManager->send( 'heater.home', 9999, 'on' );
		} elseif ( $t > $threshold ) {
			$this->homeSocketManager->send( 'heater.home', 9999, 'off' );
		}
	}
}
