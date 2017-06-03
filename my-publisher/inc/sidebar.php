 
<aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
<?php
$ul = $adm->userlogged();

if (!empty($ul['user']))
	{
?>
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php
	echo $ul["avatar"]; ?>" class="img-circle"/>
            </div>
            <div class="pull-left info">
              <p><?php
	echo ucfirst($ul['user']["username"]); ?></p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> <?php
	echo _t("Online"); ?> </a>
            </div>
          </div>

		  <?php
	}

?>

         <ul class="sidebar-menu">
	        <?php foreach($swcnt_post as $posttype => $swb) { ?>
	<li class="header"><?php echo strtoupper($swb['sw_title']); ?> </li>
			<?php
	foreach($swcnt_options['languages'] as $k)
		{ 

		?>
	                <li class="treeview <?php
		echo ($sposts->lang == $k and $sposts->pubtype == $posttype) ? 'active' : ''; ?>">
              <a href="#"><i class="material-icons">&#xE873;</i> <span><?php
		echo $swcnt_options["languages_names"][$k]; ?></span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
	               <li class="<?php
		echo ($sposts->lang == $k and $sposts->page == 'new') ? 'active' : ''; ?>"> <a href="?item=new&type=<?php echo $posttype; ?>&lang=<?php
		echo $k; ?>" ><i class="material-icons">&#xE150;</i> <?php
		echo $swb['sw_singleitem_title']; ?> </a></li>
	               <li class="<?php
		echo ($sposts->lang == $k and $sposts->page == 'list') ? 'active' : ''; echo ($sposts->lang == $k and $sposts->page == 'edit') ? 'active' : ''; ?>"><a href="?item=list&type=<?php echo $posttype; ?>&lang=<?php
		echo $k; ?>"><i class="material-icons">&#xE3C7;</i> <?php
		echo $swb['sw_items_title']; ?> </a></li>
	              <li class="<?php
		echo ($sposts->lang == $k and $sposts->page == 'cats') ? 'active' : ''; ?>"> <a href="?item=cats&type=<?php echo $posttype; ?>&lang=<?php
		echo $k; ?>" ><i class="material-icons">&#xE2C8;</i> <?php
		echo $swb['sw_cat_title']; ?> </a></li>
                            </ul>
            </li>
	        <?php
		}
}
?>


                  <li class="header"><?php
echo _tr("EDITION"); ?> </li>
            <li class="treeview <?php
echo ($smod->mod == 'editor') ? 'active' : ''; ?>">
              <a href="#"><i class="material-icons">&#xE051;</i> <span><?php
echo _tr("Editor"); ?> </span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">


              <?php
$smod->listpages();
?>                            </ul>
            </li>
<?php
$smod->listplugins();
?>             <li class="header"><?php
echo _tr("ADMINISTRATION"); ?> </li>
            <!-- Optionally, you can add icons to the links -->
            <li class=" <?php
echo ($smod->mod == 'users') ? 'active' : ''; ?>">
              <a href="?users=list"><i class="material-icons">&#xE853;</i> <span><?php
echo _tr("Users"); ?> </span> </a>
            </li>
                 <li class=" <?php
echo ($smod->mod == 'translate') ? 'active' : ''; ?>">
              <a href="?translate=home"><i class="material-icons">&#xE894;</i><span><?php
echo _tr("Translate"); ?> </span> </a>
            </li>

          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>
