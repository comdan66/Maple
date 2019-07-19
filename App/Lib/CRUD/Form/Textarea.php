<?php

namespace CRUD\Form;

class Textarea extends Unit {
  private $type = 'pure',
          $placeholder = null,
          $focus = false,
          $minLength = null,
          $maxLength = null,
          $val = '',
          $readonly = false;

  public function val($val = '') {
    $this->val = $val;
    return $this;
  }

  public function type(string $type = null) {
    $this->type = $type ?? 'pure';
    return $this;
  }

  public function focus(bool $focus = true) {
    $this->focus = $focus;
    return $this;
  }

  public function placeholder(string $placeholder) {
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

  protected function getContent() {
    $value = (is_array(\CRUD\Form::$flash) ? array_key_exists($this->name, \CRUD\Form::$flash) : \CRUD\Form::$flash[$this->name] !== null) ? \CRUD\Form::$flash[$this->name] : $this->val;
    $this->must && ($this->minLength === null || $this->minLength <= 0) && $this->minLength(1);

    $attrs = [
      'class'  => $this->type,
      'name'  => $this->name,
    ];

    isset($this->placeholder) || $attrs['placeholder'] = '請輸入' . $this->title . '…';
    $this->must && $attrs['required'] = true;
    $this->focus && $attrs['autofocus'] = true;
    $this->readonly && $attrs['readonly'] = true;
    $this->readonly && $attrs['required'] = false;
    $this->must || $attrs['data-optional'] = 'true';
    isset($this->minLength) && $attrs['minlength'] = $this->minLength;
    isset($this->maxLength) && $attrs['maxlength'] = $this->maxLength;

    $class = implode(' ', array_filter([$this->type == 'ckeditor' ? 'form-ckeditor' : 'form-text', $this->must ? 'must' : null, $this->class], function($t) { return $t !== null && $t !== ''; }));
    $return = '';
    $return .= '<label' . attr(['class' => $class, 'id' => $this->id]) . '>';
      $return .= '<b>' . $this->title . '</b>';
      $return .= $this->tip !== '' ? '<span>' . $this->tip . '</span>' : '';
      $return .= '<div>';
        $return .= '<textarea' . attr($attrs) .'>' . $value . '</textarea>';
      $return .= '</div>';
    $return .= '</label>';
    
    return $return;
  }
}