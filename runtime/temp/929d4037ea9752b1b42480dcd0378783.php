<?php /*a:1:{s:82:"C:\wwwroot\www.dayuli.cn\application\index\view\invite\commission_record_ajax.html";i:1593434356;}*/ ?>
<?php foreach($list as $item): ?>
<li>
    <input type="hidden" value="<?php echo htmlentities($item->id); ?>">
    <p class="first_child">
        <?php if(($item['num']) < 0): ?>
            <i class="sr1_icon"></i>
            <span><?php echo htmlentities($item['type_name']); ?><em style="font-size: .24rem;">(<?php echo htmlentities($item['sub_mobile']); ?>)</em></span>
            <span>-<?php echo htmlentities($item['num']); ?>云链</span>
        <?php else: ?>
            <i class="sr2_icon"></i>
            <span><?php echo htmlentities($item['type_name']); ?><em style="font-size: .24rem;">(<?php echo htmlentities($item['sub_mobile']); ?>)</em></span>
            <span>+<?php echo htmlentities($item['num']); ?>云链</span>
        <?php endif; ?>
    </p>
    <p style="margin-left: .9rem;"><?php echo htmlentities(date("Y-m-d H:i:s",!is_numeric($item['add_time'])? strtotime($item['add_time']) : $item['add_time'])); ?></p>
</li>
<?php endforeach; ?>