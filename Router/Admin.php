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

  // Ckeditor
  Router::post('ckeditor/image/upload')->controller('Ckeditor@imageUpload');
  Router::get('ckeditor/image/browse')->controller('Ckeditor@imageBrowse');
});