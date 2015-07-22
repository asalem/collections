<?php

class Abuse {
	protected static $_ABUSE_PAGE = 'Abuse_Url';
	protected $channelURL;
	/**
	 * the channel url where you should add schema & host
	 * @var string
	 */
	protected $applicationId;
	/**
	 * the application id for each channel, 
	 * @var string
	 */
	protected $channelKey;
	/**
	 * the channel key for each channel, 
	 * @var string
	 */
	protected $lang;
	/**
	 * 
	 * @param string $channelURL
	 * @param string $applicationId
	 * @param string $channelKey
	 */
	public function __construct($channelURL, $applicationId, $channelKey, $lang = '') {
		$this->channelURL = $channelURL;
		$this->applicationId = $applicationId;
		$this->channelKey = $channelKey;
		$this->lang = $lang;
	}
	
	/**
	 * this function will generate url with querystring contain 3 param 
	 * ln=link abuse
	 * sh=sha1 for rawurlencoded content with application id
	 * ch=channel Key 
	 * @method generateAbuseLink
	 */
	public function generateAbuseLink( ) {
		
		
		if (empty ( $this->channelURL ) || empty ( $this->applicationId ) || empty ( $this->channelKey )) {
			return false;
		}
		$setLang = '';
		if (! empty ( $this->lang )) {
			$setLang = '&ln=' . $this->lang;
		
		}
		
		$url = $this->channelURL;
		//$url = filter_var ( $url, FILTER_SANITIZE_STRING );
		//validate url retun false with internationlization content like arabic chars
		/*if (filter_var ( $url, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED ) === FALSE) {
			return false;
		}*/
		$url = rawurlencode ( $url );
		$hash = sha1 ( strtolower ( $url ) . $this->applicationId );
		$abuseLink = self::$_ABUSE_PAGE . 'la=' . $url . '&hs=' . $hash . '&ch=' . $this->channelKey.$setLang;
		
		return $abuseLink;
	
	}

}