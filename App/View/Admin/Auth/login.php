<!DOCTYPE html>
<html lang="tw">
  <head>
    <meta http-equiv="Content-Language" content="zh-tw" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />
    <meta name="robots" content="noindex,nofollow" />

    <title>登入後台系統</title>

    <?php echo Asset::create()
                    ->addCSS('/Asset/css/Admin/Login.css')
                    ->renderCSS();?>
  </head>
  <body lang="zh-tw">
    
    <main id="main">
      <h1><span>登入後台</span></h1>

      <form id="login" action="<?php echo Url::router('AdminAuthSignin');?>" method="post">
        <span<?php echo $flash['type'] ? ' class="' . $flash['type'] . '"' : '';?>><?php echo $flash['msg'];?></span>
        <label data-title="帳號" class="account"><input type="text" name="account" placeholder="請輸入您的帳號…" autofocus required></label>
        <label data-title="密碼" class="password"><input type="password" name="password" placeholder="請輸入您的密碼…" required></label>
        <button type="submit">登入</button>
      </form>
      <span>© 2014 - <?php echo date ('Y');?> www.ioa.tw | 後台版型設計 by <a href="https://www.ioa.tw/" target="_blank">OAWU</a></span>
    </main>

  </body>
</html>
