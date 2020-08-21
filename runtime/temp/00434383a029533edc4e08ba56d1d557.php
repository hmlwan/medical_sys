<?php /*a:1:{s:70:"D:\WWW\candyworld\application\index\view\member\magicloglist_ajax.html";i:1594462994;}*/ ?>
<?php foreach($list as $k=>$item): ?>
<li>
    <a href="magiclogdetail?times=<?php echo htmlentities($k); ?>">
        <p class="first_child">
            <i class="task_icon"></i>
            <span>今日收支</span>
            <?php if($item<0): ?>
                <span><?php echo htmlentities($item); ?>糖果 ></span>
            <?php else: ?>
                <span><?php echo htmlentities($item); ?>糖果 ></span>
            <?php endif; ?>
        </p>
        <p><?php echo htmlentities($k); ?></p>
    </a>
</li>
<?php endforeach; ?>