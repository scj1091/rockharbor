<?php

/**
 * If true, all plugins will be subject to debugging as well. Beware of poorly
 * written plugins breaking the site!
 */
define('WP_SUPER_DEBUG', false);

/**
 * Short version of DIRECTORY_SEPARATOR
 */
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

/**
 * Quick access to debugging
 *
 * @param string $var Var to debug
 */
function debug($var = null) {
	if (!WP_DEBUG) {
		return null;
	}
	$traceout = '';
	$traced = debug_backtrace();
	// remove debug function from trace
	$debug = array_shift($traced);
	$traced[0]['line'] = $debug['line'];
	$traced[0]['file'] = $debug['file'];
	if ($traced[0]['function'] == 'customErrorHandler') {
		array_shift($traced);
	}
	// passable list of wp functions
	$passable = array('_deprecated_argument');
	if ($traced[0]['function'] == 'trigger_error' && in_array($traced[1]['function'], $passable)) {
		return null;
	}
	foreach ($traced as $trace) {
		$trace = array_merge(array(
			'line' => '??',
			'file' => '??'
		), $trace);
		$traceout .= '<div style="margin-bottom: 5px"><strong>Line '.$trace['line'].'</strong> : '.$trace['file'];
		$args = array();
		if (isset($trace['args'])) {
			foreach ($trace['args'] as $arg) {
				$args[] = print_r($arg, true);
			}
		}
		if (!empty($args)) {
			$traceout .= "<br /><a href=\"javascript:;\"";
			$traceout .= "onclick=\"var span = this.parentNode.getElementsByTagName('span')[0]; span.style.display = span.style.display == 'none' ? 'block' : 'none'";
			$traceout .= "\">[args]</a>&nbsp;";
			$traceout .= $trace['function'].'(';
			$traceout .= "<span style=\"display:none;padding: 5px;background:#EFEFEF\">".implode(', ', $args).'</span>)';
			$traceout .= '</div>';
		} elseif ($trace['function'] !== 'none') {
			$traceout .= $trace['function'].'()';
		}
	}
	if (!is_null($var) && !is_string($var)) {
		$var = print_r($var, true);
	}
	$out  = "<pre style=\"background:#FFE25F\">";
	$out .= '<span style="margin-bottom: 5px"><strong>Line '.$debug['line'].'</strong> : '.$debug['file'].'</span>';
	$out .= '<br />';
	$out .= "<div>$var</div>";
	$out .= "<a href=\"javascript:;\"";
	$out .= "onclick=\"var div = this.parentNode.getElementsByTagName('div')[1]; div.style.display = div.style.display == 'none' ? 'block' : 'none'";
	$out .= "\">[Show trace]</a>";
	$out .= "<div style=\"display:none;padding: 5px; background: #DFDFDF;\">$traceout</div>";
	$out .= "</pre>";
	echo $out;
}

/**
 * Custom error handler, to ignore plugin errors that break the site when in
 * debug ><
 *
 * @param string $errno
 * @param string $errstr
 * @param string $errfile
 * @param string $errline
 * @return boolean
 */
function customErrorHandler($errno, $errstr, $errfile, $errline) {
	$show = false;
	if (defined('WP_SUPER_DEBUG') && WP_SUPER_DEBUG == true) {
		$show = true;
	}
	if (stripos($errfile, 'plugins'.DS) === false) {
		$show = true;
	}
	$passable = array(E_RECOVERABLE_ERROR, E_STRICT, E_DEPRECATED);
	if ($show && !in_array($errno, $passable)) {
		debug($errstr);
	}
	return true;
}
set_error_handler('customErrorHandler');
error_reporting(E_ALL | E_STRICT);