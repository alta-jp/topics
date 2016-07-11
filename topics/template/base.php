<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
    <head>
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="content-style-type" content="text/css" />
        <title>トピック管理ツール</title>
        <link rel="stylesheet" href="template/base.css" type="text/css" media="screen" title="default" />
        <link rel="stylesheet" href="template/themes/djime-cerulean/style.css" type="text/css" media="screen" title="default" />
    </head>
    <body>
        <div id="container">
            <div id="header">
                <h1><a href="./admin.php">トピック管理ツール</a></h1>
                <div id="user-navigation"><ul class="wat-cf"><li><a href="?mode=logout">ログアウト</a></li></ul></div>
                <div id="main-navigation">
                    <?php echo $menu ?>
                </div>
            </div>
        </div>
        <div id="wrapper" class="wat-cf">
            <div id="main">
                <div class="block">
                    <div class="content">
                        <h2 class="title"><?php echo $title ?></h2>
                        <div class="inner">
                            <div class="flash"><?php echo $flash ?></div>
                            <?php echo $html ?>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="sidebar">
                <div class="block">
                    <h3>トピック管理ツール</h3>
                    <div class="content">
                        <p>Version <?php echo $version ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer">
            <div class="block">
                <p>Copyright &copy; 2010 alta.</p>
            </div>
        </div>
    </body>
</html>