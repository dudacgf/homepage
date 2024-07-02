<?php
class cssparser {
	var $css;

	function cssparser() {
		// Register "destructor"
		register_shutdown_function(array(&$this, "finalize"));
		$this->Clear();
	}

	function finalize() {
		unset($this->css);
	}

	function Clear() {
		unset($this->css);
		$this->css = array();
	}

	function Add($key, $codestr) {
		$key = strtolower($key);
		$codestr = strtolower($codestr);
		if(!isset($this->css[$key])) {
			$this->css[$key] = array();
		}
		$codes = explode(";",$codestr);
		if(count($codes) > 0) {
			foreach($codes as $code) {
				$code = trim($code);
				if(strpos($code, ":") == TRUE) {
					list($codekey, $codevalue) = explode(":",$code,2);
					if(strlen($codekey) > 0) {
						$this->css[$key][trim($codekey)] = trim($codevalue);
					}
				}
			}
		}
	}

	function ParseStr($str) {
		$this->Clear();
		// Remove comments
		$str = preg_replace("/\/\*(.*)?\*\//Usi", "", $str);
		// Parse this damn csscode
		$parts = explode("}",$str);
		if(count($parts) > 0) {
			foreach($parts as $part) {
				if (strpos($part, "{") == TRUE) {
					list($keystr,$codestr) = explode("{",$part);
					$keys = explode(",",trim($keystr));
					if(count($keys) > 0) {
						foreach($keys as $key) {
							if(strlen($key) > 0) {
								$key = str_replace("\n", "", $key);
								$key = str_replace("\\", "", $key);
								$this->Add($key, trim($codestr));
							}
						}
					}
				}
			}
		}
		//
		return (count($this->css) > 0);
	}

	function Parse($filename) {
		$this->Clear();
		if(file_exists($filename)) {
			return $this->ParseStr(file_get_contents($filename));
		} else {
			return false;
		}
	}

	function getCSSSelector($_Selector = 'body') {
		foreach($this->css as $key => $atributes) {
			if (strtolower($key) == strtolower($_Selector)) 
				return $this->css[$key];
		}
		return false;
	}

	function getCSSSelectorAtribute($_Selector = 'body', $_Atribute = 'background-color') {
		$selector = $this->getCSSSelector($_Selector);
		print_r($selector);
		if ($selector !== FALSE) {
			foreach ($selector as $key => $values) {
				if (strtolower($key) == strtolower($_Atribute)) {
					return $values;
				}
			}
		}
	}
}
?>
