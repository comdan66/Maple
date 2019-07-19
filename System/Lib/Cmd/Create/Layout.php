<?php

namespace CMD\Create;

use \CMD\Layout\Menu  as Menu;
use \CMD\Layout\Input as Input;
use \CMD\Str          as Str;
use \CMD\Tool         as Tool;
use \CMD\Display      as Display;

class Layout {
  private static function columnDouble($c1, $c2) {
    if (!$c1) return false;
    if (!$c2) return false;
    foreach ($c2 as $c) if (in_array($c, $c1)) return true;
    return false;
  }

  private static function scanModel($path) {
    return arrayFlatten(array_values(array_filter(array_map(function($name) use ($path) {
      if (is_dir($path .  $name) && !in_array($name, ['.', '..']))
        return self::scanModel($path .  $name . DIRECTORY_SEPARATOR);
      if (is_file($path .  $name) && pathinfo($name, PATHINFO_EXTENSION) == 'php')
        return pathinfo($name, PATHINFO_FILENAME);
    }, @scandir($path) ?: []))));
  }

  private static function splitColumn($columns) {
    return array_filter(array_unique(preg_split('/\s+/', is_string($columns) ? $columns : '')), function($t) {
      return $t !== '';
    });
  }

  private static function setCRUDFocus(&$column1s, &$column2s) {
    $hasFocus = false;
    foreach ($column1s as &$column1) { if ($hasFocus) $column1['focus'] = false; if ($column1['focus'] === true) $hasFocus = true; }
    foreach ($column2s as &$column2) { if ($hasFocus) $column2['focus'] = false; if ($column2['focus'] === true) $hasFocus = true; }
    if ($hasFocus) return;
    foreach ($column1s as &$column1) { if ($hasFocus) $column1['focus'] = false; $column1['focus'] = true; $hasFocus = true; break; }
    if ($hasFocus) return;
    foreach ($column2s as &$column2) { if ($hasFocus) $column2['focus'] = false; $column2['focus'] = true; $hasFocus = true; break; }
  }

  public static function validatorModel() {
    $args         = func_get_args();
    $modelName    = array_shift($args);
    $imageColumns = array_shift($args);
    $fileColumns  = array_shift($args);

    $modelNames = self::scanModel(PATH_APP_MODEL);
    $errors = [];
    
    in_array($modelName, $modelNames)
      && array_push($errors, 'Model 名稱重複。');

    $imageColumns = array_filter(array_unique(preg_split('/\s+/', is_string($imageColumns) ? $imageColumns : '')), function($t) { return $t !== ''; });
    $fileColumns  = array_filter(array_unique(preg_split('/\s+/', is_string($fileColumns) ? $fileColumns : '')), function($t) { return $t !== ''; });

    self::columnDouble($imageColumns, $fileColumns)
      && array_push($errors, '檔案上傳器有欄位與圖片上傳器欄位衝突。');

    return $errors;
  }
  
  public static function createMigration() {
    \Load::systemFunc('File') ?: Display::error('載入 System/Func/File 失敗！');
    
    $args  = func_get_args();
    $input = array_shift($args);
    $name  = array_shift($args);

    is_writable(PATH_MIGRATION)   ?: Display::error('您的 Migration 目錄沒有讀寫權限！');
    \Load::systemLib('Migration') ?: Display::error('載入 System/Lib/Migration 失敗！');

    $files = array_keys(\Migration::files());
    $nextVersion = $files ? end($files) + 1 : 1;
    $path = PATH_MIGRATION . sprintf('%03s-%s.php', $nextVersion, $name);

    file_exists($path) && Display::error('Migration 名稱重複！');

    $args = preg_split('/\s/', $name);
    
    switch (strtolower(array_shift($args))) {
      case 'create':
        $tName = array_shift($args);
        $tName = $tName === null ? '{資料表}' : $tName;
        
        $migrationStr = Tool::getTemplate('Migration.template', [
          'type' => 'create',
          'tName' => $tName]);
        break;

      case 'drop':
        $tName = array_shift($args);
        $tName = $tName === null ? '{資料表}' : $tName;
        $migrationStr = Tool::getTemplate('Migration.template', [
          'type' => 'drop',
          'tName' => $tName]);
        break;

      case 'alter':
        $tName  = array_shift($args);
        $action = strtolower(array_shift($args));
        $field  = array_shift($args);

        $tName = $tName === null ? '{資料表}' : $tName;
        in_array($action, ['add', 'drop', 'change']) || $action = '';
        $field = $field === null ? '{欄位名稱}' : $field;

        $migrationStr = Tool::getTemplate('Migration.template', [
          'type' => 'alter' . $action,
          'tName' => $tName,
          'field' => $field]);
        break;
      
      defaultLable:
      default:
        $migrationStr = Tool::getTemplate('Migration.template', ['type' => null]);
        break;
    }

    fileWrite($path, $migrationStr);
    file_exists($path) || Display::error('Migration 寫入失敗！');

    Display::title('完成');
    Display::markListLine('新增 Migration「' . Display::colorBoldWhite($name) . '」成功。');
    Display::markListLine('Migration 檔案位置' . Display::markSemicolon() . Display::colorBoldWhite(Tool::depath($path)));
    echo Display::LN;
  }

  public static function createModel() {
    \Load::systemFunc('File') ?: Display::error('載入 System/Func/File 失敗！');
    
    $args         = func_get_args();
    $input        = array_shift($args);
    $modelName    = array_shift($args);
    $imageColumns = array_shift($args);
    $fileColumns  = array_shift($args);

    $imageColumns = self::splitColumn($imageColumns);
    $fileColumns  = self::splitColumn($fileColumns);

    $modelStr = Tool::getTemplate('Model.template', [
      'modelName'    => $modelName,
      'space'        => Str::repeat(Str::width($modelName)),
      'imageColumns' => $imageColumns,
      'fileColumns'  => $fileColumns,
    ]);

    is_writable(PATH_APP_MODEL) || Display::error('您的 Model 目錄沒有讀寫權限！');
    
    $path = PATH_APP_MODEL . $modelName . '.php';
    \fileWrite($path, $modelStr, 'x');

    $exists = file_exists($path);

    Display::title('完成');
    Display::markListLine('新增 Model「' . Display::colorBoldWhite($modelName) . '」成功，檔案' . Display::markSemicolon() . Display::colorBoldWhite(Tool::depath($path)));
    Display::markListLine('圖片上傳器欄位' . Display::markSemicolon() . ($imageColumns ? implode(\Xterm::black('、', true)->blod(), array_map('\CMD\Display::colorBoldWhite', $imageColumns)) : \Xterm::black('無', true)->dim()));
    Display::markListLine('檔案上傳器欄位' . Display::markSemicolon() . ($fileColumns ?  implode(\Xterm::black('、', true)->blod(), array_map('\CMD\Display::colorBoldWhite', $fileColumns))  : \Xterm::black('無', true)->dim()));
    echo Display::LN;
  }

  public static function notEmptyString($arr) {
    $args = func_get_args();
    array_shift($args);
    foreach ($args as $arg)
      if (!(isset($arr[$arg]) && $arr[$arg] !== ''))
        return false;
    return true;
  }

  public static function validatorConfigCRUD() {
    \Load::systemFunc('File') ?: Display::error('載入 System/Func/File 失敗！');

    $config = config('CRUD');
    
    if (!self::notEmptyString($config, 'dir', 'title', 'routerUri', 'controllerName', 'modelName'))
      Display::error('Config 格式有誤！');

    $errors = [];

    $dir                  = trim($config['dir'], DIRECTORY_SEPARATOR);
    $title                = $config['title'];
    $routerUri            = $config['routerUri'];
    $controllerName       = $config['controllerName'];
    $modelName            = $config['modelName'];
    $enable               = $config['enable'] ?? false;
    $sort                 = $config['sort']   ?? false;
    $parentTitle          = $config['parent']['title'] ?? null;
    $parentRouterUri      = $config['parent']['routerUri'] ?? null;
    $parentControllerName = $config['parent']['controllerName'] ?? null;
    $parentModelName      = $config['parent']['modelName'] ?? null;

    if (isset($parentTitle, $parentRouterUri, $parentControllerName, $parentModelName)) {
      preg_match_all('/^\\\M\\\.+/', $parentModelName, $match) || $parentModelName = '\\M\\' . $parentModelName;
      preg_match_all('/^[A-Z].*/', $parentControllerName, $match) || array_push($errors, \Xterm::gray('父層 Controller', true) . ' 名稱' . \Xterm::red('不是大駝峰') . '，請確認命名是否正確！');
      preg_match_all('/^\\\M\\\[A-Z].[0-9A-Za-z-_ ]*/', $parentModelName, $match) || array_push($errors, \Xterm::gray('父層 Model', true) . ' 名稱' . \Xterm::red('不是大駝峰') . '，請確認命名是否正確！');
      class_exists($parentModelName) || array_push($errors, '父層 Model「' . \Xterm::gray($parentModelName, true) . '」' . \Xterm::red('不存在') . '！');
    }

    preg_match_all('/^\\\M\\\.+/', $modelName, $match) || $modelName = '\\M\\' . $modelName;
    preg_match_all('/^[A-Z].*/', $controllerName, $match) || array_push($errors, 'Controller 名稱' . \Xterm::red('不是大駝峰') . '，請確認命名是否正確！');

    is_dir(PATH_ROUTER . $dir . DIRECTORY_SEPARATOR) || array_push($errors, '「' . \Xterm::gray('Router' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR, true) . '」目錄' . \Xterm::red('不存在') . '！');
    is_writable(PATH_ROUTER . $dir . DIRECTORY_SEPARATOR) || array_push($errors, '「' . \Xterm::gray('Router' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR, true) . '」目錄' . \Xterm::red('不可被讀寫') . '！');

    is_file(PATH_ROUTER . $dir . DIRECTORY_SEPARATOR . $controllerName . '.php') && array_push($errors, '「' . \Xterm::gray('Router' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR, true) . '」目錄內' . \Xterm::red('已存在') . ' ' . \Xterm::gray($controllerName . '.php', true));
    is_dir(PATH_APP_CONTROLLER . $dir . DIRECTORY_SEPARATOR) || array_push($errors, '「' . \Xterm::gray('Controller' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR, true) . '」目錄' . \Xterm::red('不存在') . '！');
    is_writable(PATH_APP_CONTROLLER . $dir . DIRECTORY_SEPARATOR) || array_push($errors, '「' . \Xterm::gray('Controller' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR, true) . '」目錄' . \Xterm::red('不可被讀寫') . '！');
    
    is_dir(PATH_APP_CONTROLLER . $dir . DIRECTORY_SEPARATOR . $controllerName . '.php') && array_push($errors, '「' . \Xterm::gray('Controller' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR, true) . '」目錄內' . \Xterm::red('已存在') . '一個名為 ' . \Xterm::gray($controllerName . '.php', true) . ' 目錄！');
    is_file(PATH_APP_CONTROLLER . $dir . DIRECTORY_SEPARATOR . $controllerName . '.php') && array_push($errors, '「' . \Xterm::gray('Controller' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR, true) . '」目錄內' . \Xterm::red('已存在') . ' ' . \Xterm::gray($controllerName . '.php', true) . ' 檔案');

    is_dir(PATH_APP_VIEW . $dir . DIRECTORY_SEPARATOR) || array_push($errors, '「' . \Xterm::gray('View' . DIRECTORY_SEPARATOR . $dir, true) . '」目錄' . \Xterm::red('不存在') . '！');
    is_writable(PATH_APP_VIEW . $dir . DIRECTORY_SEPARATOR) || array_push($errors, '「' . \Xterm::gray('View' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR, true) . '」目錄' . \Xterm::red('不可被讀寫') . '！');
    is_file(PATH_APP_VIEW . $dir . DIRECTORY_SEPARATOR . $controllerName) && array_push($errors, '「' . \Xterm::gray('View' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR, true) . '」目錄內' . \Xterm::red('已存在') . ' ' . \Xterm::gray($controllerName, true) . ' 檔案');

    if (is_dir(PATH_APP_VIEW . $dir . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR))
      foreach (['index.php', 'add.php', 'edit.php', 'show.php'] as $name) {
        is_dir(PATH_APP_VIEW . $dir . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR . $name) && array_push($errors, '「' . \Xterm::gray('View' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR, true) . '」目錄內' . \Xterm::red('已存在') . '一個名為 ' . \Xterm::gray($name, true) . ' 目錄！');
        is_file(PATH_APP_VIEW . $dir . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR . $name) && array_push($errors, '「' . \Xterm::gray('View' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR, true) . '」目錄內' . \Xterm::red('已存在') . ' ' . \Xterm::gray($name, true) . ' 檔案');
      }

    preg_match_all('/^\\\M\\\[A-Z].[0-9A-Za-z-_ ]*/', $modelName, $match) || array_push($errors, 'Model 名稱' . \Xterm::red('不是大駝峰') . '，請確認命名是否正確！');
    class_exists($modelName) || array_push($errors, 'Model「' . \Xterm::gray($modelName, true) . '」' . \Xterm::red('不存在') . '！');

    $enable && !defined($modelName . '::ENABLE') && array_push($errors, 'Model「' . \Xterm::gray($modelName, true) . '」' . \Xterm::red('尚未定義') . '「' . \Xterm::gray('ENABLE', true) . '」常數！');
    $enable && !defined($modelName . '::ENABLE_YES') && array_push($errors, 'Model「' . \Xterm::gray($modelName, true) . '」' . \Xterm::red('尚未定義') . '「' . \Xterm::gray('ENABLE_YES', true) . '」常數！');
    $enable && !defined($modelName . '::ENABLE_NO') && array_push($errors, 'Model「' . \Xterm::gray($modelName, true) . '」' . \Xterm::red('尚未定義') . '「' . \Xterm::gray('ENABLE_NO', true) . '」常數！');

    $errors && Display::error($errors);

    return null;
  }

  public static function createConfigCRUD() {
    $config = config('CRUD');

    $uri                  = $config['uri'] ?? '';
    $dir                  = trim($config['dir'], DIRECTORY_SEPARATOR);
    $title                = $config['title'];
    $routerUri            = $config['routerUri'];
    $controllerName       = $config['controllerName'];
    $modelName            = $config['modelName'];
    $enable               = $config['enable'] ?? false;
    $sort                 = $config['sort']   ?? false;
    
    $parentTitle          = $config['parent']['title'] ?? null;
    $parentRouterUri      = $config['parent']['routerUri'] ?? null;
    $parentControllerName = $config['parent']['controllerName'] ?? null;
    $parentModelName      = $config['parent']['modelName'] ?? null;
    $parent               = isset($parentTitle, $parentRouterUri, $parentControllerName, $parentModelName);

    $images               = $config['images']    ?? [];
    $texts                = $config['texts']     ?? [];
    $textareas            = $config['textareas'] ?? [];

    $images = array_values(array_filter(array_map(function($t) { $t['name'] = $t['name'] ?? ''; if ($t['name'] === '') return null; $t['must'] = $t['must'] ?? false; $t['text'] = $t['text'] ?? ''; $t['text'] = $t['text'] === '' ? $t['name'] : $t['text']; $t['formats'] = $t['formats'] ?? []; $t['accept'] = $t['accept'] ?? 'image/*'; return $t; }, $images)));
    $texts = array_values(array_filter(array_map(function($t) { $t['name'] = $t['name'] ?? ''; if ($t['name'] === '') return null; $t['must'] = $t['must'] ?? false; $t['text'] = $t['text'] ?? ''; $t['text'] = $t['text'] === '' ? $t['name'] : $t['text']; $t['focus'] = $t['focus'] ?? false ? true : false; $t['type'] = $t['type'] ?? 'text'; return $t; }, $texts)));
    $textareas = array_values(array_filter(array_map(function($t) { $t['name'] = $t['name'] ?? ''; if ($t['name'] === '') return null; $t['must'] = $t['must'] ?? false; $t['text'] = $t['text'] ?? ''; $t['text'] = $t['text'] === '' ? $t['name'] : $t['text']; $t['focus'] = $t['focus'] ?? false ? true : false; $t['type'] = $t['type'] ?? 'pure'; return $t; }, $textareas)));
    self::setCRUDFocus($texts, $textareas);
    
    preg_match_all('/^\\\M\\\.+/', $parentModelName, $match) || $parentModelName = $parentModelName ? '\\M\\' . $parentModelName : null;
    $parentModelFkey = $parent ? lcfirst(deNamespace($parentModelName)) . 'Id' : null;

    preg_match_all('/^\\\M\\\.+/', $modelName, $match) || $modelName = '\\M\\' . $modelName;

    $routerFilePath     = PATH_ROUTER         . $dir . DIRECTORY_SEPARATOR . $controllerName . '.php';
    $controllerFilePath = PATH_APP_CONTROLLER . $dir . DIRECTORY_SEPARATOR . $controllerName . '.php';
    $viewDirPath        = PATH_APP_VIEW       . $dir . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR;

    $router = Tool::getTemplate('CRUD' . DIRECTORY_SEPARATOR . 'Router.template', ['title' => $title, 'controllerName' => $controllerName, 'enable' => $enable, 'sort' => $sort, 'dir' => $dir, 'uri' => $uri, 'baseUri' => ($parent ? $parentRouterUri . '/(' . $parentModelFkey . ':id)/' : '') . $routerUri]);
    $controller = Tool::getTemplate('CRUD' . DIRECTORY_SEPARATOR . 'Controller.template', ['dir' => $dir, 'sort' => $sort, 'title' => $title, 'parent' => $parent, 'enable' => $enable, 'modelName' => $modelName, 'parentTitle' => $parentTitle, 'controllerName' => $controllerName, 'parentModelName' => $parentModelName, 'parentModelFkey' => $parentModelFkey, 'parentControllerName' => $parentControllerName, 'texts' => array_map(function($text) { $validator = ''; switch ($text['type']) { case 'number': $validator = '->isNumber()'; break; case 'email': $validator = '->isEmail()'; break; case 'date': $validator = '->isDate()'; break; default: $validator = '->isString(' . ($text['must'] ? '1' : '0') . ', 190)'; break; } return 'Validator::' . ($text['must'] ? 'must' : 'optional') . "(" . '$params' . ", '" . $text['name'] . "', '" . $text['text'] . "')" . $validator . ";\n"; }, $texts), 'images' => array_map(function($image) { $validator = '->isUpload()'; $image['formats'] = implode(', ', array_map(function($format) { return "'" . $format . "'";}, $image['formats'])); $image['formats'] && $validator .= '->formatFilter([' . $image['formats'] . '])'; return ['Validator::' . ($image['must'] ? 'must' : 'optional'), "(" . '$files' . ", '" . $image['name'] . "', '" . $image['text'] . "')" . $validator . ";\n"]; }, $images), 'textareas' => array_map(function($textarea) { $validator = ''; switch ($textarea['type']) { case 'ckeditor': $validator = '->isStr()->strTrim()->allowableTags(false)' . ($textarea['must'] ? '->strMinLength(1)' : ''); break; default: $validator = '->isString(' . ($textarea['must'] ? '1' : '') . ')'; break; } return 'Validator::' . ($textarea['must'] ? 'must' : 'optional') . "(" . '$params' . ", '" . $textarea['name'] . "', '" . $textarea['text'] . "')" . $validator . ";\n"; }, $textareas)]);
    $index = Tool::getTemplate('CRUD' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'Index' . '.template', ['dir' => $dir, 'texts' => $texts, 'enable' => $enable, 'images' => $images, 'parent' => $parent, 'modelName' => $modelName, 'textareas' => $textareas, 'controllerName' => $controllerName]);
    $add   = Tool::getTemplate('CRUD' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'Add' . '.template', ['texts' => $texts, 'enable' => $enable, 'images' => $images, 'parent' => $parent, 'modelName' => $modelName, 'textareas' => $textareas, 'controllerName' => $controllerName]);
    $edit  = Tool::getTemplate('CRUD' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'Edit' . '.template', ['texts' => $texts, 'enable' => $enable, 'images' => $images, 'parent' => $parent, 'modelName' => $modelName, 'textareas' => $textareas, 'controllerName' => $controllerName]);
    $show  = Tool::getTemplate('CRUD' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'Show' . '.template', ['texts' => $texts, 'enable' => $enable, 'images' => $images, 'parent' => $parent, 'modelName' => $modelName, 'textareas' => $textareas, 'controllerName' => $controllerName]);

    is_dir($viewDirPath) || umaskMkdir($viewDirPath, 0777, true);
    is_dir($viewDirPath) || Display::error('建立 View 目錄下的「' . $viewDirPath . '」目錄失敗！');
    file_exists($routerFilePath) || fileWrite($routerFilePath, $router);
    file_exists($routerFilePath) || Display::error('Router 寫入失敗！');
    file_exists($controllerFilePath) || fileWrite($controllerFilePath, $controller);
    file_exists($controllerFilePath) || Display::error('Controller 寫入失敗！');

    foreach (['index', 'add', 'edit', 'show'] as $name) {
      file_exists($path = $viewDirPath . $name . '.php') || fileWrite($path, $$name);
      file_exists($path) || Display::error('「View' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR . $name . '.php」寫入失敗！');
    }

    Display::title('完成');
    Display::markListLine('新增 Admin CRUD「' . ($parent ? ($parentTitle === '' ? \Xterm::black('空字串', true)->dim() : Display::colorBoldWhite($parentTitle)) . \Xterm::create('＞', true)->dim() : '') . Display::colorBoldWhite($title) . '」成功！');
    Display::markListLine('Router File    ' . Display::markSemicolon() . \Xterm::gray('Router' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . $controllerName . '.php', true));
    Display::markListLine('Controller File' . Display::markSemicolon() . \Xterm::gray('App' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . $controllerName . '.php', true));
    Display::markListLine('Index View File' . Display::markSemicolon() . \Xterm::gray('App' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'index.php', true));
    Display::markListLine('Add   View File'   . Display::markSemicolon() . \Xterm::gray('App' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'add.php', true));
    Display::markListLine('Edit  View File'  . Display::markSemicolon() . \Xterm::gray('App' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'edit.php', true));
    Display::markListLine('Show  View File'  . Display::markSemicolon() . \Xterm::gray('App' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'show.php', true));
    echo Display::LN;
  }
  
  public static function get() {
    if (!\Load::system('Env'))
      return null;

    $item1 = Input::create('新增 Migration 檔案', 'Create Migration')
                  ->appendTip('快速建立資料表，範例：' . \Xterm::gray('create TableName', true))
                  ->appendTip('快速新增欄位，範例：' . \Xterm::gray('alter TableName add fieldName', true))
                  ->appendTip(Display::controlC())
                  ->appendInput('請輸入名稱')
                  ->isCheck()
                  ->setAutocomplete('create', 'alter', 'drop', 'insert', 'update', 'delete')
                  ->thing('\CMD\Create\Layout::createMigration');

    $item2 = Input::create('新增 Model 檔案', 'Create Model')
                  ->isCheck()
                  ->appendTip('Model 名稱請使用' . Display::colorBoldWhite('大駝峰') . '命名。')
                  ->appendTip('圖片、檔案上傳器多筆欄位時，用' . Display::colorBoldWhite('空白隔') . '開即可。')
                  ->appendTip(Display::controlC())
                  ->appendInput('請輸入 Model 名稱')
                  ->appendInput('請輸入' . Display::colorBoldWhite('圖片上傳器') . '欄位', false)
                  ->appendInput('請輸入' . Display::colorBoldWhite('檔案上傳器') . '欄位', false)
                  ->setValidator('\CMD\Create\Layout::validatorModel')
                  ->thing('\CMD\Create\Layout::createModel');

    if (file_exists(PATH_CONFIG . ENVIRONMENT . DIRECTORY_SEPARATOR . 'CRUD.php') && ($config = config('CRUD')) && self::notEmptyString($config, 'dir', 'title', 'routerUri', 'controllerName', 'modelName')) {
      $item3 = Input::create('依 Config 新增 CRUD', 'Create CRUD by Config')
                    ->appendTip('Config 位置    ' . Display::markSemicolon() . \Xterm::gray('Config' . DIRECTORY_SEPARATOR . ENVIRONMENT . DIRECTORY_SEPARATOR . 'CRUD.php', true))
                    ->appendTip('目錄           ' . Display::markSemicolon() . \Xterm::gray($config['dir'], true))
                    ->appendTip('標題           ' . Display::markSemicolon() . (isset($config['parent']['title']) ? ($config['parent']['title'] === '' ? \Xterm::black('空字串', true)->dim() : Display::colorBoldWhite($config['parent']['title'])) . \Xterm::create('＞', true)->dim() : '') . Display::colorBoldWhite($config['title']))
                    ->appendTip('Router Uri     ' . Display::markSemicolon() . \Xterm::gray($config['routerUri'], true))
                    ->appendTip('Controller 名稱' . Display::markSemicolon() . \Xterm::gray($config['controllerName'], true))
                    ->appendTip('Model 名稱     ' . Display::markSemicolon() . \Xterm::gray($config['modelName'], true))
                    ->appendTip(Display::controlC())
                    ->isCheck('請確認以上要新增的 CRUD 資訊？')
                    ->setValidator('\CMD\Create\Layout::validatorConfigCRUD')
                    ->thing('\CMD\Create\Layout::createConfigCRUD');
    } else {
      $item3 = null;
    }

    return Menu::create('新增檔案', 'Create Migration or Model')
               ->appendItem($item1)
               ->appendItem($item2)
               ->appendItem($item3);
  }
}