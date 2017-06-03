<?php if(!empty($swcnt_post[$_GET['type']])) {
	$posttype = htmlentities($_GET['type']);
   $swb = $swcnt_post[$_GET['type']];
	if ($sposts->page=='new' or $sposts->page=='edit') { 
			$sposts -> save();
    ?>
<section class="content-header">
    <h1><b><?php echo $swb['sw_title']; ?></b> - <?php echo strtolower($swcnt_options["languages_names"][$sposts -> lang]); ?> <small><?php if($sposts->page=='new') echo $swb['sw_singleitem_title']; else echo $swb['sw_singleitem_title']; ?> </small></h1>

    <ol class="breadcrumb">
        <li>
            <a href="./"><?php echo _tr("Edition"); ?> </a>
        </li>

        <li class="active"><?php echo ucfirst($swb['sw_singleitem_name']); ?>  </li>
    </ol>
</section>

<section class="content">
    <div class='box box-primary'>
       

        <div class='box-body'>
            <?php    $sposts -> showform(); ?>
        </div>
    </div>
</section>
<?php } else if ($sposts->page=='cats' ) { 
	$sposts_cat -> save();
	?>
	
<section class="content-header">
	<h1><b><?php echo $swb['sw_title']; ?></b> - <?php echo strtolower($swcnt_options["languages_names"][$sposts -> lang]); ?> <small><?php	echo $swb['sw_cat_title']; ?></small></h1>
</section>
<section class="content">
    <div class='box box-primary'>
        <div class='box-body'>
            <?php  $sposts_cat  -> showform(); ?>
        </div>
    </div>
</section>
	
	
		
	
	
<?php	}  else if ($sposts->page=='list' && !empty($swb['sw_ordermode'])) { 
			
	$db = new swcnt_index($posttype,$sposts -> lang);
	$db->savePositions();
	$spostsproducts = $db->read(true); 	
	$cta = array(); ;
	$cta['nocat'] = array();	  
	foreach($spostsproducts as $v) { 
	   if(empty($v['category'])) $v['category'] = 'nocat';
	   $cta[$v['category']][$v["id"]] = $v;
	   $cta[$v['category']][$v["id"]]['key'] = $v["id"];
	}  
?>
<section class="content-header">
	<h1><b><?php echo $swb['sw_title']; ?></b> - <?php echo strtolower($swcnt_options["languages_names"][$sposts -> lang]); ?> <small><?php	echo $swb['sw_cat_title']; ?></small></h1>
</section>
<section class="content">
    <div class='box box-primary'>
	        <form action="" id="formeditor" method="post">
	<?php 	
		$no_c = 0;
	function cmp($a, $b){    return strcmp((int)$a["position"], (int)$b["position"]); }
	if(!empty($sposts->structure['category'])) $cats = $sposts->structure['category']['options'];
	$cats["nocat"] = "Non associés";

	
	foreach($cats as $kc=>$vc) {
		if($kc!='') {
		?>
	<div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="elems"><?php echo $vc; ?></label>
                    <div class="box-body table-responsive no-padding" style="padding-bottom: 20px !important;" id="dtable_listact_ref_elems">
	                    
	                    
	                     
                        <table class="table table-hover dtablepub">
                            <tbody>
                          
                        <?php

						$nextpos = -100;
						
						$allrp = array();
					    
						foreach($cta[$kc] as $reop => $repp) {
							if(!empty($repp['position']))  $allrp[$repp['position']] = $repp;
							else $allrp[$nextpos] = $repp;
							$nextpos++;
						 }

						ksort($allrp);
					    foreach($allrp as $k => $v) { 
	                 	                 
	                    if($v['status']!=2 and $v['category']==$kc) {
	               
	                    if($v['status']==1) $stat = 'Publié'; 
	                    else if($v['status']==3) $stat = 'showcase'; 
	                    else $stat = 'En attente'; 
	            
	                     ?>
	                    
	                    
	                 <tr id="liItemelems-<?php echo  $no_c; ?>">    
					  <td class="movable"><i class="fa fa-fw fa-sort"></i>
                       <input type="hidden" value="<?php echo intval($v['position']);  ?>" id="inputtitle" name="elposition[<?php echo $v['key']; ?>]" class="form-control" /> <a href="?item=edit&amp;type=<?php echo $posttype; ?>&lang=<?php echo $v['lang']; ?>&post=<?php echo $v['key']; ?>"><?php echo ucfirst($v['title']); ?></a></td>
                
                    <td width="100" align="right">
	                  <em style="opacity: .5">(<?php echo $v['position']; ?>)</em> <?php echo ucfirst($stat); ?>  
	                </td>  </tr>
    
                    <?php  $no_c++; } } ?>
                                  
                                
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
		 </div>
		 </div>
		 
		 <?php
	} }
	?> 
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">Sauver cette liste</button>
    </div>
	</form>	
    </div>
</section>


<?php } else { 
		
		
		
		$db = new swcnt_index($posttype,$sposts -> lang);
        $blogposts = $db->read(); 

?>

<section class="content-header">
   
       
    <div class="pull-right"><a href="?item=new&amp;type=<?php echo $posttype; ?>&amp;lang=<?php echo $sposts -> lang; ?>" title="Add" class="btn  btn-sm btn_modern"><i class="fa fa-plus"></i> <?php echo ucfirst($swb['sw_singleitem_title']); ?></a></div>
     <h1><b><?php echo $swb['sw_title']; ?></b> - <?php echo strtolower($swcnt_options["languages_names"][$sposts -> lang]); ?> <small><?php echo $swb['sw_items_title']; ?></small></h1>

    
</section><!-- Main content -->

<section class="content">

     <div class="box">
                <div class="box-body">
                  <table id="datatable"  class="table table-hover">
	                   <thead>
                    <tr>
                      <th><?php echo _tr("Date"); ?> </th>
                      <th><?php echo ucfirst($swb['sw_singleitem_name']); ?></th>
                      <th></th>
                    </tr>
	                   </thead>
	                   <tbody>
                    <?php foreach($blogposts as $k => $v) { 
	                    
	                    if($v['status']!=2) {
	               
	                    if($v['status']==1) $stat = _tr('visible'); 
	                    else if($v['status']==3) $stat = _tr('archivé'); 
	                    else $stat = _tr('closed'); 
	                    
	                    $dateupdate = date_create($v['pubdate']); 
	                     ?>
	                 <tr>
                      <td><i style="display: none"><?php echo date_format($dateupdate,'YmdHis'); ?></i> <?php echo date_format($dateupdate,'d/m/Y H:i'); ?></td>
                      <td><a href="?item=edit&amp;type=<?php echo $posttype; ?>&lang=<?php echo $v['lang']; ?>&post=<?php echo $v["id"]; ?>"><?php echo ucfirst($v['title']); ?></a></td>
                      <td class="text-right"><?php echo ucfirst($stat); ?></td>
                      </tr>
                    <?php } } ?>
	                   </tbody>
					</table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
		</section>



<?php } }