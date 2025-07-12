<?php
// Do not include directly, instead use includeArticle($id)
// to include specified article using this template
// Variables to use:
// $theArticle (contains title, ingress, body, author and date)
// $theId (of the requested article)
?>
<article id="<?php echo strtolower($theId) ?>">
<h1><?php echo $theArticle['title'] ?></h1>
<p class="ingress"><?php echo $theArticle['ingress'] ?></p>
<p><?php echo $theArticle['body'] ?></p>
<div class="meta">
<span class="author"><?php echo $theArticle['author'] ?></span>
<time><?php echo $theArticle['date'] ?></time>
</div>
</article>
