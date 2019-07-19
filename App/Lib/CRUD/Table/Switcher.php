<?php

namespace CRUD\Table;

class Switcher extends Unit {
  private $on, $off, $api, $column, $label;

  public static function create(string $title) {
    $obj = new static($title);
    return $obj->width(56)->align('center');
  }

  public function on($on) { $this->on = $on; return $this; }
  public function off($off) { $this->off = $off; return $this; }
  public function api(string $api) { $this->api = $api; return $this; }
  public function router(string $router) { return $this->api(call_user_func_array('\Url::router', func_get_args())); }
  public function column($column) { $this->column = $column; return $this; }
  public function label($label) { $this->label = $label; return $this; }

  public function getVal() {
    $this->api !== null    || gg('\CRUD\Table\Switcher 未設定 Ajax 時的 API！');
    $this->column !== null || gg('\CRUD\Table\Switcher 未設定要變更的欄位名稱！');
    $this->on !== null     || gg('\CRUD\Table\Switcher 未設定 ON 的值！');
    $this->off !== null    || gg('\CRUD\Table\Switcher 未設定 OFF 的值！');

    $return = '';
    $return .= '<label class="switch ajax" data-api="' . $this->api . '" data-column="' . $this->column . '"data-true="' . $this->on . '" data-false="' . $this->off . '"' . (isset($this->column) ? ' data-cntlabel="' . $this->label . '"' : '') . '>';
      $return .= '<input type="checkbox"' . ($this->obj->{$this->column} == $this->on ? ' checked' : '') . '/>';
      $return .= '<span></span>';
    $return .= '</label>';

    return $return;
  }
}