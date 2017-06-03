<?php header('Content-Type: text/html; charset=utf-8');


	
/* config site */
$sitelang = 'fr';

define('ADMIN_URL', 'my-publisher/');
include ADMIN_URL.'inc/system.php';

$message = $sw-> getmessage();

$listnav = [
		'hp' => 'template/hp.php',
		'article' => 'template/post.php',
		'about' => 'template/about.php',
		'contact' => 'template/contact.php',
		'section' => 'template/section.php',
		'sendmail' => 'template/sendmail.php',
		'sitemap' => 'template/sitemap.php'
	];



if (file_exists($listnav[$page])) include ($listnav[$page]);
else include 'template/error404.php'	

?>
    
    
    
    


     