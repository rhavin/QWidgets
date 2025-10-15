<?php
namespace Q\Schema;

/**
 * A Schema describes a Node. This is the Baseclass for more dedicated child
 * type schemata.
 * 
 * @author .rhavin;)
 */
abstract class Schema {
	private \Q\Restricted\Map $my_map;
	/**
	 * Constructor.
	 *  
	 * @param string|null $id Schema's Identifier or null for anonymous schemata.
	 * @param string $type The type of the schema node.
	 */
	public function __construct(?string $id = null, string $type = '') {
		$this->my_map = new \Q\Restricted\Map();
		$this->my_map['id']   = $id;
		$this->my_map['type'] = $type;
		$this->my_map->lockKeys();
	}
	/**
	 * The type of the schema node.
	 * 
	 * @return string type.
	 */
	public final function getType() : string {
		return $this->my_map['type'];
	}
	/**
	 * The identifier of the schema. Might be null for anonymous schemata.
	 * 
	 * @return string|null
	 */
	public final function getID() : ?string {
		return $this->my_map['id'];
	}
	/**
	 * Sets the ID of the schema. Might be null for anonymous schemata.
	 * Subclasses might deny a change of the schema's ID. This is syntactic
	 * sugar for the setAttribute('id',...) function and therefore final.
	 * 
	 * @param string|null $id Schema's Identifier or null for anonymous schemata.
	 * 
	 * @return bool true if successful, otherwise false.
	 */
	public final function setID(?string $id) : bool {
		return $this->setAttribute('id', $id);
	}
	/**
	 * Sets any attribute to the provided value. Calls onSetAttribute to allow
	 * child-classes to check for permission.
	 * 
	 * @param string $attribute The attribute to set.
	 * @param mixed $value The value of the attribute. Might be anything or null.
	 * 
	 * @return bool true if successful, otherwise false.
	 */
	public final function setAttribute(string $attribute, mixed $value) : bool {
		// type cant be changed once initialized
		if ($attribute == 'type')
			return false;
		$this->my_map[$attribute] = $value;
		return true;
	}
	/**
	 * Gets the value of a schema-nodes attribute. If that attribute
	 * either does not exist or is not accessible, null is returned.
	 * 
	 * @param string $attribute The attribute to set.
	 * 
	 * @return mixed $value The value of the attribute. Might be anything or null.
	 */
	public function getAttribute(string $attribute) : mixed {
		return $this->my_map[$attribute];
	}
}