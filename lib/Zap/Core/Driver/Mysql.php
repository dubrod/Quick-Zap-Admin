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
namespace Zap\Core\Driver;

use Zap\Core\Recordset;
use Zap\Zap;

/**
 * Zap Driver Mysql class
 *
 * @author Shay Anderson 07.13
 */
class Mysql extends \Zap\Core\Driver
{
	/**
	 * Mysqli object
	 *
	 * @var \mysqli
	 */
	private $__db;

	/**
	 * Open database connection
	 *
	 * @return boolean (true when connection successful)
	 */
	protected function _connect()
	{
		if(!$this->_isConnected(false))
		{
			if(\function_exists('mysqli_connect'))
			{
				$this->__db = @new \mysqli(
						$this->_getConnection()->getHost(),
						$this->_getConnection()->getUsername(),
						$this->_getConnection()->getPassword(),
						$this->_getConnection()->getDatabase(),
						$this->_getConnection()->getPort()
					);

				if(!$this->__db->connect_error) // success
				{
					return true;
				}

				// error detected
				Zap::error('Failed to connection to database "' . $this->_getConnection()->getDatabase()
					. '" using host "' . $this->_getConnection()->getHost() . '": "'
					. $this->__db->connect_error . '"', $this->_getConnection()->getId(), \E_USER_ERROR);
			}
			else
			{
				Zap::error('Failed to find the PHP mysqli extension',
					$this->_getConnection()->getId(), \E_USER_ERROR);
			}
		}

		return false; // connection failed
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
	public function &call($sp_name, $_ = NULL)
	{
		$params = \func_get_args();
		self::_setQueryAndArgs($sp_name, $params);

		\array_shift($params); // unset sp_name in args

		$params_str = '';

		if(\count($params) > 0)
		{
			foreach($params as &$v)
			{
				$v = \preg_match('/^@[\w]+$/', $v) // match @outparam (OUT param)
					? $v : $this->value($v);
			}

			$params_str = \implode(', ', $params);
		}

		return $this->query('CALL ' . $sp_name . '(' . $params_str . ')');
	}

	/**
	 * Close connection
	 *
	 * @return void
	 */
	public function close()
	{
		if($this->_isConnected(false))
		{
			$this->__db->close();
			$this->_connectionClosed();
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
	 */
	public function &delete($table, $args = '', $_ = NULL)
	{
		$args = \func_get_args();
		self::_setQueryAndArgs($table, $args);

		$table = ( !\preg_match('/^[\s]*?DELETE/i', $table) ? 'DELETE FROM ' : '' ) . $table;

		$query = '';

		if(\count($args) == 1) // table name only, DELETE FROM [tablename]
		{
			$query = $this->safe($table) . ';';
		}
		else if(isset($args[1]) && \is_array($args[1])) // table and array args
		{
			$query = $this->safe($table) . ' WHERE ' . \implode(' AND ', $this->value($args[1])) . ';';
		}
		else if(\count($args) > 1) // [0] is query, ex: '
		{
			$query = ( !\preg_match('/^[\s]*?DELETE/i', $args[0]) ? 'DELETE FROM ' : '' ) . $args[0];

			$query = $this->_formatQuerySafeValues($query, $args);
		}

		return $this->query($query);
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
	 */
	public function &insert($table, $fields_and_values = [], $_ = NULL)
	{
		$args = \func_get_args();
		self::_setQueryAndArgs($table, $args);

		$table = ( !\preg_match('/^[\s]*?INSERT/i', $table) ? 'INSERT INTO ' : '' ) . $table;

		$query = '';

		if(count($args) > 1) // must have args for insert fields/values
		{
			if(isset($args[1]) && \is_array($args[1])) // field and values
			{
				$values = [];

				foreach($args[1] as $v)
				{
					$values[] = $this->value($v);
				}

				$query = $table . '(' . \implode(', ', \array_keys($args[1])) . ') VALUES('
					. \implode(', ', $values) . ');';
			}
			else // query safe values
			{
				$query = $this->_formatQuerySafeValues($table, $args);
			}

			return $this->query($query);
		}

		// no field/values for insert
		$rs = &$this->_getNewRecordset();
		$rs->error = 'Invalid field/values for insert() method';
		return $rs;
	}

	/**
	 * Ping DB server
	 *
	 * @return boolean (true when ping successful)
	 */
	public function ping()
	{
		if($this->_isConnected())
		{
			if($this->__db->ping())
			{
				Zap::log('Ping successful', $this->_getConnection()->getId());

				return true;
			}
			else
			{
				Zap::error('Ping failed', $this->_getConnection()->getId(), \E_USER_WARNING);
			}
		}

		return false;
	}

	/**
	 * Execute single query
	 *
	 * @param string $query
	 * @return \Zap\Core\Recordset
	 */
	public function &query($query)
	{
		if(!\preg_match('/[\s]*?;/', $query)) // query close semicolon needed?
		{
			$query = trim($query) . ';';
		}

		$rs = &$this->_getNewRecordset();

		if($this->_isConnected())
		{
			Zap::log('Executing query "' . self::_formatQueryForLog($query) . '"',
				$this->_getConnection()->getId());

			$rs->query = $query;

			$r = $this->__db->query($query);

			if($r !== false && $r instanceof \mysqli_result)
			{
				if($r->num_rows > 0)
				{
					$rs->has_rows = true;
					$rs->rows = $r->num_rows;

					while($v = $r->fetch_assoc())
					{
						$rs->addRecord($v);

						// auto set row count (only auto set if one count record)
						if(isset($v[self::AUTO_ROW_COUNT_FIELD_ALIAS]) && $r->num_rows === 1)
						{
							$rs->count = (int)$v[self::AUTO_ROW_COUNT_FIELD_ALIAS];
						}
					}

					$r->close();

					if($this->__db->more_results()) // prepare next result
					{
						$this->__db->next_result();
					}
				}
			}
			else if($r === false) // mysqli error
			{
				$rs->error = $this->__db->error ? $this->__db->error : 'Unknown error';

				Zap::log('Query error: "' . $rs->error . '"', $this->_getConnection()->getId());
			}
			else // non-result type (delete, insert, update, [...])
			{
				$rs->affected = (int)$this->__db->affected_rows;

				if($rs->affected > 0)
				{
					$rs->is_affected_rows = true; // flag affected rows
				}
			}
		}

		return $rs;
	}

	/**
	 * Format value for safe DB usage in query
	 *
	 * @param mixed $value
	 * @return string
	 */
	public function safe($value = NULL)
	{
		if($this->_isConnected())
		{
			return $this->__db->real_escape_string($value);
		}

		return '';
	}

	/**
	 * Execute select query
	 *
	 * @param string $query
	 * @param mixed $_ (optional values to make safe in query,
	 *		ex: ('SELECT ... WHERE id = {$1}', $id))
	 * @return \Zap\Core\Recordset
	 */
	public function &select($query, $_ = NULL)
	{
		$args = \func_get_args();
		self::_setQueryAndArgs($query, $args);

		if(\strpos($query, ' ') === false) // table name only, SELECT * FROM [tablename]
		{
			$query = 'SELECT * FROM ' . $this->safe($query) . ';';
		}

		if(!\preg_match('/^[\s]*?SELECT/i', $query)) // add SELECT keyword
		{
			$query = 'SELECT ' . $query;
		}

		$query = $this->_formatQuerySafeValues($query, $args);

		return $this->query($query);
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
	 */
	public function &update($table, $fields_and_values = '', $args = '', $_ = NULL)
	{
		$args = \func_get_args();
		self::_setQueryAndArgs($table, $args);

		$table = ( !\preg_match('/^[\s]*?UPDATE/i', $table) ? 'UPDATE ' : '' ) . $table . ' ';

		$query = '';

		if(count($args) > 1) // must have args for update fields/values/args
		{
			if(isset($args[1]) && \is_array($args[1]))
			{
				$query = $table . 'SET ' . implode(', ', $this->value($args[1]))
					. ( isset($args[2]) && \is_array($args[2]) ? ' WHERE '
						. \implode(' AND ', $this->value($args[2])) : '' ) . ';';
			}
			else // query safe values
			{
				$query = $this->_formatQuerySafeValues($table, $args);
			}

			return $this->query($query);
		}

		// no field/values/args for update
		$rs = &$this->_getNewRecordset();
		$rs->error = 'Invalid field/values/arguments for update() method';
		return $rs;
	}
}