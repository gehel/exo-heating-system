<?php

declare(strict_types=1);

use exo\heating\HomeHTTPClient;
use PHPUnit\Framework\TestCase;

class HomeHTTPClientTest extends TestCase
{
	use \phpmock\phpunit\PHPMock;

	public function testGetStartHour(): void
	{
		$fileGetContentsMock = $this->getFunctionMock( 'exo\heating', 'file_get_contents' );
		$fileGetContentsMock
			->expects( $this->once() )
			->with( 'http://timer.home:9990/start' )
			->willReturn( '1234567890' );
		$httpClient = new HomeHTTPClient();

		$this->assertSame( 12345.0, $httpClient->getStartHour() );
	}

	public function testGetEndHour(): void
	{
		$fileGetContentsMock = $this->getFunctionMock( 'exo\heating', 'file_get_contents' );
		$fileGetContentsMock
			->expects( $this->once() )
			->with( 'http://timer.home:9990/end' )
			->willReturn( '1234567890' );
		$httpClient = new HomeHTTPClient();

		$this->assertSame( 12345.0, $httpClient->getEndHour() );
	}

	public function testGetTemperature(): void
	{
		$fileGetContentsMock = $this->getFunctionMock( 'exo\heating', 'file_get_contents' );
		$fileGetContentsMock
			->expects( $this->once() )
			->with( 'http://probe.home:9999/temp' )
			->willReturn( '1234567890' );
		$httpClient = new HomeHTTPClient();

		$this->assertSame( 1234.0, $httpClient->getTemperature() );
	}
}
