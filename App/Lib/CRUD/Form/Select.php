<?php

namespace CRUD\Form;

class Select extends \CRUD\Form\Unit\Items {
  private $val = '', $focus = false, $readonly = false;

  public function val($val) {
    $this->val = $val;
    return $this;
  }

  public function focus(bool $focus = true) {
    $this->focus = $focus;
    return $this;
  }

  public function readonly(bool $readonly = true) {
    $this->readonly = $readonly;
    return $this;
  }

  protected function getContent() {
    $value = (is_array(\CRUD\Form::$flash) ? array_key_exists($this->name, \CRUD\Form::$flash) : \CRUD\Form::$flash[$this->name] !== null) ? \CRUD\Form::$flash[$this->name] : $this->val;

    $attrs = [
      'name' => $this->name,
    ];

    $this->focus    && $attrs['autofocus'] = true;
    $this->must     && $attrs['required'] = true;
    $this->readonly && $attrs['disabled'] = $this->readonly;
    $this->readonly && $attrs['required'] = false;

    $class = implode(' ', ['form-select', $this->must ? 'must' : null]);
    $return = '';
    $return .= '<label' . attr(['class' => $class]) . '>';
      $return .= '<b>' . $this->title . '</b>';
      $return .= $this->tip !== '' ? '<span>' . $this->tip . '</span>' : '';
      $return .= '<div>';
        $return .= '<select' . attr($attrs) .'>';
          $return .= '<option value=""' . ($value == '' ? ' selected' : '') . '>請選擇' . $this->title . '</option>';
          $return .= implode('', array_map(function($item) use ($value) {
            return '<option value="' . $item['value'] . '"' . ($value == $item['value']  ? ' selected' : '') . '>' . $item['text'] . '</option>';
          }, $this->items));
        $return .= '</select>';
      $return .= '</div>';
    $return .= '</label>';

    return $return;
  }
}