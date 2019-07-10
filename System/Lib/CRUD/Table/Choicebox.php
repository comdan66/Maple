<?php

namespace CRUD\Table;

class Choicebox extends Unit {
  private $attrs = ['class' => 'checkbox', 'data-feature' => 'choicebox'];

  public function method($method) { $this->attrs['data-method'] = $method; return $this; }
  public function action($action) { $this->attrs['data-action'] = $action; return $this; }
  public function router($router) { return $this->action(\Url::router($action)); }
  public function id($id) { $this->attrs['data-id'] = $id; return $this; }
  public function name($name) { $this->attrs['data-name'] = $name; return $this; }

  // public static function create($title = null) {
  //   return new static($title);
  // }

  public function getVal() {
    isset($this->attrs['data-method']) || $this->attrs['data-method'] = 'post';
    isset($this->attrs['data-action']) || \gg('\CRUD\Table\Choicebox 未設定 Action 的值！');
    isset($this->attrs['data-id'])     || \gg('\CRUD\Table\Choicebox 未設定 ID 的值！');
    isset($this->attrs['data-name'])   || \gg('\CRUD\Table\Choicebox 未設定 Name 的值！');
    is_string($this->attrs['data-method']) && in_array($this->attrs['data-method'] = strtolower(trim($this->attrs['data-method'])), ['post', 'get', 'delete', 'put']) || \gg('\CRUD\Table\Choicebox Method 的值錯誤！');
    is_numeric($this->attrs['data-id'])    && $this->attrs['data-id'] > 0 || \gg('\CRUD\Table\Choicebox ID 的值錯誤！');
    is_string($this->attrs['data-name'])   && $this->attrs['data-name'] !== '' || \gg('\CRUD\Table\Choicebox Name 的值錯誤！');

    $this->attrs['data-type'] = $this->title;

    $return = '';
    $return .= '<label' . attr(['class' => 'checkbox']) . '>';
      $return .= '<input type="checkbox"' . attr($this->attrs) . '/>';
      $return .= '<span></span>';
    $return .= '</label>';

    return $return;
  }
}