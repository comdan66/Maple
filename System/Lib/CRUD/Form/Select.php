<?php

namespace CRUD\Form;

class Select extends \CRUD\Form\Unit\Items {
  private $val = '', $focus, $readonly;

  public function val($val) {
    $this->val = $val;
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

  protected function getContent() {
    $value = (is_array(\CRUD\Form::$flash) ? array_key_exists($this->name, \CRUD\Form::$flash) : \CRUD\Form::$flash[$this->name] !== null) ? \CRUD\Form::$flash[$this->name] : $this->val;

    $attrs = [
      'name' => $this->name,
    ];
    $this->focus && $attrs['autofocus'] = $this->focus;
    $this->need && $attrs['required'] = true;
    $this->readonly && ($attrs['disabled'] = $this->readonly) && $attrs['required'] = false;

    $return = '';
    $return .= '<select' . attr($attrs) .'>';
      $return .= '<option value=""' . ($value == '' ? ' selected' : '') . '>請選擇' . $this->title . '</option>';
      $return .= implode('', array_map(function($item) use ($value) {
        return '<option value="' . $item['value'] . '"' . ($value == $item['value']  ? ' selected' : '') . '>' . $item['text'] . '</option>';
      }, $this->items));
    $return .= '</select>';

    return $return;
  }
}