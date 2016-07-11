<?php

$imagedelform = '';
if ($data[0]) {
    $pmode = "renewcheck";
    if ($data[1]) {
        $imagedelform = "<input type=\"checkbox\" name=\"imagedel\" value=\"1\"> アップロード済みのファイルを削除<br /><br />";
    }
} else {
    $pmode = "check";
}



$setextensionhtml = implode(' ',$setextension);

$index_html = <<<html
<form action="$PHP_SELF" method="post" class="form" enctype="multipart/form-data">
<table class="table" cellpadding="0" cellspacing="0">
<tr>
<td class="label">日付</td>
<td>
<input class="text_field" style="width:60px;" type="text" name="datedata[]" size="4" maxlength="4" value="{$datedata['year']}" />年　
<input class="text_field" style="width:30px;" type="text" name="datedata[]" size="2" maxlength="2" value="{$datedata['mon']}" />月　
<input class="text_field" style="width:30px;" type="text" name="datedata[]" size="2" maxlength="2" value="{$datedata['mday']}" />日　
<input class="text_field" style="width:30px;" type="text" name="datedata[]" size="2" maxlength="2" value="{$datedata['hours']}" />時　
<input class="text_field" style="width:30px;" type="text" name="datedata[]" size="2" maxlength="2" value="{$datedata['minutes']}" />分　
</td>
</tr>
<tr>
<td>タイトル</td>
<td><input class="text_field" type="text" name="title" size="40" maxlength="40" value="{$data[3]}" style="width:220px;"><br />
<span class="formsubcomment">（全角${titlemax}文字まで）</span>
</td>
</tr>
<tr>
<td>コメント</td>
<td><textarea class="text_area" name="comment" rows="10" style="width:450px;">{$data[4]}</textarea><br />
<span class="formsubcomment">（全角{$commentmax}文字まで）</span>
</td>
</tr>
<tr>
<td>画像ファイル</td>
<td>{$imagedelform}{$data[10]}<input class="form" type="file" name="userfile" size="40" /><br />
<p>「参照」ボタンを押して、画像ファイルを指定ください。<br />
アップロードができる画像サイズの最大は、縦{$up_hlimit_upper}px、横{$up_wlimit_upper}pxで、ファイルサイズは${up_filesizelimit_upper}KBです。<br />
最小サイズは、縦${up_hlimit_lower}px、横{$up_wlimit_lower}pxです。<br />
また、アップロードできる画像の拡張子は『{$setextensionhtml}』のみです。
</p>
</td>
</tr>
</table>
<input type="hidden" name="mode" value="{$pmode}" />
<input type="hidden" name="fname" value="{$data[0]}" />
<input type="hidden" name="savefile" value="{$data[1]}" />
    
    <div class="group navform wat-cf">
        <button type="submit" class="button">
            <img alt="Save" src="template/images/icons/tick.png"> 入力確認
        </button>
    </div>
</form>
html;
