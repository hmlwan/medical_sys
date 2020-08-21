<?php /*a:1:{s:80:"C:\wwwroot\www.dayuli.cn\application\index\view\product\receive_detail_ajax.html";i:1592306810;}*/ ?>
<?php foreach($list as $item): ?>
<a href="javascript:;">
    <li>
        <p> <span>云链矿场</span><span><?php echo htmlentities(date("Y-m-d H:i:s",!is_numeric($item['add_time'])? strtotime($item['add_time']) : $item['add_time'])); ?> </span>  </p>
        <p class="inc_blue">+<?php echo htmlentities($item['show_num']); ?></p>
    </li>
</a>

<?php endforeach; ?>