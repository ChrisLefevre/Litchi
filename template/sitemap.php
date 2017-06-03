<?php header('Content-Type: text/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
';
foreach ($swcnt_post as $group_k => $group_v) {
$ipost = 1;
	foreach ($sw ->  blogposts($group_k,50000,1) as $k => $v) {
		if(!empty($group_v["sw_url_preview"])) {
		echo '<url><loc>'; 
		
			echo $sw->previewUrl($v,$group_v["sw_url_preview"]);	
			if($ipost<10) $refresh = 'hourly';		
			else if($ipost<100) $refresh = 'daily';
			else $refresh = 'monthly';
			echo '</loc>'."\n".'<changefreq>'.$refresh.'</changefreq>'."\n".'</url>'."\n";
			$ipost++;
		}  
	}	
}?></urlset>
