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
    <main id="main"><?php echo isset($content) ? $content : ''; ?></main>
  </body>
</html>
