<?php

namespace CRUD\Table\Search;

class Input extends \CRUD\Table\Search {
  private $type;
  
  public function type($type) {
    $this->type = $type;
    return $this;
  }

  public function __toString() {
    $return = '';
    $return .= '<label class="row">';
    $return .= '<b>' . $this->title . '</b>';
    $return .= '<input name="' . $this->key . '" type="' . ($this->type ? $this->type : 'text') . '" placeholder="依' . $this->title . '搜尋…" value="' . $this->val . '" />';
    $return .= '</label>';
    return $return;
  }
}