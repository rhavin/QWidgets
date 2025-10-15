<?php

namespace Q\Tools;

class ErrorStack {
	private $my_stack = [];
	public function hasError() : bool {
		return count($this->my_stack) != 0;
	}
	public function fail(mixed $return, mixed $msg_obj) : mixed {
		$this->my_stack[] = $msg_obj;
		return $return;
	}
	public function getLastError() : mixed {
		if (count($this->my_stack) == 0)
			return null;
		return end($this->my_stack);
	}
	public function &getErrorArray() : array {
		return $this->my_stack;
	}
	public function resetErrors() {
		$this->my_stack = [];
	} 
}
