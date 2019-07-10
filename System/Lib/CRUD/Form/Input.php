<?php

namespace CRUD\Form;

class Input extends Unit {
  private $type = 'text', $placeholder, $focus, $minLength, $maxLength, $min, $max, $val = '', $readonly;

  public function val($val = '') {
    $this->val = $val;
    return $this;
  }

  public function type($type = null) {
    if ($type === null)
      return $this->type;

    $this->type = $type;
    return $this;
  }

  public function focus($focus = true) {
    $this->focus = $focus;
    return $this;
  }

  public function placeholder($placeholder = '') {
    $this->placeholder = $placeholder;
    return $this;
  }
  
  public function minLength(int $minLength) {
    $this->minLength = $minLength;
    return $this;
  }
  
  public function maxLength(int $maxLength) {
    $this->maxLength = $maxLength;
    return $this;
  }

  public function readonly($readonly = true) {
    $this->readonly = $readonly;
    return $this;
  }
  
  // for type=number
  public function min($min) {
    $this->min = $min;
    return $this;
  }
  
  // for type=number
  public function max($max) {
    $this->max = $max;
    return $this;
  }

  protected function getContent() {
    $value = (is_array(\CRUD\Form::$flash) ? array_key_exists($this->name, \CRUD\Form::$flash) : \CRUD\Form::$flash[$this->name] !== null) ? \CRUD\Form::$flash[$this->name] : $this->val;
    $this->must && ($this->minLength === null || $this->minLength <= 0) && $this->minLength(1);

    $attrs = [];
    $attrs = [
      'type'  => $this->type,
      'name'  => $this->name,
      'value' => $value,
    ];

    $this->placeholder !== null || $this->placeholder = '請輸入' . $this->title . '…';

    $this->must && $attrs['required'] = true;
    $this->focus && $attrs['autofocus'] = true;
    $this->minLength && $attrs['minlength'] = $this->minLength;
    $this->maxLength && $attrs['maxlength'] = $this->maxLength;
    $this->placeholder && $attrs['placeholder'] = $this->placeholder;
    $this->readonly && $attrs['readonly'] = true;
    
    if ($this->type == 'number') {
      $this->min === null || $attrs['min'] = $this->min;
      $this->max === null || $attrs['max'] = $this->max;
    }

    return '<input' . attr($attrs) .'/>';
  }
}