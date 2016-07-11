<?php
//$date_default_timezone_set = date_default_timezone_set('Asia/Tokyo');


include("config.php");
include("libs/admin.php");

require_once 'libs/template.php';

$version = '0.01';

$PHP_SELF = "admin.php";


$admin = new admin();
$admin->setView();
$admin->assign('title',null);
$admin->assign('flash',null);
$admin->assign('html',null);

$admin->logincheck();
$admin->assign('menu',$admin->menu());
$admin->assign('version',$version);


$mode = isset($_POST['mode']) ? $_POST['mode'] : null;
if (!$mode) {
    $mode = isset($_GET['mode']) ? $_GET['mode'] : null;
}
switch ($mode) {
    case 'htmlrenew':

        if (TOPHTML) {
            $admin->assign('html',
                $admin->list_renew_bt("リストページのHTMLファイルを更新します。<br />並び順の変更後に使用ください。")
                . $admin->detailed_renew_bt("各画像ページのHTMLファイルを更新します。<br />テンプレートファイルのデザイン変更後等、必要に応じ使用ください。")
                . $admin->toplist_renew_bt("トップページ用リストページのHTMLファイルを更新します。<br>並び順の変更後に使用ください。")
            );
        } else {
            $admin->assign('html',
                $admin->list_renew_bt("リストページのHTMLファイルを更新します。<br />並び順の変更後に使用ください。")
                . $admin->detailed_renew_bt("各画像ページのHTMLファイルを更新します。<br>テンプレートファイルのデザイン変更後等、必要に応じ使用ください。")
            );
        }
        break;


    case 'detailedhtmlrenew':
        //詳細更新

        $template = file_get_contents(TEMPLATEDETAILED);

        $lines = file($log_file);

        for ($i = 0; $i < count($lines); $i++) {

            $data = explode("<>", $lines[$i]);

            $_POST['fname'] = $data[0];
            $_POST['savefile'] = $data[1];
            $_POST['date'] = $admin->date_html($data[2]);
            $_POST['title'] = $data[3];
            $_POST['comment'] = $data[4];

            if ($_POST['savefile']) {
                $imgsize = GetImageSize($images_dir . $_POST['savefile']);
                $reducesize = $admin->imagesizedivision($imgsize[0], $imgsize[1], $view_wsize, $view_hsize);
                $_POST['image'] = $admin->imagelinktag($reducesize[4], $_POST['savefile'], $_POST['title'], $reducesize[3]);
            } else {
                $_POST['image'] = "";
            }


            $admin->fsave_w(
                $admin->str_replace_template(
                    $_POST, $template
                ), $_POST['fname'] . ".html"
            );
        }

        $admin->ok("HTMLファイル更新完了");
        $admin->assign('html',$admin->adminlist_bt() );

        break;



    case 'listtoplisthtmlrenew':


        $lines = file($log_file);
        if (TOPHTML) {
            $admin->toplist_html(0, $lines);
        }
        $admin->list_html(0, $lines);

        if (file_exists($htmlrenew_file)) {
            @unlink($htmlrenew_file);
        }

        $admin->assign('html',$admin->ok("HTMLファイル更新完了") . $admin->adminlist_bt());
        break;


    case 'toplisthtmlrenew':
        //トップページHTML作成
        if (TOPHTML) {
            $lines = file($log_file);
            $admin->toplist_html(0, $lines);
            $admin->assign('html',
                $admin->ok("トップページHTMLファイル更新完了") . $admin->adminlist_bt()
            );
        } else {

        }


        break;


    case 'listhtmlrenew':
        $lines = file($log_file);
        $admin->list_html(0, $lines);
        $admin->ok("HTMLファイル更新完了");
        $admin->assign('html', $admin->adminlist_bt());
        break;


    case 'logset':
        $lines = file($log_file);
        $lines = $admin->logsort($_POST['fname'], $lines);
        $admin->fsave_array_w($lines, $log_file);
        $admin->fsave_w("1", $htmlrenew_file);
        $html_list_renew_bt = $admin->listtoplist_renew_bt("並び順の変更後、最後にリストページのHTMLファイルを更新ください。");
        $admin->assign('html',$html_list_renew_bt . $admin->adminlist_html(0, $lines));
        if (TOPHTML) {
            $admin->toplist_html(0, $lines);
        }
        break;


    case 'check':
        $admin->check();
        if ($htmlinput) {
            $_POST['title'] = $admin->strcode($_POST['title']);
            $_POST['comment'] = $admin->strcode($_POST['comment']);
            $_POST['checkcomment'] = nl2br($_POST['comment']);
        } else {
            $_POST['title'] = $admin->strcode($_POST['title']);
            $_POST['comment'] = $admin->strcode($_POST['comment']);
            $_POST['checkcomment'] = $_POST['comment'];
        }

        if ($_FILES['userfile']['name']) {
            $imageurl = $admin->imageup();
        } else {
            $admin->noimageup();
            $imageurl = "--";
        }

        include(CHECKPAGE);
        $admin->assign('html',$check_html);
        $admin->assign('title','入力確認');
        $admin->warning('内容をご確認のうえ、「実行」ボタンを押してください。');
        break;


    case 'end':
        //トピック登録
        $admin->check();

        $_POST['title'] = $admin->strcode($_POST['title']);
        $_POST['comment'] = $admin->strcode($_POST['comment']);

        if ($htmlinput) {
            $_POST['comment'] = str_replace("\r\n", "<br />", $_POST['comment']);
            $_POST['comment'] = str_replace("\r", "<br />", $_POST['comment']);
            $_POST['comment'] = str_replace("\n", "<br />", $_POST['comment']);
        } else {
            $_POST['comment'] = str_replace("\r\n", "<!--br-->", $_POST['comment']);
            $_POST['comment'] = str_replace("\r", "<!--br-->", $_POST['comment']);
            $_POST['comment'] = str_replace("\n", "<!--br-->", $_POST['comment']);
        }


        if ($_POST['savefile']) {

            $admin->filemove(
                $imagestmp_dir . $_POST['savefile'], $images_dir . $_POST['savefile']
            );
            $admin->filemove(
                $imagestmp_dir . "thumb" . $_POST['savefile'], $images_dir . "thumb" . $_POST['savefile']
            );

            $imgsize = GetImageSize($images_dir . $_POST['savefile']);

            $reducesize = $admin->imagesizedivision($imgsize[0], $imgsize[1], $view_wsize, $view_hsize);

            $_POST['image'] = $admin->imagelinktag($reducesize[4], $_POST['savefile'], $_POST['title'], $reducesize[3]);
        } else {
            $imageurl = "";
        }


        $_POST['date'] = strftime($dateformat, mktime(
                $_POST['datedata'][3], $_POST['datedata'][4], 0, $_POST['datedata'][1], $_POST['datedata'][2], $_POST['datedata'][0]
            ));
        $_POST['listpagetitle'] = $listpagetitle;

        //詳細ページ
        $template = file_get_contents(TEMPLATEDETAILED);
        $admin->fsave_w($admin->str_replace_template($_POST, $template), $_POST['fname'] . ".html");

        $lines = $admin->logregist();

        $admin->fsave_array_w($lines, $log_file);

        $admin->list_html(0, $lines);

        if (TOPHTML) {
            $admin->toplist_html(0, $lines);
        }
        $admin->ok("情報登録完了");
        $admin->assign('html', $admin->adminlist_bt());
        break;


    case 'adminlist':
        //登録済み一覧
        $admin->del_dir($imagestmp_dir);
        $lines = file($log_file);
        $html_list_renew_bt = file_exists($htmlrenew_file) ?
            $admin->listtoplist_renew_bt("並び順の変更後、最後にリストページのHTMLファイルを更新ください。") : null;


        $admin->assign('html', $html_list_renew_bt . $admin->adminlist_html(0, $lines));
        if (TOPHTML) {
            $admin->toplist_html(0, $lines);
        }

        $admin->assign('title', '登録済みリスト');
        break;


    case 'renew':
        //編集
        $lines = file($log_file);
        $data = $admin->dataget($_GET['fname'], $lines);
        if ($htmlinput) {
            $data[4] = str_replace("<br />", "\n" , $data[4]);
        } else {

            $data[4] = str_replace("<!--br-->", "\n" , $data[4]);
            $data[4] = str_replace("<<!---->>", "<>", $data[4]);
        }

        $titlemax = TYTLEMAX;
        $commentmax = COMMENTMAX;

        $datedata = $admin->redate($data[2]);

        include(INDEXPAGE);

        $admin->assign('html',$index_html);
        $admin->assign('title', '編集');
        $admin->warning('各項目を入力の上、「入力確認」ボタンを押してください。');
        break;


    case 'renewcheck':

        $admin->check();

        if ($htmlinput) {

            $_POST['title'] = $admin->strcode($_POST['title']);
            $_POST['comment'] = $admin->strcode($_POST['comment']);

            $_POST['checkcomment'] = nl2br($_POST['comment']);
        } else {

            $_POST['checkcomment'] = $_POST['comment'];

            $_POST['title'] = $admin->strcode($_POST['title']);
            $_POST['comment'] = $admin->strcode($_POST['comment']);
        }


        if (isset($_POST['imagedel']) && !isset($_FILES['userfile']['name'])) {

            $imageurl = "";
        } elseif ($_FILES['userfile']['name']) {

            $imageurl = $admin->imageup();
        } else {

            if ($_POST['savefile']) {
                $imagefile = $_POST['savefile'];
                $imgsize = GetImageSize($images_dir . $imagefile);
                $viewsize = $admin->imagesizedivision($imgsize[0], $imgsize[1], $adminview_wsize, $adminview_hsize);

                $imageurl = $admin->imagelinktag($viewsize[4], $imagefile, $_POST['title'], $viewsize[3]);
            } else {
                $imageurl = "";
            }
        }


        include(CHECKPAGE);

        $admin->assign('html',$check_html);
        $admin->assign('title', '編集確認');
        $admin->warning('各項目を入力の上、「実行」ボタンを押してください。');

        break;


    case 'renewend':
        //更新実行
        $admin->check();

        $_POST['title'] = $admin->strcode($_POST['title']);
        $_POST['comment'] = $admin->strcode($_POST['comment']);

        if ($htmlinput) {

            $_POST['comment'] = str_replace("\r\n", "<br />", $_POST['comment']);
            $_POST['comment'] = str_replace("\r", "<br />", $_POST['comment']);
            $_POST['comment'] = str_replace("\n", "<br />", $_POST['comment']);
        } else {
            $_POST['comment'] = str_replace("\r\n", "<!--br-->", $_POST['comment']);
            $_POST['comment'] = str_replace("\r", "<!--br-->", $_POST['comment']);
            $_POST['comment'] = str_replace("\n", "<!--br-->", $_POST['comment']);
        }

        if ($_POST['savefile']) {

            if ($_POST['imagedel']) {

                @unlink($images_dir . $_POST['savefile']);
                @unlink($images_dir . "thumb" . $_POST['savefile']);
                $_POST['savefile'] = "";
            } else {

                if (file_exists($imagestmp_dir . $_POST['savefile'])) {
                    $admin->filemove($imagestmp_dir . $_POST['savefile'], $images_dir . $_POST['savefile']);
                    $admin->filemove($imagestmp_dir . "thumb" . $_POST['savefile'], $images_dir . "thumb" . $_POST['savefile']);
                }

                $imgsize = GetImageSize($images_dir . $_POST['savefile']);

                $reducesize = $admin->imagesizedivision($imgsize[0], $imgsize[1], $view_wsize, $view_hsize);

                $_POST['image'] = $admin->imagelinktag($reducesize[4], $_POST['savefile'], $_POST['title'], $reducesize[3]);
            }
        }

        $_POST['date'] = strftime($dateformat, mktime(
                $_POST['datedata'][3], $_POST['datedata'][4], 0, $_POST['datedata'][1], $_POST['datedata'][2], $_POST['datedata'][0]
            ));
        $_POST['listpagetitle'] = $listpagetitle;


        $template = file_get_contents(TEMPLATEDETAILED);

        $admin->fsave_w(
            $admin->str_replace_template($_POST, $template), $_POST['fname'] . ".html"
        );

        $lines = file($log_file);

        $lines = $admin->list_html($_POST['fname'], $lines);

        if (TOPHTML) {
            $admin->toplist_html(0, $lines);
        }

        $admin->fsave_array_w($lines, $log_file);
        $admin->ok("更新完了");
        $admin->assign('html', $admin->adminlist_bt());

        break;


    case 'del':


        $lines = file($log_file);

        $data = $admin->dataget($_POST['fname'], $lines);

        $_POST['title'] = $data[3];
        $imageurl = $data[10];
        $_POST['checkcomment'] = $data[4];
        $_POST['fname'] = $data[0];
        $_POST['savefile'] = $data[1];

        $titlemax = TYTLEMAX;
        $commentmax = COMMENTMAX;

        $_POST['datedata'] = $admin->redate($data[2]);

        include(CHECKPAGE);

        $admin->assign('title','記事を削除します');
        $admin->assign('html',$check_html);
        $admin->warning('本当に削除してもよろしいですか？');
        break;


    case 'delend':
        @unlink($_POST['fname'] . ".html");

        if ($_POST['savefile']) {
            @unlink($images_dir . $_POST['savefile']);
            @unlink($images_dir . "thumb" . $_POST['savefile']);
        }

        $lines = $admin->log_del_save($_POST['fname'], $log_file);

        $admin->list_html("", $lines);

        if (TOPHTML) {
            $admin->toplist_html(0, $lines);
        }

        $admin->ok("削除完了");
        $admin->assign('html', $admin->adminlist_bt());
        break;


    case 'regist':

        $titlemax = TYTLEMAX;
        $commentmax = COMMENTMAX;

        $datedata = getdate();

        $datedata['hours'] = str_pad($datedata['hours'], 2, '0', STR_PAD_LEFT);
        $datedata['minutes'] = str_pad($datedata['minutes'], 2, '0', STR_PAD_LEFT);


        for ($i = 0; $i < 11; $i++) {
            $data[$i] = '';
        }

        include(INDEXPAGE);
        $admin->assign('html', $index_html);
        $admin->assign('title', '新規登録');
        break;


    default:

        $lines = file($log_file);
        $admin->assign('html', $admin->adminlist_html(0, $lines));
        $admin->assign('title', '登録済みリスト');

}

$admin->render();
