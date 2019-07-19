<?php

namespace CRUD;

class Form extends \CRUD {
  private $action;
  private $str;

  private $obj;
  private $defaultGroup = null;
  private $groups = [];
  private $units = [];
  private $hasImage = false;
  public static $flash;
  
  public static function create(\M\Model $obj = null) {
    return new static($obj);
  }

  public function __construct(\M\Model $obj = null) {
    $this->obj = $obj;

    $traces = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
    foreach ($traces as $trace)
      if (isset($trace['object']) && $trace['object'] instanceof \AdminController && isset($trace['object']->flash['params']) && $this->setFlash($trace['object']->flash['params']))
        break;
  }
  
  public function appendUnitToGroup(\CRUD\Form\Unit $unit) {
    if ($this->defaultGroup === null) {
      $this->defaultGroup = \CRUD\Form\Group::create();
    }
    $this->defaultGroup->appendUnit($unit);
    return $this;
  }
  
  public function appendGroup(\CRUD\Form\Group $group) {
    array_push($this->groups, $group);
    return $this;
  }
  
  public function setFlash($flash) {
    Form::$flash = $flash;
    return $this;
  }
  
  public function hasImage(bool $hasImage = true) {
    $this->hasImage = $hasImage;
    return $this;
  }
  
  public function setActionUrl($action) {
    $this->action = $action;
    return $this;
  }

  public function setActionRouter($router) {
    return $this->setActionUrl(call_user_func_array('\Url::router', func_get_args()));
  }

  private function closure($obj) {
    $closure = $this->closure;
    $closure($obj);
  }

  public function form($closure) {
    if ($this->str)
      return $this->str;

    $this->action || gg('請設定 Action 網址！');

    $this->units = [];
    
    $title = null;
    $closure($this->obj);

    $this->str = '';
    
    if (!$this->groups)
      return $this->str;
    
    $groups = implode('', $this->groups);

    $this->str .= '<div class="panel"' . ($title ? ' data-title="' . $title . '"' : '') . '>';
      $this->str .= '<form class="form" action="' . $this->action . '" method="post"' . ($this->hasImage ? ' enctype="multipart/form-data"' : '') . '>';
        $this->str .= $this->obj ? '<input type="hidden" name="_method" value="put" />' : '';

        $this->str .= $groups;

        $this->str .= '<div class="form-ctrl">';
          $this->str .= '<button type="reset">重填</button>';
          $this->str .= '<button type="submit">確定</button>';
        $this->str .= '</div>';
      $this->str .= '</form>';
    $this->str .= '</div>';
  
    $this->units = [];
    return $this->str;
  }

  public function appendUnit(\CRUD\Form\Unit $unit) {
    array_push($this->units, $unit);
    return $this;
  }

  public function &obj() {
    return $this->obj;
  }
}
