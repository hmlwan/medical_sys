<?php /*a:1:{s:79:"C:\wwwroot\www.dayuli.cn\application\index\view\member\magiclogdetail_ajax.html";i:1593435834;}*/ ?>
<?php foreach($list as $item): ?>
<li>
    <input type="hidden" value="<?php echo htmlentities($item->id); ?>">
    <p class="first_child">
        <?php if(($item['magic']) < 0): ?>
            <i class="sr1_icon"></i>
            <span><?php echo htmlentities($item->types); ?></span>
            <span><?php echo htmlentities($item->show_magic); ?>云链</span>
        <?php else: ?>
            <i class="sr2_icon"></i>
            <span><?php echo htmlentities($item->types); ?></span>
            <span>+<?php echo htmlentities($item->show_magic); ?>云链</span>
        <?php endif; ?>
    </p>
    <p style="margin-left: .9rem;"><?php echo htmlentities(date("H:i:s",!is_numeric($item->create_time)? strtotime($item->create_time) : $item->create_time)); ?></p>
</li>
<?php endforeach; ?>