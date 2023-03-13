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
			->method( $this->anything() );
		$this->fakeApiEndpoints();

		$getTimeOfDayMock = $this->getFunctionMock( 'exo\heating', 'gettimeofday' );
		$getTimeOfDayMock->expects( $this->once() )
			->willReturn( '10003' );

		$sut = ScheduleManager::class;
		$sut::setHomeSocketManager( $socketMock );

		$sut::manage( new HeatingManagerImpl(), '22' );
	}

	public function testDoesNothingAfterTime() {
		$socketMock = $this->createMock( HomeSocketManager::class );
		$socketMock->expects( $this->never() )
			->method( $this->anything() );
		$this->fakeApiEndpoints();

		$getTimeOfDayMock = $this->getFunctionMock( 'exo\heating', 'gettimeofday' );
		$getTimeOfDayMock->expects( $this->once() )
			->willReturn( '10023' );

		$sut = ScheduleManager::class;
		$sut::setHomeSocketManager( $socketMock );

		$sut::manage( new HeatingManagerImpl(), '22' );
	}

	public function testTurnsOnIfCold() {
		$socketMock = $this->createMock( HomeSocketManager::class );
		$socketMock->expects( $this->once() )
			->method( 'turnOn' );
		$this->fakeApiEndpoints();

		$getTimeOfDayMock = $this->getFunctionMock( 'exo\heating', 'gettimeofday' );
		$getTimeOfDayMock->expects( $this->once() )
			->willReturn( '10010' );

		$sut = ScheduleManager::class;
		$sut::setHomeSocketManager( $socketMock );

		$sut::manage( new HeatingManagerImpl(), '22' );
	}

	public function testTurnsOffIfHot() {
		$socketMock = $this->createMock( HomeSocketManager::class );
		$socketMock->expects( $this->once() )
			->method( 'turnOff' );
		$this->fakeApiEndpoints( '24' );

		$getTimeOfDayMock = $this->getFunctionMock( 'exo\heating', 'gettimeofday' );
		$getTimeOfDayMock->expects( $this->once() )
			->willReturn( '10010' );

		$sut = ScheduleManager::class;
		$sut::setHomeSocketManager( $socketMock );

		$sut::manage( new HeatingManagerImpl(), '22' );
	}

	public function testDoesNothingIfExactTempMatch() {
		$socketMock = $this->createMock( HomeSocketManager::class );
		$socketMock->expects( $this->never() )
			->method( $this->anything() );
		$this->fakeApiEndpoints( '20' );

		$getTimeOfDayMock = $this->getFunctionMock( 'exo\heating', 'gettimeofday' );
		$getTimeOfDayMock->expects( $this->once() )
			->willReturn( '10010' );

		$sut = ScheduleManager::class;
		$sut::setHomeSocketManager( $socketMock );

		$sut::manage( new HeatingManagerImpl(), '20' );
	}

	private function fakeApiEndpoints( string $temperature = '20' ): void
	{
		$fileGetContentsMock = $this->getFunctionMock('exo\heating', 'file_get_contents');
		$fileGetContentsMock->expects( $this->any() )
			->willReturnCallback(fn($url) => match ($url) {
				'http://timer.home:9990/start' => '10007',
				'http://timer.home:9990/end' => '10022',
				'http://probe.home:9999/temp' => $temperature,
				default => throw new Exception('Unexpected URL'),
			});
	}


}
