<?php
namespace Q\Restricted;
/**
 * Abstract class using the trait restrictor to implement the IRestrictor
 * interface. This is a workaround to PHP not allowing for multiple
 * inheritance.
 *
 * @author rhavin
 */
abstract class Base implements Restrictor
{
	use Restricted;
}
