<?php
/**
 * @Author: Ahonn
 * @Date:   2015-12-15 09:04:11
 * @Last Modified by:   Ahonn
 * @Last Modified time: 2015-12-24 17:49:52
 */

date_default_timezone_set('PRC');

class Request
{
	private static $cookie = COOKIE;
	
	private static $header = array(
		"Host: www.zhihu.com",
        "Accept-Encoding:gzip, deflate, sdch",
        "Accept: text/html, application/xhtml+xml, image/jxr, */*",
        "User-Agent: Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36"
	);

	/**
	 * get 方法请求
	 * @param  [string] $url [请求url]
	 * @return [string]      [请求内容]
	 */
	public static function get($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, self::$header);
		curl_setopt($ch, CURLOPT_COOKIE, self::$cookie);
		curl_setopt($ch, CURLOPT_ENCODING ,'gzip');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$result = curl_exec($ch);

		curl_close($ch);
		return $result;
	}


	/**
	 * post 方法请求
	 * @param  [string] $url  [请求url]
	 * @param  array  $data [post数据]
	 * @return [string]       [请求内容]
	 */
	public static function post($url, array $data, $header=array())
	{
		$header = array_merge(self::$header, $header);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_COOKIE, self::$cookie);
		curl_setopt($ch, CURLOPT_ENCODING ,'gzip');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);

		curl_close($ch);
		return $result;
	}

}