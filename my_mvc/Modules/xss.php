<?php
class XSS {
	private $patterns_tags   = array();
	private $patterns_events = array();
	public function __construct() {

		$this->patterns_tags = array(
		'/<.*script[^<]/i','/<meta/i','/<applet/i','/<style/i','/<link/i','/<applet/i','/<embed.*\.js/i',
		'/<object.*\.js/i','/<.*frame/i','/<xml/i','/<base/i','/<title/i','/frameset/i','/<.*xss[^<]/i',
		'/<expression/i', '/<.*layer/i', '/<bgsound/i', '/<title/i', '/<base/i','/<marquee/i','/<xss/i'
		);

		$this->patterns_events = array(
		'/onabort/i','/onactivate/i','/onafterprint/i','/onafterupdate/i','/onbeforeactivate/i',
		'/onbeforecopy/i','/onbeforecut/i','/onbeforedeactivate/i','/onbeforeeditfocus/i',
		'/onbeforepaste/i','/onbeforeprint/i','/onbeforeunload/i','/onbeforeupdate/i','/onblur/i',
		'/onbounce/i','/oncellchange/i','/onchange/i','/onclick/i','/oncontextmenu/i',
		'/oncontrolselect/i','/oncopy/i','/oncut/i','/ondataavailable/i','/ondatasetchanged/i',
		'/ondatasetcomplete/i','/ondblclick/i','/ondeactivate/i','/ondrag/i','/ondragend/i',
		'/ondragenter/i','/ondragleave/i','/ondragover/i','/ondragstart/i','/ondrop/i','/onerror/i',
		'/onerrorupdate/i','/onfilterchange/i','/onfinish/i','/onfocus/i','/onfocusin/i',
		'/onfocusout/i','/onhelp/i','/onkeydown/i','/onkeypress/i','/onkeyup/i','/onlayoutcomplete/i',
		'/onload/i','/onlosecapture/i','/onmousedown/i','/onmouseenter/i','/onmouseleave/i',
		'/onmousemove/i','/onmouseout/i','/onmouseover/i','/onmouseup/i','/onmousewheel/i','/onmove/i',
		'/onmoveend/i','/onmovestart/i','/onpaste/i','/onpropertychange/i','/onreadystatechange/i',
		'/onreset/i','/onresize/i','/onresizeend/i','/onresizestart/i','/onrowenter/i','/onrowexit/i',
		'/onrowsdelete/i','/onrowsinserted/i','/onscroll/i','/onselect/i','/onselectionchange/i',
		'/onselectstart/i','/onstart/i','/onstop/i','/onsubmit/i','/onunload/i'
		);

	}

	public function __destruct() {

	}

	/**
	* Returns a one-character string containing the character specified by ascii...
	*
	* @param array $matches
	* @return string
	*/
	private function pack2Hex($matches){
		if(isset($matches[1])) {
			$ascii = hexdec($matches[1]);
			$match = chr($ascii);
			return $match;
		}
	}

	/**
	* Pack data into binary string...
	*
	* @param array $matches
	* @return binary string
	*/
	private function pack2Ascii($matches){
		if(isset($matches[1])) {
			return pack("c", $matches[1]);
		}
	}

	/**
	* Enter description here...
	*
	* @param string $value
	* @param array $options
	*/
	private function cleanGPC($value, $options) {

		// check if the `urldecode` flag is enable.
		if($options[0] == 1) {
			// Decodes URL-encoded string.
			$value = urldecode($value);
		}

		// Strip whitespace (or other characters) from the beginning and end of a string.
		$value = trim($value);

		$value  = preg_replace('/&#x([A-Fa-f0-9]{2,4})\;/',"%$1",$value);
		$decode = $value;
		if(preg_match('/%3C%([A-Fa-f0-9]{2})/',$value)) {
			$charArray = explode('%', $value);
			$newValue  = implode('%%', $charArray);
			$newValue  = '%' . $newValue . "%";
			$decode = $newvalue;
			//$decode    = preg_replace_callback('/%([A-Fa-f0-9]{2})%/', "pack2Hex", $newValue) faster because this is string replacement.
			//$decode    = preg_replace('/%%/', "%", $decode);
			$decode    = str_replace('%%', '%', $decode);
			$decode    = trim($decode);
			$value     = substr($decode, 1, ( strlen($decode) - 2 ) );
		}
		if(preg_match('/(&#)([0-9]{2,3})/',$value)) {
			//$charArray = explode('&#',$value);
			//$newValue  = implode('&#&#',$charArray);
			$newValue  = str_replace('&#', '&#&#', $value);
			$newValue  = '&#'.$newValue."&#";
			$decode = $newvalue;
			//$decode    = preg_replace_callback('/&#([0-9]{2,3})&#/', "pack2Ascii", $newValue);
			//$decode    = preg_replace('/&#&#/', "&#", $decode);
			$decode    = str_replace('&#&#', '&#', $decode);
			$decode    = trim($decode);
			$value     = substr($decode, 2, ( strlen($decode) - 4 ) );
		}

		$patterns	  = array('/&lt;/i', '/&gt;/i');
		$replacements = array('<', '>');
		# str_replace faster because this is string replacement.
		//$value = preg_replace($patterns, $replacements, $value);
		$value = str_replace($patterns, $replacements, $value);
		unset($patterns, $replacements);

		$patterns	  = array('%22', '%27', '%3C', '%3E');
		$replacements = array('"', '\'', '<', '>');
		$value = str_replace($patterns, $replacements, $value);
		unset($patterns, $replacements);

		// check if the `strip_tags` flag is enable.
		if($options[1] == 1) {
			// Strip HTML and PHP tags from a string.
			$value = strip_tags($value);
		}

		// check if the `strip_tags` flag is disable and the `strip xss tags patterns` flag is enable.
		if($options[1] == 0 && $options[2] == 1) {
			$value = preg_replace($this->patterns_tags,'',$value);
		}

		// check if the `strip xss event patterns` flag is enable.
		if($options[3] == 1) {
			$value = preg_replace($this->patterns_events,'',$value);
		}

		$patterns	  = array('"', '\'', '<', '>');
		$replacements = array('%22', '%27', '%3C', '%3E');
		$value = stripslashes($value);
		$value = str_replace($patterns, $replacements, $value);
		unset($patterns, $replacements);

		return $value;

	}

	/**
	* Enter description here...
	*
	* @param string or array $toClean
	* @param number $options, the defult value is 1101. 1 => urldecode, 2 => striptags, 3 => strip xss tags patterns, 4 => strip xss event patterns.
	*/

	/**
	* Enter description here...
	*
	* @param string or array $toClean
	* @param array $options, the defult value is 1101. 1 => urldecode, 2 => striptags, 3 => strip xss tags patterns, 4 => strip xss event patterns.
	* @param array $exclude
	* @param boolean $cleanKey
	*/
	public function checkXSS($toClean, $options = array(1,1,0,1), $exclude = null, $cleanKey = false) {

		// check if the variable is a string.
		if(is_string($toClean)) {
			// call cleaning function.
			return $this->cleanGPC($toClean, $options);
		}
		elseif (is_array($toClean)) {
			$output = array();
			foreach ($toClean as $k1 => $v1) {
				if(!array_key_exists($k1, (array) $exclude)) {

					if($cleanKey) {
						$k1 = $this->cleanGPC($k1, array(1,1,0,1));
					}

					// check if the variable is an array, for nested arrays.
					if(is_array($v1)) {
						// recursive call.
						$output[$k1] = $this->checkXSS($v1, &$options);
					}
					else {
						// call cleaning function.
						$output[$k1] = $this->cleanGPC($v1, &$options);
					}
				}
			}
		}

		return $output;
	}

}

//$options, the default value is array(1,1,0,1). 1 => urldecode, 2 => striptags, 3 => strip xss tags patterns, 4 => strip xss event patterns.
$xssObj   	= new XSS();
$_GET     	= $xssObj->checkXSS($_GET     , array(1,1,0,1), null, true);
$_COOKIE    = $xssObj->checkXSS($_COOKIE  , array(1,1,0,1), null, true);
$_SESSION   = $xssObj->checkXSS($_SESSION , array(1,1,0,1), null, true);
$_REQUEST 	= $xssObj->checkXSS($_REQUEST , array(1,1,0,1), null, true);
$_SERVER	= $xssObj->checkXSS($_SERVER  , array(1,1,0,1), null, true);

?>