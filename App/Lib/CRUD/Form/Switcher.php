<?php

namespace CRUD\Form;

class Switcher extends Unit {
  private $val, $on, $off;

  public function val($val) {
    $this->val = $val;
    return $this;
  }

  public function on($on) {
    $this->on = $on;
    return $this;
  }

  public function off($off) {
    $this->off = $off;
    return $this;
  }

  protected function getContent() {

    $this->on !== null  || gg('請設定 Switcher 啟用值(on)！');
    $this->off !== null || gg('請設定 Switcher 關閉值(off)！');
    $this->val === $this->on || $this->val === $this->off || gg('Switcher 預設值請設定為 啟用值(on) 或 關閉值(off) 其中一項！');
    $value = (is_array(\CRUD\Form::$flash) ? array_key_exists($this->name, \CRUD\Form::$flash) : \CRUD\Form::$flash[$this->name] !== null) && (\CRUD\Form::$flash[$this->name] === $this->on || \CRUD\Form::$flash[$this->name] === $this->off) ? \CRUD\Form::$flash[$this->name] : $this->val;

    $class = implode(' ', array_filter(['form-switch', $this->must ? 'must' : null, $this->class], function($t) { return $t !== null && $t !== ''; }));
    $return = '';
    $return .= '<div' . attr(['class' => $class, 'id' => $this->id]) . '>';
      $return .= '<b>' . $this->title . '</b>';
      $return .= '<div>';
        $return .= '<label>';
          $return .= '<input type="checkbox" name="' . $this->name . '" value="' . $this->on . '" data-off="' . $this->off . '"' . ($value !== null && $value == $this->on ? ' checked' : '') . '/>';
          $return .= '<span></span>';
        $return .= '</label>';
      $return .= '</div>';
      $return .= $this->tip !== '' ? '<span>' . $this->tip . '</span>' : '';
    $return .= '</div>';

    return $return;
  }
}