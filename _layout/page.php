<?php
	require 'header.php';
?>

<div id="main">
	<section class="page">
		<h2 class="name"><?=$data['page']['name']?></h2>
		<article><?=$data['page']['content']?></article>
	</section>
	
	<?php if ($data['page']['comment']) {
		require 'comment.php';
	} ?>

</div>

<?php
	require 'footer.php';
?>