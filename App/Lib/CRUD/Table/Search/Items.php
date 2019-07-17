<?php

namespace CRUD\Table\Search;

abstract class Items extends \CRUD\Table\Search {
  protected $items = [];

  public function items(array $items) {
    if (!$items)
      return $this;

    is_string(array_values($items)[0]) && $items = items(array_keys($items), array_values($items));

    $this->items = $items;
    return $this;
  }
}