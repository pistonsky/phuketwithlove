<?php


class FormControl {

	protected $name = "";

	protected $type = "";

	protected $id = "";

	protected $class = "";

	protected $style = "";

	protected $options = array();

	protected $value = "";

	protected $placeholder = "";

	protected $checked = false;

	static $commonClass = "unifato-input";

	public function __construct( $settings ) {

		$this->settings = $settings;

		if( isset( $settings['id'] ) ) {
			$this->setId( $settings['id'] );
		}

		if( isset( $settings['type'] ) ) {
			$this->setType( $settings['type'] );
		}

		if( isset( $settings['name'] ) ) {
			$this->setName( $settings['name'] );
		}

		if( isset( $settings['class'] ) ) {
			$this->setClass( $settings['class'] );
		}

		if( isset( $settings['style'] ) ) {
			$this->setStyle( $settings['style'] );
		}

		if( isset( $settings['value'] ) ) {
			$this->setValue( $settings['value'] );
		}

		if( isset( $settings['placeholder'] ) ) {
			$this->setPlaceholder( $settings['placeholder'] );
		}

		if( isset( $settings['options'] ) ) {
			$this->setOptions( $settings['options'] );
		}

		if( isset( $settings['checked'] ) ) {
			$this->setChecked( $settings['checked'] );
		}

		if( isset( $settings['label'] ) ) {
			$this->setLabel( $settings['label'] );
		}

	}

	public function setLabel($label) {

		$this->label = $label;
		return $this;
	}

	public function setChecked( $checked ) {
	
		$this->checked = $checked === true;
		return $this;
	}

	public function getChecked() {

		return $this->checked;
	}

	public function setPlaceholder( $placeholder ) {
	
		$this->placeholder = esc_attr( $placeholder );
		return $this;
	}

	public function getPlaceholder() {

		return $this->placeholder;
	}

	public function setType($type) {
	
		$this->type = esc_attr( $type );
		return $this;
	}

	public function getType() {

		return $this->type;
	}

	public function setName($name) {

		$this->name = esc_attr( $name );
		return $this;
	}

	public function getName() {

		return $this->name;
	}

	public function setId($id) {

		$this->id = esc_attr( $id );
		return $this;
	}

	public function getId() {

		return $this->id;
	}

	public function setClass( $class ) {

		$this->class = $class;
		return $this;
	}

	public function getClass() {

		$class = esc_attr( $this->class );

		$class = self::$commonClass . ' ' . $this->className . ' ' . $class;

		return $class;
	}

	public function setStyle( $style ) {

		$this->style = esc_attr( $style );
		return $this;
	}

	public function getStyle() {

		return $this->style;
	}

	public function setOptions($options) {

		$this->options = $options;
		return $this;
	}

	public function getOptions() {

		return $this->options;
	}

	protected function getAttributes() {


	}

	public function setValue( $value ) {

		$this->value = esc_html( $value );
		return $this;
	}

	public function getValue() {

		return $this->value;
	}

	public function renderLabel() {

		$output = '<label for="' . $this->label['for'] . '" class="unifato-label">' . $this->label['text'] . '</label>';

		return $output;
	}

	public function __toString() {

		$output = '';

		if( isset( $this->label) ) {
			$label = $this->renderLabel();
			$output .= $label;
		}

		$control = $this->render();
		$output .= $control;

		if( isset( $this->settings['append']) ) {

			$output .= $this->settings['append'];
		}

		return $output;
	}
}

class InputFormControl extends FormControl {

	protected $type = 'text';

	protected $className = "unifato-input-std";

	protected function render() {

		$id = $this->getId();

		if( empty($id) ) {
			$id = $this->getName();
		}

		$id .= '_control';

		return '<input ' . ( $this->getChecked() ? ' checked="checked"' : '' ) . ' type="' . $this->getType() . '" name="' . $this->getName() . '" id="' . $id . '" class="' . $this->getClass() . '" style="' . $this->getStyle() . '" value="' . $this->getValue() . '">';
	}
}

class SelectFormControl extends FormControl {

	protected $className = "unifato-input-select";

	protected function render() {

		$id = $this->getId();

		if( empty($id) ) {
			$id = $this->getName();
		}

		$id .= '_control';

		$output = '<select name="' . $this->getName() . '" id="' . $id . '" class="' . $this->getClass() . '" style="' . $this->getStyle() . '"' . ((isset($this->settings['multiple']) && $this->settings['multiple'] ) ? ' multiple="multiple"' : '' ) . '>';

		$options = $this->getOptions();

		$value = $this->getValue();
		$value = html_entity_decode($value);

		$value_arr = @unserialize($value);

		if(is_array($value_arr)) {
			$values = $value_arr;
		} else {
			$values = array($value);
		}

		foreach( $options as $key => $value ) {

			$output .= '<option value="' . $key . '"' . ( in_array( $key, $values ) ? ' selected="selected"' : '' ) . '>' . $value . '</option>';
		}

		$output .= '</select>';

		return $output;
	}

	public function getName() {

		if( isset($this->settings['multiple']) && $this->settings['multiple'] ) {
			return $this->name . '[]';
		}

		return $this->name;
	}
}

class TextareaFormControl extends FormControl {

	protected $className = "unifato-input-textarea";

	protected function render() {

		$id = $this->getId();

		if( empty($id) ) {
			$id = $this->getName();
		}

		$id .= '_control';

		$output = '<textarea name="' . $this->getName() . '" id="' . $id . '" class="' . $this->getClass() . '" style="' . $this->getStyle() . '">' . $this->getValue() . '</textarea>';

		return $output;
	}
}