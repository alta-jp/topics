<?php

if ($_POST['mode'] == "renewcheck") {
    $mode = "renewend";
} elseif ($_POST['mode'] == "del") {
    $mode = "delend";
} else {
    $mode = "end";
}

$check_html = <<<html
<table class="table" cellpadding="0" cellspacing="0">
<tr>
<td class="tabletitle">日付</td>
<td class="tableform">
{$_POST['datedata'][0]}年
{$_POST['datedata'][1]}月
{$_POST['datedata'][2]}日　
{$_POST['datedata'][3]}時
{$_POST['datedata'][4]}分
</td>
</tr>
<tr>
<td class="tabletitle">タイトル</td>
<td class="tableform">{$_POST['title']}</td>
</tr>
<tr>
<td class="tabletitle">コメント</td>
<td class="tableform">{$_POST['checkcomment']}</td>
</tr>
<tr>
<td class="tabletitle">画像ファイル</td>
<td class="tableform">{$imageurl}</td>
</tr>
</table>

<div class="group navform wat-cf">
<form action="{$PHP_SELF}" method="post">
<input type="hidden" name="title" value="{$_POST['title']}" />
<input type="hidden" name="comment" value="{$_POST['comment']}" />
<input type="hidden" name="datedata[]" value="{$_POST['datedata'][0]}" />
<input type="hidden" name="datedata[]" value="{$_POST['datedata'][1]}" />
<input type="hidden" name="datedata[]" value="{$_POST['datedata'][2]}" />
<input type="hidden" name="datedata[]" value="{$_POST['datedata'][3]}" />
<input type="hidden" name="datedata[]" value="{$_POST['datedata'][4]}" />
<input type="hidden" name="fname" value="{$_POST['fname']}" />
html;
if(isset($_POST['imagedel'])){
    $check_html .= '<input type="hidden" name="imagedel" value="'.$_POST['imagedel'].'" />';
}
$check_html .= <<<html
<input type="hidden" name="savefile" value="{$_POST['savefile']}" />
<input type="hidden" name="mode" value="{$mode}" />
<button type="submit" class="button">
    <img alt="Save" src="template/images/icons/tick.png"> 実行
</button>
or
<a href="javascript:void(0)" onClick="history.back()">戻る</a>
</form>
</div>

html;
