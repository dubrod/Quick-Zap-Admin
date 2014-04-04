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
namespace Zap;

use Zap\Core\Connection;

/**
 * Zap core class
 *
 * @author Shay Anderson 07.13
 */
class Zap
{
	/**
	 * Version
	 */
	const VER = '1.0.r8.beta';

	/**
	 * Active connection ID
	 *
	 * @var mixed (false when not set)
	 */
	private static $__active_connection_id = false;

	/**
	 * Global configuration
	 *
	 * @var array
	 */
	private static $__conf = [];

	/**
	 * Global connections
	 *
	 * @var array (\Zap\Core\Connection)
	 */
	private static $__connections = [];

	/**
	 * Default connection ID
	 *
	 * @var mixed (false when not set)
	 */
	private static $__connections_default_id = false;

	/**
	 * Event log
	 *
	 * @var array
	 */
	private static $__log = [];

	/**
	 * Active Recordset object
	 *
	 * @var \Zap\Core\Recordset
	 */
	private static $__recordset;

	/**
	 * Connection getter
	 *
	 * @param mixed $connection_id
	 * @return \Zap\Core\Connection (or false on error)
	 */
	private static function &__getConnection($connection_id = false)
	{
		$connection_id = $connection_id !== false ? $connection_id : self::$__active_connection_id;

		if(self::__isConnection($connection_id))
		{
			return self::$__connections[$connection_id];
		}

		$tmp = false;

		if(self::$__connections_default_id === false) // no connections set
		{
			self::error('Failed to get connection (no connections set, set connections in '
				. '"zap.conf.php" in the "connections" section)');
			return $tmp;
		}

		self::error('Failed to get connection "' . $connection_id . '" (connection not found)');
		return $tmp;
	}

	/**
	 * Error type string getter
	 *
	 * @param int $error_type
	 * @return string
	 */
	private static function __getErrorTypeString($error_type)
	{
		switch($error_type)
		{
			case \E_USER_ERROR:
				return 'Error';
				break;

			case \E_USER_NOTICE:
				return 'Notice';
				break;

			case \E_USER_WARNING:
				return 'Warning';
				break;

			default:
				return 'Unknown error';
				break;
		}
	}

	/**
	 * Memory usage getter (kb|mb)
	 *
	 * @return string
	 */
	private static function __getMemoryUsage()
	{
		$mem = \memory_get_usage() / 1024;
		return $mem < 1000 ? \round($mem, 2) . 'k' : \round($mem / 1024, 2) . 'm';
	}

	/**
	 * Check if connection ID exists in connections
	 *
	 * @param mixed $connection_id
	 * @return boolean
	 */
	private static function __isConnection($connection_id)
	{
		return isset(self::$__connections[$connection_id]);
	}

	/**
	 * Reset active connection ID
	 *
	 * @return void
	 */
	private static function __resetActiveConnectionId()
	{
		self::connection(self::$__connections_default_id); // reset to default connection ID
	}

	/**
	 * Call stored procedure (or stored function)
	 *
	 * @param string $sp_name
	 * @param mixed $_ (optional values to make safe in query,
	 *		ex: ('sp_name', 'value_1', 'value_2') => "CALL sp_name('value_1', 'value_2')",
	 *		OUT param ex: ('sp_name', 'value_1', '@outparam') => "CALL sp_name('value_2', @outparam))
	 * @return \Zap\Core\Recordset
	 *
	 * @example
	 *		// CALL sp_addUser();
	 *		z::call('sp_addUser');
	 *
	 *		// CALL sp_addUser('Mike Smith', @outvar);
	 *		z::call('sp_addUser', 'Mike Smith', '@outvar');
	 */
	public static function &call($sp_name, $_ = NULL)
	{
		self::$__recordset = &self::__getConnection()->getDriver()->call(\func_get_args());

		self::__resetActiveConnectionId();

		return self::$__recordset;
	}

	/**
	 * Configuration value getter
	 *
	 * @param string $name (ex: 'conf.conf_setting')
	 * @param boolean $warn_missing_key (trigger warning (E_USER_WARNING) with conf key missing)
	 * @return mixed
	 */
	public static function conf($name, $warn_missing_key = true)
	{
		$name_orig = $name;
		$name = \explode('.', $name);

		$conf = NULL;

		// find conf value in conf, ex: 'x.y.z' => returns $conf['x']['y']['z']
		for($i = self::$__conf; $k = \array_shift($name); $i = $i[$k])
		{
			if(!isset($i[$k]))
			{
				if($warn_missing_key)
				{
					self::error('Failed to find configuration key "'
						. $name_orig . '"', \E_USER_WARNING);
				}

				return NULL;
			}

			$conf = $i[$k];
		}

		return $conf;
	}

	/**
	 * Global configuration getter
	 *
	 * @return array
	 */
	public static function confGet()
	{
		return self::$__conf;
	}

	/**
	 * Register global configuration
	 *
	 * @param array $conf
	 * @return void
	 */
	public static function confRegister(array &$conf)
	{
		self::$__conf = &$conf;

		self::log('Registered configuration settings');

		if(isset($conf['connections'])) // register connections
		{
			foreach($conf['connections'] as $id => &$v)
			{
				if(!self::__isConnection($id)) // set connection
				{
					self::$__connections[$id] = new Connection($id, $v);
					self::log($id . ': registered connection');

					if(self::$__connections_default_id === false) // set default connection ID
					{
						self::$__connections_default_id = $id;
						self::log('default connection', $id);
						self::__resetActiveConnectionId();
					}
				}
			}
		}
	}

	/**
	 * Set active connection ID
	 *
	 * @param mixed $connection_id (false when using default connection ID)
	 * @return void
	 */
	public static function connection($connection_id = false)
	{
		if(self::__isConnection($connection_id))
		{
			if(self::$__active_connection_id != $connection_id)
			{
				self::$__active_connection_id = $connection_id;
				self::log('active connection', self::$__active_connection_id);
			}
		}
		else // error, connection does not exist
		{
			self::error('Failed to set active connection ID "' . $connection_id
				. '" (connection ID not found in connections)', $connection_id);
		}
	}

	/**
	 * Execute delete query
	 *
	 * @param string $table (table name)
	 * @param array|string $args (array ex: ['id' => 5] => "DELETE FROM table WHERE id = '5'")
	 * @param mixed $_ (optional values to make safe in query,
	 *		ex: ('DELETE ... WHERE id = {$1}', $id))
	 * @return \Zap\Core\Recordset
	 *
	 * @example
	 *		// DELETE FROM users WHERE id = '5' AND fullname = 'Mike Smith';
	 *		z::delete('users WHERE id = {$1} AND fullname = {$2}', $id, $fullname);
	 *
	 *		// DELETE FROM users WHERE id = '5';
	 *		z::delete('users', ['id' => 5]);
	 *
	 *		// DELETE FROM users WHERE id = '5' AND fullname = 'Mike Smith';
	 *		z::delete('users', ['id' => 5, 'fullname' => 'Mike Smith']);
	 *
	 *		// DELETE FROM users;
	 *		z::delete('users');
	 */
	public static function &delete($table, $args = '', $_ = NULL)
	{
		self::$__recordset = &self::__getConnection()->getDriver()->delete(\func_get_args());

		self::__resetActiveConnectionId();

		return self::$__recordset;
	}

	/**
	 * Trigger error
	 *
	 * @param string $error_message
	 * @param mixed $connection_id
	 * @param int $error_type (\E_USER_NOTICE, \E_USER_WARNING, \E_USER_ERROR)
	 * @return void
	 */
	public static function error($error_message, $connection_id = false, $error_type = \E_USER_ERROR)
	{
		self::log(self::__getErrorTypeString($error_type) . ': ' . $error_message, $connection_id);

		if($error_type == \E_USER_ERROR) // finalize when fatal error
		{
			self::finalize();
		}

		if(self::conf('core.error_details') && $error_type !== \E_USER_NOTICE) // add error details
		{
			$error_message .= ' <pre>' . \print_r(\debug_backtrace(), true) . '</pre>';
		}

		\trigger_error(( $connection_id !== false ? $connection_id . ': ' : '' ) . $error_message,
			$error_type);
	}

	/**
	 * Finalize connections
	 *
	 * @staticvar boolean $is_finalized
	 * @return void
	 */
	public static function finalize()
	{
		static $is_finalized = false;

		if(!$is_finalized)
		{
			foreach(self::$__connections as $c)
			{
				$c->getDriver()->close();
			}

			self::log('Zap finalized.');

			if(self::conf('core.log_dump'))
			{
				self::logDisplay();
			}

			$is_finalized = true;
		}
	}

	/**
	 * Execute insert query
	 *
	 * @param string $table
	 * @param array|string $fields_and_values
	 *		(array ex: ['id' => 5] => "INSERT INTO table (id) VALUES('5')")
	 * @param mixed $_ (optional values to make safe in query,
	 *		ex: ('INSERT INTO ... (field) VALUES({$1})', $id))
	 * @return \Zap\Core\Recordset
	 *
	 * @example
	 *		// INSERT INTO users(fullname, email) VALUES('Mike Smith', 'example@email.com');
	 *		z::insert('users', ['fullname' => 'Mike Smith', 'email' => 'example@email.com']);
	 *
	 *		// INSERT INTO users (fullname, email) VALUES('Mike Smith', 'example@email.com');
	 *		z::insert('users (fullname, email) VALUES({$1}, {$2})', $name, $email);
	 *
	 *		// INSERT IGNORE INTO users(fullname) VALUES('Mike Smith');
	 *		z::insert('INSERT IGNORE INTO users', ['fullname' => 'Mike Smith']);
	 */
	public static function &insert($table, $fields_and_values = [], $_ = NULL)
	{
		self::$__recordset = &self::__getConnection()->getDriver()->insert(\func_get_args());

		self::__resetActiveConnectionId();

		return self::$__recordset;
	}

	/**
	 * Load core library file
	 *
	 * @param array|string $file (will load multiple files when array of filenames)
	 * @return void
	 */
	public static function load($filename)
	{
		if(is_array($filename))
		{
			foreach($filename as $v)
			{
				self::load($v);
			}

			return;
		}

		$filename = \str_replace('/', \DIRECTORY_SEPARATOR, $filename);

		if(\strpos($filename, \Zap\LIB_PATH) === false)
		{
			$filename = \Zap\LIB_PATH . $filename;
		}

		require $filename;
	}

	/**
	 * Log event message
	 *
	 * @staticvar boolean $mt_start
	 * @param string $message
	 * @param mixed $connection_id
	 * @return void
	 */
	public static function log($message, $connection_id = false)
	{
		static $mt_start = false;

		if($mt_start === false)
		{
			$mt_start = \microtime(true);
			self::log('Zap initialized (v' . self::VER . ')');
		}

		$mt_diff = \round(\microtime(true) - $mt_start, 2);

		self::$__log[] = ( self::conf('core.log_memory_usage') ? '(' . self::__getMemoryUsage() . ') ' : '' )
			. ( $mt_diff > 0 ? '(+' . $mt_diff . ') ' : '' )
			. ( $connection_id !== false ? $connection_id . ': ' : '' ) . $message;
	}

	/**
	 * Event log getter
	 *
	 * @return array
	 */
	public static function logGet()
	{
		return self::$__log;
	}

	/**
	 * Display/print event log
	 *
	 * @return void
	 */
	public static function logDisplay()
	{
		self::pa(self::logGet());
	}

	/**
	 * Print array
	 *
	 * @param array $a
	 * @return void
	 */
	public static function pa($a)
	{
		echo '<pre>' . \print_r($a, true) . '</pre>';
	}

	/**
	 * Ping DB server
	 *
	 * @return boolean (true when ping successful)
	 *
	 * @example
	 *		if(z::ping())
	 *		{
	 *			// success
	 *		}
	 *		else
	 *		{
	 *			// failed
	 *		}
	 */
	public static function ping()
	{
		return self::__getConnection()->getDriver()->ping();
	}

	/**
	 * Execute single query
	 *
	 * @param string $query
	 * @return \Zap\Core\Recordset
	 *
	 * @example
	 *		// execute query
	 *		z::query('SELECT * FROM tablename;');
	 */
	public static function &query($query)
	{
		self::$__recordset = &self::__getConnection()->getDriver()->query($query);

		self::__resetActiveConnectionId();

		return self::$__recordset;
	}

	/**
	 * Active Recordset object getter
	 *
	 * @return \Zap\Core\Recordset
	 *
	 * @example
	 *		// execute query
	 *		z::query('SELECT * FROM tablename;');
	 *
	 *		// use recordset
	 *		if(z::recordset()->has_rows)
	 *		{
	 *			foreach(z::recordset()->records as $record)
	 *			{
	 *				echo 'field_1: ' . $record->field_1
	 *					. ', field_2: ' . $record->field_2 . '<br />';
	 *			}
	 *		}
	 *		else // no rows
	 *		{
	 *			if(z::recordset()->error)
	 *			{
	 *				echo 'Error: ' . z::recordset()->error;
	 *			}
	 *			else
	 *			{
	 *				echo 'No rows.';
	 *			}
	 *		}
	 */
	public static function &recordset()
	{
		if(empty(self::$__recordset))
		{
			self::error('Recordset has not been set (execute query before calling ' . __METHOD__ . '())');
		}

		return self::$__recordset;
	}

	/**
	 * Reset active Recordset object
	 *
	 * @return void
	 */
	public static function recordsetReset()
	{
		self::$__recordset = NULL;
	}

	/**
	 * Format value for safe DB usage in query
	 *
	 * @param mixed $value
	 * @return string
	 */
	public static function safe($value = NULL)
	{
		return self::__getConnection()->getDriver()->safe($value);
	}

	/**
	 * Execute select query
	 *
	 * @param string $query
	 * @param mixed $_ (optional values to make safe in query,
	 *		ex: ('SELECT ... WHERE id = {$1}', $id))
	 * @return \Zap\Core\Recordset
	 *
	 * @example
	 *		// SELECT * FROM tablename;
	 *		z::select('tablename');
	 *
	 *		// SELECT f1, f2 FROM tablename;
	 *		z::select('f1, f2 FROM tablename');
	 *
	 *		// SELECT * FROM tablename WHERE id = '5';
	 *		z::select('* FROM tablename WHERE id = {$1}', $id);
	 */
	public static function &select($query, $_ = NULL)
	{
		self::$__recordset = &self::__getConnection()->getDriver()->select(\func_get_args());

		self::__resetActiveConnectionId();

		return self::$__recordset;
	}

	/**
	 * Execute update query
	 *
	 * @param string $table (table name)
	 * @param array|string $fields_and_values
	 *		(array ex: ['field_1' => 10] => "UPDATE table SET field_1 = '10' ...")
	 * @param array|string $args (array ex: ['id' => 5] => "UPDATE table ... WHERE id = '5'")
	 * @param mixed $_ (optional values to make safe in query,
	 *		ex: ('UPDATE ... WHERE id = {$1}', $id))
	 * @return \Zap\Core\Recordset
	 *
	 * @example
	 *		// UPDATE users SET fullname = 'Mike Smith';
	 *		z::update('users', ['fullname' => 'Mike Smith']);
	 *
	 *		// UPDATE users SET fullname = 'Mike Smith', email = 'example@domain.com'
	 *		//		WHERE id = '44' AND is_active = '1';
	 *		z::update('users', ['fullname' => 'Mike Smith', 'email' => 'example@domain.com'],
	 *			['id' => 44, 'is_active' => 1]);
	 *
	 *		// UPDATE users SET fullname = 'Mike Smith', email = 'example@domain.com'
	 *		//		WHERE id = '44';
	 *		z::update('users SET fullname = {$1}, email = {$2} WHERE user_id = {$3}',
	 *			$fullname, $email, $id);
	 */
	public static function update($table, $fields_and_values = '', $args = '', $_ = NULL)
	{
		self::$__recordset = &self::__getConnection()->getDriver()->update(\func_get_args());

		self::__resetActiveConnectionId();

		return self::$__recordset;
	}
}