<?php

class IndexController extends My_Controller_Action
{



	public function indexAction(){
			if ($this->_request->isPost()){
				Zend_Layout::getMvcInstance()->disableLayout();
				$values = $this->_request->getParams();
				$values["created_on"] = date("Y-m-d H:i:s");
				$form = new Form_Reservation();
				$table = new Model_DbTable_Reservation();
				if($form->isValid($values) && $table->insert($form->getValues())){
					echo "妥！";
				}else{
					echo "好像哪里不对... 该填的都填了么？";
				}
				exit();
			}
	}
	
	
	public function email($data){
		$salesEmail = "fidy.watcher@gmail.com";
		$emailTitle = "婚礼来宾注册 - " . $data["name"];
		$emailContent = <<<EOF
		新来宾：{$data["name"]}
		电话：{$data["phone"]}
		人数：{$data["number_of_attendee"]}
		宾馆预订：{$data["room_reserve"]}
		宾馆要求：{$data["room_description"]}
EOF;
			$mail = new Zend_Mail();
			$mail->setBodyHtml($emailContent)
				->setSubject($emailTitle)
				->setFrom("admin@fidy.net", "wedding robot")
				->addTo($salesEmail)
				->send();
				exit();
       }
       
       
	public function commentAction(){
		if ($this->_request->isPost()){
			Zend_Layout::getMvcInstance()->disableLayout();
			$values = $this->_request->getParams();
			$values["created_on"] = date("Y-m-d H:i:s");
			$form = new Form_Reservation();
			$table = new Model_DbTable_Reservation();
			if($form->isValid($values) && $table->insert($form->getValues())){
				echo "发出去了";
			}else{
				echo "好像哪里不对... 该填的都填了么？";
			}
			exit();
		}
	}
	
	public function weiboCallbackAction(){
		$this->view->params = $this->_request->getParams();
	}




}

