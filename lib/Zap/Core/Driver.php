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

use Zap\Core\Recordset;
use Zap\Zap;

/**
 * Zap Driver class
 *
 * @author Shay Anderson 07.13
 */
abstract class Driver
{
	/**
	 * Query field alias for auto setting \Zap\Core\Recordset->count property
	 */
	const AUTO_ROW_COUNT_FIELD_ALIAS = 'zap_count';

	/**
	 * Connection object
	 *
	 * @var \Zap\Core\Connection
	 */
	private $__connection;

	/**
	 * Active connection flag
	 *
	 * @var boolean
	 */
	private $__is_connected = false;

	/**
	 * Init
	 *
	 * @param \Zap\Core\Connection $connection
	 */
	final public function __construct(\Zap\Core\Connection &$connection)
	{
		$this->__connection = &$connection;
	}

	/**
	 * Finalize connections
	 */
	final public function __destruct()
	{
		Zap::finalize();
	}

	/**
	 * Execute database connection
	 *
	 * @return boolean (true when connection successful)
	 */
	abstract protected function _connect();

	/**
	 * Flag connection closed
	 *
	 * @return void
	 */
	final protected function _connectionClosed()
	{
		$this->__is_connected = false;
		Zap::log('connection closed', $this->_getConnection()->getId());
	}

	/**
	 * Format query for log message (strip newlines, whitespaces, truncate)
	 *
	 * @param string $query
	 * @return string
	 */
	final protected static function _formatQueryForLog($query)
	{
		return \preg_replace('/\s+/', ' ', \str_replace(["\r\n", "\n", "\r", "\t"], ' ', $query));
	}

	/**
	 * Format query safe values (ex: ("SELECT * FROM table WHERE x = {$1}", "value") =>
	 *		"SELECT * FROM table WHERE x = 'value'")
	 *
	 * @param string $query
	 * @param array $args
	 * @return string (query with safe values)
	 */
	final protected function _formatQuerySafeValues($query, array $args = [])
	{
		if(\count($args) > 1) // first element is query ($args[0])
		{
			// check for values to make safe
			if(\preg_match_all('/{\$([0-9])}/', $query, $m)) // match ...{$1}...{$2}]...
			{
				foreach($m[0] as $k => $v) // k => '{$[n]}'
				{
					if(isset($m[1][$k]) && isset($args[(int)$m[1][$k]])
						&& !\is_array($args[(int)$m[1][$k]]))
					{
						$query = \str_replace($v, $this->value($args[(int)$m[1][$k]]), $query);
					}
				}
			}
		}

		return $query;
	}

	/**
	 * Connection object getter
	 *
	 * @return \Zap\Core\Connection
	 */
	final protected function &_getConnection()
	{
		return $this->__connection;
	}

	/**
	 * New Recordset object getter
	 *
	 * @return \Zap\Core\Recordset
	 */
	final protected function &_getNewRecordset()
	{
		$rs = new Recordset;
		$rs->connection_id = $this->_getConnection()->getId();
		return $rs;
	}

	/**
	 * Connected flag getter (will attempt auto connect if $auto_connect true)
	 *
	 * @param boolean $auto_connect (false will only check if connected, not force connect attempt)
	 * @return boolean (true when connected)
	 */
	final protected function _isConnected($auto_connect = true)
	{
		if(!$this->__is_connected && $auto_connect)
		{
			$this->__is_connected = $this->_connect();

			$this->_getConnection()->unsetPassword(); // expire password

			if($this->__is_connected)
			{
				Zap::log('connection opened', $this->_getConnection()->getId());
			}
		}

		return $this->__is_connected;
	}

	/**
	 * Query and args setter for query methods
	 *
	 * @param array|string $query
	 * @param array $func_args
	 * @return void
	 */
	final protected static function _setQueryAndArgs(&$query, array &$func_args)
	{
		if(is_array($query)) // [0 => 'query', (optional values)]
		{
			$func_args = $query;
			$query = $query[0];
		}
	}

	/**
	 * Call stored procedure (or stored function)
	 *
	 * @param string $sp_name
	 * @param mixed $_ (optional values to make safe in query,
	 *		ex: ('sp_name', 'value_1', 'value_2') => "CALL sp_name('value_1', 'value_2')",
	 *		OUT param ex: ('sp_name', 'value_1', '@outparam') => "CALL sp_name('value_2', @outparam))
	 * @return \Zap\Core\Recordset
	 */
	abstract public function &call($sp_name, $_ = NULL);

	/**
	 * Close connection
	 *
	 * @return void
	 */
	abstract public function close();

	/**
	 * Execute delete query
	 *
	 * @param string $table (table name)
	 * @param array|string $args (array ex: ['id' => 5] => "DELETE FROM table WHERE id = '5'")
	 * @param mixed $_ (optional values to make safe in query,
	 *		ex: ('DELETE ... WHERE id = {$1}', $id))
	 * @return \Zap\Core\Recordset
	 */
	abstract public function &delete($table, $args = '', $_ = NULL);

	/**
	 * Execute insert query
	 *
	 * @param string $table
	 * @param array|string $fields_and_values
	 *		(array ex: ['id' => 5] => "INSERT INTO table (id) VALUES('5')")
	 * @param mixed $_ (optional values to make safe in query,
	 *		ex: ('INSERT INTO ... (field) VALUES({$1})', $id))
	 * @return \Zap\Core\Recordset
	 */
	abstract public function &insert($table, $fields_and_values = [], $_ = NULL);

	/**
	 * Ping DB server
	 *
	 * @return boolean (true when ping successful)
	 */
	abstract public function ping();

	/**
	 * Execute single query
	 *
	 * @param string $query
	 * @return \Zap\Core\Recordset
	 */
	abstract public function &query($query);

	/**
	 * Format value for safe DB usage in query
	 *
	 * @param mixed $value
	 * @return string
	 */
	abstract public function safe($value = NULL);

	/**
	 * Execute select query
	 *
	 * @param string $query
	 * @param mixed $_ (optional values to make safe in query,
	 *		ex: ('SELECT ... WHERE id = {$1}', $id))
	 * @return \Zap\Core\Recordset
	 */
	abstract public function &select($query, $_ = NULL);

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
	 */
	abstract public function &update($table, $fields_and_values = '', $args = '', $_ = NULL);

	/**
	 * Prepare safe values for query (ex: "test's" => "'test\'s'")
	 *
	 * @param array|string $value (array for multiple values, ex: ['field_1' => 'value_1',
	 *		'f2' => 'v2', ...] => ["field_1 = 'field_1 = 'value1'", "f2 = 'v2'", ...')
	 * @return array|string (array for multiple values)
	 */
	final public function value($value)
	{
		if($this->_isConnected())
		{
			if(!\is_array($value))
			{
				return '\'' . $this->safe($value) . '\'';
			}
			else // multiple values
			{
				foreach($value as $k => &$v)
				{
					$v = $k . ' = ' . $this->value($v);
				}

				return $value;
			}
		}

		return !\is_array($value) ? '' : [];
	}
}