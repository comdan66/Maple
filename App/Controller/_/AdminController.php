<?php

abstract class AdminController extends Controller {
  protected $asset, $view;
  protected $obj, $parent;
  public $flash, $roles = [];

  const THEME_BLUE    = 'Blue';
  const THEME_GREEN   = 'Green';

  const THEME = [
    self::THEME_BLUE => '預設藍', 
    self::THEME_GREEN => '森林綠',
  ];
  
  public function __construct() {
    
    Load::systemLib('Asset');
    Load::systemLib('Validator');
    Load::systemLib('CRUD');

    if (ifErrorTo('AdminAuthLogin') && !\M\Admin::current())
      return error('請先登入！');
    
    if (ifErrorTo('AdminMainIndex') && !\M\Admin::current()->inRoles($this->roles = arrayFlatten(func_get_args())))
      return error('您的權限不符！');

    $this->flash = Session::getFlashData('flash');
    if (isset($this->flash['params']))
      if (!$this->flash['params'])
        $this->flash['params'] = null;

    \M\AdminLog::create(['flash' => json_encode(is_array($this->flash) ? $this->flash : [])]);

    $this->asset = Asset::create()
                        ->addCSS('/Asset/css/icon-AdminMenu.css')
                        ->addCSS('/Asset/css/Admin/CRUD.css')
                        ->addCSS('/Asset/css/Admin/Layout.css')
                        ->addJS('/Asset/js/_/jQuery.js')
                        ->addJS('/Asset/js/_/jQueryUI.js')
                        ->addJS('/Asset/js/_/jQueryUJS.js')
                        ->addJS('/Asset/js/_/OAIPS.js')
                        ->addJS('/Asset/js/_/Ckeditor/ckeditor.js')
                        ->addJS('/Asset/js/_/Ckeditor/adapters/jquery.js')
                        ->addJS('/Asset/js/_/Ckeditor/plugins/tabletools/tableresize.js')
                        ->addJS('/Asset/js/_/Ckeditor/plugins/dropler/dropler.js')
                        ->addJS('/Asset/js/Admin/Layout.js')
                        ->addJS('/Asset/js/Admin/CRUD.js');
    

    if (array_key_exists($theme = Session::getData('theme'), AdminController::THEME))
      $this->asset->addCSS('/Asset/css/Admin/Layout' . $theme . '.css');

    $this->obj = $this->parent = null;

    $this->view = View::create('Admin/' . Router::className() . '/' . Router::methodName() . '.php', false)
                      ->appendTo(View::create('Admin/Layout.php'), 'content')
                      ->with('flash', $this->flash)
                      ->with('theme', $theme)
                      ->with('currentUrl', null)
                      ->withReference('asset', $this->asset)
                      ->withReference('obj', $this->obj)
                      ->withReference('parent', $this->parent);
  }

  protected function methedIn() {
    $args = func_get_args();
    $methods = array_filter($args, 'is_string');

    if ($methods && !in_array(Router::methodName(), $methods))
      return true;

    $closures = array_filter($args, 'is_callable');
    foreach ($closures as $closure)
      $closure() || error('找不到資料！');

    return true;
  }
}
