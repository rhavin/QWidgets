<?php
namespace Q\Schema;
/**
 * StringSchema
 *
 * @author rhavin
 */
class StringSchema extends Schema {
	public function __construct($id = null) {
		parent::__construct($id, 'string');
	}
}