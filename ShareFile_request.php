<?php
// Defines base ShareFile URL template
if ( ! defined('SHAREFILE_SERVER_URL')) {
	define('SHAREFILE_SERVER_URL', '{subdomain}.sharefile.com');
}

// Defines REST API path template
if ( ! defined('SHAREFILE_REST_API_PATH')) {
	define('SHAREFILE_REST_API_PATH', '/rest/{method}.aspx?{params}');
}

/*
 * function ShareFile_post_request
 * Does custom POST request.
 */
if ( ! function_exists('ShareFile_post_request'))
{
	function ShareFile_post_request($secure, $url, $path, $data = '', $x_headers = null)
	{
		// Open socket connection
		if ($secure) {
			@$fp = fsockopen("ssl://$url", 443, $errno, $errstr, 30);
		}
		else {
			@$fp = fsockopen($url, 80, $errno, $errstr, 30);
		}

		// Do the request
		$response = array();
		if ($fp) {
			// Build and send request
			$out = "POST $path HTTP/1.1\r\n";
			$out .= "Host: $url\r\n";
			
			$add_content_length = true;
			if (isset($x_headers)) {
				if (is_array($x_headers)) {
					foreach ($x_headers as $header) {
						if (preg_match('/^Content-Length/i', $header)) {
							$add_content_length = false;
						}
						$out .= "$header\r\n";
					}
				}
				else {
					$out .= "$x_headers\r\n";
				}
			}
			
			if ($add_content_length) {
				$out .= "Content-Length: " . strlen($data) . "\r\n";
			}
			
			$out .= "Connection: close\r\n\r\n";
			$out .= $data;
			
			// Debug output
			// echo preg_replace("/\n/", '<br>', $out);
			 
			fwrite($fp, $out);
			
			// Read response and close the connection
			$responseText = '';
			while (($buffer = fgets($fp, 4096)) !== false) {
				$responseText .= $buffer;
			}
			fclose($fp);
			
			// Get response body
			if ($responseText != '') {
				// Debug output
				// print_r(preg_replace("/\\r\\n/", '<br>', $responseText) . '<br><br>');
				// Divide response to headers and body
				$parts = preg_split("/\\r\\n\\r\\n/", $responseText);
				$response['headers'] = array_shift($parts);
				$response['body'] = implode('', $parts);
			}
		}
		
		// Return the response
		return $response;
	}
}

/*
 * function ShareFile_method_request
 * Does custom ShareFile method request.
 */
if ( ! function_exists('ShareFile_method_request'))
{
	function ShareFile_method_request($secure, $subdomain, $method, $params = '', $x_headers = null)
	{
		// Build URL
		$url = preg_replace('/\\{subdomain\\}/i', $subdomain, SHAREFILE_SERVER_URL);
		// Build REST API URI
		$path = preg_replace('/\\{method\\}/i', $method, SHAREFILE_REST_API_PATH);
		$path = preg_replace('/\\{params\\}/i', $params, $path);
		// Do POST request
		return ShareFile_post_request($secure, $url, $path, '', $x_headers);
	}
}

/*
 * function ShareFile_json_request
 * Does custom ShareFile request and parses JSON-response.
 */
if ( ! function_exists('ShareFile_json_request'))
{
	function ShareFile_json_request($secure, $subdomain, $method, $params = '', $x_headers = null)
	{
		// Add response format to parameters
		$params = 'fmt=json&' . $params;
		// Do the request
		return ShareFile_method_request($secure, $subdomain, $method, $params, $x_headers);
	}
}
?>