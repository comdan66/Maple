<?php

namespace CRUD\Table;

class EditText extends Text {
  private $type = 'text',
          $placeholder = null,
          $minLength = null,
          $maxLength = null,
          $min = null,
          $max = null,
          $readonly = false,
          $must = true,
          $api = null,
          $column = null;
  
  public function api(string $api) { $this->api = $api; return $this; }
  public function router(string $router) { return $this->api(call_user_func_array('\Url::router', func_get_args())); }
  public function column($column) { $this->column = $column; return $this; }

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

  public function min(int $min) {
    $this->min = $min;
    return $this;
  }
  
  public function max(int $max) {
    $this->max = $max;
    return $this;
  }
  
  public function must(bool $must = true) {
    $this->must = $must;
    return $this;
  }

  public function getVal() {
    $this->api !== null    || gg('\CRUD\Table\EditText 未設定 Ajax 時的 API！');
    $this->column !== null || gg('\CRUD\Table\EditText 未設定要變更的欄位名稱！');

    $attrs = [
      'type'  => $this->type,
      'value' => $this->val,
    ];

    isset($this->placeholder) && $attrs['placeholder'] = '請輸入要修改的' . $this->title . '…';
    $this->must && $attrs['required'] = true;

    isset($this->minLength) && $attrs['minlength'] = $this->minLength;
    isset($this->maxLength) && $attrs['maxlength'] = $this->maxLength;
    
    if ($this->type == 'number') {
      isset($this->min) && $attrs['min'] = $this->min;
      isset($this->max) && $attrs['max'] = $this->max;
    }

    $return = '';
    $return .= '<form class="editable" data-api="' . $this->api . '" data-column="' . $this->column . '">';
      $return .= '<input' . attr($attrs) .'/>';
      $return .= '<span>' . $this->val . '</span>';
    $return .= '</form>';

    return $return;
  }
}