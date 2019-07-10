<?php

return [
  // // 巢狀
  // 'parent' => [ // 巢狀父層
  //   'title'          => '', // 文章管理
  //   'routerUri'      => '', // article
  //   'controllerName' => '', // Article
  //   'modelName'      => '', // \M\Article
  // ],
  
  'title'          => '',   // 文章管理
  'routerUri'      => '',   // articles
  'controllerName' => '',   // Article
  'modelName'      => '',   // \M\Article
  'enable'         => true, // 是否有「啟用」的功能，enable 欄位
  'sort'           => true, // 是否有「排序」的功能，sort 欄位

  'images' => [
    // ['need' => true, 'name' => 'cover', 'text' => '封面', 'accept' => 'image/*', 'formats' => ['jpg', 'png', 'jpeg']]
  ],
  'texts' => [
    // ['need' => true, 'name' => 'title', 'text' => '標題', 'type' => 'text']
  ],
  'textareas' => [
    // ['need' => true, 'name' => 'content', 'text' => '內容', 'type' => 'ckeditor']
  ],
];
