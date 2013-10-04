<?php
	require 'header.php';
?>

<div id="main" class="sixteen columns">
	<section class="page">
		<article><?=$data['page']['content']?></article>
	</section>
	
	<?php if ($data['page']['comment']) {
		require 'comment.php';
	} ?>

</div>

<?php
	require 'footer.php';
?>
