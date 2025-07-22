<?php $titles = getTexts('titles', $GLOBALS['my_language']); ?>
<nav>
    <ul>
        <li>
            <a href="<?php echo $GLOBALS['base_uri'].'/m' ?>">
                <?php echo $titles['RATED_MOVIES_TITLE']; ?>
            </a>
        </li>
        <li>
            <a href="<?php echo $GLOBALS['base_uri'].'/reviews' ?>"><?php echo $titles['VIDEO_REVIEWS_TITLE']; ?></a>
        </li>
        <li>
            <a href="<?php echo $GLOBALS['base_uri'].'/lists' ?>"><?php echo $titles['MISC_LISTS_TITLE']; ?></a>
        </li>
    </ul>
</nav>
