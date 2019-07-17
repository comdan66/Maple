<?php

namespace CRUD\Form;

abstract class Unit {
  protected $title = '', $tip = '', $name = '', $must = false, $obj = null;

  public function __construct(string $name, string $title) {
    $traces = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
    
    foreach ($traces as $trace)
      if (isset($trace['object']) && ($trace['object'] instanceof \CRUD\Form || $trace['object'] instanceof \CRUD\Form\Group) && method_exists($trace['object'], 'appendUnit') && $this->obj = $trace['object'])
        break;

    if ($this->obj instanceof \CRUD\Form) {
      $this->obj->appendUnitToGroup($this);
    } else {
      $this->obj->appendUnit($this);
    }
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
  
  public function tip(string $tip) {
    $this->tip = $tip;
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

  protected function getContent() {
    return '';
  }

  public function __toString() {
    return $this->getContent();
  }
}