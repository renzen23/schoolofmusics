<?php
namespace App\Builder;
use WP_Query;

/**
 * Class Block
 * @package App\Builder
 *
 * This class is meant to be extended in order to separate the functionality of
 * a builder block from its presentation.
 *
 * @property-read $position
 * @property-read $title
 * @property-read $text
 */
class Block  {
	/**
	 * Internal prefix for a block's data.
	 *
	 * @var string
	 */
	public $prefix = '';

	/**
	 * Current position inside a loop of blocks.
	 *
	 * @var int
	 */
	public $position = 0;

	/**
	 * Main HTML class for the current block.
	 *
	 * @var string
	 */
	public $class = 'b';

	/**
	 * Array of classes for the main HTML element.
	 *
	 * @var array
	 */
    public $classes = [];


	/**
	 * Array of inline styles for the main HTML element.
	 *
	 * @var array
	 */
	public $styles = [];

	/**
	 * Title of the current block.
	 *
	 * @var bool|string
	 */
	public $title = '';

	/**
	 * Text of the current block.
	 *
	 * @var bool|string
	 */
	public $text = '';

	public $template = '';

	/**
	 * Block constructor.
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
        global $count;

		foreach ( $args as $prop ) {
			$this->{$prop} = get_sub_field( $prop );
        }

        $this->template = $this->from_camel_case(substr(get_called_class(), strrpos(get_called_class(), '\\') + 1));

        $this->prefix = get_row_layout();

        $this->class = 'b-' . str_replace('_','-',$this->prefix);

        $this->position = intval( $count++ );

        if( method_exists($this, $this->prefix) ){
            call_user_func( array( $this, $this->prefix ) );
        }

        $this->set_classes();

        $this->set_styles();

		$this->id = $this->get_sub_field( 'id' ) ?: "block-{$this->position}";
	}

	/**
	 * Obtain the value of a public or private property.
	 *
	 * @param  string $property
	 *
	 * @return null
	 */
	public function __get( $property ) {
		if ( property_exists( get_called_class(), $property ) ) {
			if ( null === $this->{$property} && method_exists( $this, "set_{$property}" ) ) {
				$this->{"set_{$property}"}();
			}

			return $this->{$property};
		}

		return null;
	}

	/**
	 * Obtain specific field data.
	 *
	 * @param  string $field
	 *
	 * @return mixed
	 */
	public function get_sub_field( $field ) {
		return get_sub_field( $this->prefix . '_' . $field );
	}

	/**
	 * Set classes for the main HTML element.
	 */
	public function set_classes() {
		array_unshift( $this->classes, $this->class );
    }

	/**
	 * Add classes to the main HTML element.
	 *
	 * @param array $classes
	 */
    public function add_class( $class ) {
		$this->classes[] = $class;
    }

	public function add_classes( array $classes ) {
		foreach ( $classes as $class ) {
			$this->classes[] = $class;
		}
    }

    /**
	 * Check for classes in the main HTML element.
	 */
    public function has_class(String $class = "") {
		return in_array($class, $this->classes);
	}
    // Has all the classes
	public function has_classes(array $classes = []) {
		return count(array_intersect($classes, $this->classes)) == count($classes);
    }
    // Has any of the clases
    public function has_classes_or(array $classes = []) {
		return count(array_intersect($classes, $this->classes)) != 0;
	}

	/**
	 * Obtain a list of parsed classes for the main HTML element.
	 *
	 * @param array $classes
	 *
	 * @return string
	 */
	public function get_parsed_classes( array $classes = [] ) {
		$this->add_classes( $classes );

		return join( ' ', $this->classes );
	}

	/**
	 * Wrapper for `Block::get_parsed_classes()`
	 *
	 * @see Block::get_parsed_classes()
	 *
	 * @param array $classes
	 *
	 * @return string
	 */
	public function classes( array $classes = [] ) {
		return $this->get_parsed_classes( $classes );
	}

	/**
	 * Set inline styles for the main HTML element.
	 *
	 * Inline styles can be quite different from one block to another, so this
	 * method is just a placeholder for other classes.
	 */
	public function set_styles() {
		if ( is_array( $this->image ) && isset( $this->image['url'] ) ) {
			// $this->styles['background-image'] = 'url(\'' . esc_url( $this->image['url'] ) . '\')';
		}
	}

	/**
	 * Parse inline styles to be printed as HTML.
	 *
	 * @return string
	 */
	public function get_parsed_styles() {
		$styles = [];

		foreach ( $this->styles as $prop => $value ) {
			$styles[] = $prop . ': ' . $value . ';';
		}

		return join( ' ', $styles );
	}

	/**
	 * Wrapper for `Block::get_parsed_styles()`
	 *
	 * @see Block::get_parsed_styles()
	 *
	 * @return string
	 */
	public function styles() {
		return $this->get_parsed_styles();
	}

	public function from_camel_case($input) {
	  preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
	  $ret = $matches[0];
	  foreach ($ret as &$match) {
	    $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
	  }
	  return implode('_', $ret);
	}


	public function snakecase($str) {
		return str_replace("-","_", $str);
	}

	public function traincase($str) {
		return str_replace("_","-", $str);
	}

	/**
	 * Show/hide entire wrapper
	 */
    public function show_wrapper() {
        if (isset($this->show_wrapper)) {
            return $this->show_wrapper;
        }
        return true;
	}
}
