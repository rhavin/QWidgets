<?php
namespace Q\Restricted;
/**
 * Description of FlagRestrictorTest
 *
 * @author rhavin
 */
class FlagRestrictorTest extends \PHPUnit\Framework\TestCase {
	/**
	 * @var Attributes
	 */
	protected ?FlagRestrictor $object = null;

	/**
	 * This method is called before a test is executed.
	 */
	#[\Override]
	protected function setUp(): void {
		$this->object = new FlagRestrictor();
	}

	/**
	 * This method is called after a test is executed.
	 */
	#[\Override]
	protected function tearDown(): void {}
	
	public function testInits() {
		$this->AssertFalse($this->object->isDeniedCreate(),
						'Flag restictor must not restrict creation in default setting.');
		$this->AssertFalse($this->object->isDeniedDelete(),
						'Flag restictor must not restrict deletion in default setting.');
		$this->AssertFalse($this->object->isDeniedRead(),
						'Flag restictor must not restrict reading in default setting.');
		$this->AssertFalse($this->object->isDeniedWrite(),
						'Flag restictor must not restrict writing in default setting.');
		$this->AssertFalse($this->object->isDeniedRestrictor(),
						'Flag restictor must not restrict restrictor in default setting.');
	}
	public function testSets() {
		$this->AssertTrue($this->object->setAccess(Restrictor::DENY_CREATE),
						'Flag restictor must allow setAccess in default setting.');
		$this->AssertTrue($this->object->isDeniedCreate(),
						'Flag restictor must restrict creation if set so.');
		$this->AssertTrue($this->object->setAccess(Restrictor::DENY_WRITE),
						'Flag restictor must allow setAccess in default setting.');
		$this->AssertFalse($this->object->isDeniedCreate(),
						'Set must clear unset bits.');
		$this->AssertTrue($this->object->isDeniedWrite(),
						'Flag restictor must restrict writing if set so.');
	}
	public function testNonRestrictorChanges() {
		$this->AssertEquals(0, $this->object->getAccess(),
						'Flag restictor must be initially unrestricted.');
		for ($i=0; $i<4; ++$i)
		{
			// create random combination of masterflags without restrict-flag
			// and two additional flag for preset test.
			$randomFlags = 0;
			// move up behind restrict-flag
			$presetFlags = 0x5 << random_int(6, 8);
			while ($randomFlags == 0)
			{
				$randomFlags = random_int(1, 0x00FF) & ~Restrictor::DENY_RESTRICT;
				$randomFlags &= ~$presetFlags;
			}
			$expectedFlags = $presetFlags | $randomFlags;
			$this->AssertTrue($this->object->setAccess($presetFlags),
						'Flag restictor must allow setAccess in default setting.');
			$this->AssertEquals($presetFlags, $this->object->getAccess(),
						'Flag restictor must be set to given restriction.');
			$this->AssertTrue($this->object->denyAccess($randomFlags),
						'Flag restictor must allow denyAccess in default setting.');
			$this->AssertEquals($expectedFlags, $this->object->getAccess(),
						'Flag restictor must be set to given restriction.');
			$this->AssertTrue($this->object->allowAccess($randomFlags),
						'Flag restictor must allow allowAccess in default setting.');
			$this->AssertEquals($presetFlags, $this->object->getAccess(),
						'Flag restictor must be set to given restriction.');
		}
	}
	public function testRestrictorFlag() {
		$this->AssertEquals(0, $this->object->getAccess(),
					'Flag restictor must be initially unrestricted.');
		$this->AssertTrue($this->object->setAccess(Restrictor::DENY_RESTRICT),
					'Flag restictor must allow setAccess in default setting.');
		$this->AssertFalse($this->object->allowAccess(Restrictor::DENY_READ),
					'Locked Restrictor must deny firther changes to restrictor.');
		$this->AssertFalse($this->object->denyAccess(Restrictor::DENY_READ),
					'Locked Restrictor must deny firther changes to restrictor.');
		$this->AssertFalse($this->object->setAccess(Restrictor::DENY_READ),
					'Locked Restrictor must deny firther changes to restrictor.');
	}

}