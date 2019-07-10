<?php

namespace CRUD\Table;

abstract class Unit {
  protected $title, $val, $class, $width, $order, $obj;
  
  public function __construct($title = null) {
    $traces = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
    
    if (!($this instanceof Sort))
      foreach ($traces as $trace)
        if (isset($trace['object']) && $trace['object'] instanceof \CRUD\Table && method_exists($trace['object'], 'appendUnit') && $trace['object']->appendUnit($this))
          break;

    foreach ($traces as $trace)
      if (isset($trace['function']) && $trace['function'] == '{closure}' && $trace['args'] && isset($trace['args'][0]) && $trace['args'][0] instanceof \M\Model && $this->obj($trace['args'][0]))
        break;

    $this->title($title);
  }

  public static function create($title = null) {
    return new static($title);
  }

  public function obj($obj) {
    $this->obj = $obj;
    return $this;
  }

  public function getObj() {
    return $this->obj;
  }

  public function title($title) {
    $this->title = $title;
    return $this;
  }
  
  public function val($val) {
    $this->val = $val;
    return $this;
  }

  public function getVal() {
    return $this->val;
  }
  
  public function className($class) {
    $this->class = $class;
    return $this;
  }
  
  public function align($align) {
    if (!in_array($align, ['left', 'center', 'right', 'c', 'l', 'r']))
      return $this;
    
    switch (strtolower($align)) {
      case 'l': case 'left':
        $this->class .= ($this->class ? ' ' : '') . 'left';
        break;
      case 'c': case 'center':
        $this->class .= ($this->class ? ' ' : '') . 'center';
        break;
      case 'r': case 'right':
        $this->class .= ($this->class ? ' ' : '') . 'right';
        break;
    }
    return $this;
  }

  public function width($width) {
    $this->width = $width;
    return $this;
  }

  public function order($order) {
    $this->order = $order;
    return $this;
  }

  public function attrs() {
    $attrs = [];
    $this->class && $attrs['class'] = $this->class;
    $this->width && $attrs['width'] = $this->width;
    return attr($attrs);
  }

  public function __toString() {
    return $this->tdString();
  }

  public function tdString() {
    return '<td' . $this->attrs() . '>' . $this->getVal() . '</td>';
  }

  public function thString($sortUrl) {
    return '<th' . $this->attrs() . '>' . Order::set($this->title, $sortUrl ? '' : $this->order) . '</th>';
  }
}