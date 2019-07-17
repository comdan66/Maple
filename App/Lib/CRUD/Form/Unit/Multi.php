<?php

namespace CRUD\Form\Unit;

abstract class Multi {
  protected $title = '', $width = null, $name = '', $must = false, $obj = null;

  public function __construct(string $name, string $title) {
    $traces = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
    
    foreach ($traces as $trace)
      if (isset($trace['object']) && $trace['object'] instanceof \CRUD\Form\Multi && method_exists($trace['object'], 'appendUnit') && $this->obj = $trace['object'])
        break;

    $this->obj->appendUnit($this);

    $this->title($title);
    $this->name($name);
  }

  public static function create(string $name, $title) {
    return new static($name, $title);
  }
  
  public function title(string $title) {
    $this->title = $title;
    return $this;
  }
  
  public function name(string $name) {
    $this->name = $name;
    return $this;
  }
  
  public function must(bool $must = true) {
    $this->must = $must;
    return $this;
  }
  
  public function width(int $width) {
    $this->width = $width;
    return $this;
  }
  
  public function getWidth() {
    return $this->width;
  }
  
  public function widthString($width) {
    $this->width = $width;
    return $this;
  }

  public function getFormat() {
    return [];
  }
}