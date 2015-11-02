<?php
require_once('ShareFile_request.php');
require_once('ShareFileBase.php');

class ShareFileConnection extends ShareFileBase {
	
	private $secure;
	private $subdomain;
	private $auth_id;
	
	public function __construct($secure, $subdomain, $auth_id, $cookies = array())
	{
		parent::__construct($cookies);
		$this->secure = $secure;
		$this->subdomain = $subdomain;
		$this->auth_id = $auth_id;
	}
	
	/*
	 * Getters 
	 */
	
	public function get_secure()
	{
		return $this->secure;
	}
	
	public function get_subdomain()
	{
		return $this->subdomain;
	}
	
	public function get_auth_id()
	{
		return $this->auth_id;
	}
	
	/*
	 * Execute method
	 */
	
	protected function execute_method($method, $params, $x_headers = null)
	{
		// Prepare parameters and do the reques
		$params = 'authid=' . urlencode($this->auth_id) . '&' . $params;
		return $this->json_request($this->secure, $this->subdomain, $method, $params, $x_headers);
	}
	
	/*
	 * Folder operations
	 */

	/*
	 * Returns id of the current folder verifying it exists
	 */
	public function folder_get($id)
	{
		$params =
			_sf_param_folder_id($id) .
			'op=get';
		return $this->execute_method('folder', $params);
	}
	
	/*
	 * Returns all metadata of the requested folder id
	 */
	public function folder_get_ex($id)
	{
		$params =
			_sf_param_folder_id($id) .
			'op=getex';
		return $this->execute_method('folder', $params);
	}
	
	/*
	 * Passing the required "name" parameter creates a folder with this name.
	 * Optionally, passing an "overwrite" parameter as true/false, will overwrite
	 * an existing folder if true.
	 */
	public function folder_create($id, $name, $overwrite = true)
	{
		$params =
			_sf_param_folder_id($id) .
			_sf_param_str('name', $name) .
			_sf_param_bool('overwrite', $overwrite) .
			'op=create';
		return $this->execute_method('folder', $params);
	}
	
	/*
	 * Returns a list of all sub-folders and files in the current folder
	 */
	public function folder_list($id)
	{
		$params =
			_sf_param_folder_id($id) .
			'op=list';
		return $this->execute_method('folder', $params);
	}
	
	/*
	 * Deletes a folder given the passed in "id" parameter and all children of this folder.
	 */
	public function folder_delete($id)
	{
		$params =
			_sf_param_folder_id($id) .
			'op=delete';
		return $this->execute_method('folder', $params);
	}
	
	/*
	 * Passing the required "name" parameter will change a folders name to what is passed.
	 */
	public function folder_rename($id, $name)
	{
		$params =
			_sf_param_folder_id($id) .
			_sf_param_str('name', $name) .
			'op=rename';
		return $this->execute_method('folder', $params);
	}
	
	/*
	 * Calling this function will return a link directly to a specified folder given the "id"
	 * parameter of a folder. 
	 */
	public function folder_request_url($id, $require_login = false, $require_user_info = false, $expiration_days = 30, $notify_upload = false)
	{
		$params =
			_sf_param_folder_id($id) .
			_sf_param_bool('requirelogin', $require_login) .
			_sf_param_bool('requireuserinfo', $require_user_info) .
			_sf_param_int('expirationdays', $expiration_days) .
			_sf_param_bool('notifyonupload', $notify_upload) .
			'op=request-url';
		return $this->execute_method('folder', $params);
	}
	
	/*
	 * Calling this function will return a link directly to a specified folder given the "id"
	 * parameter of a folder.
	 */
	public function folder_grant($id, $user_id, $download = true, $upload = false, $view = true, $admin = false, $delete = false, $notify_upload = false, $notify_download = false)
	{
		$params =
			_sf_param_folder_id($id) .
			_sf_param_str('userid', $user_id) .
			_sf_param_bool('download', $download) .
			_sf_param_bool('upload', $upload) .
			_sf_param_bool('view', $view) .
			_sf_param_bool('admin', $admin) .
			_sf_param_bool('delete', $delete) .
			_sf_param_bool('notifyupload', $notify_upload) .
			_sf_param_bool('notifydownload', $notify_download) .
			'op=grant';
		return $this->execute_method('folder', $params);
	}
	
	/*
	 * File operations
	 */
	
	/*
	 * Returns all metadata of the requested file id.
	 */
	public function file_get($id)
	{
		$params =
			_sf_param_str('id', $id) .
			'op=get';
		return $this->execute_method('file', $params);
	}
	
	/*
	 * Generates a public link for download.
	 */
	public function file_get_link($id, $require_user_info = false, $expiration_days = 30, $notify_download = false, $max_downloads = -1)
	{
		$params =
			_sf_param_str('id', $id) .
			_sf_param_bool('requireuserinfo', $require_user_info) .
			_sf_param_int('expirationdays', $expiration_days) .
			_sf_param_bool('notifyondownload', $notify_download) .
			_sf_param_int('maxdownloads', $max_downloads) .
			'op=getlink';
		return $this->execute_method('file', $params);
	}
	
	/*
	 * Removes a file from the system. Files can be recovered within 7 days,
	 * but not currently through the API. 
	 */
	public function file_delete($id)
	{
		$params =
			_sf_param_str('id', $id) .
			'op=delete';
		return $this->execute_method('file', $params);
	}
	
	/*
	 * Passing the required "name" parameter will change a files name to what is passed. 
	 */
	public function file_rename($id, $name)
	{
		$params =
			_sf_param_str('id', $id) .
			_sf_param_str('name', $name) .
			'op=rename';
		return $this->execute_method('file', $params);
	}
	
	/*
	 * Passing the required parameters will create the file in the specified folder
	 * AFTER the upload has completed. Since a file cannot exist without a physical file uploaded,
	 * if the upload fails or is cancelled before all data has been transferred to ShareFile,
	 * this file will not exist. "filename" must include the extension.  
	 */
	public function file_get_upload_link($filename, $folder_id = null, $unzip = true, $overwrite = false, $details = '')
	{
		$params = '';
		if (isset($folder_id)) {
			$params .= _sf_param_str('folderid', $folder_id);
		}
		
		$params .=
			_sf_param_str('filename', $filename) .
			_sf_param_bool('unzip', $unzip) .
			_sf_param_bool('overwrite', $overwrite) .
			_sf_param_str('details', $details) .
			'op=upload';
		return $this->execute_method('file', $params);
	}
	
	public function file_upload($filename, $content, $folder_id = null, $unzip = true, $overwrite = false, $details = '')
	{
		$data = $this->file_get_upload_link($filename, $folder_id, $unzip, $overwrite, $details);
		if ( ! $this->is_error()) {
			$url = parse_url($data->value);
			// Check if we need to use secure connection
			$secure = preg_match('/https/i', $url['scheme']);
			// Prepare headers
			$x_headers[] = 'Content-Type: application/octet-stream';
			$x_headers[] = 'Content-Length: ' . strlen($content);
			// Path = path + query string + raw=1 + filename=File_Name
			$path = $url['path'] . '?' . $url['query'] . '&raw=1&filename=' . urlencode($filename);
			// Send data
			$response = $this->post_request($secure, $url['host'], $path, $content, $x_headers);
			if (isset($response['body'])) {
				return preg_match('/OK:/i', $response['body']);
			}
		}
		
		return false;
	}
	
	/*
	 * Passing the required parameter "id" will return a direct link to download the file. 
	 */
	public function file_get_download_link($id)
	{
		$params =
			_sf_param_str('id', $id) .
			'op=download';
		return $this->execute_method('file', $params);
	}
	
	public function file_download($id)
	{
		$data = $this->file_get_download_link($id);
		if ( ! $this->is_error()) {
			return file_get_contents($data->value);
		}

		return null;
	}
	
	/*
	 * User operations.
	 */
	
	/*
	 * Returns the user given the required parameter "id" of a user. The "id" can either be
	 * an email address or the id of a user. 
	 */
	public function users_get($id)
	{
		$params =
			_sf_param_str('id', $id) .
			'op=get';
		return $this->execute_method('users', $params);
	}
	
	/*
	 * Returns a list of all the account employees. 
	 */
	public function users_list_employees()
	{
		$params = 'op=liste';
		return $this->execute_method('users', $params);
	}
	
	/*
	 * Returns a list of all the account clients. 
	 */
	public function users_list_clients()
	{
		$params = 'op=listc';
		return $this->execute_method('users', $params);
	}
	
	/*
	 * Calling this will delete a user based off the required "id" parameter
	 * completely from the system. An email address may NOT be passed to this id.
	 * Note: This operation may take several minutes depending on the number
	 * of associated folders the user is assigned to.  
	 */
	public function users_delete($id)
	{
		$params =
			_sf_param_str('id', $id) .
			'op=delete';
		return $this->execute_method('users', $params);
	}
	
	/*
	 * Calling this will delete a user based off the required "id" parameter
	 * from all folders ONLY but not the system. An email address may NOT be passed to this id.
	 * Note: This operation may take several minutes depending on the number
	 * of associated folders the user is assigned to. 
	 */
	public function users_delete_from_folders($id)
	{
		$params =
			_sf_param_str('id', $id) .
			'op=deletef';
		return $this->execute_method('users', $params);
	}
	
	/*
	 * Passing in the required parameters, a user is created in the system as either
	 * a client or an employee of the account. 
	 */
	public function users_create($first_name, $last_name, $email, $is_employee, $password = null, $company = '', $create_folders = false, $use_file_box = false, $manage_users = false, $is_admin = false)
	{
		$params = '';
		if (isset($password)) {
			$params .= _sf_param_str('password', $password);
		}
		
		if ($is_employee) {
			$params .=
				_sf_param_bool('usefilebox', $use_file_box) .
				_sf_param_bool('manageusers', $manage_users);
		}
		
		$params .=
			_sf_param_str('firstname', $first_name) .
			_sf_param_str('lastname', $last_name) .
			_sf_param_str('email', $email) .
			_sf_param_bool('isemployee', $is_employee) .
			_sf_param_str('company', $company) .
			_sf_param_bool('createfolders', $create_folders) .
			_sf_param_bool('isadmin', $is_admin) .
			'op=create';
		return $this->execute_method('users', $params);
	}
	
	/*
	 * Passing in the required parameters, a user is updated in the system.
	 * Other specific updates must be made to separate operators. 
	 */
	public function users_update($id, $first_name, $last_name, $email = null)
	{
		$params = '';
		if (isset($email)) {
			$params .= _sf_param_str('email', $email);
		}
		
		$params .=
			_sf_param_str('id', $id) .
			_sf_param_str('firstname', $first_name) .
			_sf_param_str('lastname', $last_name) .
			'op=update';
		return $this->execute_method('users', $params);
	}
	
	/*
	 * Calling this will reset the specified users password. 
	 */
	public function users_reset_password($id, $old_password, $new_password, $notify = false)
	{
		$params =
			_sf_param_str('id', $id) .
			_sf_param_str('oldp', $old_password) .
			_sf_param_str('newp', $new_password) .
			_sf_param_bool('notify', $notify) .
			'op=resetp';
		return $this->execute_method('users', $params);
	}
	
	/*
	 * Search operations
	 */
	
	/*
	 * Returns a result set of file and folders that match the criteria of the "query" parameter. 
	 */
	public function search($query, $show_partial = false)
	{
		$params =
			_sf_param_str('query', $query) .
			_sf_param_bool('showpartial', $show_partial) .
			'op=search';
		return $this->execute_method('search', $params);
	}
	
}


/*
 * URL parameters helper functions 
 */

function _sf_param_int($name, $value) {
	return $name . '=' . ((int) $value) . '&';
}

function _sf_param_bool($name, $value) {
	return $name . '=' . ($value ? 'true' : 'false') . '&';
}

function _sf_param_str($name, $value) {
	return $name . '=' . urlencode($value) . '&';
}

function _sf_param_folder_id($value) {
	// If there is slash in the value then it's the path
	if (preg_match('/\\//', $value)) {
		$name = 'path';
	}
	else {
		$name = 'id';
	}
	
	return $name . '=' . urlencode($value) . '&';
}
?>