<?php
/*------------------------------------------------------------------------------
 *
 *
 * $toplistlink：トピック一覧ページリンク
 * $rss：RSSリンク
 *
 * $topics：array
 *   date:登録日
 *   title:タイトル
 *   commnet:コメント
 *   image:画像
 *
 *
 *
 *
 */
?>
<div>
    <?php echo $toplistlink ?> <?php echo $rss ?>
</div>

<?php foreach($topics as $i): ?>
<p class="txt10 dotline">
<span class="txtyel"><?php echo $i['date'] ?></span>
<br />
<?php echo $i['comment'] ?>
</p>
<?php endforeach; ?>