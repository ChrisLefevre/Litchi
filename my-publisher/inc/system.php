<?php /* Vers 2.1 beta */ 
include (ADMIN_URL . '../models.php');

if(!empty($swcnt_options['debug'])) {
            error_reporting(E_ALL);
            ini_set("display_errors", 1);
} 

class sw

	{
	public $datastore;

	function __construct()
		{
		
		
		$this->timestart=microtime(true);	
			
			
		global $swcnt_plugins;
		global $swcnt_options;
		global $swcnt_pluglist;
		$this->plugins = $swcnt_pluglist;
		$this->ldb	= $swcnt_options['db_location'];    
		$swcnt_pluglist = array();
		foreach($swcnt_plugins as $pname)
			{
			include_once ADMIN_URL . 'plugins/' . $pname . '/conf.php';

			}

		$this->site_url = '';	
		$this->plugins = $swcnt_pluglist;
		$swcnt_languages = $swcnt_options['languages'];
		$swcnt_secure_key = $swcnt_options['secure_key'];
		$swcnt_urlrewriting = $swcnt_options['urlrewriting'];
		$swcnt_crypt = $swcnt_options['crypt'];
		$key = md5($swcnt_secure_key);
		if (isset($_GET['lang']) and in_array($_GET['lang'], $swcnt_languages)) $actlang = htmlentities($_GET['lang']);
		  else
		if (isset($swcnt_languages[0])) $actlang = $swcnt_languages[0];
		if ($actlang == '') $actlang = 'nolang';
		$this->lang = $actlang;
		$this->key = $key;
		$this->crypt = $swcnt_crypt;
		$this->urlrewriting = $swcnt_urlrewriting;
		}
		
		
	public 
	
	function nl2li($string) {
		$bits = explode("\n", $string);

			foreach($bits as $bit)
			{
			  $newstring .= "<li>" . $bit . "</li>";
			}
			
			return $newstring;
		
	}	
		
		
	public

	function format_url($texte)
		{
		$texte = utf8_decode($texte);
		$texte = html_entity_decode($texte);
		$tofind = utf8_decode('ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËéèêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ');
		$replac = utf8_decode('AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn');
		$texte_pre_pre_pre = trim(strtolower(strtr($texte, $tofind, $replac)));
		$texte_pre_pre = preg_replace('/[^a-zA-Z0-9_]/i', '-', $texte_pre_pre_pre);
		$texte_pre = preg_replace('/-+/i', '-', $texte_pre_pre);
		$texte_final = substr($texte_pre, '0', '128');
		return $texte_final;
		}

	public

	function setmessage($message,$mode='warning')
		{
		//0= info, 1 = success, 2= error	
		$message = '<div class="alert alert-'.$mode.' alert-dismissable">' . $message . '</div>';
		setcookie("sw_tmp_message", $message, time() + 3600 * 24 * 7 * 30, '/');
		
		
		}

	public

	function getmessage()
		{
			
		if (!empty($_COOKIE["sw_tmp_message"]))
			{
			setcookie("sw_tmp_message", '', time() - 3600 * 24 * 7 * 30, '/');
			return $_COOKIE["sw_tmp_message"];
			}	

		else return '';
		}

	public

	function form($string)
		{
		global $swcnt_form;
		if (!empty($swcnt_form[$string])) return $swcnt_form[$string];
		}

	public

	function saveform($string)
		{
		$form = $this->form($string);
		$thisformvalues = array();
		foreach($form as $k => $v)
			{
			if (!empty($_POST[$k])) $thisformvalues[$k] = htmlentities($_POST[$k]);
			}

			

		$this->saveData($string, date("Y-m-d H:i:s") , $thisformvalues);
		return $thisformvalues;
		}

	public

	function mailform($string, $email = '')
		{
		$form = $this->form($string);
		global $swcnt_options;
		if ($email == '') $email = $swcnt_options['contact_email'];
		$from = $swcnt_options['contact_email'];
		$body = $this->_("You have received a new message from your website contact form") . ".\n\n";
		$thisformvalues = array();
		foreach($form as $k => $v)
			{
			if (!empty($_POST[$k]))
				{
				$thisformvalues[$k] = htmlspecialchars($_POST[$k]);
				if ($k == 'email' or $k == 'mail') $from = $thisformvalues[$k];
				$body = $body . $k . " " . $thisformvalues[$k] . "\n\n";
				}
			}

		$subject = "Website email " . $string;
		$headers = "From: $from\n";
		$headers.= "Reply-To: $from";
		mail($email, $subject, $body, $headers);
		return true;
		}

	public

	function saveData($d = 'temp', $k, $v)
		{
		$confx = $this->loadPublicstore($d);
		$configfile = ADMIN_URL . $this->ldb.'db.' . $d . '.conf.php';
		$configbkpfile = ADMIN_URL . $this->ldb.'backup/db.' . $d . '.' . date("y-m-d") . 'conf.php';
		$confx[$k] = $v;

		$c = json_encode($confx);
		file_put_contents($configfile, '<?php //' . $this->encrypt($c));
		file_put_contents($configbkpfile, '<?php //' . $this->encrypt($c));
		$this->datastore[$d] = $confx;
		}

	public

	function encrypt($string)
		{
		if ($this->crypt == 0) return base64_encode($string);
		  else
			{
			$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
			return mcrypt_encrypt(MCRYPT_BLOWFISH, $this->key, $string, MCRYPT_MODE_ECB, $iv);
			}
		}
		
	public 
	
	function truncateStringWords($str, $maxlen)
		{	
			$str = strip_tags($str);
			
		    if (strlen($str) <= $maxlen) return $str;
		
		    $newstr = substr($str, 0, $maxlen);
		    if (substr($newstr, -1, 1) != ' ') $newstr = substr($newstr, 0, strrpos($newstr, " "));
		
		    return $newstr;
		}	

	public

	function decrypt($crypttext)
		{
		if ($this->crypt == 0) return base64_decode($crypttext);
		  else
			{
			$crypttext = ($crypttext);
			$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
			return trim(mcrypt_decrypt(MCRYPT_BLOWFISH, $this->key, $crypttext, MCRYPT_MODE_ECB, $iv));
			}
		}

	public

	function get_tags_name($tag)
		{
		$actpage = 'tags_list';
		global $swcnt_tree;
		$actlang = $this->lang;
		$slots = array();
		$tagname = '';
		$doc = ADMIN_URL . $this->ldb.'' . $actlang . '_' . $actpage . '.source.json';
		if (file_exists($doc))
			{
			$d = file_get_contents($doc);
			$slots = json_decode($d, true);
			$keyword = $slots['keyword'];
			}

		$tagname = $keyword[$tag];
		return $tagname;
		}

	public

	function list_tags()
		{
		$actpage = 'tags_list';
		global $swcnt_tree;
		$actlang = $this->lang;
		$slots = array();
		$doc = ADMIN_URL . $this->ldb.'' . $actlang . '_' . $actpage . '.source.json';
		if (file_exists($doc))
			{
			$d = file_get_contents($doc);
			$slots = json_decode($d, true);
			return $slots['keyword'];
			}

		}

	public

	function plugin_save($plugin, $document, $lang, $k, $v)
		{
		global $swcnt_plugins;
		$actlang = $this->lang;
		foreach($swcnt_plugins as $pname)
			{
			if ($pname == $plugin)
				{
				$doc = ADMIN_URL . $this->ldb.'' . $lang . '_plugin_' . $this->format_url($pname) . '_' . $this->format_url($document) . '.source.json';
				$vals = array();
				if (file_exists($doc))
					{
					$d = file_get_contents($doc);
					$vals = json_decode($d, true);
					}

				$vals[$k] = $v;
				$final = json_encode($vals);
				file_put_contents($doc, $final);
				}
			}
		}

	public

	function plugin_datas($plugin, $document, $lang)
		{
		global $swcnt_plugins;
		$actlang = $this->lang;
		foreach($swcnt_plugins as $pname)
			{
			if ($pname == $plugin)
				{
				$doc = ADMIN_URL . $this->ldb.'' . $lang . '_plugin_' . $this->format_url($pname) . '_' . $this->format_url($document) . '.source.json';
				$vals = array();
				if (file_exists($doc))
					{
					$d = file_get_contents($doc);
					$d = str_replace("[lang]", $actlang, $d);
					$d = str_replace("[site_url]",  $this->site_url , $d);
					$d = str_replace("[base_url]",  $this->site_url  . $actlang . '/', $d);
					$vals = json_decode($d, true);
					}

				return $vals;
				}
			}
		}

	public

	function block($actpage)
		{
		global $swcnt_tree;
		
		
		

		
		$actlang = $this->lang;
		$slots = array();
		if ($actpage != '' and !empty($swcnt_tree[$actpage]['sw_blocks']))
			{
			$structure = $swcnt_tree[$actpage]['sw_blocks'];
			$doc = ADMIN_URL . $this->ldb.'' . $actlang . '_' . $actpage . '.source.json';
			$this->structure = $structure;
			$this->doc = $doc;
			$this->page = $actpage;
			if (file_exists($doc))
				{
					
				$d = file_get_contents($doc);
				$d = str_replace('src=\"..\/files', 'src=\"' . $this->site_url . 'files', $d);
				$d = str_replace("[lang]", $actlang, $d);
				$d = str_replace("[site_url]",  $this->site_url , $d);
				$d = str_replace("[base_url]",  $this->site_url  . $actlang . '/', $d);
				$slots = json_decode($d, true);
				}

			return $slots;
			}
		}
		
		
		
		
		
		
		

	function uri($page, $arg = '')
		{
		$swcnt_urlrewriting = $this->urlrewriting;
		if ($arg == '')
			{
			if ($swcnt_urlrewriting) return $this->lang . '/' . $page;
			  else return '?lang=' . $this->lang . '&page=' . $page;
			}
		  else
			{
			if ($swcnt_urlrewriting) return $this->lang . '/' . $page . '/' . $arg;
			  else return '?lang=' . $this->lang . '&page=' . $page . '&id=' . $arg;
			}
		}

	public

	function loadDatastore($d)
		{
		$confx = array();
		$configfile = ADMIN_URL . $this->ldb.'db.' . $d . '.conf.php';
		
		
		if (file_exists($configfile))
			{
			$c = file_get_contents($configfile);
			$c = str_replace("<?php //", "", $c);
			$c = $this->decrypt($c);
			$confx = json_decode($c, true);
			}

		$this->datastore[$d] = $confx;
		return $confx;
		}
		
	public 
	
	function loadPublicstore($d)
    {
        
        $confx      = array();
        $configfile = ADMIN_URL . $this->ldb.'db.' . $d . '.index.json';
  
        
        
        if (file_exists($configfile)) {
            $c     = file_get_contents($configfile);
            $confx = json_decode($c, true);       
        }
        
        $this->datastore[$d] = $confx;
        return $confx;
    }
	
		

		

	public

	function dtformat($timestamp, $format)
		{
		$mtime = strtotime($timestamp);
		$pubdate = date($format, $mtime);
		return $pubdate;
		}

	public

	function dateTime($timestamp, $format = 'dateonly')
		{
		$lang = $this->lang;
		$lday = array(
			$this->_("Sunday") ,
			$this->_("Monday") ,
			$this->_("Tuesday") ,
			$this->_("Wednesday") ,
			$this->_("Thursday") ,
			$this->_("Friday") ,
			$this->_("Saturday")
		);
		$lmonth = array(
			'',
			$this->_("January") ,
			$this->_("February") ,
			$this->_("March") ,
			$this->_("April") ,
			$this->_("May") ,
			$this->_("June") ,
			$this->_("July") ,
			$this->_("August") ,
			$this->_("September") ,
			$this->_("October") ,
			$this->_("November") ,
			$this->_("December")
		);
		$timestamp = strtotime($timestamp);
		$w_ = $lday[date('w', $timestamp) ]; // date('w d F Y', $timestamp);
		$d_ = date('d', $timestamp);
		$m_ = $lmonth[intval(date('m', $timestamp)) ];
		if ($format == 'dateonly') $rdate = $w_ . ', ' . $d_ . ' ' . $m_ . ' ' . date('Y', $timestamp);
		  else
		if ($format == 'datehour') $rdate = $w_ . ', ' . $d_ . ' ' . $m_ . ' ' . date('Y', $timestamp) . ' - ' . date('H:i', $timestamp);
		return $rdate;
		}

	public

	function _m($t)
	{
	return ucFirst($this->_($t));
	}

	public

	function _e($t)
	{
	echo $this->_($t);
	}
	
	public

	function _em($t)
	{
	echo ucFirst($this->_($t));
	}
	
	public

	function _($t)
		{
		$lang = $this->lang;
		$word = $t;
		$word_key = $this->format_url($t);
		$doc = ADMIN_URL . $this->ldb.'xtext.list.json';
		$vals = array();
		if (file_exists($doc))
			{
			$d = file_get_contents($doc);
			$vals = json_decode($d, true);
			}

		if (!empty($vals[$word_key]))
			{
			$ldoc = ADMIN_URL . $this->ldb.'' . $lang . '_xtext.list.json';
			$lvals = array();
			if (file_exists($ldoc))
				{
				$ld = file_get_contents($ldoc);
				$lvals = json_decode($ld, true);
				if (!empty($lvals[$word_key])) $word = $lvals[$word_key];
				}
			}
		  else
			{
			$vals[$word_key] = $t;
			$final = json_encode($vals);
			file_put_contents($doc, $final);
			}

		return $word;
		}
	


		
		
	
		/* Blog functions */

	public

	function blogpost($id,$pubtype = 'blog', $full=true)
		
		{

		$db = new sw_db($pubtype,$this->lang);
		$post = $db->byid($id);

		if(!empty($full)) {
			
			$fullpost = $this->getPostBlogById($post['id'],$pubtype);
			foreach($fullpost as $key => $fiels) {
				$post[$key] = $fiels;
			}

		}

		return $post;
	}



	public
	
	function openGraph($canonical,$title,$image,$twitter,$domain,$sitename,$description,$type='article') {
	
	return
'<meta name="description" content="'.htmlspecialchars($description).'">
	<link rel="canonical" href="'.htmlspecialchars($canonical).'" />
	<meta property="og:title" content="'.htmlspecialchars($title).'" />
	<meta property="og:type" content="'.$type.'"/>
	<meta property="og:url" content="'.htmlspecialchars($canonical).'" />
	<meta property="og:image" content="'.htmlspecialchars($image).'" />
	<meta property="og:site_name" content="'.htmlspecialchars($sitename).'" />
	<meta property="og:description" content="'.htmlspecialchars($description).'" />
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:site" content="'.htmlspecialchars($sitename).'" />
	<meta name="twitter:title" content="'.htmlspecialchars($title).'" />
	<meta name="twitter:description" content="'.htmlspecialchars($description).'" />
	<meta name="twitter:creator" content="'.htmlspecialchars($twitter).'" />
	<meta name="twitter:image:src" content="'.htmlspecialchars($image).'" />
	<meta name="twitter:domain" content="'.htmlspecialchars($domain).'" />
';	

		
		
		
	}
	
		

	
	public

	function blogsearch($search,$pubtype = 'blog')
		{
		$db = new sw_db($pubtype,$this->lang);
		$post = $db->search($search);
		}


	public

	function blogposts($pubtype = 'blog', $maxitems = 10, $page = 1, $full = false, $cat = '')
		{	
	
			
		global $swcnt_post;		
		$swcnt_blog = $swcnt_post[$pubtype];
		if(!empty($swcnt_blog['sw_ordermode'])) $ordermode = true; else $ordermode = false; 
		$db = new sw_db($pubtype,$this->lang);
		$blogposts = $db->read($maxitems,$page,$ordermode,$cat);
	
		if(!empty($full)) {
			
				
			foreach($blogposts as $pos => $post)
				{
					$fullpost = $this->getPostBlogById($post['id'],$pubtype);
					foreach($fullpost as $key => $fiels) {
						$blogposts[$pos][$key] = $fiels;
					}

				}
			
		}
		
		return $blogposts;
	
	}
		
	public  
	function sanitize_content($data) {
		$data = strip_tags($data,'<p>,<br>,<img>,<a>,<strong>,<u>,<em>,<blockquote>,<ol>,<ul>,<li>,<span>');
		$data = trim($data,'<p>');
		$data = trim($data,'</p>');
		$data = trim($data,'<br />');
		$data = preg_replace('#(?:<br\s*/?>\s*?){2,}#','</p><p>',$data);
		$data = '<p>'.$data.'</p>';
		return $data;
	}

		
	public
	function getCatName($catId,$pubtype = 'blog') {
		
		$allcats = $this ->listCats($pubtype);

		
		if(!empty($allcats[$catId])) return $allcats[$catId]; 
		else return ucFirst($catId);	
	}
	

	public
	function getPostBlogById($id,$pubtype = 'blog')
		{
		global $swcnt_post;		
		$swcnt_blog = $swcnt_post[$pubtype];

		
		if (!empty($swcnt_blog['sw_blocks']))
			{
			$structure = $swcnt_blog['sw_blocks'];
			$doc = ADMIN_URL . $this->ldb.''.$pubtype.'/' . $id . '.source.json';
			
		
			$this->structure = $structure;
			$this->doc = $doc;
			$slots = array();
		
			
			if (file_exists($doc))
				{
				$d = file_get_contents($doc);
				$d = str_replace('src=\"..\/files', 'src=\"' . $this->site_url . 'files', $d);
				$slots = json_decode($d, true);	
				
				$slots['categoryName'] = '';			
				if(!empty($slots['category'])) {
					$slots['categoryName'] = $this -> getCatName($slots['category'],$pubtype);
				}

			}
				
		return $slots;
			}
		}
		
		
public function hide_email($email)

{ $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';

  $key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999);

  for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])];

  $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';

  $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';

  $script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';

  $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")"; 

  $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';

  return '<span id="'.$id.'">[protected]</span>'.$script;

}
 	
		
		
	public function listCats($pubtype = 'blog') {
			
			return $this -> listCatsInfos($pubtype,'name');
			
					
	}	

	public function listCatsInfos($pubtype = 'blog',$select='all') {
			
			
			
			$listCats = array();
		
			$doc = ADMIN_URL .$this->ldb.''.$pubtype.'/' .$this->lang . '_cats.source.json';
			
			if (file_exists($doc))
					{
					$d = file_get_contents($doc);
					$vals = json_decode($d, true);
			if(!empty($vals ['elems'] )) {
			foreach($vals ['elems'] as $v)
					{
					
					
					if($select=='all') $listCats[$v['slug']] = $v;
					else  $listCats[$v['slug']] = $v[$select];
					
					}
			}
			
		}	
			return $listCats;
			
					
	}	
	
		public function catDescriptions($catId,$pubtype = 'blog') {
			
			
			$decription = array();
		
			$doc = ADMIN_URL .$this->ldb.''.$pubtype.'/' .$this->lang . '_cats.source.json';
			
			
			if (file_exists($doc))
					{
					$d = file_get_contents($doc);
					$vals = json_decode($d, true);
			if(!empty($vals ['elems'] )) {
				foreach($vals ['elems'] as $v) {
				if($v['slug']==$catId)
					{ 
						$decription = $v;
					}
				}
			}
			
		}	
			return $decription;
			
					
	}	
	
	public function toLi($string) {
		
		$bits = explode("\n", $string);

		
		foreach($bits as $bit)
		{
		  $newstring .= "<li>" . $bit . "</li>";
		}
		
		
		return $newstring;
	}	


	
	public function siteinfo() {
   if(array_key_exists('siteInfodatas', $GLOBALS)) global $siteInfodatas; 
	if(!empty($siteInfodatas)) $siteinfo = $this->block($siteInfodatas); else $siteinfo = $this->block('siteinfos');
	if (!empty($siteinfo['site_url'])) $site_url = $siteinfo['site_url']; else $site_url = '/';
	
	define('SITE_URL', $site_url);
	define('TEMPLATE_URL', $site_url . 'template/');
	$this->site_url = $site_url;
	return $siteinfo;
	}
	
	
	public function cmsInfos() {
		
		
		$timeend=microtime(true);
		$time=$timeend-$this->timestart;
		$page_load_time = number_format($time, 3);
		echo '
		<!-- 
		Page loaded in '.$page_load_time.' seconds 
		-->';
	}
	
	public function previewUrl($vals,$model) {
	    
	    if(!empty($vals['title']) and !empty($model)) { 
	     
	     $urlP = str_replace('{title}', $this->format_url($vals['title']), $model);
	     $urlP = str_replace('{lang}', $this->lang, $urlP);
	    
	     return  $this->site_url.$urlP;
	    
	    } else '';
	    
    }

	
	
}	



class sw_db
{
	
    function __construct($pubtype,$lang)
    {
	    if(file_exists(ADMIN_URL . '../db.conf.php')) include (ADMIN_URL . '../db.conf.php');
	    
	    global $swcnt_options;
        $swcnt_languages    = $swcnt_options['languages'];
		$this->ldb			=  $swcnt_options['db_location']; 
		$this->pubtype		= $pubtype; /* blog */	    
	    $this->iposts		= $pubtype.'_'.$lang.'_posts';
	    	    
		try{	
	    
		    if(empty($dbconnect)) {
			$pdo = new PDO('sqlite:'.ADMIN_URL .$this->ldb.'db.sqlite');	
		
			} else {
				
			$pdo = new PDO('mysql:host='.$dbconnect['server'].';dbname='.$dbconnect['dbname'], $dbconnect['user'], $dbconnect['pass']);
				
			}

	   	    
	    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
		} catch(Exception $e) {
		    echo "Impossible d'accéder à la base de données  : ".$e->getMessage();
		    die();
		}
		$this->pdo = $pdo;	
	}
	
	

	function count($word) {
		
		$stmt = $this->pdo->prepare("SELECT Count(*) as max FROM ".$this->iposts); //WHERE titre = :titre
			$stmt->execute();
			$result = $stmt->fetchAll();
			$max = 0;
			foreach($result as $r) {
				$max = $r["max"];
				
			}
			return $max;

	}	
	
	function search($word) {
		
			$pubtype = $this->pubtype;
		
			$stmt = $this->pdo->prepare("SELECT title, urltxt, body, pubdate FROM ".$this->iposts." WHERE status = 1 AND (title LIKE ? OR body LIKE ?) ORDER BY pubdate DESC LIMIT 5" ); 
			$stmt->execute(array('%'.$word.'%','%'.$word.'%'));
			$result = $stmt->fetchAll();
			return $result;
			
	}
	
	function byid($id) {
		 	$return = array();
			$pubtype = $this->pubtype;
		
			$stmt = $this->pdo->prepare("SELECT * FROM ".$this->iposts." WHERE status = 1 AND (id = ? OR urltxt LIKE ?) LIMIT 1" ); 
			$stmt->execute(array($id,$id));
			$result = $stmt->fetchAll();
			
			foreach($result as $r) {
				$return = $r;
			}
			return $return;
		
	}
	
	
	function read($limit=10,$page=1,$ordermode=0, $cat='') {
		
		
		
		$result = array();
		$limit=intval($limit);
		$page=intval($page);
				
		$pubtype = $this->pubtype;
		$offset = ($page-1)*$limit;
	

		if(empty($cat)) {
			if($ordermode) {
				$stmt = $this->pdo->prepare("SELECT * FROM ".$this->iposts."  WHERE status = 1 ORDER BY position ASC LIMIT $offset, $limit" ); 
			} else {
				$stmt = $this->pdo->prepare("SELECT * FROM  ".$this->iposts."  WHERE status = 1  ORDER BY pubdate DESC LIMIT $offset, $limit" ); 	
			}
		$stmt->execute();
		$result = $stmt->fetchAll(); 
		}
		else {
		

		if($ordermode) {
			$stmt = $this->pdo->prepare("SELECT * FROM  ".$this->iposts." WHERE category LIKE ? and status = 1 ORDER BY position ASC LIMIT $offset, $limit" ); 
		} else {
			$stmt = $this->pdo->prepare("SELECT * FROM  ".$this->iposts." WHERE category LIKE ? and status = 1  ORDER BY pubdate DESC LIMIT $offset, $limit" ); 	
		}
		
		
		$stmt->execute(array('%'.$cat.'%')); 
		$result = $stmt->fetchAll();
		
			
		}	
		return $result;
			
	}
	
	
	
		
}
	







/* on charge la classe et le system SW */
$sw = new sw();
$siteinfo = $sw -> siteinfo();

$sw_vars = array(
'site_url' => $sw->site_url,	
'returnmessage'	=> $sw->getmessage()
);

$page = 'hp';


/* on charge les datas de la page en cours */
if (!empty($_GET['page'])) $page = htmlentities($_GET['page']);
$blocks = $sw->block($page);

/* on charge les plugins */
$swcnt_pluglist = array();

foreach($swcnt_plugins as $pname)
	{
	$obj = ADMIN_URL . 'plugins/' . $pname . '/models.php';
	if (file_exists($obj)) include_once $obj;

	$obj = ADMIN_URL . 'plugins/' . $pname . '/system.php';
	if (file_exists($obj)) include_once $obj;

	}

?>
