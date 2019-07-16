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
    $return .= '<div class="search-row">';
      $return .= '<b>' . $this->title . '</b>';
      $return .= '<div class="search-input">';
        $return .= '<input name="' . $this->key . '" type="' . ($this->type ? $this->type : 'text') . '" placeholder="依' . $this->title . '搜尋…" value="' . $this->val . '" />';
      $return .= '</div>';
    $return .= '</div>';
    return $return;
  }
}