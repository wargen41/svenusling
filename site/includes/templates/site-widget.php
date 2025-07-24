<!--<a href="<?php echo $GLOBALS['base_uri'].'/'; ?>"><label for="site-widget" id="site-widget-label"><?php echo $my_site['name'] ?></label></a>-->

<label for="site-widget-button" id="site-widget-label"><?php echo getStr('MAIN_MENU_TITLE'); ?></label><div id="site-widget">
<button id="site-widget-button" onclick="javascript:location.href='<?php echo $GLOBALS['base_uri'].'/'; ?>'" title="<?php echo getStr('MAIN_MENU_TITLE'); ?>">
    <img class="circle" alt="<?php echo getStr('MAIN_MENU_TITLE'); ?>" src="<?php echo $GLOBALS['base_uri'] . $my_site['icon'] ?>">
</button>
</div>

<!-- Försök med input-element (cirkeln blir inte rund?!?!)
<label for="site-widget-button" id="site-widget-label"><?php echo getStr('MAIN_MENU_TITLE'); ?></label><div id="site-widget">
    <input class="circle" type="image" src="<?php echo $GLOBALS['base_uri'].$my_site['icon'] ?>" alt="<?php echo getStr('MAIN_MENU_TITLE'); ?>" id="site-widget-button" onclick="javascript:location.href='<?php echo $GLOBALS['base_uri'].'/'; ?>'" title="<?php echo getStr('MAIN_MENU_TITLE'); ?>">
    </a>
</div>-->

<!-- backup-kod som funkar med a-element istället för button
<label for="site-widget-button" id="site-widget-label"><?php echo getStr('MAIN_MENU_TITLE'); ?></label><div id="site-widget">
    <a id="site-widget-button" onclick="javascript:location.href='<?php echo $GLOBALS['base_uri'].'/'; ?>'" title="<?php echo getStr('MAIN_MENU_TITLE'); ?>">
        <img class="circle" alt="<?php echo getStr('MAIN_MENU_TITLE'); ?>" src="<?php echo $GLOBALS['base_uri'] . $my_site['icon'] ?>">
    </a>
</div>-->
