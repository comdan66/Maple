<?php

namespace CRUD\Table;

class ChoiceBox extends Unit {
  private $id, $name;
  private $attrs = ['class' => 'checkbox', 'data-feature' => 'choicebox', 'data-method' => 'post'];

  public function method(string $method) { in_array($method = strtolower($method), ['get', 'post', 'put', 'delete']) && $this->attrs['data-method'] = $method; return $this; }
  public function action(string $action) { $this->attrs['data-action'] = $action; return $this; }
  public function router(string $router) { return $this->action(call_user_func_array('\Url::router', func_get_args())); }
  public function id(int $id) { $this->id = $id; return $this; }
  public function name($name) { $this->name = '' . $name; return $this; }

  public static function create(string $title) {
    $obj = new static($title);
    return $obj->width(80)->align('center');
  }

  public function getVal() {
    $id = $this->id ?? $this->obj;
    $id instanceof \M\Model && $id = $id->id ?? '';

    $id || \gg(get_called_class() . ' ID 值錯誤！');
    isset($this->attrs['data-action']) || \gg(get_called_class() . ' 未設定 Action 的值！');
    
    $this->attrs['data-type'] = $this->title;
    $this->attrs['data-id'] = $id;
    $this->attrs['data-name'] = $this->name ?? $id;

    $return = '';
    $return .= '<label class="choice-box">';
      $return .= '<input type="checkbox"' . attr($this->attrs) . '/>';
      $return .= '<span></span>';
    $return .= '</label>';

    return $return;
  }
}