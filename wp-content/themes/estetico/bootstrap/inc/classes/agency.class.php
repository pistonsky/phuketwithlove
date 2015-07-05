<?php

class Agency {

	public function __construct( $id = null ) {
		$this->setId($id);
		$this->init();
	}

	public function setData($data) {

		$this->_data = $data;

		if( isset( $data->ID ) ) {
			$this->id = $data->ID;
		}

		if( empty( $this->_meta ) ) {
			$this->_meta = get_post_meta( $this->id );
		}
	}

	public function setId( $id ) {

		$this->id = (int)$id;

		if( $this->id == 0 ) {
			throw new Exception("Invalid Agency ID");
		}
	}

	public function getId() {
		return $this->id;
	}

	private function init() {

		$this->_data = get_post( $this->id );
		$this->_meta = get_post_meta( $this->id );
	}

	public function getAddress() {

		return ! empty( $this->_meta[USE_PREFIX . 'address'][0] ) ? $this->_meta[USE_PREFIX . 'address'][0] : "";
	}

	public function getCity() {

		return ! empty( $this->_meta[USE_PREFIX . 'city'][0] ) ? $this->_meta[USE_PREFIX . 'city'][0] : "";
	}

	public function getName() {

		return $this->_data->post_title;
	}

	public function hasPhone() {

		$phone = $this->getPhone();

		return ! empty( $phone );	
	}

	public function getPhone() {

		return ! empty( $this->_meta[USE_PREFIX . 'phone'][0] ) ? $this->_meta[USE_PREFIX . 'phone'][0] : "";
	}

	public function hasEmail() {

		$email = $this->getEmail();
		return ! empty( $email );
	}

	public function getEmail() {

		return ! empty( $this->_meta[USE_PREFIX . 'email'][0] ) ? $this->_meta[USE_PREFIX . 'email'][0] : "";
	}

	public function hasWebsite() {

		$website = $this->getWebsite();
		return ! empty( $website );
	}

	public function getWebsite() {

		return ! empty( $this->_meta[USE_PREFIX . 'website'][0] ) ? $this->_meta[USE_PREFIX . 'website'][0] : "";
	}

	public function getDescription() {

		return $this->_data->post_content;
	}
}