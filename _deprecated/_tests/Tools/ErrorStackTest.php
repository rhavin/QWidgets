<?php

namespace Q\Tools;

require_once(__DIR__ .'/../autoloader.php');

class ErrorStackTest extends \PHPUnit\Framework\TestCase {

	protected ?ErrorStack $object = null;

	#[\Override]
	protected function setUp(): void {
		$this->object = new ErrorStack();
	}

	#[\Override]
	protected function tearDown(): void {}
	
		public function testInitialSetting(): void {
			$this->assertFalse($this->object->hasError(),'Initial setting must be error-free.');
		}

		private function failtest($exp_return, $exp_msg) : void {
			$ret = $this->object->fail($exp_return, $exp_msg);
			$msg = $this->object->getLastError();
			$this->assertSame($exp_return, $ret ,'Returns mismatch.');
			$this->assertSame($exp_msg, $msg ,'Messages mismatch.');
		}
		public function testFails(): void {
			$this->failtest(null, 'fail: Nulltest');
			$this->failtest('string', 'fail: stringtest');
			$this->failtest(1, 'fail: numbertest');
		}
		public function testReset(): void {
			$this->assertFalse($this->object->hasError(),'Initial setting must be error-free.');
			$this->failtest(null, 'fail: Nulltest');
			$this->assertTrue($this->object->hasError(),'Must have errors after fail.');
			$ret = $this->object->resetErrors();
			$this->assertFalse($this->object->hasError(),'Must be error-free after reset.');
		}
		public function testErrorArray(): void {
			$this->assertEmpty($this->object->getErrorArray(), 'Initial array must be empty.');
			$this->assertFalse($this->object->hasError(),'Initial setting must be error-free.');
			$count = random_int(10, 20);
			for ($i = 0; $i < $count; ++$i) {
				$this->object->fail(true, 'Test'.$i);
			}
			$this->assertTrue($this->object->hasError(),'Must have errors now.');
			$error_array = $this->object->getErrorArray();
			$this->assertTrue(is_array($error_array),'Return must be an array.');
			$this->assertEquals($count, count($error_array),'Array must contain all errors.');
			
		}

}
