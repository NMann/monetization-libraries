<?php

  /**
   * 140Proof API PHP Binding
   *
   * Simple wrapper for 140Proof's REST API
   *
   * @link http://dev.140proof.com/docs
   * @since 2010-11-18
   */
class Api140Proof
{
  const API_BASE = "http://api.140proof.com";
  const ADS_USER = "/ads/user";
  
  const HTML = "html";
  const JS   = "js";
  const JSON = "json";
  const XML  = "xml";

  const HTML_TYPE = 'text/html';
  const JS_TYPE   = 'application/javascript';
  const JSON_TYPE = 'application/json';
  const XML_TYPE  = 'text/xml';
  
  const TINY        = "240";
  const SMALL       = "320";
  const MEDIUM      = "480";
  const LARGE       = "640";
  const EXTRA_LARGE = "960";
  
  const GET    = "GET";
  const POST   = "POST";
  const PUT    = "PUT";
  const DELETE = "DELETE";
  
  const DEFAULT_LANG = "en";

  /**
   * Indicates whether API methods should return the REST URL or full response
   * @access public
   */
  public $return_url = false;
  
  private $app_id;

  /**
   * @param string $api_id Required. Your 140 Proof API ID
   * @param string $api_base Optional. Base URL for 140 Proof API (defaults to production)
   */ 
  public function __construct($app_id, $api_base = self::API_BASE)
  {
    $app_id = trim($app_id);
    $api_base = preg_replace('/\/$/', '', trim($api_base));
    
    if(!$app_id){
      throw new Exception("app_id is required");
    }
    if(!$api_base){
      throw new Exception("api_base is required");
    }
    
    if(!preg_match('/^http(s)?:\/\//', $api_base)){
      $api_base = 'http://' . $api_base;
    }
    
    $this->app_id = $app_id;
    $this->api_base = $api_base;
  }

  /**
   * Wrapper for 140Proof's /ads/user
   *
   * Returns an ad for a specified user. Returns an HTTP 404 error if the user does not exist, if the app does not exist, or if a targetted ad could not be found for the user.
   *
   * @param string $user_id Required. The screen name or id of the user on Twitter.
   * @param string $format Required. Response format, one of Api140Proof::HTML, Api140Proof::JS, Api140Proof::JSON, or Api140Proof::XML
   * @param integer $width Required (html format only). The width, in pixels, of the requested ad unit. Possible values: 240 (Api140Proof::TINY), 320 (Api140Proof::SMALL), 480 (Api140Proof::MEDIUM), 640 (Api140Proof::LARGE), or 960 (Api140Proof::EXTRA_LARGE)
   * @param float $lat Optional. The user's current latitude. Note: The valid ranges for latitude is -90.0 to +90.0 (North is positive) inclusive. This parameter will be ignored if outside that range, if it is not a number, or if there not a corresponding long parameter with this request.
   * @param float $long Optional. The user's current longitude. Note: The valid ranges for longitude is -180.0 to +180.0 (East is positive) inclusive. This parameter will be ignored if outside that range, if it is not a number, or if there not a corresponding lat parameter with this request.
   * @param string $lang Optional. Restricts ads to the given language, specified by an ISO 639-1 code.
   * @param string $jsonp Optional. Specify the name of a callback function for a js response.
   */
  public function getAd($user_id, $format, $width = null, $lat = null, $long = null, $lang = null, $jsonp = null)
  {
    $req_params = array("app_id" => $this->app_id);
    
    if(!$user_id){
      throw new Exception("user_id is required");
    }
    $req_params['user_id'] = $user_id;

    $type;

    $format = strtolower($format);
    
    switch($format){
    case self::HTML:
      if(!$width){
	throw new Exception("width is required when request HTML format");
      }
      else{
	switch($width){
	case self::TINY:
	case self::SMALL:
	case self::MEDIUM:
	case self::LARGE:
	case self::EXTRA_LARGE:
	  $req_params['width'] = $width;
	  break;
	default:
	  throw new Exception("width '" . $width . "' is invalid");
	}
      }
      $type = self::HTML_TYPE;
      break;
    case self::JS:
      $type = self::JS_TYPE;
      break;
    case self::JSON:
      $type = self::JSON_TYPE;
      break;
    case self::XML:
      $type = self::XML_TYPE;
      break;
    default:
      throw new Exception("invalid format: '" . $format . "'");
      break;
    }

    /* Could optionally throw exceptions for missing of invalid lat/long pairs, but seems like that's overkill and could be problematic in the wild */
    if($lat !== null && trim($lat !== "") && is_numeric($lat)){
      $req_params['lat'] = $lat;
    }
    if($long !== null && trim($long !== "") && is_numeric($long)){
      $req_params['long'] = $long;
    }

    if($lang) $req_params['lang'] = $lang;
    if($format === self::JS && $jsonp) $req_params['jsonp'] = $jsonp;

    $ret = $this->getUrl(self::ADS_USER, $format, $req_params);

    if(!$this->return_url){
      $ret = $this->sendRequest($ret, $type);
    }

    return $ret;
  }

  private function getUrl($method, $format, $req_params)
  {
    $url = self::API_BASE . $method . '.' . $format;
    
    $query = "";
    foreach($req_params as $label => $value){
      if($query) $query .= '&';
      $query .= urlencode($label) . '=' . urlencode($value);
    }
    if($query) $url .= '?' . $query;

    return $url;
  }
  
  private function sendRequest($url, $type, $method = self::GET)
  {
    $ret = "";
    
    $headers = array(
      'Accept: ' . $type,
      'Content-Type: ' . $type,
      );

    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, $url);
    curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

    switch(strtoupper($method)){
      
    case self::GET:
      break;

    case self::POST:
      curl_setopt($handle, CURLOPT_POST, true);
      curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
      break;
      
    case self::PUT:
      curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
      curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
      break;
      
    case self::DELETE:
      curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
      break;
    }
    
    $response = curl_exec($handle);
    $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

    switch($code){
    case "200":
      $ret = $response;
      break;
    default:
      throw new Exception("Received HTTP Error Code " . $code . ": " . self::getStatusCodeMessage($code));
    }
    
    return $ret;
  }

  public static function getStatusCodeMessage($status)
  {

    $codes = array(
      100 => 'Continue',
      101 => 'Switching Protocols',
      200 => 'OK',
      201 => 'Created',
      202 => 'Accepted',
      203 => 'Non-Authoritative Information',
      204 => 'No Content',
      205 => 'Reset Content',
      206 => 'Partial Content',
      300 => 'Multiple Choices',
      301 => 'Moved Permanently',
      302 => 'Found',
      303 => 'See Other',
      304 => 'Not Modified',
      305 => 'Use Proxy',
      306 => '(Unused)',
      307 => 'Temporary Redirect',
      400 => 'Bad Request',
      401 => 'Unauthorized',
      402 => 'Payment Required',
      403 => 'Forbidden',
      404 => 'Not Found',
      405 => 'Method Not Allowed',
      406 => 'Not Acceptable',
      407 => 'Proxy Authentication Required',
      408 => 'Request Timeout',
      409 => 'Conflict',
      410 => 'Gone',
      411 => 'Length Required',
      412 => 'Precondition Failed',
      413 => 'Request Entity Too Large',
      414 => 'Request-URI Too Long',
      415 => 'Unsupported Media Type',
      416 => 'Requested Range Not Satisfiable',
      417 => 'Expectation Failed',
      500 => 'Internal Server Error',
      501 => 'Not Implemented',
      502 => 'Bad Gateway',
      503 => 'Service Unavailable',
      504 => 'Gateway Timeout',
      505 => 'HTTP Version Not Supported'
      );

    return (isset($codes[$status])) ? $codes[$status] : '';

  }
  
}
