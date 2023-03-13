<?php

declare( strict_types=1 );

use exo\heating\ScheduleManager;
use exo\heating\HeatingManagerImpl;
use exo\heating\HomeHTTPClient;
use exo\heating\HomeSocketManager;
use PHPUnit\Framework\TestCase;

class ScheduleManagerTest extends TestCase
{
	use \phpmock\phpunit\PHPMock;

	public function testDoesNothingBeforeTime() {
		$socketMock = $this->createMock( HomeSocketManager::class );
		$socketMock->expects( $this->never() )
			->method( 'send' );
		$httpClientMock = $this->createMock( HomeHTTPClient::class );
		$httpClientMock
			->expects( $this->exactly(2 ))
			->method('stringFromURL')
			->willReturnCallback( fn ( $url, $s ) => match ( $url ) {
				'http://timer.home:9990/start' => '10007',
				'http://timer.home:9990/end' => '10022',
				'http://probe.home:9999/temp' => '20',
				default => throw new Exception( 'Unexpected URL' ),
			} );

		$getTimeOfDayMock = $this->getFunctionMock( 'exo\heating', 'gettimeofday' );
		$getTimeOfDayMock->expects( $this->once() )
			->willReturn( '10003' );

		$sut = ScheduleManager::class;
		$sut::setHomeSocketManager( $socketMock );
		$sut::setHomeHTTPClient( $httpClientMock );

		$sut::manage( new HeatingManagerImpl(), '22' );
	}

	public function testDoesNothingAfterTime() {
		$socketMock = $this->createMock( HomeSocketManager::class );
		$socketMock->expects( $this->never() )
			->method( 'send' );
		$httpClientMock = $this->createMock( HomeHTTPClient::class );
		$httpClientMock
			->expects( $this->exactly(3 ))
			->method('stringFromURL')
			->willReturnCallback( fn ( $url, $s ) => match ( $url ) {
				'http://timer.home:9990/start' => '10007',
				'http://timer.home:9990/end' => '10022',
				'http://probe.home:9999/temp' => '20',
				default => throw new Exception( 'Unexpected URL' ),
			} );

		$getTimeOfDayMock = $this->getFunctionMock( 'exo\heating', 'gettimeofday' );
		$getTimeOfDayMock->expects( $this->once() )
			->willReturn( '10023' );

		$sut = ScheduleManager::class;
		$sut::setHomeSocketManager( $socketMock );
		$sut::setHomeHTTPClient( $httpClientMock );

		$sut::manage( new HeatingManagerImpl(), '22' );
	}

	public function testTurnsOnIfCold() {
		$socketMock = $this->createMock( HomeSocketManager::class );
		$socketMock->expects( $this->once() )
			->method( 'send' )
			->with( 'heater.home', 9999, 'on' );
		$httpClientMock = $this->createMock( HomeHTTPClient::class );
		$httpClientMock
			->expects( $this->exactly(3 ))
			->method('stringFromURL')
			->willReturnCallback( fn ( $url, $s ) => match ( $url ) {
				'http://timer.home:9990/start' => '10007',
				'http://timer.home:9990/end' => '10022',
				'http://probe.home:9999/temp' => '20',
				default => throw new Exception( 'Unexpected URL' ),
			} );

		$getTimeOfDayMock = $this->getFunctionMock( 'exo\heating', 'gettimeofday' );
		$getTimeOfDayMock->expects( $this->once() )
			->willReturn( '10010' );

		$sut = ScheduleManager::class;
		$sut::setHomeSocketManager( $socketMock );
		$sut::setHomeHTTPClient( $httpClientMock );

		$sut::manage( new HeatingManagerImpl(), '22' );
	}

	public function testTurnsOffIfHot() {
		$socketMock = $this->createMock( HomeSocketManager::class );
		$socketMock->expects( $this->once() )
			->method( 'send' )
			->with( 'heater.home', 9999, 'off' );
		$httpClientMock = $this->createMock( HomeHTTPClient::class );
		$httpClientMock
			->expects( $this->exactly(3 ))
			->method('stringFromURL')
			->willReturnCallback( fn ( $url, $s ) => match ( $url ) {
				'http://timer.home:9990/start' => '10007',
				'http://timer.home:9990/end' => '10022',
				'http://probe.home:9999/temp' => '24',
				default => throw new Exception( 'Unexpected URL' ),
			} );

		$getTimeOfDayMock = $this->getFunctionMock( 'exo\heating', 'gettimeofday' );
		$getTimeOfDayMock->expects( $this->once() )
			->willReturn( '10010' );

		$sut = ScheduleManager::class;
		$sut::setHomeSocketManager( $socketMock );
		$sut::setHomeHTTPClient( $httpClientMock );

		$sut::manage( new HeatingManagerImpl(), '22' );
	}

	public function testDoesNothingIfExactTempMatch() {
		$socketMock = $this->createMock( HomeSocketManager::class );
		$socketMock->expects( $this->never() )
			->method( 'send' );
		$httpClientMock = $this->createMock( HomeHTTPClient::class );
		$httpClientMock
			->expects( $this->exactly(3 ))
			->method('stringFromURL')
			->willReturnCallback( fn ( $url, $s ) => match ( $url ) {
				'http://timer.home:9990/start' => '10007',
				'http://timer.home:9990/end' => '10022',
				'http://probe.home:9999/temp' => '22',
				default => throw new Exception( 'Unexpected URL' ),
			} );

		$getTimeOfDayMock = $this->getFunctionMock( 'exo\heating', 'gettimeofday' );
		$getTimeOfDayMock->expects( $this->once() )
			->willReturn( '10010' );

		$sut = ScheduleManager::class;
		$sut::setHomeSocketManager( $socketMock );
		$sut::setHomeHTTPClient( $httpClientMock );

		$sut::manage( new HeatingManagerImpl(), '22' );
	}

}
