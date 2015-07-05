<?php

class Agent {

	private $id = null;

	private $_data = array();

	public function __construct( $id = null ) {

		$this->setId( $id );

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
			throw new Exception("Invalid Agent ID");
		}
	}

	public function getId() {
		return $this->id;
	}

	private function init() {

		$this->_data = get_post( $this->id );
		$this->_meta = get_post_meta( $this->id );
	}

	public function getName() {

		return $this->_data->post_title;
	}

	public function hasPhone() {

		$phone = $this->getPhone();

		return ! empty( $phone );	
	}

	public function hasEmail() {

		$email = $this->getEmail();
		return ! empty( $email );
	}

	public function hasWebsite() {

		$website = $this->getWebsite();
		return ! empty( $website );
	}

	public function getWebsite() {

		return ! empty( $this->_meta[USE_PREFIX . 'website'][0] ) ? $this->_meta[USE_PREFIX . 'website'][0] : "";
	}

	public function getLanguages() {
		
		return ! empty( $this->_meta[USE_PREFIX . 'languages'][0] ) ? $this->_meta[USE_PREFIX . 'languages'][0] : "";
	}

	public function getDescription() {

		return $this->_data->post_content;
	}

	public function getPhone() {

		return ! empty( $this->_meta[USE_PREFIX . 'phone'][0] ) ? $this->_meta[USE_PREFIX . 'phone'][0] : "";
	}

	public function getEmail() {

		return ! empty( $this->_meta[USE_PREFIX . 'email'][0] ) ? $this->_meta[USE_PREFIX . 'email'][0] : "";
	}

	public function hasTwitter() {

		$twitter = $this->getTwitter();
		return ! empty( $twitter );
	}

	public function getTwitter() {

		return ! empty( $this->_meta[USE_PREFIX . 'twitter'][0] ) ? $this->_meta[USE_PREFIX . 'twitter'][0] : "";
	}

	public function hasFacebook() {

		$facebook = $this->getFacebook();
		return ! empty( $facebook );
	}

	public function getFacebook() {

		return ! empty( $this->_meta[USE_PREFIX . 'facebook'][0] ) ? $this->_meta[USE_PREFIX . 'facebook'][0] : "";
	}

	public function hasGooglePlus() {

		$google_plus = $this->getGooglePlus();
		return ! empty( $google_plus );
	}

	public function getGooglePlus() {

		return ! empty( $this->_meta[USE_PREFIX . 'google_plus'][0] ) ? $this->_meta[USE_PREFIX . 'google_plus'][0] : "";
	}

	public function getImage($size) {

		$thumbnail = get_the_post_thumbnail($this->getId(), array(150, 150));

		preg_match("/src=[\"'](.*?)[\"']/", $thumbnail, $matches );
		$src = $matches[1];

		return $src;
	}

	public function isDirectContactEnabled() {

		$global = estetico_get_setting( 'enable_direct_agents_contact' );

		if( isset( $this->_meta[USE_PREFIX . 'enable_direct_agents_contact'][0] ) && $this->_meta[USE_PREFIX . 'enable_direct_agents_contact'][0] == "use_global" ) {

			return $global == '1';
		} else {

			return isset( $this->_meta[USE_PREFIX . 'enable_direct_agents_contact'][0] ) && $this->_meta[USE_PREFIX . 'enable_direct_agents_contact'][0] == 'yes';
		}
	}

	public function sendMessage( $name, $email, $phone, $person_message = null ) {

		$property_id = (int)$_POST['property_id'];

		$property = new Property($property_id);
		$property->fetch();

		$message_format = estetico_get_setting( 'agent_message_format' );

		$message = str_replace('%person_name%', $name, $message_format );
		$message = str_replace('%person_email%', $email, $message);
		$message = str_replace('%person_phone%', $phone, $message);
		$message = str_replace('%person_message%', $person_message, $message);

		$message = str_replace('%agent_name%', $this->getName(), $message);
		$message = str_replace('%property_name%', $property->getTitle(), $message);
		$message = str_replace('%property_link%', '<a href="' . $property->getLink() . '">' . $property->getLink() . '</a>', $message);

		$message = nl2br($message);

		$headers = "Content-Type:text/html;charset=utf-8\r\n";
		$headers .= "From: " . $name . ' <' . $email . '>' . "\r\n";

		$success = false;

		$recipient = $this->getEmail();

		if( mail( $recipient, __('Contact message about a property', THEME_NAME), $message, $headers) ) {
			$success = true;
		}

		return $success;
	}
}