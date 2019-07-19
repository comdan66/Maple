<?php

namespace CRUD\Table;

class Ctrl extends Unit {
  private $ctrls = [], $isIconType = true;

  public static function create($title = null) {
    $obj = new static($title);
    return $obj->class('ctrl')->center();
  }

  public function isIconType(bool $isIconType) {
    $this->isIconType = $isIconType;
    return $this;
  }

  private function update($title) {
    $c = count($this->ctrls);
    $width = 20 + $c * ($this->isIconType ? 13 : 26) + ($c - 1) * 9 + 1 * 2;
    $width > 50 || $width = 50;

    $this->title(count($this->ctrls) > 1 ? '操作' : $title);
    $this->width($width);
    return $this;
  }

  public function setShowRouter($name) {
    array_push($this->ctrls, '<a href="' . call_user_func_array('Url::router', func_get_args()) . \CRUD::backOffsetLimit() . '" class="show"></a>');
    return $this->update('檢視');
  }

  public function setEditRouter($name) {
    array_push($this->ctrls, '<a href="' . call_user_func_array('Url::router', func_get_args()) . \CRUD::backOffsetLimit() . '" class="edit"></a>');
    return $this->update('修改');
  }

  public function setDeleteRouter($name) {
    array_push($this->ctrls, '<a href="' . call_user_func_array('Url::router', func_get_args()) . \CRUD::backOffsetLimit() . '" class="delete" data-method="delete"></a>');
    return $this->update('刪除');
  }

  public function appendLink($hyperlink) {
    array_push($this->ctrls, $hyperlink);
    return $this->update('編輯');
  }

  public function getVal() {
    return $this->ctrls
      ? '<div class="ctrl' . ($this->isIconType ? ' icon' : '') . '">' . implode('', $this->ctrls) . '</div>'
      : '';
  }
}