<!DOCTYPE html>
<html>
<head>
	<meta http-quive="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, user-scalable=0"/>
	<title><?=$data['config']['siteTitle']?></title>
  <meta http-quive="description" content="<?=$data['config']['description']?>"/>
	<link rel="stylesheet" type="text/css" href="<?=$data['config']['baseurl']?>/theme/style/style.css">
	<!--[if gte IE 8]><script type="text/javascript" src="<?=$data['config']['baseurl']?>/theme/js/goodbyeie.js"></script><![endif]-->
</head>
<body>
<div id="container">
	<header id="header">
		<h1 id="logo"><a href="<?=$data['config']['baseurl']?>"><?=$data['config']['siteName']?></a></h1>
		<nav id="nav">
			<a href="<?=$data['config']['baseurl']?>">Blog</a>
			<?php foreach ($data['pages'] as $item) { ?>
			<a href="<?=$item['url']?>"><?=$item['name']?></a>
			<?php } ?>
		</nav>
	</header>

	<?php require 'sidebar.php'; ?>
