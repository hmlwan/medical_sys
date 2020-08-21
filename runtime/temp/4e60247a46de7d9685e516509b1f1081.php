<?php /*a:1:{s:78:"C:\wwwroot\www.dayuli.cn\application\index\view\invite\invite_record_ajax.html";i:1593415230;}*/ ?>
<?php foreach($list as $item): ?>
<li class="wdtd-view">
    <p class="first_line">
        <img src="<?php if($item->avatar): ?><?php echo htmlentities($item->avatar); else: ?>/static/img/newimg/tx1.png<?php endif; ?>" alt="">
        <span><?php echo htmlentities($item->mobile); ?></span>
        <?php if($item->is_cert == 2): ?>
            <i class="rz_icon"></i>
        <?php else: ?>
            <i class="wrz_icon"></i>
        <?php endif; ?>
    </p>
    <p class="second_line">
        <span>算力：<em><?php echo htmlentities(round($item->energy,0)); ?></em> T</span>
        <span>注册时间：<em><?php echo htmlentities(date("Y-m-d H:i:s",!is_numeric($item->register_time)? strtotime($item->register_time) : $item->register_time)); ?></em></span>
    </p>
</li>
<?php endforeach; ?>