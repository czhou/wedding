<?php

class Form_Reservation extends Zend_Form {

	public function init() {

		$this->setMethod ( 'post' );

		$this->addElement (
			'text', 'name', array (
			'required' => true,
			'filters' => array ('StringTrim','StripTags' ) )
		 );

		$this->addElement (
				'text', 'phone', array (
						'required' => true,
						'filters' => array ('StringTrim','StripTags' ) )
		);

		$this->addElement (
				'text', 'number_of_attendee', array (
						'required' => true,
						'filters' => array ('StringTrim','StripTags' ) )
		);

		$this->addElement (
				'text', 'room_reserve', array (
						'required' => true,
						'filters' => array ('StringTrim','StripTags' ) )
		);
		$this->addElement (
				'text', 'created_on', array (
						'required' => true,
						'filters' => array ('StringTrim','StripTags' ) )
		);


		$this->addElement (
				'textarea', 'room_description', array (
						'required' => false,
						'filters' => array ('StringTrim','StripTags' ) )
		);


	}

}

