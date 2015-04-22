<?php
class My_Controller_Action extends Zend_Controller_Action {
	
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
	
	protected $_permittions = array();
	
	protected $config = null;
	
	public function init() {
		$this->logger = Zend_Registry::get("logger");
		require_once 'My/Auth/Admin.php';
		$this->auth = My_Auth_Admin::getInstance ();
		Zend_Layout::getMvcInstance ()->setLayout ( "admin.layout" );
		$this->view->adminMenuClass = array ();
		$this->view->logger = $this->logger;
		if (! $this->auth->getIdentity () && $this->_request->getActionName () != "login") {
			$this->_redirect ( "/index/login" );
		} else {
			$currentUser = $this->auth->getIdentity ();
			$roleTable = new Model_DbTable_Roles();
			if ($currentUser){
				$role = $roleTable->fetchRow("id = $currentUser->role_id");
			}else{
				$role = null;
			}
			if ($role){
				$this->_permittions = $role->getBackEndActions();
			}
			$this->view->currentAdmin = $currentUser;
			if (!$this->hasAccessTo($this->_request->getControllerName(), $this->_request->getActionName())){
				$this->error("您无权访问本页面！"); 
			}
		}
	}
	
	
	public function initTopMenu(){
		$this->auth->getIdentity();
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
	
	public function error($msg) {
		Zend_Layout::getMvcInstance ()->setLayout ( "admin.layout" );
		$this->view->title = "出错啦！";
		$this->view->message = $msg;
		if (isset($msg["http_response_code"]) && is_int($msg["http_response_code"])){
			$this->_response->setHttpResponseCode($msg["http_response_code"]);
		}
		
		$this->renderScript('error/message.phtml');
		return ;
	}
	
	public function succeed($msg) {
		Zend_Layout::getMvcInstance ()->setLayout ( "index.layout" );
		$this->view->title = "成功啦！";
		$this->view->message = $msg;
		$this->renderScript('error/message.phtml');
		return ;
	}
	
	public static function pager($currpage, $perpage, $nums, $q, $currPageStyle='', $othersPageStyle='')
	{
		$dp=10;
		$nums = intval($nums);
		$maxPages = ceil($nums/$perpage);
		$pageStart=1;
		if ($maxPages==0) {
			$maxPages = 1;
		}
		if ($currpage>$maxPages) {
			$currpage=$maxPages;
		}
		if ($currpage<=1) {
			$s = "<li class=\"{$currPageStyle}\" style=\"margin-right:10px;\">Prev</li>";
			$pageStart = 1;
			$currpage=1;
			$pageEnd=$dp;
		} else {
			$tmp = $currpage-1;
			$s = "<li><a href=\"".str_replace('{page}', $tmp, $q)."\" class=\"{$othersPageStyle}\">Prev</a></li>";
			$rangeOrder = floor(($currpage-2)/($dp-2));
			$pageStart = $rangeOrder*($dp-2)+1;
			$pageEnd=$pageStart+$dp-1;
		}

		for ($i=$pageStart; $i<=$pageEnd; $i++) {
			if ($i>$maxPages) {
				break;
			}
			if ($i!=$currpage) {
				$s.= '<li><a href="'.str_replace('{page}', $i, $q).'" class="'.$othersPageStyle.'">'.$i.'</a></li>';
			}
			else {
				$s.= '<li class="'.$currPageStyle.'">'.$i.'</li>';
			}
		}

		if ($currpage>=$maxPages) {
			$s.= "<li class=\"{$currPageStyle}\" style=\"margin-left:10px;\">Next</li>";
		} else {
			$tmp = $currpage+1;
			$s.= "<li><a href=\"".str_replace('{page}', $tmp, $q)."\" class=\"{$othersPageStyle}\">Next</a></li>";
		}
		if ($maxPages > 1) {
			$s.='<li><form method="post" action="'.str_replace('&page={page}', "", $q).'"><input value="" type="text" name="page" size="1" /><input type="submit" value="Go" /></form></a>';
		}
		
		return $s;
	}
}