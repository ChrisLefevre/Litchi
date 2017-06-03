<?php




$table = new swcnt_tables();


?>

<section class="content-header">
    <h1>Contact <small><?php echo _("All Messages"); ?> </small></h1>

    <ol class="breadcrumb">
        <li>
            <a href="./"><?php echo _("Admin"); ?> </a>
        </li>

        <li class="active"><?php echo _("Contact"); ?> </li>
    </ol>
</section>




 


<section class="content">
   <div class="box">
          
                <div class="box-body table-responsive">
                             
                             <?php 
	 
	 $table -> showformtable('contact');

	 
	 ?>
                             
                             
                             
                              </div><!-- /.box-body -->
              </div>
              
              
             
</section>
