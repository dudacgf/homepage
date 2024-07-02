<?php

/**
 * void debug ( mixed Var [, bool Exit] )
 *
 * Carlos Reche
 * Jan 14, 2006
 */
if (!function_exists("debug")) {
	function debug($var, $exit = false) {
		if (isset($_REQUEST['debug']) && $_REQUEST['debug'] == 'sim')
		{
			echo '<div><table><tr><td>';
			echo "\n<code>";
			if (is_array($var) || is_object($var)) {
				echo htmlentities(print_r($var, true));
			} elseif (is_string($var)) {
				echo "string(" . strlen($var) . ") \"" . htmlentities($var) . "\"\n";
			} else {
				var_dump($var);
			}
			echo "</code>";
			echo "</td></tr></table></div>";
			if ($exit) {
				exit;
			}
		}
	}
}

/**
 * void dump ( mixed Var [, bool Exit] )
 *
 * Carlos Reche
 * Jan 14, 2006
 */
if (!function_exists("dump")) {
	function dump($var, $exit = false) {
		if (isset($_REQUEST['debug']) && $_REQUEST['debug'] == 'sim')
		{
			echo '<div><table><tr><td>';
			echo "\n<code>";
			echo str_replace(
				array('(', '[', ','), 
				array('(<br />&nbsp;', '<br />&nbsp;[', ',<br />&nbsp;') , 
				htmlentities(var_export($var, true))) ;
			echo "</code>";
			echo "</td></tr></table></div>";
			if ($exit) {
				exit;
			}
		}
	}
}

//-- vi: set tabstop=4  shiftwidth=4 showmatch:

?>

