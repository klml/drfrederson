<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, user-scalable=0"/>
	<title><?=$data['page']['name']?> | <?=$data['config']['siteName']?></title>
    <meta http-equiv="description" content="<?=$data['page']['description']?>"/>

	<link rel="stylesheet" type="text/css" href="<?=$data['config']['baseurl']?>lib/CSS-Mini-Reset/CSS-Mini-Reset-min.css">

    <link rel="stylesheet" type="text/css" href="<?=$data['config']['baseurl']?>lib/skeleton/stylesheets/base.css">
    <link rel="stylesheet" type="text/css" href="<?=$data['config']['baseurl']?>lib/skeleton/stylesheets/layout.css">
    <link rel="stylesheet" type="text/css" href="<?=$data['config']['baseurl']?>lib/skeleton/stylesheets/skeleton.css">

    <link rel="stylesheet" type="text/css" href="<?=$data['config']['baseurl']?>lib/usefulclassroomphrases/ucp.css">
	<link rel="stylesheet" type="text/css" href="<?=$data['config']['baseurl']?>_source/_custom-css.md">
</head>
<body class="d2c_<?=$data['page']['lemma']?>" >


<div class="container">
	<header id="header" class="sixteen columns">
		<nav id="nav">
            <article><?=$data['page']['pagedurable']?></article>
        </nav>
	</header>

