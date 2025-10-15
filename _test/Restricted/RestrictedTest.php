<?php
namespace Q\Restricted;

class MockRestricted extends RestrictedClass {}

class RestrictedTest extends \PHPUnit\Framework\TestCase {
	protected ?Restrictor $object = null;

	#[\Override]
	protected function setUp(): void
	{
		$this->object = new MockRestricted();
	}

	#[\Override]
	protected function tearDown(): void {}
	/**
	 * @covers Q\Restrictor::denyRead()
	 * @covers Q\Restrictor::denyWrite()
	 * @covers Q\Restrictor::denyCreate()
	 * @covers Q\Restrictor::denyDelete()
	 */
	public function testGetValue(): void
	{
		$this->AssertFalse($this->object->isDeniedCreate(), 'Default restrictor should not restrict.');
		$this->AssertFalse($this->object->isDeniedRead(), 'Default restrictor should not restrict.');
		$this->AssertFalse($this->object->isDeniedWrite(), 'Default restrictor should not restrict.');
		$this->AssertFalse($this->object->isDeniedDelete(), 'Default restrictor should not restrict.');
	}
}