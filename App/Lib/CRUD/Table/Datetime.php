<?php

namespace CRUD\Table;

class Datetime extends Unit {
  public static function create(string $title) {
    $obj = new static($title);
    return $obj->width(120);
  }

  public function val($val) {
    if (!isDatetime($val)) return $this;
    $val instanceof \_M\DateTime || $val instanceof \DateTime || $val = \DateTime::createFromFormat('Y-m-d H:i:s', $val);
    parent::val($val
      ? '<div class="datetime"><span>' . $val->format('Y-m-d') . '</span><span>&nbsp;' . $val->format('H:i:s') . '</span></div>'
      : '');
    return $this;
  }
}