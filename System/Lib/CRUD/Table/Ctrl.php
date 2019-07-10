<?php

namespace CRUD\Table;

class Ctrl extends Unit {
  private $ctrls = [];

  private function update($title) {
    $width = 14 + (count($this->ctrls) - 1) * 22 + 4 * 2 + 2 * 2;
    $this->className('ctrls');
    $this->title(count($this->ctrls) > 1 ? '編輯' : $title);
    $this->width($width < 44 ? 44 : $width);
    return $this;
  }

  public function addShow($name) {
    array_unshift($this->ctrls, \HTML\A::create()->href(call_user_func_array('Url::router', func_get_args()) . \CRUD::backOffsetLimit())->class('show'));
    return $this->update('檢視');
  }

  public function addEdit($name) {
    array_unshift($this->ctrls, \HTML\A::create()->href(call_user_func_array('Url::router', func_get_args()) . \CRUD::backOffsetLimit())->class('edit'));
    return $this->update('修改');
  }

  public function addDelete($name) {
    array_unshift($this->ctrls, \HTML\A::create()->href(call_user_func_array('Url::router', func_get_args()) . \CRUD::backOffsetLimit())->class('delete')->attrs('data-method', 'delete'));
    return $this->update('刪除');
  }

  public function add($hyperlink) {
    array_unshift($this->ctrls, $hyperlink);
    return $this->update('編輯');
  }

  public function getVal() {
    return implode('', $this->ctrls);
  }
}