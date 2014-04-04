<?php
/**
 * Zap - Lightning Fast Abstract Database Layer for PHP 5.4+
 * 
 * @package Zap
 * @version 1.0.r8.beta - Jul 19, 2013
 * @author Shay Anderson <http://www.shayanderson.com/contact-shay-anderson.htm>
 * @copyright 2013 Shay Anderson <http://www.shayanderson.com>
 * @license MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @link <http://www.shayanderson.com/projects/zap.htm>
 */
namespace Zap\Core;

use Zap\Zap;

/**
 * Zap Record class
 *
 * @author Shay Anderson 07.13
 */
class Record
{
	/**
	 * Connection ID
	 *
	 * @var mixed
	 */
	public $zap_connection_id;

	/**
	 * Init record
	 *
	 * @param array $properties
	 * @param mixed $connection_id
	 */
	public function __construct(array $properties, $connection_id)
	{
		foreach($properties as $k => $v)
		{
			$this->{$k} = $v;
		}

		$this->zap_connection_id = $connection_id;
	}

	/**
	 * Record field value getter
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		if(\property_exists($this, $name))
		{
			return $this->{$name};
		}
		else
		{
			if(Zap::conf('core.record_field_not_found_error_level') > 0)
			{
				Zap::error('Failed to get record field "' . $name . '" (field not found)',
					$this->zap_connection_id, Zap::conf('core.record_field_not_found_error_level'));
			}
		}
	}

	/**
	 * Print record array
	 *
	 * @return void
	 */
	public function display()
	{
		Zap::pa($this);
	}
}