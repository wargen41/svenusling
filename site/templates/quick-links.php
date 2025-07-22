<?php $titles = getTexts('titles', $GLOBALS['my_language']); ?>
<nav>
    <ul>
        <li>
            <a href="<?php echo $GLOBALS['base_uri'].'/view/movies' ?>">
                <?php echo $titles['RATED_MOVIES_TITLE']; ?>
            </a>
        </li>
        <li>
            <a href="<?php echo $GLOBALS['base_uri'].'/view/reviews' ?>"><?php echo $titles['VIDEO_REVIEWS_TITLE']; ?></a>
        </li>
<!--        <li>
            <a href="<?php echo $GLOBALS['base_uri'].'/view/lists' ?>"><?php echo $titles['MISC_LISTS_TITLE']; ?></a>
        </li>-->
    </ul>
</nav>
