<?php
$article = getArticle($articleID);
?>
<article id="article-introduction">
<h1><?php echo $article['title'] ?></h1>
<p><?php echo $article['ingress'] ?></p>
<p><?php echo $article['body'] ?></p>
<p><?php echo $article['author'] ?></p>
</article>
