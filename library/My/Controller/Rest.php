<?php
abstract class My_Controller_Rest extends Zend_Rest_Controller {

	/**
	 * @var Zend_Auth
	 */
	protected $auth = null;

	/**
	 * @var Zend_Log
	 */
	protected $logger = null;

	/**
	 *
	 * Enter description here ...
	 * @var Zend_Controller_Request_Http
	 */
	protected $_request = null;

	protected $config = null;

	public function init() {
		$this->logger = Zend_Registry::get("logger");
	}


	public function hasAccessTo($controllerName, $actionName){
		if ($actionName == "login" || $actionName == "logout" || $actionName == "change-my-password"){
			return true;
		}

		$actionCode = "b|".$controllerName."|".$actionName;
		foreach ($this->_permittions as $action){
			if ($actionCode === strtolower($action->code)){
				return true;
			}
		}
		return false;
	}


}