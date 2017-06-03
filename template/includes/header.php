<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="author" content="<?php echo $meta_author; ?>">
    <title><?php echo $title ?></title>
	<meta property="og:title" content="<?php echo htmlspecialchars($title); ?>">
	<meta property="og:image" content="<?php echo $meta_preview; ?>">
	<meta property="og:type" content="article">
	<meta property="og:url" content="<?php echo $meta_url; ?>">
	<meta property="og:description" content="<?php echo $description; ?>">
	<meta property="og:site_name" content="<?php echo $siteinfo['title']; ?>">
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:site" content="@chrislefevre" />
	<meta name="twitter:title" content="<?php echo htmlspecialchars($title); ?>" />
	<meta name="twitter:description" content="<?php echo $description; ?>" />
	<meta name="twitter:creator" content="@chrislefevre" />
	<meta name="twitter:image:src" content="<?php echo $meta_preview; ?>" />
	<meta name="twitter:domain" content="<?php echo parse_url($meta_url, PHP_URL_HOST); ?>" />
	<link rel="shortcut icon" href="<?php echo SITE_URL; ?>template/img/icon.png" type="image/x-icon" />
    <title><?php echo $title ?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo TEMPLATE_URL; ?>vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="<?php echo TEMPLATE_URL; ?>vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="<?php echo TEMPLATE_URL; ?>css/clean-blog.min.css" rel="stylesheet">

    <!-- Temporary navbar container fix -->
    <style>
    .navbar-toggler {
        z-index: 1;
    } 
    @media (max-width: 576px) {
        nav > .container {
            width: 100%;
        }
    }
    </style>
</head>

<body>
      <!-- Navigation -->
    <nav class="navbar fixed-top navbar-toggleable-md navbar-light" id="mainNav">
        <div class="container">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="<?php echo SITE_URL ; ?>"><?php echo $siteinfo['title']; ?></a>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <?php foreach($siteinfo["navigation"] as $nav) {           
                            if ($nav["type"]=="extern") echo '<li class="nav-item"><a class="nav-link" href="'.$nav["url"].'">'.$nav["name"].'</a></li>';
		                    else echo '<li class="nav-item"><a class="nav-link" href="'.SITE_URL.$nav["url"].'">'.$nav["name"].'</a></li>';
                     } ?> 
                </ul>
            </div>
        </div>
    </nav>