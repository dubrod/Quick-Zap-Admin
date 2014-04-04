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

use Zap\Core\Record;
use Zap\Zap;

/**
 * Zap Recordset class

 * @author Shay Anderson 07.13
 */
class Recordset
{
	/**
	 * Affected rows
	 *
	 * @var int
	 */
	public $affected = 0;

	/**
	 * Connection ID
	 *
	 * @var mixed
	 */
	public $connection_id;

	/**
	 * Count of rows (auto set when using query with 'zap_count' (case sensitive),
	 * MySQL ex: 'SELECT COUNT(*) AS zap_count FROM tablename')
	 *
	 * @var int
	 */
	public $count = 0;

	/**
	 * Error message if error occurs
	 *
	 * @var boolean|string (false when no error)
	 */
	public $error = false;

	/**
	 * Has rows flag
	 *
	 * @var boolean
	 */
	public $has_rows = false;

	/**
	 * Affected rows flag
	 *
	 * @var boolean
	 */
	public $is_affected = false;

	/**
	 * Query string
	 *
	 * @var string
	 */
	public $query;

	/**
	 * Array of records
	 *
	 * @var array (\Zap\Core\Record)
	 */
	public $records = [];

	/**
	 * Number of records in recordset
	 *
	 * @var int
	 */
	public $rows = 0;

	/**
	 * Add record to array of records and record objects
	 *
	 * @param array $record
	 * @return void
	 */
	public function addRecord(array &$record)
	{
		$this->records[] = new Record($record, $this->connection_id);
	}

	/**
	 * Display/print array of records
	 *
	 * @return void
	 */
	public function displayRecords()
	{
		Zap::pa($this->records);
	}

	/**
	 * Records as array (instead of \Zap\Core\Record objects) getter
	 *
	 * @return array
	 */
	public function &getRecordsAsArray()
	{
		$a = [];

		foreach($this->records as $v)
		{
			$a[] = (array)$v;
		}

		return $a;
	}
}