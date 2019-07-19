<?php

namespace CRUD\Table;

abstract class Unit {
  protected $title, $val, $class, $width, $order, $obj, $align = 'left';
  
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

  public static function create(string $title) {
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
  
  public function __call($name, $arges) {
    if ($name === 'class')
      $this->class = '' . array_shift($arges);
    else
      \gg(get_called_class() . ' 沒有「' . $name . '」此方法');
    return $this;
  }
  
  public function left() { return $this->align('left'); }
  public function center() { return $this->align('center'); }
  public function right() { return $this->align('right'); }

  public function align($align) {
    switch (strtolower($align)) {
      case 'l': case 'left':   $this->align = 'left';   break;
      case 'c': case 'center': $this->align = 'center'; break;
      case 'r': case 'right':  $this->align = 'right';  break;
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
    $attrs['class'] = ($this->class ? $this->class . ' ' : '') . $this->align;
    $this->width && $attrs['width'] = $this->width;
    return \attr($attrs);
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