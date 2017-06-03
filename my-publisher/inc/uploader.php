<?php 

if ($adm->islogged()) {
if (!empty($_GET['uploader'])) $idp = htmlentities($_GET['uploader']);
else $idp = 'default';

function findexts ($filename)
 {
 return strtolower(pathinfo($filename, PATHINFO_EXTENSION));

 }

function swcnt_image_resize($src, $dst, $width, $height, $crop = 0)
{
    if (!list($w, $h) = getimagesize($src))
        return "Unsupported picture type!";
    $type = strtolower(substr(strrchr($src, "."), 1));
    if ($type == 'jpeg')
        $type = 'jpg';
    switch ($type) {
        case 'bmp':
            $img = imagecreatefromwbmp($src);
            break;
        
        case 'gif':
            $img = imagecreatefromgif($src);
            break;
        
        case 'jpg':
            $img = imagecreatefromjpeg($src);
            break;
        
        case 'png':
            $img = imagecreatefrompng($src);
            break;
        
        default:
            return "Unsupported picture type!";
    }
    
    if ($w == $width and $h == $height) {
        $new_height = $height;
        $new_width  = $width;
        $crop_x     = 0;
        $crop_y     = 0;
    } else if ($w > $h) {
        $new_height = $height;
        $new_width  = floor($w * ($new_height / $h));
        $crop_x     = ceil(($w - $h) / 2);
        $crop_y     = 0;
    } else {
        $new_width  = $width;
        $new_height = floor($h * ($new_width / $w));
        $crop_x     = 0;
        $crop_y     = ceil(($h - $w) / 2);
    }
    
    $new = imagecreatetruecolor($width, $height);
    
    
    
    if ($type == "gif" or $type == "png") {
        imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
        imagealphablending($new, false);
        imagesavealpha($new, true);
    }
    
    imagecopyresampled($new, $img, 0, 0, $crop_x, $crop_y, $new_width, $new_height, $w, $h);
    switch ($type) {
        case 'bmp':
            imagewbmp($new, $dst);
            break;
        
        case 'gif':
            imagegif($new, $dst);
            break;
        
        case 'jpg':
            imagejpeg($new, $dst);
            break;
        
        case 'png':
            imagepng($new, $dst);
            break;
    }
    
    return true;
}

	$etape = 1;

$addimage = '';


if (!empty($_POST)) $etape = 3;

$errormessage = _tr('Wrong format');




if (isset($_FILES["file"])) {

if ($_FILES["file"]["size"] > 10000000) {

$errormessage = _tr('Image too Big');

}


$allowedExts = array("jpg", "jpeg", "gif", "png");
$extension = findexts($_FILES["file"]["name"]);
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/png")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "application/octet-stream")



)
&& ($_FILES["file"]["size"] < 10000000)
&& in_array($extension, $allowedExts))
  {



  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";

    $errormessage = _tr('Bad picture').' '.$_FILES["file"]["error"];

    }
  else
    {
      if (file_exists("../files/temp/" . $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists. ";

       $errormessage = $_FILES["file"]["name"] . ' '. _tr('already exists');

      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "../files/full/" . date("Ymd-His.").$extension);

	  $filename = "../files/full/" . date("Ymd-His.").$extension;
	  $thumbimage = "../files/thumb/" . date("Ymd-His.").$extension;
	  $tmpnimg = date("Ymd-His.").$extension;


	  list($width, $height) = getimagesize($filename);
	  $squaresize = 128;
	  swcnt_image_resize($filename,$thumbimage,$squaresize, $squaresize, 1);
      $etape = 2;

	  }
    }
  }
else
  {

 $etape = 3;
  }
}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Download</title>








<style type="text/css">
@import url(https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic);
	
body{margin:0;
	font-family: 'Source Sans Pro', sans-serif;
}
#rightzone{width:150px;height:500px;position:absolute;z-index:100;right:0;top:0}
#zonerecad{height:500px;width:650px;overflow:auto}
.btn{display:inline-block;font-weight:400;text-align:center;vertical-align:middle;cursor:pointer;background-image:none;border:1px solid transparent;white-space:nowrap;padding:6px 12px;font-size:14px;line-height:1.42857143;border-radius:4px;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;background-color: transparent;color: #2BB7E9; border: solid 1px #2BB7E9;text-transform: uppercase;position:relative;overflow:hidden;margin:0; font-size: 12px;margin-top: 1px;}
.fileUpload input.upload{position:absolute;top:0;right:0;margin:0;padding:0;font-size:20px;cursor:pointer;opacity:0;filter:alpha(opacity=0)}
.spinner{width:50px;height:30px;text-align:center;font-size:10px;margin-left:auto;margin-right:auto}
.spinner > div{background-color:#428bca;height:100%;width:6px;display:inline-block;-webkit-animation:stretchdelay 1.2s infinite ease-in-out;animation:stretchdelay 1.2s infinite ease-in-out}
.spinner .rect2{-webkit-animation-delay:-1.1s;animation-delay:-1.1s}
.spinner .rect3{-webkit-animation-delay:-1s;animation-delay:-1s}
.spinner .rect4{-webkit-animation-delay:-.9s;animation-delay:-.9s}
.spinner .rect5{-webkit-animation-delay:-.8s;animation-delay:-.8s}
@-webkit-keyframes stretchdelay {
0%,40%,100%{-webkit-transform:scaleY(0.4)}
20%{-webkit-transform:scaleY(1.0)}
}
@keyframes stretchdelay {
0%,40%,100%{transform:scaleY(0.4);-webkit-transform:scaleY(0.4)}
20%{transform:scaleY(1.0);-webkit-transform:scaleY(1.0)}
}
</style>
<script src="assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
</head>
<body><?php  if ($etape == 2) {  ?>
             
    			  <script type="text/javascript">
				  
	

$(document).ready(function () { 
	parent.document.getElementById('picturpreview-<?php echo $idp; ?>').src='../files/thumb/<?php echo  $tmpnimg ; ?>'; 
	parent.document.getElementById('picturelement-<?php echo $idp; ?>').value='<?php echo  $tmpnimg ; ?>'; 
	
	});			  
		
			   </script>          


               <?php } ?>
<form style="margin:0px;" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="10000000">
<div class="fileUpload btn btn-primary">
    <span><?php echo _tr('Add Picture'); ?></span>
       <input onchange=" parent.document.getElementById('picturelement-<?php echo $idp; ?>').src='assets/dist/img/loading.gif';  form.submit()" id="buttonupload" class="upload" name="file" type="file">
</div>
<div id="tapp2" style="display:none">
<div class="spinner">
  <div class="rect1"></div>
  <div class="rect2"></div>
  <div class="rect3"></div>
  <div class="rect4"></div>
  <div class="rect5"></div>
</div>
</div>


					<input id="btupl2" style="display:none" class="btn-primary" type="button" value="<?php echo _tr("Uploading"); ?> ">
				</form>
	
               
                
                
</body>
</html><?php } ?>