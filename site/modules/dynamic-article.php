<?php
// Do not include directly, instead use dynamicArticle($id)
// to include specified article using this template
// Variables to use:
// $theArticle (contains title, ingress, body, author and date)
// $theId (of the requested article)
use League\CommonMark\CommonMarkConverter;
$md = new CommonMarkConverter();
$ingressHTML = $md->convert($theArticle['ingress'])->getContent();
$bodyHTML = $md->convert($theArticle['body'])->getContent();
?>
<article id="<?php echo strtolower($theId) ?>">
<h1><?php echo $theArticle['title'] ?></h1>
<div class="ingress"><?php echo $ingressHTML ?></div>
<div class="body"><?php echo $bodyHTML ?></div>
<div class="meta">
<span class="author"><?php echo $theArticle['author'] ?></span>
<time><?php echo $theArticle['date'] ?></time>
</div>
</article>
