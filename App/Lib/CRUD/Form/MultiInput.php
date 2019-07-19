<?php

namespace CRUD\Form;

class MultiInput extends \CRUD\Form\Unit\Multi {
  private $type = 'text',
          $placeholder = null,
          $minLength = null,
          $maxLength = null,
          $min = null,
          $max = null,
          $step = null,
          $val = '';

  public function val($val = '') {
    $this->val = $val;
    return $this;
  }

  public function type(string $type = null) {
    $this->type = $type ?? 'text';
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
  
  public function step(float $step) {
    $this->step = $step;
    return $this;
  }
  
  public function min(int $min) {
    $this->min = $min;
    return $this;
  }
  
  public function max(int $max) {
    $this->max = $max;
    return $this;
  }

  public function getFormat() {
    return [
      'name' => $this->name,
      'width' => $this->width,
      'value' => $this->val,
      'attrs' => array_filter([
        'title' => $this->title,
        'type' => $this->type,
        'placeholder' => $this->placeholder ?? '請輸入' . $this->title . '…',
        'required' => $this->must ? true : false,
        'minlength' => $this->minLength,
        'maxlength' => $this->maxLength,
        'min' => $this->type == 'number' ? $this->min : null,
        'max' => $this->type == 'number' ? $this->max : null,
        'step' => $this->type == 'number' ? $this->step : null,
        'data-optional' => !$this->must,
      ], function($t) { return $t !== null; })
    ];
  }
}