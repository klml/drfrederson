<?php
	require 'header.php';
?>

<div id="main" class="archives page">
	<h2 class="name">Blog Archives</h2>
	
	<section>

	<?php
		$year = 0;
		foreach ($data['posts'] as $post) { 
	?>
		<?php 
			if($year !== $post['year']) {
			$year = $post['year'];
		?>		
			<h3 class="year"><?=$year?></h3>
		<?php } ?>

		<article class="item">
			<time class="date"><?=$post['month'] . ' - ' . $post['day']?></time>
			<h4 class="title"><a href="<?=$post['url']?>"><?=$post['title']?></a></h4>
		</article>

	<?php } ?>

	</section>

</div>

<?php
	require 'footer.php';
?>
