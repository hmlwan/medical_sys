<?php /*a:1:{s:80:"D:\WWW\candyworld\application\index\view\welfare\flash_exchange_record_ajax.html";i:1594721362;}*/ ?>
<?php foreach($list as $item): ?>
<li>

    <a href="<?php echo url('flash_exchange_detail',array('id'=>$item->id)); ?>">
        <p class="first_child">
            <?php if($item->status == 1): ?>
                <i class="sr1_icon"></i>
                <span>已处理</span>
            <?php elseif($item->status == 2): ?>
                <i class="sr1_icon"></i>
                <span>未处理</span>
            <?php else: ?>
                <i class="sr2_icon"></i>
                <span>等待处理</span>
            <?php endif; ?>
            <span>-<?php echo htmlentities($item->cny_num); ?> CNY ></span>
        </p>
        <p style="margin-left: .9rem;"><?php echo htmlentities(date("Y/m/d",!is_numeric($item->add_time)? strtotime($item->add_time) : $item->add_time)); ?></p>
    </a>
</li>
<?php endforeach; ?>