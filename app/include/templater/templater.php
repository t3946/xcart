<?php
if (!defined('XCART_START')) { header("Location: ../../"); die("Access denied"); }

//include_once 'Smarty.class.php';
//include_once 'Smarty_Compiler.class.php';

if (!class_exists('Smarty')) {
	echo "Can't find template engine!";
	exit;
}
		
class Templater extends Smarty
{
    private static $_instance = null;
    public bool $webmaster_mode = false;

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

	function __construct() {
		global $xcart_dir;

		$this->strict_resources = array ();

		$this->request_use_auto_globals = false;

        $this->addPluginsDir($xcart_dir.'/../app/'.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'templater'.DIRECTORY_SEPARATOR.'plugins');

		//$this->compiler_file	= "templater.php";
		//$this->compiler_class	= "TemplateCompiler";

		$this->compile_check_md5 = false;

		return parent::__construct();
	}

	function fetch($resource_name, $cache_id = null, $compile_id = null, $display = false) {
		$this->current_resource_name = $resource_name;
		return parent::fetch($resource_name, $cache_id, $compile_id, $display);
	}

	function _is_compiled($resource_name, $compile_path) {
		if (!empty($this->strict_resources)) {
			foreach ($this->strict_resources as $rule) {
				if (preg_match($rule, $resource_name)) {
					return false;
				}
			}
		}

		$result = parent::_is_compiled($resource_name, $compile_path);
		if ($result && $this->compile_check_md5)
			return $this->_check_compiled_md5($compile_path);

		return $result;
	}

	#
	# Test if compiled resource was changed by third party
	#
	function _check_compiled_md5($compiled_file) {

		if ((rand() % 10) != 5) return true;

		$control_file = $compiled_file.'.md5';

		$compiled_data = $this->_read_file($compiled_file);
		if ($compiled_data === false)
			return false;

		$control_data = $this->_read_file($control_file);
		if ($control_data === false)
			return false;

		$md5 = md5($compiled_file.$compiled_data);
		return !strcmp($md5,$control_data);
	}

	function _compile_resource($resource_name, $compile_path) {
		$result = parent::_compile_resource($resource_name, $compile_path);

		if ($result && $this->compile_check_md5) {
			$tpl_source = $this->_read_file($compile_path);
			if ($tpl_source !== false) {
				$_params = array(
					'filename' => $compile_path.'.md5',
					'contents' => md5($compile_path.$tpl_source),
					'create_dirs' => true
				);
				smarty_core_write_file($_params, $this);
			}
		}

		return $result;
	}
};

/*class TemplateCompiler extends Smarty_Compiler {
	function _compile_file($resource_name, $source_content, &$compiled_content) {
		$this->current_resource_name = $resource_name;

		return parent::_compile_file($resource_name, $source_content, $compiled_content);
	}
};*/

