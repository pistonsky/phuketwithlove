<?php

class Page {

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
			throw new Exception("Invalid page ID");
		}
	}

	public function getId() {
		return $this->id;
	}

	private function init() {

		$this->_data = get_post( $this->id );

		if($this->_data == null) {
			throw new Exception("No page with that ID #" . $this->id);
		}

		$this->_meta = get_post_meta( $this->id );
	}

	public function getTitle() {

		return $this->_data->post_title;
	}

	public function getLink() {

		return get_permalink( $this->getId() );
	}

	public function getExcerpt() {

		return $this->_data->post_excerpt;
	}

	public function getImageSrc($size = array(150, 150)) {
		$thumbnail = get_the_post_thumbnail($this->getId(), $size);

		preg_match("/src=[\"'](.*?)[\"']/", $thumbnail, $matches );
		
		if( empty( $matches ) )
			return false;

		$src = $matches[1];

		return $src;
	}

	public function getImage($size = array(150, 150)) {

		$image = get_the_post_thumbnail($this->getId(), $size);
		
		return $image;
	}
}