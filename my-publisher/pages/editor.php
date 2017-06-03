<?php


$smod -> save();


?>

<section class="content-header">
    <h1><?php echo "Editeur"; ?> <small><?php echo "Editer une page"; ?> </small></h1>

    <ol class="breadcrumb">
        <li>
            <a href="./"><?php echo "Admin"; ?> </a>
        </li>

        <li class="active"><?php echo "Editeur"; ?> </li>
    </ol>
</section>





 


<section class="content">
    <div class='box box-primary'>
        <div class='box-header'>
            <h3 class='box-title'><?php echo $smod->pagetitle; ?> <small><?php echo $smod -> lang; ?></small></h3>
            
            <div class="pull-right box-tools">
	            
	            
	            	<?php foreach($swcnt_options['languages'] as $k) {
		            if ($smod -> lang==$k)	echo ' <a href="?editor='.$smod -> page.'&lang='.$k.'" class="btn  btn-primary btn-sm">'.strtoupper($k).'</a>';
		            	else echo ' <a href="?editor='.$smod -> page.'&lang='.$k.'"  class="btn btn-sm">'.strtoupper($k).'</a>';
	            	}
	            
	            
	            
						?>
                   </div>
            
        </div>

        <div class='box-body'>
	        
	        
	        
	        
	        
	        
            <?php    $smod -> showform(); ?>
        </div>
    </div>
</section>







