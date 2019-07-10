<?php

namespace CRUD\Form;

class Radio extends \CRUD\Form\Unit\Items {
  private $val;

  public function val($val) {
    $this->val = $val;
    return $this;
  }

  protected function getContent() {
    $value = (is_array(\CRUD\Form::$flash) ? array_key_exists($this->name, \CRUD\Form::$flash) : \CRUD\Form::$flash[$this->name] !== null) ? \CRUD\Form::$flash[$this->name] : $this->val;

    $return = '';

    $return .= '<div class="radios">';
    $return .= implode('', array_map(function($item) use ($value) {
      $return = '';
      $return .= '<label>';
        $return .= '<input type="radio" name="' . $this->name . '" value="' . $item['value'] . '"' . ($this->must === true ? ' required' : '') . ($value !== null && $value == $item['value']  ? ' checked' : '') . '/>';
        $return .= '<span></span>';
        $return .= $item['text'];
      $return .= '</label>';
      return $return;
    }, $this->items));
    $return .= '</div>';

    return $return;
  }
}

