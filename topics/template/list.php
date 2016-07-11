<?php
/* ------------------------------------------------------------------------------
 *
 *
 *
 *
 */
?>


<div class="pager">
    <?php echo $thisPage ?> ページ目を表示 / <?php echo $totalPage ?>ページ (合計：<?php echo $total ?>件) <?php echo $rss ?><br />
    <?php echo $pager['back'] ?> <?php echo $pager['lists'] ?> <?php echo $pager['next'] ?>
</div>

<?php foreach ($topics as $i): ?>
    <div class="txt10 dotline">
        <div class="image"><?php echo $i['image'] ?></div>
        <p><?php echo $i['comment'] ?></p>
        <span class="txtyel"><?php echo $i['date'] ?></span>
    </div>
<?php endforeach; ?>

<div class="pager">
    <?php echo $pager['back'] ?> <?php echo $pager['lists'] ?> <?php echo $pager['next'] ?>
</div>