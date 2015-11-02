<?php
class ShareFileBase {

	private $is_error;
	private $error;
	private $cookies;

	public function __construct($cookies = array())
	{
		$this->is_error = false;
		$this->error = '';
		$this->cookies = $cookies;
	}
	
	protected function reset_error()
	{
		$this->is_error = false;
		$this->error = '';
		return true;
	}
	
	protected function set_error($error)
	{
		$this->is_error = true;
		$this->error = $error;
		return false;
	}
	
	public function set_cookie($name, $value)
	{
		$this->cookies[$name] = $value;
	}
	
	public function is_error()
	{
		return $this->is_error;
	}
	
	public function get_error()
	{
		return $this->error;
	}
	
	public function get_cookie($name)
	{
		if (isset($this->cookies[$name])) {
			return $this->cookies[$name];
		}
		else {
			return null;
		}
	}
	
	public function get_cookies()
	{
		return $this->cookies;
	}
	
	protected function post_request($secure, $url, $path, $data = '', $x_headers = null)
	{
		// Add Session ID cookie
		$x_headers = $this->_add_header_cookie_session_id($x_headers);
		// Prepare parameters and do the reques
		$response = ShareFile_post_request($secure, $url, $path, $data, $x_headers);
		// Parse headers
		$this->_parse_response_headers($response);
		// Parse body
		if (isset($response)) {
			return $response;
		}
		else {
			$this->set_error('Error communicating the server');
			return null;
		}
	}

	protected function json_request($secure, $subdomain, $method, $params, $x_headers)
	{
		// Add Session ID cookie
		$x_headers = $this->_add_header_cookie_session_id($x_headers);
		// Prepare parameters and do the reques
		$response = ShareFile_json_request($secure, $subdomain, $method, $params, $x_headers);
		// Parse headers
		$this->_parse_response_headers($response);
		// Parse body
		if (isset($response['body'])) {
			$data = json_decode($response['body']);
			if ($data->error) {
				$this->set_error($data->errorMessage);
				return null;
			}
			else {
				$this->reset_error();
				return $data;
			}
		}
		else {
			$this->set_error('Error communicating the server');
			return null;
		}
	}
	
	protected function _add_header_cookie_session_id($x_headers)
	{
		$session_id = $this->get_cookie('session_id'); 
		if (isset($session_id)) {
			if (isset($x_headers)) {
				if (!is_array($x_headers)) {
					$x_headers = array($x_headers);
				}
			}
			$x_headers[] = 'Cookie: ASP.NET_SessionId=' . $session_id;
		}
		
		return $x_headers;
	}
	
	protected function _parse_response_headers($response)
	{
		if (isset($response['headers'])) {
			$headers = preg_split("/\\r\\n/", $response['headers']);
			foreach ($headers as $header) {
				if (preg_match('/^set-cookie/i', $header)) {
					if (preg_match('/SessionId=([\\d\\w]+)/i', $header, $matches)) {
						$this->set_cookie('session_id', $matches[1]);
					}
				}
			}
		}
	}

}
?>