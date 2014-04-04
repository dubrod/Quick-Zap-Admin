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

use Zap\Core\Driver\Mysql;
use Zap\Zap;

/**
 * Zap Connection class
 *
 * @author Shay Anderson 07.13
 */
class Connection
{
	/**
	 * Connection configuration settings
	 *
	 * @var array ([
	 *		database,
	 *		host,
	 *		password,
	 *		port,
	 *		username
	 *	])
	 */
	private $__conf;

	/**
	 * Connection driver object
	 *
	 * @var \Zap\Core\Driver
	 */
	private $__driver;

	/**
	 * Connection ID
	 *
	 * @var mixed
	 */
	private $__id;

	/**
	 * Init
	 *
	 * @param mixed $id
	 * @param array $conf (connection params/configuration)
	 */
	public function __construct($id, array &$conf)
	{
		$this->__id = $id;
		$this->__conf = &$conf;
		$this->__driver = new Mysql($this);

		// validate connection params
		if(\strlen($this->getHost()) < 1)
		{
			Zap::error('Empty host configuration value (connections.'
				. $this->getId() . '.host)', $this->getId(), \E_USER_WARNING);
		}

		if(\strlen($this->getDatabase()) < 1)
		{
			Zap::error('Empty database (database name) configuration value '
				. '(connections.' . $this->getId() . '.database)', $this->getId(),
				\E_USER_WARNING);
		}

		if(\strlen($this->getUsername()) < 1)
		{
			Zap::error('Empty username configuration value (connections.'
				. $this->getId() . '.username)', $this->getId(), \E_USER_WARNING);
		}

		if(\strlen($this->getPassword()) < 1)
		{
			Zap::error('Empty password configuration value (connections.'
				. $this->getId() . '.password)', $this->getId(), \E_USER_WARNING);
		}
	}

	/**
	 * Get configuration setting getter
	 *
	 * @param string $name
	 * @return mixed
	 */
	private function __getConf($name)
	{
		if(isset($this->__conf[$name]))
		{
			return $this->__conf[$name];
		}

		return NULL;
	}

	/**
	 * Database name getter
	 *
	 * @return string
	 */
	public function getDatabase()
	{
		return $this->__getConf('database');
	}

	/**
	 * Driver getter
	 *
	 * @return \Zap\Core\Driver
	 */
	public function &getDriver()
	{
		return $this->__driver;
	}

	/**
	 * Host name getter
	 *
	 * @return string
	 */
	public function getHost()
	{
		return $this->__getConf('host');
	}

	/**
	 * ID getter
	 *
	 * @return mixed
	 */
	public function getId()
	{
		return $this->__id;
	}

	/**
	 * Password getter
	 *
	 * @return mixed
	 */
	public function getPassword()
	{
		return $this->__getConf('password');
	}

	/**
	 * Port getter
	 *
	 * @return int
	 */
	public function getPort()
	{
		return (int)$this->__getConf('port');
	}

	/**
	 * Username getter
	 *
	 * @return string
	 */
	public function getUsername()
	{
		return $this->__getConf('username');
	}

	/**
	 * Unset password property (for security)
	 *
	 * @return void
	 */
	public function unsetPassword()
	{
		if(isset($this->__conf['password']))
		{
			unset($this->__conf['password']);
		}
	}
}