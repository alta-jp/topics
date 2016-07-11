<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
    <head>
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="content-style-type" content="text/css" />
        <title>トピック管理ツール</title>
        <link media="screen" type="text/css" href="template/base.css" rel="stylesheet">
            <link rel="stylesheet" href="template/themes/djime-cerulean/style.css" type="text/css" media="screen" title="default" />
    </head>
    <body>
        <div id="box">
            <h1>トピック管理ツール</h1>
            <div class="block" id="block-login">
                <h2>Login Box</h2>
                <div class="content login">
                    <div class="flash"><?php echo $flash ?></div>

                    <form action="<?php echo $_SERVER["SCRIPT_NAME"]; ?>" method="post" class="form login">
                        <div class="group wat-cf">
                            <div class="left">
                                <label class="label right">Login</label>
                            </div>

                            <div class="right">
                                <input type="text" name="id" class="text_field" />
                            </div>
                        </div>
                        <div class="group wat-cf">
                            <div class="left">
                                <label class="label right">Password</label>
                            </div>

                            <div class="right">
                                <input type="password" name="pass" class="text_field" />
                            </div>
                        </div>
                        <div class="group navform wat-cf">
                            <div class="right">
                                <button class="button" type="submit">
                                    <img src="template/images/icons/key.png" alt="Save" /> Login
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="footer">
            <div class="block">
                <p>Copyright &copy; alta.</p>
            </div>
        </div>
    </body>
</html>