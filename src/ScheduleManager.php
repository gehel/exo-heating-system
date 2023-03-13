<?php

namespace exo\heating;

/**
 * The system obtains temperature data from a remote source,
 * compares it with a given threshold and controls a remote heating
 * unit by switching it on and off. It does so only within a time
 * period configured on a remote service (or other source)
 *
 * This is purpose-built crap.
 */
class ScheduleManager {

	private static HomeSocketManager $homeSocketManager;
	private static HomeHTTPClient $httpClient;

	public static function setHomeSocketManager( HomeSocketManager $homeSocketManager ): void {
		static::$homeSocketManager = $homeSocketManager;
	}

	public static function setHomeHTTPClient( HomeHTTPClient $httpClient ): void {
		static::$httpClient = $httpClient;
	}

	/**
	 * This method is the entry point into the code. You can assume that it is
	 * called at regular interval with the appropriate parameters.
	 */
	public static function manage( HeatingManagerImpl $hM, string $threshold ): void {
		if ( !isset(static::$homeSocketManager) ) {
			static::$homeSocketManager = new HomeSocketManager();
		}
		if ( !isset(static::$httpClient) ) {
			static::$httpClient = new HomeHTTPClient();
		}
		$hM->setHomeSocketManager( static::$homeSocketManager );

		$t = (float)self::stringFromURL( "http://probe.home:9999/temp", 4 );

		$now = gettimeofday(true);
		if ( $now > self::startHour() && $now < self::endHour() ) {
			$hM->manageHeating( $t, (float)$threshold, true );
		}
	}

	private static function endHour(): float {
		return (float) self::stringFromURL( "http://timer.home:9990/end", 5 );
	}

	private static function stringFromURL( string $urlString, int $s ) {
		return static::$httpClient->stringFromURL( $urlString, $s );
	}

	static function startHour(): float {
		return (float) self::stringFromURL( "http://timer.home:9990/start", 5 );
	}
}
