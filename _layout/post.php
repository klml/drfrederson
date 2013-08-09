<?php
	require 'header.php';
?>

<div id="main">
	<section class="post">
		<h2 class="title"><?=$data['post']['title']?></h2>
		<div class="meta">
			<time class="date"><?=$data['post']['year'] . ' - ' . $data['post']['month'] . ' - ' . $data['post']['day']?></time>
		</div>
		<article class="content"><?=$data['post']['content']?></article>
	</section>

	<div class="postNav">
		<?php if ($data['newerPostUrl']) { ?>
			<a class="newer" href="<?=$data['newerPostUrl']?>" title="Newer Article"><?=$data['newerPostTitle']?> »</a>
		<?php } ?>
		<?php if ($data['olderPostUrl']) { ?>
			<a class="older" href="<?=$data['olderPostUrl']?>"  title="Older Article">« <?=$data['olderPostTitle']?></a>
		<?php } ?>
	</div>

	<?php if ($data['post']['comment']) {
		require 'comment.php';
	} ?>

</div>

<?php
	require 'footer.php';
?>
