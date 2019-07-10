<?php

namespace CRUD\Form;

class Textarea extends Unit {
  private $val = '', $type = 'pure', $placeholder, $focus, $minLength, $maxLength, $readonly;

  public function val($val = '') {
    is_string($val) && $this->val = $val;
    return $this;
  }

  // pure, ckeditor
  public function type($type) {
    $this->type = $type;
    return $this;
  }

  public function placeholder($placeholder = '') {
    $this->placeholder = $placeholder;
    return $this;
  }

  public function focus($focus = true) {
    $this->focus = $focus;
    return $this;
  }

  public function readonly($readonly = true) {
    $this->readonly = $readonly;
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

  protected function getContent() {
    $value = (is_array(\CRUD\Form::$flash) ? array_key_exists($this->name, \CRUD\Form::$flash) : \CRUD\Form::$flash[$this->name] !== null) ? \CRUD\Form::$flash[$this->name] : $this->val;
    $this->must && ($this->minLength === null || $this->minLength <= 0) && $this->minLength(1);

    $attrs = [
      'class' => $this->type,
      'name' => $this->name,
    ];

    $this->placeholder !== null || $this->placeholder = '請輸入' . $this->title . '…';

    $this->must && $attrs['required'] = true;
    $this->focus && $attrs['autofocus'] = true;
    $this->minLength && $attrs['minlength'] = $this->minLength;
    $this->maxLength && $attrs['maxlength'] = $this->maxLength;
    $this->placeholder && $attrs['placeholder'] = $this->placeholder;
    $this->readonly && $attrs['readonly'] = true;

    return '<textarea' . attr($attrs) .'>' . $value . '</textarea>';
  }
}