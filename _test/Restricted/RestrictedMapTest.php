<?php
namespace Q\Restricted;

class RestrictedMapTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @var Attributes
	 */
	protected ?RestrictedMap $object = null;

	/**
	 * This method is called before a test is executed.
	 */
	#[\Override]
	protected function setUp(): void {
		$this->object = new RestrictedMap();
	}

	/**
	 * This method is called after a test is executed.
	 */
	#[\Override]
	protected function tearDown(): void {}
	
	public function testInit() {
		$this->AssertEquals(0, count($this->object),
						'Initial Restricted Map must be empty.');
		$this->AssertFalse($this->object->isDeniedCreate(),
						'Initial Restricted Map must be unrestricted to create.');
		$this->AssertFalse($this->object->isDeniedDelete(),
						'Initial Restricted Map must be unrestricted to delete.');
		$this->AssertFalse($this->object->isDeniedRead(),
						'Initial Restricted Map must be unrestricted to read.');
		$this->AssertFalse($this->object->isDeniedWrite(),
						'Initial Restricted Map must be unrestricted to write.');
		$this->AssertFalse($this->object->isDeniedRestrictor(),
						'Initial Restricted Map must allow to change restrictor.');
	}
}