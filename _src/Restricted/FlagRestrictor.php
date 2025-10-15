<?php
namespace Q\Restricted;

/**
 * Simple Restrictor based on flags.
 *
 * @author rhavin
 */
class FlagRestrictor implements Restrictor
{
	private int $my_flags = 0;
	public function __construct(int $flags = 0)
    {
		$this->my_flags = $flags;
	}
	/**
	 * Checks if the current user is denied to create a new entry or method.
	 * 
	 * @return bool true if denied, otherwise false.
	 */
	public function isDeniedCreate(): bool
    {
		return (($this->my_flags & Restrictor::DENY_CREATE) > 0);
	}
	/**
	 * Checks if the current user is denied to delete an existing entry or method.
	 * 
	 * @return bool true if denied, otherwise false.
	 */
	public function isDeniedDelete(): bool
    {
		return (($this->my_flags & Restrictor::DENY_DELETE) > 0);
	}
	/**
	 * Checks if the current user is denied to read an entry.
	 * 
	 * @return bool true if denied, otherwise false.
	 */
	public function isDeniedRead(): bool
    {
		return (($this->my_flags & Restrictor::DENY_READ) > 0);
	}
	/**
	 * Checks if the current user is denied to write an entry.
	 * 
	 * @return bool true if denied, otherwise false.
	 */
	public function isDeniedWrite(): bool
    {
		return (($this->my_flags & Restrictor::DENY_WRITE) > 0);
	}
	/**
	 * Checks if the current user is denied to change the restrictor.
	 * 
	 * @return bool true if denied, otherwise false.
	 */
	public function isDeniedRestrictor(): bool
    {
		return (($this->my_flags & Restrictor::DENY_RESTRICT) > 0);
	}
	/**
	 * Checks if the current user is denied to do the requested access.
	 * 
	 * @return bool true if denied, otherwise false.
	 */
	public function isDenied(mixed $access): bool
    {
		return (($this->my_flags & $access) > 0);
	}
	/**
	 * Sets the access to a new value if the current user is allowed
	 * to do so.
	 * 
	 * @return bool true if successful, otherwise false.
	 */
	public function setAccess(mixed $access) : bool
    {
		if ($this->my_flags & Restrictor::DENY_RESTRICT)
			return false;
		$this->my_flags = $access;
		return true;
	}
	/**
	 * Allows the access if the current user is allowed to change it.
	 * 
	 * @return bool true if successful, otherwise false.
	 */
	public function allowAccess(mixed  $access) : bool
    {
		if ($this->my_flags & Restrictor::DENY_RESTRICT)
			return false;
		$this->my_flags &= ~$access;
		return true;
	}	/**
	 * Denies the access if the current user is allowed to change it.
	 * 
	 * @return bool true if successful, otherwise false.
	 */
	public function denyAccess(mixed $access) : bool
    {
		if ($this->my_flags & Restrictor::DENY_RESTRICT)
			return false;
		$this->my_flags |= $access;
		return true;
	}
	/**
	 * Get the current access settings.
	 * 
	 * @return bool true if successful, otherwise false.
	 */
	public function getAccess() : mixed
    {
		return $this->my_flags;
	}
}
