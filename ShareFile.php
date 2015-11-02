<?php
require_once('ShareFile_request.php');
require_once('ShareFileBase.php');
require_once('ShareFileConnection.php');

class ShareFile extends ShareFileBase {

	private $secure;

	public function __construct($secure)
	{
		parent::__construct();
		$this->secure = $secure;
	}
	
	public function open($subdomain, $email, $password)
	{
		// Open new session
		$x_headers[] = "password: $password";
		$x_headers[] = "username: $email";
		$data = $this->json_request($this->secure, $subdomain, 'getAuthID', '', $x_headers);
		if (isset($data) && ! $this->is_error()) {
			return new ShareFileConnection($this->secure, $subdomain, $data->value, $this->get_cookies());
		}
		else {
			return null;
		}
	}

}
?>