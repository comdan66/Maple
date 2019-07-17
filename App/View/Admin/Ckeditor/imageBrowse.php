<?php

use \CRUD\Table\Search\Input as Input;

echo $table->search(function() {
  
  Input::create('ID')
       ->sql('id = ?');

  Input::create('上傳時間')
       ->type('date')
       ->sql('createAt LIKE ?');
});

echo "<div id='imageBrowse'>" . implode('', array_map(function($obj) { return \HTML\Figure::create()->attrs(['data-datetime' => $obj->createAt->format('Y-m-d H:i:s'), 'data-bgurl' => $obj->image->url()]); }, $table->objs())) . "</div>";