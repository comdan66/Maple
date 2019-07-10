<?php

namespace CRUD\Table;

class Switcher extends Unit {
  private $on, $off, $url, $column, $label;

  private function update() {
    return $this->width(56)->className('center');
  }
  
  public function on($on) {
    $this->on = $on;
    return $this->update();
  }

  public function off($off) {
    $this->off = $off;
    return $this->update();
  }

  public function url($url) {
    $this->url = $url;
    return $this->update();
  }

  public function column($column) {
    $this->column = $column;
    return $this->update();
  }

  public function label($label) {
    $this->label = $label;
    return $this->update();
  }

  public function getVal() {
    $this->on !== null     || gg('\CRUD\Table\Switcher 未設定 ON 的值！');
    $this->off !== null    || gg('\CRUD\Table\Switcher 未設定 OFF 的值！');
    $this->url !== null    || gg('\CRUD\Table\Switcher 未設定 Ajax 時的 Url！');
    $this->column !== null || gg('\CRUD\Table\Switcher 未設定要變更的欄位名稱！');

    $attrs = [
      'class' => 'switch ajax',
      'data-url' => $this->url,
      'data-column' => $this->column,
      'data-true' => $this->on,
      'data-false' => $this->off,
    ];

    $this->column === null || $attrs['data-cntlabel'] = $this->label;

    $return = '';
    $return .= '<label ' . attr($attrs) . '>';
      $return .= '<input type="checkbox"' . ($this->obj->{$this->column} == $this->on ? ' checked' : '') . '/>';
      $return .= '<span></span>';
    $return .= '</label>';

    return $return;
  }
}