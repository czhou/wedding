<?php
class My_Controller_Service extends Zend_Controller_Action {

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


	protected $apiConfig = null;

	public function init() {
		parent::init();
		Zend_Layout::getMvcInstance()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$this->logger = Zend_Registry::get("logger");
		$this->apiConfig = Zend_Registry::get("api_config");
		if (!$this->hasAccess($this->_request->getParam("api_key"))){
			$this->fetalError("No access!");
		}
	}

	public function hasAccess($APIKey){
		if ($this->apiConfig["key"] == $APIKey){
			return true;
		}
		return false;
	}

	public function fetalError($msg){
		$theMsg['msg_type'] = 0;
		$theMsg['msg'] = $msg;
		$this->_response->appendBody(Zend_Json_Encoder::encode($theMsg));
		$this->_response->sendResponse();
		exit();
	}

	public function error($msg, array $item=array()) {
		$theMsg = array();
		$theMsg['msg_type'] = 0;
		$theMsg['msg'] = $msg;
		$theMsg['item'] = $item;
		$this->_response->appendBody(Zend_Json::prettyPrint(Zend_Json::encode($theMsg)));
	}

	public function succeed($msg, array $item=array(), array $debugInfo = array()) {
		$theMsg = array();
		$theMsg['msg_type'] = 1;
		$theMsg['msg'] = $msg;
		$theMsg['item'] = $item;
		$theMsg['debug_info'] = $debugInfo;
		$this->_response->appendBody(Zend_Json::prettyPrint(Zend_Json::encode($theMsg)));
	}


}