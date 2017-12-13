<?php

class admin
{

    var $view;

    function setView()
    {
        $this->view = new Template();
        $this->view->set_path('template/');

    }


    /**
     * 保存データー解析
     *
     * @param type $data
     * @return type
     */
    function redate($data)
    {
        $datearray = explode(" ", $data);
        $datedata = explode("/", $datearray[0]);
        $datedata['year'] = $datedata[0];
        $datedata['mon'] = $datedata[1];
        $datedata['mday'] = $datedata[2];
        $hm = explode(":", $datearray[1]);
        $datedata['hours'] = $hm[0];
        $datedata['minutes'] = $hm[1];
        $datedata[3] = $hm[0];
        $datedata[4] = $hm[1];

        return $datedata;

    }


    function logregist()
    {
        global $log_file;

        $lines[] = $_POST['fname'] . "<>" . $_POST['savefile'] . "<>" . $_POST['datedata'][0] . "/" . $_POST['datedata'][1] . "/" . $_POST['datedata'][2] . " " . str_pad($_POST['datedata'][3], 2, '0', STR_PAD_LEFT) . ":" . str_pad($_POST['datedata'][4], 2, '0', STR_PAD_LEFT) . "<>" . $_POST['title'] . "<>" . $_POST['comment'] . "<>" . "<>" . "\n";

        $fp = fopen($log_file, "r") or die("logregist Error");
        if (function_exists('stream_set_write_buffer'))
            stream_set_write_buffer($fp, 0);
        flock($fp, LOCK_EX);
        while (!feof($fp)) {

            $lineschek = fgets($fp);
            if ($lineschek) {
                $lines[] = $lineschek;
            }
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        return $lines;

    }


    function logsort($mark, $lines)
    {

        $totallines = count($lines);

        for ($i = 0; $i < $totallines; $i++) {
            $data = explode("<>", $lines[$i]);

            if ($data[0] == $mark) {

                if (($_POST['updown'] == "up") && ($i != 0)) {
                    $d = $lines[$i];
                    $lines[$i] = $lines[$i - 1];
                    $lines[$i - 1] = $d;
                } elseif (($_POST['updown'] == "down") && ($i != $totallines - 1)) {
                    $d = $lines[$i];
                    $lines[$i] = $lines[$i + 1];
                    $lines[$i + 1] = $d;
                    $i++;
                } else {
                    $lines[$i] = $lines[$i];
                }
            } else {
                $lines[$i] = $lines[$i];
            }
        }

        return $lines;

    }


    function dataget($fname, $lines)
    {
        global $images_dir, $adminview_wsize, $adminview_hsize;

        for ($i = 0; $i < count($lines); $i++) {
            $data = explode("<>", $lines[$i]);
            if ($data[0] == $fname) {
                break;
            }
        }

        if ($data[1]) {
            $imgsize = GetImageSize($images_dir . $data[1]);

            $viewsize = $this->imagesizedivision($imgsize[0], $imgsize[1], $adminview_wsize, $adminview_hsize);

            $data[10] = $this->imagelinktag($viewsize[4], $data[1], $data[3], $viewsize[3])
                . '<br />' . $imgsize[0] . 'px x ' . $imgsize[1] . 'px<br />';
        } else {
            $data[10] = "";
        }


        return $data;

    }


    /**
     *
     * @global type $images_dir
     * @param type $imagelink
     * @param type $fname
     * @param type $title
     * @param type $size
     * @return string
     */
    function imagelinktag($imagelink, $fname, $title, $size)
    {
        global $images_dir;

        if ($imagelink) {
            $tag = '<a href="' . $images_dir . $fname . '" target="_blank">'
                . '<img src="' . $images_dir . $fname . '" ' . $size . ' alt="' . $title . '" />'
                . '</a>';
        } else {
            $tag = '<img src="' . $images_dir . $fname . '" ' . $size . ' alt="' . $title . '" />';
        }
        return $tag;

    }


    /**
     *
     * @global type $PHP_SELF
     * @param type $txt
     * @return string
     */
    function listtoplist_renew_bt($txt)
    {
        global $PHP_SELF;
        $list_renew_bt = '<table border="0" cellspacing="0" cellpadding="0">'
            . '<tr><td>' . $txt . '</td><td>'
            . '<form action="' . $PHP_SELF . '" method="POST">'
            . '<input type="hidden" name="mode" value="listtoplisthtmlrenew">'
            . '<input type="submit" value="HTMLページ更新"></form></td></tr></table>';
        return $list_renew_bt;

    }


    function toplist_renew_bt($txt)
    {
        global $PHP_SELF;

        $toplist_renew_bt = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"width:100%;font-size:13px;margin-top:15px;margin-bottom:15px;\"><tr><td>$txt</td><form action=\"$PHP_SELF\" method=\"post\"><td style=\"text-align:right;\"><input type=\"hidden\" name=\"mode\" value=\"toplisthtmlrenew\"><input type=\"submit\" value=\"TOPリストHTMLページ更新\" style=\"margin:10px 10px;width:200px;\"></td></form></tr></table>";

        return $toplist_renew_bt;

    }


    function list_renew_bt($txt)
    {
        global $PHP_SELF;

        $list_renew_bt = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"width:100%;font-size:13px;margin-top:15px;margin-bottom:15px;\"><tr><td>$txt</td><form action=\"$PHP_SELF\" method=\"post\"><td style=\"text-align:right;\"><input type=\"hidden\" name=\"mode\" value=\"listhtmlrenew\"><input type=\"submit\" value=\"リストHTMLページ更新\" style=\"margin:10px 10px;width:200px;\"></td></form></tr></table>";

        return $list_renew_bt;

    }


    function detailed_renew_bt($txt)
    {
        global $PHP_SELF;

        $detailed_renew_bt = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"width:100%;font-size:13px;margin-top:15px;margin-bottom:15px;\"><tr><td>$txt</td><form action=\"$PHP_SELF\" method=\"post\"><td style=\"text-align:right;\"><input type=\"hidden\" name=\"mode\" value=\"detailedhtmlrenew\"><input type=\"submit\" value=\"各HTMLページ更新\" style=\"margin:10px 10px;width:200px;\"></td></form></tr></table>";

        return $detailed_renew_bt;

    }


    function adminlist_bt()
    {
        global $PHP_SELF;

        $html_adminlist_bt = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"width:100%;text-align:center;\"><tr>"
            . "<td><form action=\"$PHP_SELF\" method=\"post\"><input type=\"hidden\" name=\"mode\" value=\"logsethtmlrenew\"><input type=\"submit\" value=\"情報管理\" class=\"submit\" style=\"margin:50px 10px;\"></form></td></tr></table>";

        return $html_adminlist_bt;

    }


    function noimageup()
    {
        global $_FILES, $images_dir, $imagestmp_dir;
        global $adminview_wsize, $adminview_hsize;
        global $up_filesizelimit_upper, $up_filesizelimit_lower, $setextension;
        global $up_wlimit_upper, $up_hlimit_upper, $up_wlimit_lower, $up_hlimit_lower, $save_wmax_b, $save_hmax_b, $save_wmax_s, $save_hmax_s;

        $mictime = microtime();
        $fname = substr($mictime, 11, 10) . substr($mictime, 2, 3);

        $savefile = "";

        $snsavefile = "";

        $_POST['fname'] = $fname;

    }


    function imageup()
    {
        global $_FILES, $images_dir, $imagestmp_dir;
        global $adminview_wsize, $adminview_hsize;
        global $up_filesizelimit_upper, $up_filesizelimit_lower, $setextension;
        global $up_wlimit_upper, $up_hlimit_upper, $up_wlimit_lower, $up_hlimit_lower, $save_wmax_b, $save_hmax_b, $save_wmax_s, $save_hmax_s;


        if (!is_uploaded_file($_FILES['userfile']['tmp_name'])) {
            $this->error("画像を選択してください。");
            $this->render();
        }

        if (!($_FILES['userfile']['tmp_name'])) {
            $this->error("アップロードできませんでした。ファイルサイズを確認してください。");
            $this->render();
        }

        if ($_FILES['userfile']['size'] > ($up_filesizelimit_upper * 1024)) {
            $this->error("ファイルサイズがサイズが大きいです。<br /><br />アップロード可能サイズは ${up_filesizelimit_lower}KB 以上 ${up_filesizelimit_upper} までです。");
            $this->render();
        }

        if ($_FILES['userfile']['size'] < ($up_filesizelimit_lower * 1024)) {
            $this->error("ファイルサイズがサイズが小さいです。<br /><br />アップロード可能サイズは ${up_filesizelimit_lower}KB 以上 ${up_filesizelimit_upper} までです。");
            $this->render();
        }
        $upfile_name = explode(".", $_FILES['userfile']['name']);
        if (!in_array(strtolower($upfile_name[1]), array_map('strtolower', $setextension))) {
            $html .= implode(" ",$setextension);
            $this->error("画像タイプに誤りがあります。<br /><br />アップロードできる画像の拡張子は『${html}』のみです。");
            $this->render();
        }
        $mictime = microtime();
        $fname = substr($mictime, 11, 10) . substr($mictime, 2, 3);

        $savefile = $fname . "." . $upfile_name[1];

        $snsavefile = 'thumb' . $fname . '.' . $upfile_name[1];

        if (!$_POST['fname']) {
            $_POST['fname'] = $fname;
        }

        $_POST['savefile'] = $savefile;


        move_uploaded_file($_FILES['userfile']['tmp_name'], $imagestmp_dir . $savefile);

        $imgsize = GetImageSize($imagestmp_dir . $savefile);
        $imgwidth = $imgsize[0];
        $imgheight = $imgsize[1];

        $check_img_type = array("1", "2", "3");
        if (!(in_array($imgsize[2], $check_img_type))) {
            @unlink($imagestmp_dir . $savefile);
            for ($i = 0; $i < count($setextension); $i++) {
                $html .= $setextension[$i] . "　";
            }
            $this->error("画像タイプに誤りがあります。<br /><br />アップロードできる画像の拡張子は『${html}』のみです。");
            $this->render();
        }


        if (($imgsize[1] < $up_hlimit_lower) || ($imgsize[0] < $up_wlimit_lower)) {
            @unlink("${imagestmp_dir}$savefile");
            $this->error("画像の縦×横サイズが小さいです。<br /><br />アップロード可能サイズは、<br />　縦${up_hlimit_lower}px　、　横${up_wlimit_lower}px　以上です。");
            $this->render();
        }


        if (($imgsize[1] > $up_hlimit_upper) || ($imgsize[0] > $up_wlimit_upper)) {
            @unlink("${imagestmp_dir}$savefile");
            $this->error("画像の縦×横サイズが大きいです。<br /><br />アップロード可能サイズは、<br />　縦${up_hlimit_upper}px　、　横${up_wlimit_upper}px　以下です。");
            $this->render();
        }


        if ($imgsize[2] == 1) {

            $originalimage = imagecreatefromgif("${imagestmp_dir}$savefile");
        } elseif ($imgsize[2] == 2) {

            $originalimage = imagecreatefromjpeg("${imagestmp_dir}$savefile");
        } elseif ($imgsize[2] == 3) {

            $originalimage = imagecreatefrompng("${imagestmp_dir}$savefile");
        }

        $new_size_s = $this->imagesizedivision($imgsize[0], $imgsize[1], $save_wmax_s, $save_hmax_s);
        $this->imageresize($originalimage, "${imagestmp_dir}$snsavefile", $new_size_s[0], $new_size_s[1], $imgsize[0], $imgsize[1], $imgsize[2]);


        if ($save_wmax_b && $save_hmax_b) {
            $new_size_b = $this->imagesizedivision($imgsize[0], $imgsize[1], $save_wmax_b, $save_hmax_b);
        }


        if (isset($new_size_b[4])) {
            $this->imageresize($originalimage, $imagestmp_dir . $savefile, $new_size_b[0], $new_size_b[1], $imgsize[0], $imgsize[1], $imgsize[2]);
            $imgsize[0] = $new_size_b[0];
            $imgsize[1] = $new_size_b[1];
        }


        imagedestroy($originalimage);

        if (!file_exists($imagestmp_dir . $savefile) || !file_exists($imagestmp_dir . $snsavefile)) {
            $this->error("画像データが正しくアップロードされませんでした。");
            $this->render();
        }

        $viewsize = $this->imagesizedivision(
                $imgsize[0], $imgsize[1], $adminview_wsize, $adminview_hsize
        );
        if ($viewsize[4]) {
            $imageurl = "<a href=\"${imagestmp_dir}$savefile\" target=\"_blank\"><img src=\"${imagestmp_dir}$savefile\" $viewsize[3] /></a>";
        } else {
            $imageurl = "<img src=\"${imagestmp_dir}$savefile\" $viewsize[3] />";
        }

        return $imageurl;

    }


    function imageresize($originalimage, $new_imagefile, $new_wsize, $new_hsize, $wsize, $hsize, $new_filetype)
    {

        $new_image = imagecreatetruecolor($new_wsize, $new_hsize);

        imagecopyresampled($new_image, $originalimage, 0, 0, 0, 0, $new_wsize, $new_hsize, $wsize, $hsize);

        if ($new_filetype == 1) {
            imagegif($new_image, $new_imagefile);
        } elseif ($new_filetype == 2) {
            imagejpeg($new_image, $new_imagefile);
        } elseif ($new_filetype == 3) {
            imagepng($new_image, $new_imagefile);
        }

        imagedestroy($new_image);

    }


    function imagesizedivision($wsize, $hsize, $wmax, $hmax)
    {

        if ((($wmax > 0) && ($hmax > 0)) && (($wsize > $wmax) || ($hsize > $hmax))) {

            if (($wmax / $wsize) < ($hmax / $hsize)) {
                $maxratio = $wmax / $wsize;
            } else {
                $maxratio = $hmax / $hsize;
            }

            $size[0] = (int) ($wsize * $maxratio);
            $size[1] = (int) ($hsize * $maxratio);
            $size[3] = "width=\"$size[0]\" height=\"$size[1]\"";
            $size[4] = 1;
        } else {

            $size[0] = $wsize;
            $size[1] = $hsize;
            $size[3] = "width=\"$size[0]\" height=\"$size[1]\"";
            $size[4] = 0;
        }

        return $size;

    }


    /**
     * 直接htmlに置換
     * <!--TOPIC START-->この間に入る<!--TOPIC END-->
     *
     * @param type $str
     * @param type $original
     * @return type
     */
    function str_replace_html($str, $template)
    {
        foreach ($str as $key => $value) {
            $pattern[] = "/<!--" . strtoupper($key) . " START-->"
                . '(.+?)' . "<!--" . strtoupper($key) . " END-->/su";

            $replacement[] = "<!--" . strtoupper($key) . " START-->"
                . $value . "<!--" . strtoupper($key) . " END-->";
        }

        $html = preg_replace($pattern, $replacement, $template);
        return $html;

    }


    function str_replace_template($str, $original)
    {
        foreach ($str as $key => $value) {
            $mark = "<!--" . $key . "-->";
            $original = @str_replace($mark, $value, $original);
        }
        return $original;

    }


    function fsave_w($str, $savefile)
    {

        $fp = fopen($savefile, "w") or die("w fopen Error");
        if (function_exists('stream_set_write_buffer'))
            stream_set_write_buffer($fp, 0);
        flock($fp, LOCK_EX);
        fwrite($fp, $str);
        flock($fp, LOCK_UN);
        fclose($fp);

    }


    function fsave_array_w($lines, $savefile)
    {

        $fp = fopen($savefile, "w") or die("w fopen Error");
        if (function_exists('stream_set_write_buffer'))
            stream_set_write_buffer($fp, 0);
        flock($fp, LOCK_EX);
        for ($i = 0; $i < count($lines); $i++) {
            fwrite($fp, $lines[$i]);
        }

        flock($fp, LOCK_UN);
        fclose($fp);

    }


    function log_del_save($check, $log_file)
    {
        $fp = fopen($log_file, "r+") or die("r+ fopen Error");
        if (function_exists('stream_set_write_buffer'))
            stream_set_write_buffer($fp, 0);
        flock($fp, LOCK_EX);
        while (!feof($fp)) {

            $lineschek = fgets($fp);

            $data = explode("<>", $lineschek);

            if ($data[0] != "$check" && $data[0]) {
                $lines .= $lineschek;
                $returnlines[] = $lineschek;
            }
        }

        rewind($fp);
        fputs($fp, $lines);
        ftruncate($fp, ftell($fp));
        flock($fp, LOCK_UN);
        fclose($fp);


        return $returnlines;

    }


    function del_dir($dir_path)
    {
        $handle = opendir($dir_path);

        while (($entry = readdir($handle))) {
            if (is_file($dir_path . $entry)) {
                unlink($dir_path . $entry);
            }
        }
        closedir($handle);
    }


    function filemove($from, $to)
    {
        copy($from, $to);
        unlink($from);

    }


    /**
     *
     * @global type $navi_max
     * @global type $pageurl
     * @param type $page
     * @param type $totalpage
     * @return string
     */
    function navi_html($page, $totalpage)
    {
        global $navi_max, $pageurl;

        $bt['back'] = ($page > 1) ? '<a href="' . $pageurl . 'page_' . ($page - 1) . '.html">前のページ</a>' : null;


        if ($totalpage > 1 && $page < $totalpage) {
            $bt['next'] = '<a href="' . $pageurl . 'page_' . ($page + 1) . '.html">次のページ</a>';
        } else {
            $bt['next'] = null;
        }


        if ($page == 1) {
            $s = 1;
            if ($navi_max > $totalpage) {
                $e = $totalpage;
            } else {
                $e = $navi_max;
            }
        } elseif ($page == $totalpage) {
            $e = $totalpage;
            $s = $totalpage - $navi_max + 1;
            if (1 > $s)
                $s = 1;
        }else {
            $s = $page - floor($navi_max / 2);
            if (1 > $s)
                $s = 1;
            $e = $s + $navi_max - 1;
            if ($e > $totalpage) {
                $s = $totalpage - $navi_max + 1;
                $e = $totalpage;
            }
            if (1 > $s)
                $s = 1;
        }

        $bt['lists'] = '';
        for ($i = $s; $i <= $e; $i++) {
            if ($i == 1 && $page == 1) {
                $bt['lists'] .= '<b>1</b> ';
            } elseif ($i == $page) {
                $bt['lists'] .= '<b>' . $i . '</b> ';
            } elseif ($i == $s) {
                $bt['lists'] .= '<a href="' . $pageurl . 'page_' . $i . '.html">' . $i . '</a> ';
            } else {
                $bt['lists'] .= '<a href="' . $pageurl . 'page_' . $i . '.html">' . $i . '</a> ';
            }
        }

        return $bt;

    }


    /**
     * 管理パネル用一覧リスト
     *
     * @global type $PHP_SELF
     * @global type $adminlist_max
     * @global type images_dir
     * @global type $images_dir
     * @param type $mark
     * @param type $lines
     * @return string
     */
    function adminlist_html($mark, $lines)
    {
        global $PHP_SELF, $adminlist_max, $images_dir;


        if (isset($_POST['totallines'])) {
            $totallines = $_POST['totallines'];
        } else {
            $totallines = count($lines);
        }

        $totalpage = ceil($totallines / $adminlist_max);

        if ($totalpage == 0)
            $totalpage = 1;

        $page = isset($_POST['page']) ? $_POST['page'] : 1;


        $html['topic'] = '<div>' . $page . ' ページ目を表示 / ' . $totalpage
            . 'ページ (合計：' . $totallines . '件)</div>' . "\n";

        if ($totallines == 0) {
            $html['topic'] .= "<p>現在0件です。</p>";
            return $html['topic'];
        }

        $html['topic'] .= '<table class="table" cellpadding="0" cellspacing="0" width="100%">';
        $html['topic'] .= '<tr><th class="first">タイトル ・ コメント ・ 作成日時</th>'
            . '<th>画像</th><th>修正・削除</th><th class="last">並び替え</th></tr>';

        if ($page == $totalpage) {
            $e = $totallines;
            $s = $adminlist_max * ($page - 1);
        } else {
            $e = $page * $adminlist_max;
            $s = $e - $adminlist_max;
        }

        for ($i = $s; $i < $e; $i++) {

            $data = explode("<>", $lines[$i]);

            if ($data[1]) {
                $imgsize = @GetImageSize($images_dir . "thumb" . $data[1]);
                $upimage = "<a href=\"$data[0].html\" target=\"_blank\"><img src=\"${images_dir}thumb$data[1]\" $imgsize[3] alt=\"$data[3]\" border=\"0\"></a>\n";
            } else {
                $upimage = "--\n";
            }

            $html['topic'] .= '<tr><td><h4><a href="' . $data[0] . '.html" target="_blank">'
                . $data[3] . '</a></h4><br />' . $data[4] . '<br />'
                . $data[2] . '</td><td>' . $upimage . '</td><td>'
                . '<form method="get" action="' . $PHP_SELF . '">'
                .'<input type="hidden" name="fname" value="' . $data[0] . '" />'
                .'<input type="hidden" name="mode" value="renew" />'
                .'<input type="hidden" name="fix" value=' . time() . ' />'
                .'<button type="submit" class="button">修正</button></form>'
                . "<form method=\"post\" action=\"$PHP_SELF\">"
                .'<input type=hidden name=fname value="'.$data[0].'">'
                .'<input type=hidden name=mode value="del">'
                .'<button type="submit" class="button">削除</button></form>'
                . '</td><td>'
                . "<form method=\"post\" action=\"$PHP_SELF\">"
                . '<input type="hidden" name=fname value="' . $data[0] . '" />'
                . '<input type="hidden" name=mode value="logset" />'
                . '<input type="hidden" name=updown value="up" />'
                . '<input type="hidden" name=page value="' . $page . '" />'
                . '<input type="hidden" name=totallines value="' . $totallines . '" />'
                . '<button class="button" name="submit" type="submit">↑</button></form>'
                . "<form method=\"post\" action=\"$PHP_SELF\">"
                . '<input type="hidden" name=fname value="' . $data[0] . '" />'
                . '<input type="hidden" name=mode value="logset" />'
                . '<input type="hidden" name=updown value="down" />'
                . '<input type="hidden" name=page value="'.$page.'" />'
                . '<input type="hidden" name=totallines value="' . $totallines . '" />'
                . '<button class="button" name="submit" type="submit">↓</button></form>'
                . '</td>';
        }

        $html['topic'] .= "</table>\n";

        if ($totalpage >= 2) {

            $bt[0] = '<table cellpadding="0" cellspacing="0"><tr>';
            if ($page > 1) {
                $bt[0] .= "<form action=$PHP_SELF method=post><td><input type=hidden name=page value=\"1\"><input type=hidden name=totallines value=\"$totallines\"><input type=hidden name=mode value=\"adminlist\"><input type=submit value=\"≪最初のページ\"></td></form>";
                $bt[0] .= "<form action=$PHP_SELF method=post><td><input type=hidden name=page value=" . ($page - 1) . "><input type=hidden name=totallines value=\"$totallines\"><input type=hidden name=mode value=\"adminlist\"><input type=submit value=\"≪前の $adminlist_max 件\"></td></form>";
            }
            if ($totalpage > 1 and $page < $totalpage) {
                $bt[0] .= "<form action=$PHP_SELF method=post><td><input type=hidden name=page value=" . ($page + 1) . "><input type=hidden name=totallines value=\"$totallines\"><input type=hidden name=mode value=\"adminlist\"><input type=submit value=\"次の $adminlist_max 件≫\"></td></form>";
                $bt[0] .= "<form action=$PHP_SELF method=post><td><input type=hidden name=page value=\"$totalpage\"><input type=hidden name=totallines value=\"$totallines\"><input type=hidden name=mode value=\"adminlist\"><input type=submit value=\"最後のページ≫\"></td></form>";
            }
            $bt[0] .= '</tr></table>';

            $html['topic'] = "<div class=\"navi_line\">$bt[0]</div>\n"
                . $html['topic'] . "<p><div class=\"navi_line\">$bt[0]</div></p>\n";
        }

        return $html['topic'];

    }


    function date_html($data)
    {
        global $dateformat;
        return strftime($dateformat, strtotime($data));

    }


    /**
     * トップページのリスト
     *
     * @global type $toplistpagetitle
     * @global type $list_max
     * @global type $toplistlink
     * @global type $toprsslink
     * @param type $mark
     * @param type $lines
     */
    function toplist_html($mark, $lines)
    {
        global $images_dir,$toplistpagetitle, $list_max, $toplistlink, $toprsslink, $pageurl;

        $html['title'] = $toplistpagetitle;
        //$template = file_get_contents(TEMPLATETOPLIST);
        $template = file_get_contents(TOPLISTFNAME);


        $totallines = count($lines);
        if ($totallines >= TOPLISTMAX) {
            $e = intval(TOPLISTMAX);
        } else {
            $e = $totallines;
        }


        //簡易テンプレート呼び出し

        $Tpl = new Template();
        $Tpl->set_path('template/');
        $Tpl->set('toplistlink', $toplistlink);
        $Tpl->set('rss', $toprsslink);

        $topics = array();
        for ($i = 0; $i < $e; $i++) {
            $data = explode("<>", $lines[$i]);


            if (file_exists($images_dir . "thumb" . $data[1])) {
                $imgsize = @GetImageSize($images_dir . "thumb" . $data[1]);
                $upimage = '<a href="' . $pageurl . $data[0] . '.html"><img src="' . $images_dir . 'thumb' . $data[1] . '" ' . $imgsize[3] . ' alt="' . $data[3] . '" /></a>';
            } else {
                $upimage = "";
            }


            $topics[] = array(
                'date' => $this->date_html($data[2]),
                'title' => $data[3],
                'comment' => $data[4],
                'image' => $upimage,
                'detailUrl' => $pageurl . $data[0] . '.html'
            );
        }
        $Tpl->set('topics', $topics);
        $html['topic'] = $Tpl->fetch('toplist.php');

        $savedata = $this->str_replace_html($html, $template);
        $this->fsave_w($savedata, TOPLISTFNAME);

    }


    /**
     *
     * @global type $images_dir
     * @global type $listpagetitle
     * @global type $list_max
     * @param type $mark
     * @param type $lines
     * @return type
     */
    function list_html($mark, $lines)
    {
        global $toprsslink,$images_dir, $listpagetitle, $list_max, $toplistlink, $pageurl;

        $html['title'] = $listpagetitle;
        $template = file_get_contents(TEMPLATELIST);

        $totallines = count($lines);
        $totalpage = ceil($totallines / $list_max);

        if ($totalpage == 0)
            $totalpage = 1;

        $total = $totallines;


        //簡易テンプレート呼び出し
        require_once 'libs/template.php';

        $l = 0;
        for ($page = 1; $page <= $totalpage; $page++) {
            $topics = array();

            $Tpl = new Template();
            $Tpl->set_path('template/');
            $Tpl->set('toplistlink', $toplistlink);
            $Tpl->set('rss', $toprsslink);


            $Tpl->set('total', $total);
            $Tpl->set('thisPage', $page);
            $Tpl->set('totalPage', $totalpage);


            if ($page == $totalpage) {
                $e = $totallines;
                $s = $list_max * ($page - 1);
            } else {
                $e = $page * $list_max;
                $s = $e - $list_max;
            }

            for ($i = $s; $i < $e; $i++) {
                $data = explode("<>", $lines[$i]);
                if ($data[0] == $mark) {
                    if ($_POST['mode'] == "renewend") {
                        $returnlines[$l] = $_POST['fname'] . "<>" . $_POST['savefile'] . "<>" . $_POST['datedata'][0] . "/" . $_POST['datedata'][1] . "/" . $_POST['datedata'][2] . " " . str_pad($_POST['datedata'][3], 2, '0', STR_PAD_LEFT) . ":" . str_pad($_POST['datedata'][4], 2, '0', STR_PAD_LEFT) . "<>" . $_POST['title'] . "<>" . $_POST['comment'] . "<>" . "<>" . "\n";
                        $data[0] = $_POST['fname'];
                        $data[1] = $_POST['savefile'];

                        //フォーマット必要と思われ
                        $data[2] = $_POST['datedata'][0] . "/" . $_POST['datedata'][1] . "/" . $_POST['datedata'][2] . " " . str_pad($_POST['datedata'][3], 2, '0', STR_PAD_LEFT) . ":" . str_pad($_POST['datedata'][4], 2, '0', STR_PAD_LEFT);

                        $data[3] = $_POST['title'];
                        $data[4] = $_POST['comment'];
                    }
                } else {
                    $returnlines[$l] = $lines[$i];
                }

                if ($data[0]) {

                    if (file_exists($images_dir . "thumb" . $data[1])) {
                        $imgsize = @GetImageSize($images_dir . "thumb" . $data[1]);
                        $upimage = '<a href="' . $pageurl . $data[0] . '.html"><img src="' . $images_dir . 'thumb' . $data[1] . '" ' . $imgsize[3] . ' alt="' . $data[3] . '" /></a>';
                    } else {
                        $upimage = "";
                    }

                    $topics[] = array(
                        'date' => $this->date_html($data[2]),
                        'title' => $data[3],
                        'comment' => $data[4],
                        'image' => $upimage,
                        'detailUrl' => $pageurl . $data[0] . '.html'
                    );
                }
                $l++;
            }

            $Tpl->set('topics', $topics);

            //if ($totalpage >= 2) {
                $Tpl->set('pager', $this->navi_html($page, $totalpage));
            //}else{
            //    $Tpl->set('pager', array(''));
            //}


            $html['topic'] = $Tpl->fetch('list.php');
            $fname = 'page_' . $page;
            $savedata = $this->str_replace_template($html, $template);

            $this->fsave_w($savedata, $fname . ".html");
        }


        return $returnlines;

    }


    function check()
    {
        global $_POST;


        $errroFlg = false;

        if (!$_POST['title']) {
            $errroFlg = true;
            $this->error("タイトルを入力いただいたかご確認ください");
        }

        if (mb_strlen($_POST['title']) > TYTLEMAX) {
            $titlemax = TYTLEMAX;
            $errroFlg = true;
            $this->error("タイトルの文字数オーバーです。入力可能な文字数は " . $titlemax . " 文字です。");
        }

        if (COMMENT) {
            if (!$_POST['comment']) {
                $errroFlg = true;
                $this->error("コメントを入力いただいたかご確認ください");
            }

            if (mb_strlen($_POST['comment']) > COMMENTMAX) {
                $commentmax = COMMENTMAX;
                $errroFlg = true;
                $this->error("コメントの文字数オーバーです。入力可能な文字数は " . $commentmax . " 文字です。");
            }
        }

        if (count(preg_grep("/^[0-9]+$/", $_POST['datedata'])) != 5) {
            $errroFlg = true;
            $this->error("作成日時の入力をご確認ください");
        }

        if($errroFlg){
            $this->render();
        }

    }


    function strcode($str)
    {
        global $htmlinput;

        if (get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }

        if ($htmlinput == 1 || $_POST['mode'] == "check" || $_POST['mode'] == "renewcheck") {
            $str = htmlspecialchars($str);
        }

        $str = str_replace("<>", "<<!---->>", $str);

        $str = chop($str);

        return $str;

    }


    function passcheck($id, $pass)
    {
        $chek = false;
        if ($id == ID && $pass == PASS)
            $chek = true;

        return $chek;

    }


    /**
     * ログインチェック
     */
    function logincheck()
    {
        session_cache_limiter('private');
        session_start();

        $mode = isset($_GET['mode']) ? $_GET['mode'] : null;

        if ($mode == "logout") {
            session_cache_limiter('nocache');

            $_SESSION['login'] = false;
            @setcookie(session_name(), '', time() - 42000, '/');
            session_destroy();

            $this->render('login.php');
            exit;
        }


            $id = isset($_POST["id"]) ? $_POST["id"] : null;
            $pass = isset($_POST["pass"]) ? $_POST["pass"] : null;

        if (!isset($_SESSION['login']) && !$this->passcheck($id, $pass)) {

            if ($id || $pass) {
                $this->error("ユーザー名またはパスワードが違います。");
            }

            $this->render('login.php');
        } elseif (isset($_POST["id"]) && isset($_POST["pass"])) {
//ログインボタンを押した時の動作
            $id = isset($_POST["id"]) ? $_POST["id"] : null;
            $pass = isset($_POST["pass"]) ? $_POST["pass"] : null;
            if (!$this->passcheck($id, $pass)) {
                $this->error("ユーザー名またはパスワードが違います。");
                $this->render('login.php');
            }

            if (isset($_POST["id"]) || isset($_POST["pass"]))
                session_regenerate_id();
            $_SESSION['login'] = true;
        }
    }



    function ok($html)
    {
        $this->view->set('flash', '<div class="message notice"><p>' . $html . '</p></div>');

    }


    function error($html)
    {
        global $PHP_SELF, $mode;

        $this->view->set('flash', '<div class="message error"><p>' . $html . '</p></div>');

        if ($mode) {
            $this->view->set('html','<div style="text-align:center;"><button type="button" onClick="javascript:history.back()" class="button" style="margin:50px 10px;">戻る</button></div>');
        }

    }


    function warning($html)
    {
        $this->view->set('flash', '<div class="message warning"><p>' . $html . '</p></div>');

    }


    function menu()
    {
        global $mode;

        $menu = '<ul class="wat-cf">';
        $menu .= '<li><a href="?">登録済みリスト</a></li>';
        $menu .= '<li><a href="?mode=regist">新規登録</a></li>';
        $menu .= '<li><a href="?mode=htmlrenew">HTMLファイル更新</a></li>';
        $menu .= '</ul>';

        return $menu;

    }


    function assign($key, $value)
    {
        $this->view->set($key, $value);

    }


    function render($tplname = null)
    {
        $tplname = $tplname ? $tplname : 'base.php';
        $this->view->render($tplname);
        exit();

    }


}
