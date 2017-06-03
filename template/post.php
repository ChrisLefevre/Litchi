<?php 

$pagename = 'post';
$meta_author = $siteinfo['author'];
$title = $siteinfo['title'];
$description = $siteinfo['baseline'];
$meta_url = SITE_URL;
$meta_preview = SITE_URL . 'files/show/1200/'.$siteinfo['cover']; 
if(isset($_GET['id'])) { 
    $id = htmlentities($_GET['id']);
    $bpost = $sw->blogpost($id) ;   
}

if (!empty($bpost)) {

$title = $bpost['title'].' · '.$siteinfo['title'];
$description = $bpost['headline'];
$meta_url =  $sw->previewUrl($bpost,$swcnt_post['blog']["sw_url_preview"]);
if(!empty($bpost['cover'])) $meta_preview = SITE_URL . 'files/show/600/315/'.$bpost['cover'];

$meta_author = $bpost['author'];

include 'template/includes/header.php';



?>

    <!-- Page Header -->
    <header class="masthead" style="background-image: url('<?php echo $meta_preview; ?>')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="post-heading">
                        <h1><?php echo $bpost['title']; ?></h1>
                        <h2 class="subheading"><?php echo $bpost['headline']; ?></h2>
                        <span class="meta"><?php echo $sw->_("Posted by"); ?>  <strong><?php echo ucFirst($bpost['author']); ?></strong> <?php echo $sw->_("on"); ?> <?php echo  $sw->dateTime($bpost['pubdate']); ?> <a class="pull-right" href="<?php echo SITE_URL.'section/'. $bpost['category']; ?>"><?php echo $bpost['categoryName']; ?></a> </span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Post Content -->
    <article>
        <div class="container">
            <div class="row">
	         <div  class=" offset-lg-1 col-lg-1">   
			<div id="sharefloat">
				
			<span>Partager</span>
			
			<a target="_blank" href="http://twitter.com/?status=<?php echo urlencode($bpost['title']); ?> <?php echo urlencode($meta_url); ?>" ><i class="fa fa-twitter icons"></i></a>
			<a target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($meta_url); ?>"><i class="fa fa-facebook icons"></i></a>
			<a target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&#038;url=<?php echo urlencode($meta_url); ?>&#038;title=<?php echo urlencode($bpost['title']); ?>" ><i class="fa fa-linkedin icons"></i></a>
			</div>
			 </div>
                <div class="col-lg-8 offset-lg-0 col-md-10 offset-md-1">
                  <?php echo $bpost['article']; ?>
                </div>
            </div>
        </div>
    </article>

    <hr>

 <?php include 'template/includes/footer.php'; } else include 'template/error404.php';  ?>
