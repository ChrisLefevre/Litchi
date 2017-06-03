<?php 
header("HTTP/1.0 404 Not Found");
$pagename = '404';
$meta_author = $siteinfo['author'];
$title = '404' . $siteinfo['title'];
$description = $siteinfo['baseline'];
$meta_url = SITE_URL.'error404';
$meta_preview = SITE_URL . 'files/show/1200/'.$siteinfo['cover']; 

include 'template/includes/header.php';

?>

    <!-- Page Header -->
    <header class="masthead" style="background-image: url('<?php echo $meta_preview; ?>')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="post-heading">
                        <h1><?php $sw->_e("404"); ?> </h1>
                        <h2 class="subheading"><?php $sw->_em("page not found"); ?> </h2>
          
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Post Content -->
    <article>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                <img class="img-responsive" src="<?php echo SITE_URL; ?>template/img/404-bg.jpg">
                </div>
            </div>
        </div>
    </article>

    <hr>

 <?php include 'template/includes/footer.php';  ?>
