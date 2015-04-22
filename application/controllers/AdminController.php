<?php

class AdminController extends My_Controller_Action
{
	

	public function indexAction(){
		$table = new Model_DbTable_Reservation();
		$this->view->all = $table->fetchAll(null,"id desc");
	}
	
	public function deleteAction(){
		if (( int ) $this->_request->getParam ( "id" )) {
			$id = ( int ) $this->_request->getParam ( "id" );
			$table = new Model_DbTable_Reservation();
			$reservation = $table->fetchRow ( "id = $id" );
			if ($reservation) {
				$reservation->delete ();
			}
		}
		
		$this->_redirect ( "/admin" );
	}
	
	

}

