<?php /*a:1:{s:77:"C:\wwwroot\www.dayuli.cn\application\index\view\member\magicloglist_ajax.html";i:1593435968;}*/ ?>
<?php foreach($list as $k=>$item): ?>
<li>
    <a href="magiclogdetail?times=<?php echo htmlentities($k); ?>">
        <p class="first_child">
            <i class="task_icon"></i>
            <span>今日收支</span>
            <?php if($item<0): ?>
                <span>-<?php echo htmlentities($item); ?>云链 ></span>
            <?php else: ?>
                <span><?php echo htmlentities($item); ?>云链 ></span>
            <?php endif; ?>
        </p>
        <p><?php echo htmlentities($k); ?></p>
    </a>
</li>
<?php endforeach; ?>