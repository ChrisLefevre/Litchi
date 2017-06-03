<?php 

$pagename = 'home';
$meta_author = $siteinfo['author'];
$title = $siteinfo['title'];
$description = $siteinfo['baseline'];
$meta_url = SITE_URL;
$meta_preview = SITE_URL . 'files/show/1200/'.$siteinfo['cover']; 


$actpage = 1;
$maxitems = 5;
if(isset($_GET['id'])) { 
    $actpage = intval($_GET['id']); 
}

include 'template/includes/header.php';
$posts = $sw->blogposts('blog',$maxitems,$actpage,false);
$latestPost = sizeof($posts);

?>


    <!-- Page Header -->
    <header class="masthead" style="background-image: url('<?php echo $meta_preview; ?>')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="site-heading">
                        <h1><?php echo $title; ?></h1>
                        <span class="subheading"><?php echo $description; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">

                <?php $indexPost = 1; foreach($posts as $post) { ?> 
                <div class="post-preview">
                    <a href="<?php echo $sw->previewUrl($post,$swcnt_post['blog']["sw_url_preview"]); ?>">
                        <h2 class="post-title">
                           <?php echo $post["title"]; ?>
                        </h2>
                        <h3 class="post-subtitle">
                            <?php echo mb_strimwidth($post["body"], 0, 150, "..."); ?>
                        </h3>
                    </a>
                    <p class="post-meta"><?php $sw->_em("posted on"); ?> <?php echo  $sw->dateTime($post['pubdate']); ?></p>
                </div>
                
                <?php 
	               if($indexPost<$latestPost) echo '</hr>';
	                $indexPost++;
	                } ?>
              


                <!-- Pager -->
                <div class="clearfix">

                    <?php if ($actpage>1) { ?> 
	            
                        <a class="btn btn-secondary float-left" href="<?php echo SITE_URL.($actpage-1); ?>">&larr; <?php echo $sw->_("Next Posts"); ?></a>
               
	                <?php } ?>
	                
	                
	                <?php if (count($posts) ==  $maxitems) { ?>
                
                        <a class="btn btn-secondary float-right" href="<?php echo SITE_URL.($actpage+1); ?>"><?php echo $sw->_("Older Posts"); ?> &rarr;</a>
                    
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <hr>
<?php include 'template/includes/footer.php'; ?>