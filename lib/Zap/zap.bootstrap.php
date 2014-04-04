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

/**
 * ==============================================================================
 * Environment settings
 * ==============================================================================
 * set Zap library path, include trailing slash '/'
 */

//REMOTE
//const LIB_PATH = '/var/www/html/cs1cloud.internal/lib/Zap/';

//LOCAL
const LIB_PATH = 'lib/Zap/';

/**
 * ==============================================================================
 * Class loading
 * ==============================================================================
 * set class loading for Zap library
 */
require \Zap\LIB_PATH . 'Zap.php';

\Zap\Zap::load([
	'Core/Connection.php',
	'Core/Driver.php',
	'Core/Driver/Mysql.php',
	'Core/Record.php',
	'Core/Recordset.php'
]);

/**
 * ==============================================================================
 * Zap configuration settings
 * ==============================================================================
 * load Zap configuration settings file
 */
Zap::confRegister(require \Zap\LIB_PATH . 'zap.conf.php');

/**
 * ==============================================================================
 * Zap class shorthand
 * ==============================================================================
 * load Zap shorthand class 'z'
 */
if(Zap::conf('core.use_shorthand_class'))
{
	\Zap\Zap::load('Core/z.php');
}