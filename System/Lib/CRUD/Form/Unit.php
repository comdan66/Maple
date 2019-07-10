<?php

namespace CRUD\Form;

abstract class Unit {
  protected $title, $tip, $name, $must, $obj, $className;

  public function __construct($name, $title) {
    $traces = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
    
    foreach ($traces as $trace)
      if (isset($trace['object']) && $trace['object'] instanceof \CRUD\Form && method_exists($trace['object'], 'appendUnit') && $this->obj = $trace['object'])
        break;

    $this->obj->appendUnit($this);
    $this->title($title);
    $this->name($name);
  }

  public static function create($name, $title) {
    return new static($name, $title);
  }
  
  public function className($className) {
    $this->className = $className;
    return $this;
  }
  
  public function title($title) {
    $this->title = $title;
    return $this;
  }
  
  public function tip($tip) {
    $this->tip = $tip;
    return $this;
  }
  
  public function name($name) {
    $this->name = $name;
    return $this;
  }
  
  public function must($must = true) {
    $this->must = $must;
    return $this;
  }

  protected function getContent() {
    return '';
  }

  public function __toString() {
    $attrs = [];
    $this->must && $attrs['class'] = 'must';
    $this->tip && $attrs['data-tip'] = $this->tip;

    if ($this instanceof \CRUD\Form\Input && $this->type() == 'hidden')
      return $this->getContent();

    $return = '';
    $return .= '<div class="row' . ($this instanceof \CRUD\Form\Switcher ? ' min' : '') . ($this->className ? ' ' . $this->className : '') . '">';
      $return .= '<b' . attr($attrs) . '>' . $this->title . '</b>';
      $return .= $this->getContent();
    $return .= '</div>';

    return $return;
  }
}