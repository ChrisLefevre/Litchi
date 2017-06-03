<?php 

$pagename = 'about';

$page = $sw->block("about");

$meta_author = $siteinfo['author'];
$title = $page['title'].' - '.$siteinfo['title'];
$description = $page['baseline'];
$meta_url = SITE_URL.'about';
$meta_preview = SITE_URL . 'files/show/1200/'.$page['cover']; 



include 'template/includes/header.php';


?>


    <!-- Page Header -->
    <header class="masthead" style="background-image: url('<?php echo $meta_preview; ?>')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="page-heading">
                        <h1><?php echo $page['title']; ?></h1>
                        <span class="subheading"><?php echo $page['baseline']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
               <p><?php echo $page['text']; ?></p>
            </div>
        </div>
    </div>

    <hr>

<?php include 'template/includes/footer.php'; ?>