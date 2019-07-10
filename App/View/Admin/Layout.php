<!DOCTYPE html>
<html lang="tw">
  <head>
    <meta http-equiv="Content-Language" content="zh-tw" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />
    <meta name="robots" content="noindex,nofollow" />

    <title><?php echo isset($title) && $title ? (is_array($title) ? $title[0] : $title) . ' | ' : '';?>後台系統</title>

    <?php echo $asset->renderCSS();?>
    <?php echo $asset->renderJS();?>

  </head>
  <body lang="zh-tw">

    <input type="hidden" id="api-ajax-error" value="<?php echo Url::router('AdminMainAjaxErrorCreate');?>">
    <input type="hidden" id="api-change-theme" value="<?php echo Url::router('AdminMainTheme');?>">
    <input type="hidden" id="api-ckeditor-image-upload" value="<?php echo Url::router('AdminCkeditorImageUpload');?>">
    <input type="hidden" id="api-ckeditor-image-browse" value="<?php echo Url::router('AdminCkeditorImageBrowse');?>">

    <main id="main">
      <header id="main-header">
        <label id="hamburger"></label>

        <nav id="nav">
    <?php echo implode('', array_map(function($text) { return $text ? \HTML\B::create($text) : ''; }, isset($title) && $title ? is_array($title) ? $title : [$title] : ['']));?>
          <label>
            <select id="theme">
        <?php foreach (AdminController::THEME as $key => $val) { ?>
                <option value="<?php echo $key;?>"<?php echo $key === $theme ? ' selected' : '';?>><?php echo $val;?></option>
        <?php } ?>
            </select>
          </label>
        </nav>
        
        <a id="logout" href="<?php echo Url::router('AdminAuthLogout');?>"></a>
      </header>
      <div id="flash" class="<?php echo $flash['type'];?>"><?php echo $flash['msg'];?></div>
      <div id="main-container"><?php echo isset($content) ? $content : ''; ?></div>
    </main>

    <div id="menu">
      <header id="menu-header"><a href="<?php echo Url::base();?>"></a><span>後台系統</span></header>

      <div id="menu-user">
        <figure data-bgurl="<?php echo \M\Admin::current()->avatar->url();?>"></figure>
        <div data-title="Hi, 您好！"><?php echo \M\Admin::current()->name;?></div>
      </div>

      <div id="menu-main"><?php echo \CRUD\Layout::menus($currentUrl); ?></div>
    </div>

    <footer id="footer"><span>後台版型設計 by </span><a href="https://www.ioa.tw/" target="_blank">OAWU</a></footer>
    
  </body>
</html>
