<?php
namespace Q\Restricted;
/**
 * TRstricted trait implements default restrictor usage as
 * Interface IRestricted.
 * 
 * @author rhavin
 */
trait Restricted
{
	/**
	 * @var The Restrictor providing access information.
	 */
	private ?Restrictor $_restrictor = null;
	/**
	 * Constructor. Without a given Restrictor, full access is granted.
	 * @param ?Restrictor $restrictor
	 */
	protected function setRestrictor(?Restrictor $restrictor = null) : bool
	{
		if ($this->isDeniedRestrictor())
			return false;
		$this->_restrictor = $restrictor;
		return true;
	}
	protected function getRestrictor() : ?Restrictor
	{
		return $this->_restrictor;
	}
	/**
	 * Checks if the current user is allowed to create a new entry or
	 * method inside this object. Syntactic sugar for the isDenied() method.
	 * 
	 * @return bool false if allowed, otherwise true.
	 */
	public function isDeniedCreate(): bool
	{
		return $this->isDenied(Restrictor::DENY_CREATE);
	}
	/**
	 * Checks if the current user might delete an existing entry or method.
	 * Syntactic sugar for the isDenied() method.
	 * 
	 * @return bool false if allowed, otherwise true.
	 */
	public function isDeniedDelete(): bool
	{
		return $this->isDenied(Restrictor::DENY_DELETE);
	}
	/**
	 * Checks if the current user is allowed to read an entry.
	 * Syntactic sugar for the isDenied() method.
	 * 
	 * @return bool false if allowed, otherwise true.
	 */
	public function isDeniedRead(): bool
	{
		return $this->isDenied(Restrictor::DENY_READ);
	}
	/**
	 * Checks if the current user is allowed to write an entry.
	 * Syntactic sugar for the isDenied() method.
	 * 
	 * @return bool false if allowed, otherwise true.
	 */
	public function isDeniedWrite(): bool
	{
		return $this->isDenied(Restrictor::DENY_WRITE);
	}
	/**
	 * Checks if the current user is allowed to change the restrictor.
	 * Syntactic sugar for the isDenied() method.
	 * 
	 * @return bool false if allowed, otherwise true.
	 */
	public function isDeniedRestrictor(): bool
	{
		return $this->isDenied(Restrictor::DENY_RESTRICT);
	}
	/**
	 * Checks if the current user is allowed the provided access.
	 * 
	 * @param mixed $access access-type
	 * @return bool false if allowed, otherwise true.
	 */
	public function isDenied(mixed $access) : bool
	{
		if (is_null($this->_restrictor))
			return false;
		return $this->_restrictor->isDenied($access);
	}
	/**
	 * Directly set the access if the current user is allowed to do so.
	 * 
	 * @param mixed $access access-type
	 * @return bool false if allowed, otherwise true.
	 */
	public function setAccess(mixed $access) : bool
	{
		if (is_null($this->_restrictor))
			return false;
		return $this->_restrictor->setAccess($access);
	}
	/**
	 * Directly allow the given access if the current user is allowed to do so.
	 * 
	 * @param mixed $access access-type
	 * @return bool false if allowed, otherwise true.
	 */
	public function allowAccess(mixed $access) : bool
	{
		if (is_null($this->_restrictor))
			return false;
		return $this->_restrictor->allowAccess($access);
	}
	/**
	 * Directly deny the given access if the current user is allowed to do so.
	 * 
	 * @param mixed $access access-type
	 * @return bool false if allowed, otherwise true.
	 */
	public function denyAccess(mixed $access) : bool
	{
		if (is_null($this->_restrictor))
			return false;
		return $this->_restrictor->denyAccess($access);
	}
}