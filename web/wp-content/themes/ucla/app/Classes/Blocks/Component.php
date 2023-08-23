<?php
namespace App\Builder;

class Component {

    public $classes = [];

    public function __construct(array $args = [] ) {

        // if (method_exists( $this, $property )) {
        //     $this->{$property}();
        // }

        $this->add_properties($args);

    }

    public function add_properties(array $args = []) {
        if (!empty($args)) {
            foreach ($args as $key => $val) {
                $this->{$key} = $val;
            }
        }
    }

    public function add_class( $class ) {
		$this->classes[] = $class;
    }

	public function add_classes( array $classes ) {
		foreach ( $classes as $class ) {
			$this->classes[] = $class;
		}
    }

    public function has_class(String $class = "") {
		return in_array($class, $this->classes);
	}
    // Has all classes
	public function has_classes(array $classes = []) {
		return count(array_intersect($classes, $this->classes)) == count($classes);
    }
    // Has one or more classes
    public function has_classes_or(array $classes = []) {
		return count(array_intersect($classes, $this->classes)) != 0;
	}

    public function classes() {
        return join(" ", $this->classes);
    }
}