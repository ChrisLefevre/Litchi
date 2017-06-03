<?php
$ldoc =  $adm->ldb.'/'.$smod -> lang.'_xtext.list.json';


if(!empty($_POST)) {


				$final = json_encode($_POST);
			    file_put_contents($ldoc, $final) ;
}


?>
<section class="content-header">
    <h1><?php echo _("Translate"); ?> <small><?php echo _("Texts"); ?> </small></h1>

    <ol class="breadcrumb">
        <li>
            <a href="./"><?php echo _("Admin"); ?> </a>
        </li>

        <li class="active"><?php echo _("Translate"); ?> </li>
    </ol>
</section>




 


<section class="content">
    <div class='box box-primary'>
        <div class='box-header'>
            <h3 class='box-title'><?php echo $smod->pagetitle; ?> <small><?php echo $smod -> lang; ?></small></h3>
            
            <div class="pull-right box-tools">
	            
	            
	            	<?php 
		            	foreach($swcnt_options['languages'] as $k) {
		            if ($smod -> lang==$k)	echo ' <a href="?translate=home&lang='.$k.'" class="btn  btn-primary btn-sm">'.strtoupper($k).'</a>';
		            	else echo ' <a href="?translate=home&lang='.$k.'"  class="btn btn-sm">'.strtoupper($k).'</a>';
	            	}
	            
	            
	            
						?>
                   </div>
            
        </div>

        <div class='box-body'>
	        
	        
	        
	        
	        
	        
            <?php 
	            
	            
	                	
		          
		            	
		            	

echo '	            <form action="" id="formedtextt" method="post">  <div class="box-body">
		<div class="row">
            <div class="col-md-9">
            ';
            
          
            	
		$doc = $adm->ldb.'/xtext.list.json';
		
		$vals = array();
		if (file_exists($doc))
				{
				
					$d = file_get_contents($doc);
					$vals  = json_decode($d, true);
					
						$lvals = array();
						if (file_exists($ldoc))
				{
				
					$ld = file_get_contents($ldoc);
					$lvals  = json_decode($ld, true);
									
				}

					foreach($vals as $key => $val) {

						$resp = '';
						if(!empty($lvals[$key])) $resp= $lvals[$key]; 	
				
						      echo '
            <div class="form-group">
			<div class="row">
			    <div class="col-md-6">
			
			<label>'.$val.'</label></div> <div class="col-md-6"><input type="text" value="'.htmlspecialchars($resp).'"  name="'.addslashes($key).'" class="form-control" placeholder="'.addslashes($val).'"></div></div> </div>
			';
						
						
					}
					
				}
          
          
          
    
			
		
			
			
			echo '</div><div class="col-md-3"></div></div> </div><div class="box-footer">
                    <button type="submit" class="btn btn-primary">Sauver</button>
                  </div></form> ';
	            
	            
	         ?>
        </div>
    </div>
</section>






