<?php /*a:1:{s:80:"C:\wwwroot\www.dayuli.cn\application\index\view\product\receive_record_ajax.html";i:1591527760;}*/ ?>
<?php foreach($list as $key=>$item): ?>
<a href="receive_detail?times=<?php echo htmlentities($key); ?>">
    <li>
        <span><?php echo htmlentities($key); ?></span>
        <span class="inc_red"><?php echo htmlentities($item); ?><i></i></span>
    </li>
</a>
<?php endforeach; ?>