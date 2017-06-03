<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Swalize | <?php echo _tr("Dashboard"); ?> </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/dist/css/swadmin.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/summernote-master/dist/summernote.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/lightbox/ekko-lightbox.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/tagsinput/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
	


	<link rel="shortcut icon" href="assets/dist/img/icons/favicon.ico" type="image/x-icon" />
<link rel="apple-touch-icon" href="assets/dist/img/icons/apple-touch-icon.png" />
<link rel="apple-touch-icon" sizes="57x57" href="assets/dist/img/icons/apple-touch-icon-57x57.png" />
<link rel="apple-touch-icon" sizes="72x72" href="assets/dist/img/icons/apple-touch-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="76x76" href="assets/dist/img/icons/apple-touch-icon-76x76.png" />
<link rel="apple-touch-icon" sizes="114x114" href="assets/dist/img/icons/apple-touch-icon-114x114.png" />
<link rel="apple-touch-icon" sizes="120x120" href="assets/dist/img/icons/apple-touch-icon-120x120.png" />
<link rel="apple-touch-icon" sizes="144x144" href="assets/dist/img/icons/apple-touch-icon-144x144.png" />
<link rel="apple-touch-icon" sizes="152x152" href="assets/dist/img/icons/apple-touch-icon-152x152.png" />
	
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="sidebar-mini fixed">
    <div class="wrapper">
	
      <!-- Main Header -->
      <header class="main-header">

        <!-- Logo -->
        <a href="./" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          
          
         
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
            <li class="dropdown  tasks-menu hidden ">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa  fa-flag"></i> <?php echo ucfirst($adm->lang); ?>         </a>
                <ul class="dropdown-menu langb">
                <?php foreach($adm->langlist as $k => $v) { 
	                
	                echo '<li><a href="?language='.$k.'">'.$v['txt'].'</a></li>';
	                
                } ?>
                
                                 </ul>
              </li>
               <li><a href="../"><i class="fa fa-home"></i> Page d'accueil</a></li>
              <li>
              
              
              
                <a href="?update=exit"><i class="fa fa-sign-out"></i> <?php _t('Logout'); ?></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>
           
     
     
     
     <?php include('inc/sidebar.php'); ?>

      <div class="content-wrapper">

		