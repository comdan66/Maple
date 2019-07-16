<?php

Router::dir('admin', 'Admin', function() {
  // Auth
  Router::get('logout')->controller('Auth@logout');
  Router::get('login')->controller('Auth@login');
  Router::post('login')->controller('Auth@signin');
  
  // Main
  Router::get()->controller('Main@index');
  Router::post('theme')->controller('Main@theme');

  // Backup
  Router::get('backups')->controller('Backup@index');
  Router::get('backups/(id:id)')->controller('Backup@show');
  Router::post('backups/(id:id)/read')->controller('Backup@read');

  // Crontab
  Router::get('crontabs')->controller('Crontab@index');
  Router::get('crontabs/(id:id)')->controller('Crontab@show');
  Router::post('crontabs/(id:id)/read')->controller('Crontab@read');

  // Admin
  Router::get('admins')->controller('Admin@index');
  Router::get('admins/add')->controller('Admin@add');
  Router::post('admins')->controller('Admin@create');
  Router::get('admins/(id:id)/edit')->controller('Admin@edit');
  Router::put('admins/(id:id)')->controller('Admin@update');
  Router::get('admins/(id:id)')->controller('Admin@show');
  Router::delete('admins/(id:id)')->controller('Admin@delete');
  Router::post('admins/(id:id)/column/name')->controller('Admin@columnName');
  
  // AdminLog
  Router::get('admin/(adminId:id)/logs/(id:id)')->controller('AdminLog@show');

  // Ckeditor
  Router::post('ckeditor/image/upload')->controller('Ckeditor@imageUpload');
  Router::get('ckeditor/image/browse')->controller('Ckeditor@imageBrowse');

  // AdminAjaxError
  Router::get('ajaxErrors')->controller('AdminAjaxError@index');
  Router::post('ajaxErrors')->controller('Main@ajaxErrorCreate');
  Router::get('ajaxErrors/(id:id)')->controller('AdminAjaxError@show');
  Router::post('ajaxErrors/(id:id)/read')->controller('AdminAjaxError@read');
});