<?php

if($_GET['update']=='exit') $adm->unlog();
else {
$adm -> saveUser();
}


$ref = $_SERVER['HTTP_REFERER']; 


header("Location: ".$ref);
exit();
	

	
?>