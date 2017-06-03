<?php
	
	



$plunginlist = $smod->plugins;



		if(!empty($_GET['plugin']) ) $plugact = $_GET['plugin']; else $plugact = '';
		if(!empty($_GET['plugpage']) ) $plugpage = $_GET['plugpage']; else $plugpage = '';
		
		foreach
		($plunginlist as $plug_id => $plug_infos)
		{
		if($plug_id==$plugact) { 
			
			$pagename = $plug_infos['name'];
			
			 if (!empty($plug_infos['pages'])) { ?>
		          
		              <?php foreach ($plug_infos['pages'] as $tpage => $vpage)
						  	{  
							  	if($plugpage==$tpage) $pagename = $vpage;   }  ?>
		              
	        <?php  } ?>

			
			
		
			<section class="content-header">
    <h1><?php echo $pagename; ?></h1>

    <ol class="breadcrumb">
        <li>
            <a href="./"><?php echo _("Plugins"); ?> </a>
        </li>

        <li class="active"><?php echo $plug_infos['name']; ?> </li>
    </ol>
</section>


	
	
	<?php  include 'plugins/'.$plug_id.'/page.php'; ?>
	
	
	

			
			<?php
		} } ?>