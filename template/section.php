<?php 

$pagename = 'section';
$meta_author = $siteinfo['author'];
$meta_preview = SITE_URL . 'files/show/1200/'.$siteinfo['cover']; 


$actpage = 1;
$maxitems = 20;
if(isset($_GET['id'])) { 
    $section = htmlentities($_GET['id']); 
    $sectionInfos = $sw-> catDescriptions($section) ;
	$description = $sectionInfos['description'];
	$meta_url = SITE_URL.'section/'.$sectionInfos['slug'];
	$title = $sectionInfos['name'].' - '.$siteinfo['title'];
	
}
if(!empty($sectionInfos)) {
	include 'template/includes/header.php';
	$posts = $sw->blogposts('blog',$maxitems,$actpage,false,$section);

?>


    <!-- Page Header -->
    <header class="masthead" style="background-image: url('<?php echo $meta_preview; ?>')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="post-heading">
                        <h1><?php echo $sectionInfos['name']; ?></h1>
                        <span class="subheading"><?php echo $sectionInfos['description']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">

                <?php foreach($posts as $post) { ?> 
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
                <hr>
                <?php } ?>
              


                <!-- Pager -->
                <div class="clearfix text-center">

            
                        <a class="btn btn-secondary" href="<?php echo SITE_URL; ?>"><?php echo $sw->_("All Posts"); ?></a>
               
	                
                </div>
            </div>
        </div>
    </div>

    <hr>
<?php include 'template/includes/footer.php'; } else include 'template/error404.php'; ?>