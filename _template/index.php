<?php
	require 'header.php';
?>

<div id="main">
	<section class="posts">

	<?php foreach ($data['posts'] as $post) { ?>
		<div class="post">
			<h2 class="title"><a href="<?=$post['url']?>"><?=$post['title']?></a></h2>
			<div class="meta">
				<time class="date"><?=$post['year'] . ' - ' . $post['month'] . ' - ' . $post['day']?></time>
			</div>
			<?php if ($post['more']) { ?>
			<article class="content">
				<?=$post['more']?>
				<p class="readMoreWrap"><a class="readMore" href="<?=$post['url']?>">Read More »</a></p>
			</article>
			<?php } else { ?>
			<article class="content"><?=$post['content']?></article>
			<?php } ?>
		</div>
	<?php } ?>

	</section>

	<nav class="postNav">

		<?php if ($data['newerUrl']) { ?>
			<a class="newer" href="<?=$data['newerUrl']?>">Newer</a>
		<?php } ?>
		<?php if ($data['olderUrl']) { ?>
			<a class="older" href="<?=$data['olderUrl']?>">Older</a>
		<?php } ?>

	</nav>
</div>
    
<?php
	require 'footer.php';
?>
