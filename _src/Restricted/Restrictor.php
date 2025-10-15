<?php
namespace Q\Restricted;
/**
 * Restrictor. Default implementation allows anything.
 *
 * @author rhavin
 */
interface Restrictor
{
	final const DENY_WRITE    = 0x0001;
	final const DENY_READ     = 0x0002;
	final const DENY_CREATE   = 0x0004;
	final const DENY_DELETE   = 0x0008;
	final const DENY_RESTRICT = 0x0010;
	/**
	 * Checks if the current user is denied to create a new entry or method.
	 * 
	 * @return bool true if denied, otherwise false.
	 */
	public function isDeniedCreate() : bool;
	/**
	 * Checks if the current user is denied to delete an existing entry or method.
	 * 
	 * @return bool true if denied, otherwise false.
	 */
	public function isDeniedDelete() : bool;
	/**
	 * Checks if the current user is denied to read an entry.
	 * 
	 * @return bool true if denied, otherwise false.
	 */
	public function isDeniedRead() : bool;
	/**
	 * Checks if the current user is denied to write an entry.
	 * 
	 * @return bool true if denied, otherwise false.
	 */
	public function isDeniedWrite() : bool;
	/**
	 * Checks if the current user is denied to change the restrictor.
	 * 
	 * @return bool true if denied, otherwise false.
	 */
	public function isDeniedRestrictor() : bool;
	/**
	 * Checks if the current user is denied to do the requested access.
	 * 
	 * @return bool true if denied, otherwise false.
	 */
	public function isDenied(mixed $access) : bool;
	/**
	 * Sets the access to a new value if the current user is allowed
	 * to do so.
	 * 
	 * @return bool true if successful, otherwise false.
	 */
	public function setAccess(mixed $access) : bool;
	/**
	 * Allows the access if the current user is allowed to change it.
	 * 
	 * @return bool true if successful, otherwise false.
	 */
	public function allowAccess(mixed $access) : bool;
	/**
	 * Denies the access if the current user is allowed to change it.
	 * 
	 * @return bool true if successful, otherwise false.
	 */
	public function denyAccess(mixed $access) : bool;
}
