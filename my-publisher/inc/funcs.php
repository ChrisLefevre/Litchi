<?php 

/**
 * swcnt_sadmin class.
 */
class swcnt_sadmin
{
    public $datastore;
    
    function __construct()
    {
        if (session_id() == '') {
            session_start();
        }
        
        global $swcnt_options;
        global $swcnt_plugins;
        global $swcnt_post;
        $swcnt_languages     = $swcnt_options['languages'];
        $swcnt_secure_key    = $swcnt_options['secure_key'];
        $swcnt_crypt         = $swcnt_options['crypt'];
		$this->ldb			 =  $swcnt_options['db_location'];        
        $key                 = md5($swcnt_secure_key);
        $this->key           = $key;
        $this->crypt         = $swcnt_crypt;

        
        
        $confx               = array();
        if(!empty($swcnt_plugins)) {
	        $swcnt_pluglist      = array();
	        foreach ($swcnt_plugins as $pname) {
	            include_once 'plugins/' . $pname . '/conf.php';
	            
	        } 
	        $this->plugins    = $swcnt_pluglist;
	    }
	    
        $configfile       = $this->ldb.'db.' . substr(strtolower(md5($key . 'cfile')), 2, 8) . '.conf.php';
        $backupconfigfile = $this->ldb.'backup/'.date("Ym").'/db.' . substr(strtolower(md5($key . 'cfile')), 2, 8) . '.' . date("y-m-d") . 'conf.php';
        if (file_exists($configfile)) {
            $c     = file_get_contents($configfile);
            $c     = str_replace("<?php //", "", $c);
            $c     = $this->decrypt($c);
            $confx = json_decode($c, true);
        }
        
        $this->configfile    = $configfile;
        $this->configbkpfile = $backupconfigfile;
        $this->key           = $key;
        $this->lang          = 'en';
        $this->confx         = $confx;
        $this->showMessage   = '';
        $this->langlist      = array(
            'fr' => array(
                'iso' => 'fr_FR',
                'txt' => "Français"
            )
            /* 	'nl' => array('iso'=>'nl_NL','txt'=>"Nederlands")
            'en' => array('iso'=>'en_US','txt'=>"English"),
            */
        );
    }
    
    public function loadDatastore($d)
    {
        
        $confx      = array();
        $configfile = $this->ldb.'db.' . $d . '.conf.php';
        
        
        
        
        if (file_exists($configfile)) {
            $c     = file_get_contents($configfile);
            $c     = str_replace("<?php //", "", $c);
            $c     = $this->decrypt($c);
            $confx = json_decode($c, true);
   
        }
        
        $this->datastore[$d] = $confx;
        return $confx;
    }
 
     public function loadPublicstore($d)
    {
        
        $confx      = array();
        $configfile = $this->ldb.'db.' . $d . '.index.json';
  
        
        
        if (file_exists($configfile)) {
            $c     = file_get_contents($configfile);
            $confx = json_decode($c, true);       
        }
        
        $this->datastore[$d] = $confx;
        return $confx;
    }
 
 
 
    
    public function searchInData($tb, $q)
    {
        $confx = $this->loadPublicstore($tb);
        $ret   = array();
        if (!empty($confx)) {
            foreach ($confx as $key => $val) {
                if ($key == $q)
                    $ret[$key] = $val;
                if (array_search($q, $val, true)) {
                    $ret[$key] = $val;
                }
            }
        }
        
        return $ret;
    }
    
    public function setMylang($lang = '')
    {
        if ($lang == '') {
            if (!empty($_GET['language']))
                $lang = $_GET['language'];
            else if (!empty($_COOKIE['language']))
                $lang = $_COOKIE['language'];
            else
                $lang = 'en';
        }
        
        $directory = 'lang/';
        $langlist  = $this->langlist;
            }
    
    public function saveData($d = 'temp', $k, $v)
    {
        $confx         = $this->loadDatastore($d);
        $configfile    = $this->ldb.'db.' . $d . '.conf.php';
        $configbkpfile = $this->ldb.'backup/'.date("Ym").'/db.' . $d . '.' . date("y-m-d") . 'conf.php';
        
        
        
        $confx[$k] = $v;
        if (empty($v))
            unset($confx[$k]);
        $c = json_encode($confx);
        
        
        
        
        file_put_contents($configfile, '<?php //' . $this->encrypt($c));
        file_put_contents($configbkpfile, '<?php //' . $this->encrypt($c));
        $this->datastore[$d] = $confx;
    }


    public function savePublicData($d = 'temp', $k, $v)
    {
        $confx         = $this->loadPublicstore($d);
        $configfile    = $this->ldb.'db.' . $d . '.index.json';
        $configbkpfile = $this->ldb.'backup/'.date("Ym").'/db.' . $d . '.' . date("y-m-d") . '.index.json';
        
        
/* hack pour garder les positions dans les posts */
if(!empty($_POST["title"])) {
 
        if(!empty($confx[$k]["position"]) and empty($v["position"])) {
       $v["position"] = $confx[$k]["position"];
        } else if(empty($v["position"]) and empty($v["position"])) {
            $v["position"] = "0";

        }

}

        
        
        $confx[$k] = $v;
        if (empty($v))
            unset($confx[$k]);
        $c = json_encode($confx);
        
        
        
        
        file_put_contents($configfile,$c);
        file_put_contents($configbkpfile,$c);
        $this->datastore[$d] = $confx;
    }

    
    public function unlog()
    {
        session_unset();
        session_destroy();
        setcookie("sw_logged_user", '', time() - 3600);
    }
    
    public function islogged()
    {
        if (!empty($_SESSION['swcnt_user'])) {
            return true;
        } else
            return false;
    }
    
    public function userlogged()
    {
        if (!empty($_SESSION['swcnt_user'])) {
            $u['mail']   = $_SESSION['swcnt_user'];
            $u['user']   = $this->getConfigItem('users', $_SESSION['swcnt_user']);
            $u['avatar'] = $this->get_gravatar($_SESSION['swcnt_user']);
            return $u;
        }
    }
    
    public function get_gravatar($email)
    {
        $grav_url = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?d=mm";
        return $grav_url;
    }
    
    public function cleanHtml($string)
    {
        return trim(htmlspecialchars(strtolower($string)));
    }
    
    public function encrypt($string)
    {
        if ($this->crypt == 0)
            return base64_encode($string);
        else {
            $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
            $iv      = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            return mcrypt_encrypt(MCRYPT_BLOWFISH, $this->key, $string, MCRYPT_MODE_ECB, $iv);
        }
    }
    
    public function decrypt($crypttext)
    {
        if ($this->crypt == 0)
            return base64_decode($crypttext);
        else {
            $crypttext = ($crypttext);
            $iv_size   = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
            $iv        = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            return trim(mcrypt_decrypt(MCRYPT_BLOWFISH, $this->key, $crypttext, MCRYPT_MODE_ECB, $iv));
        }
    }
    
    public function getConfig($tb)
    {
        $confx   = $this->confx;
        $returna = array();
        if (!empty($confx[$tb])) {
            foreach ($confx[$tb] as $k => $v) {
                if (!empty($v))
                    $returna[$k] = $v;
            }
            
            return $returna;
        }
    }
    
    public function searchInConfig($tb, $q)
    {
        $confx = $this->confx;
        $ret   = array();
        if (!empty($confx[$tb])) {
            foreach ($confx[$tb] as $key => $val) {
                if ($key == $q)
                    $ret[$key] = $val;
                if (array_search($q, $val, true)) {
                    $ret[$key] = $val;
                }
            }
        }
        
        return $ret;
    }
    
    public function searchPatternInConfig($tb, $q)
    {
        
        // Exemple : $adm -> searchPatternInConfig('users','/^.*.com.*/');
        
        $confx = $this->confx;
        $ret   = array();
        if (!empty($confx[$tb])) {
            foreach ($confx[$tb] as $key => $val) {
                if (preg_match($q, $key))
                    $ret[$key] = $val;
                foreach ($val as $valk => $valv) {
                    if (preg_match($q, $valv))
                        $ret[$key] = $val;
                }
            }
        }
        
        return $ret;
    }
    
    public function getConfigItem($tb, $item)
    {
        $confx = $this->confx;
        if (!empty($confx[$tb][$item]))
            return $confx[$tb][$item];
        else
            return array();
    }
    
    public function setShowmessage($txt, $type)
    {
        if ($type == 'alert')
            $this->showMessage = '<div class="pad margin no-print"><div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Ouuups ! </h4> ' . $txt . '</div></div>';
        if ($type == 'attention')
            $this->showMessage = '<div class="pad margin no-print"><div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-warning"></i> Attention</h4>
                    ' . $txt . '
                  </div></div>';
        if ($type == 'ok')
            $this->showMessage = '<div class="pad margin no-print"><div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4>	<i class="icon fa fa-check"></i>  ' . $txt . '</h4>
                  </div></div>';
    }
    
    public function getShowmessage()
    {
        $txt               = $this->showMessage;
        $this->showMessage = '';
        return $txt;
    }
    
    public function setConfig($tb, $k, $v)
    {
        $configfile     = $this->configfile;
        $configbkpfile  = $this->configbkpfile;
        $confx          = $this->confx;
        $confx[$tb][$k] = $v;
        $c              = json_encode($confx);
        file_put_contents($configfile, '<?php //' . $this->encrypt($c));
        file_put_contents($configbkpfile, '<?php //' . $this->encrypt($c));
        $this->confx = $confx;
    }
    
    public function createPass($str)
    {
        $key   = $this->key;
        $str   = trim(strtolower($str));
        $npass = substr(strtolower(md5($key . $str)), 0, 25);
        return $npass;
    }
    
    public function login()
    {
        $users = $this->getConfig('users');
        if (empty($users)) {
            $password = $this->createPass('root');
            $parms    = array(
                'pass' => $password,
                'username' => 'root',
                'role' => 2
            );
            $this->setConfig('users', 'admin@swalize.com', $parms);
        }
        
        if (!empty($_POST['email']) and !empty($_POST['pass'])) {
            $email = $this->cleanHtml($_POST['email']);
            $pass  = $this->createPass($_POST['pass']);
            if (!empty($users[$email])) {
                if ($users[$email]['pass'] == $pass) {
                    $_SESSION['swcnt_user'] = $email;
                    if (!empty($_POST['restconnect'])) {
                        $cook = serialize(array(
                            $email,
                            $pass
                        ));
                        setcookie("sw_logged_user", $cook, time() + 3600 * 24 * 7 * 30);
                    }
                    
                    $this->setShowmessage('Vous êtes connecté', 'ok');
                    header("Location: ./");
                    exit();
                } else
                    $this->setShowmessage('Votre mot de passe est incorrecte', 'alert');
            } else
                $this->setShowmessage('Votre adresse e-mail est inconnue', 'alert');
        } else if (!empty($_COOKIE['sw_logged_user'])) {
            $credits = unserialize($_COOKIE['sw_logged_user']);
            $email   = $credits[0];
            $pass    = $credits[1];
            if (!empty($users[$email])) {
                if ($users[$email]['pass'] == $pass) {
                    $_SESSION['swcnt_user'] = $email;
                    $this->setShowmessage('Vous êtes connecté', 'ok');
                    header("Location: ./");
                    exit();
                }
            }
        }
    }
    
    public function saveUser()
    {
        if (!empty($_POST['email'])) {
            $email = $this->cleanHtml($_POST['email']);
            if (!empty($_POST['delete_user'])) {
                $parms = array();
                $this->setConfig('users', $email, $parms);
                $this->setShowmessage(_tr('Profil Saved'), 'attention');
            }
            
            $baseconf = $this->getConfigItem('users', $email);
            if (!empty($baseconf['username']))
                $username = trim($baseconf['username']);
            if (!empty($baseconf['pass']))
                $password = trim($baseconf['pass']);
            if (!empty($baseconf['role']))
                $role = intval($baseconf['role']);
            if (!empty($_POST['username']))
                $username = trim($_POST['username']);
            if (!empty($_POST['role']))
                $role = intval($_POST['role']);
            if (!empty($_POST['password']) and !empty($_POST['password2'])) {
                $password  = trim($_POST['password']);
                $password2 = trim($_POST['password2']);
                if ($password != $password2) {
                    $password = '';
                    $this->setShowmessage('Les Mots de passe ne correspondent pas', 'alert');
                } else {
                    $password = $this->createPass($password);
                }
            }
            
            if (!empty($username) and !empty($email) and !empty($password) and !empty($role)) {
                $parms = array(
                    'pass' => $password,
                    'username' => $username,
                    'role' => $role
                );
                $this->setConfig('users', $email, $parms);
                $this->setShowmessage(_tr('Profil Saved'), 'ok');
            }
        }
    }
}

class swcnt_tables
{
    function __construct()
    {
	    
    }
    
    public function form($string)
    {
        global $swcnt_form;
        if (!empty($swcnt_form[$string]))
            return $swcnt_form[$string];
    }
    
    public function showformtable($string, $editable = 1)
    {
        $form = $this->form($string);
        $adm  = new swcnt_sadmin();
        if (!empty($_POST['delete_this_form_obj'])) {
            $adm->savePublicData($string, $_POST['delete_this_form_obj'], array());
        }
        
        $mydatas = $adm->loadPublicstore($string);
        if (!empty($form)) {
            echo ' <table id="datatable" class="table table-bordered table-striped">
                    <thead>
                    <tr>';
            foreach ($form as $k => $v) {
                echo '<th>' . $v['label'] . '</th>';
            }
            
            if ($editable == 1)
                echo '<th></th>';
            echo '</thead></tr>';
            foreach ($mydatas as $k => $v) {
                echo '<tr>';
                foreach ($form as $kk => $vv) {
                    if (!empty($v[$kk]))
                        echo '<td>' . $v[$kk] . ' </td>';
                    else
                        echo '<td></td>';
                }
                
                if ($editable == 1)
                    echo '<td><form action="" method="post"><button name="delete_this_form_obj" type="submit" class="btn btn-xs bg-red color-palette" value="' . $k . '"><i class="fa fa-eraser"></i> ' . _tr('delete') . '</button></form></td>';
                echo '</tr>';
            }
            
            echo '</table> ';
        }
    }
}

class swcnt_sforms
{
    public function format_url($texte)
    {
        $texte             = utf8_decode($texte);
        $texte             = html_entity_decode($texte);
        $tofind            = utf8_decode('ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËéèêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ');
        $replac            = utf8_decode('AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn');
        $texte_pre_pre_pre = trim(strtolower(strtr($texte, $tofind, $replac)));
        $texte_pre_pre     = preg_replace('/[^a-zA-Z0-9_]/i', '-', $texte_pre_pre_pre);
        $texte_pre         = preg_replace('/-+/i', '-', $texte_pre_pre);
        $texte_final       = substr($texte_pre, '0', '128');
        return $texte_final;
    }
    
    public function plugin_form($document, $structure, $lang)
    {
        global $swcnt_plugins;
        foreach ($swcnt_plugins as $pname) {
            if ($pname == $_GET['plugin']) {
                $doc  = $this->ldb.'' . $lang . '_plugin_' . $this->format_url($pname) . '_' . $this->format_url($document) . '.source.json';
                $vals = array();
                if (file_exists($doc)) {
                    $d    = file_get_contents($doc);
                    $vals = json_decode($d, true);
                }
                
                $this->createform($structure, $vals);
            }
        }
    }
    
    public function plugin_datas($document, $lang)
    {
        global $swcnt_plugins;
        foreach ($swcnt_plugins as $pname) {
            if ($pname == $_GET['plugin']) {
                $doc  = $this->ldb.'' . $lang . '_plugin_' . $this->format_url($pname) . '_' . $this->format_url($document) . '.source.json';
                $vals = array();
                if (file_exists($doc)) {
                    $d    = file_get_contents($doc);
                    $vals = json_decode($d, true);
                }
                
                return $vals;
            }
        }
    }
    
    public function plugin_save($document, $structure, $lang)
    {
        global $swcnt_plugins;
        $actlang = $this->lang;
        if (!empty($_GET['plugin']) and !empty($_POST)) {
            foreach ($swcnt_plugins as $pname) {
                if ($pname == $_GET['plugin']) {
                    $return = array();
                    foreach ($structure as $k => $v) {
                        if (!empty($_POST[$k])) {
                            $return[$k] = $_POST[$k];
                        }
                    }
                    
                    $final     = json_encode($return);
                    $doc       = $this->ldb.'' . $lang . '_plugin_' . $this->format_url($pname) . '_' . $this->format_url($document) . '.source.json';
                    $backupdoc = $this->ldb.'backup/'.date("Ym").'/' . $lang . '_plugin_' . $this->format_url($pname) . '_' . $this->format_url($document) . '.' . date("Y-m-d H:i") . 'source.json';
                    file_put_contents($doc, $final);
                    file_put_contents($backupdoc, $final);
                }
            }
        }
    }
   
	    
    public function save()
    {
        
        
        if (!empty($this->structure)) {
            $structure = $this->structure;
            $doc       = $this->doc;
            $backupdoc = $this->backupdoc;
            if (!empty($_POST)) {
	            
	            
	            
                $return = array();
                foreach ($structure as $k => $v) {
                    if (isset($_POST[$k])) {
                        if (!empty($_POST[$k][0]['slug'])) {
                            foreach ($_POST[$k] as $atemp_num => $atemp_ko) {
                                $_POST[$k][$atemp_num]['slug'] = $this->format_url($_POST[$k][$atemp_num]['slug']);
                            }
                        }
                        
                        $return[$k] = $_POST[$k];
                    }
                }
                     
                $final = json_encode($return);
                
       
                
                
                if (!empty($this->postId) and !empty($this->pubtype) and !empty($this->lang)) {
                    $pubtype = $this->pubtype;
                    if (!empty($return['title']))
                        $title = $return['title'];        
                    else
                        $title = $pubtype . ' ' . $this->postId;
                                        
                    if (!empty($return['pubdate']))
                        $pubdate = $return['pubdate'];
                    else
                        $pubdate = date("Y-m-d H:i:s");
                         
                    if(empty($return['keyword'])) $return['keyword'] = array();    
                    if(empty($return['category'])) $return['category'] = '';       
                    if(!isset($_POST['status'])) $return['status'] = 1;     
                    else $return['status'] = $_POST['status'];    
                    $body = "";
                    $cover = "";
                    if(!empty($_POST['headline']))  $body .= " ".$_POST['headline']." ";
                    if(!empty($_POST['article']))  $body .= " ".$_POST['article']." ";
                    if(!empty($_POST['body']))  $body .= " ".$_POST['body']." ";
                    $body = trim(strip_tags($body));
                    
                    if (!empty($_POST['cover'])) $cover = $_POST['cover'];
                    else if(!empty($_POST['illustration'])) $cover = $_POST['illustration'];
                    else if(!empty($_POST['photo'])) $cover = $_POST['photo'];
                
                    
					$insertData = array(
						'id'  		=> $this->postId,
						'title'  	=> $return['title'],
						'urltxt'  	=> $this->format_url($return['title']),
						'status'  	=> $return['status'],
						'lang'  	=> $this->lang,
						'body'  	=> $body,
						'pubdate'=> $pubdate,
						'category'  => $return['category'],
						'keyword'  	=> json_encode($return['keyword']),
						'cover'		=>  $cover
					);

                    $db = new swcnt_index($pubtype,$this->lang);
                    
       
                    
                    $db->update($insertData);                      
                    
                    
                }
                
                
                
                file_put_contents($doc, $final);
                file_put_contents($backupdoc, $final);
                
                /* blog */
                if (empty($_GET['post']) and !empty($this->postId) and !empty($this->lang))
                    echo '<script>window.location.replace("?item=edit&type=' . $pubtype . '&lang=' . $this->lang . '&post=' . $this->postId . '");</script>';
                
                
                
            }
        }
    }
    
    public function saveTags($list = 'temp', $tag)
    {
        $doctags        = $this->doctags;
        $backupdoctags  = $this->backupdoctags;
        $alltags[$list] = array();
        if (file_exists($doctags)) {
            $d       = file_get_contents($doctags);
            $alltags = json_decode($d, true);
        }
        
        $tags = explode(',', $tag);
        foreach ($tags as $gt) {
            $alltags[$list][$this->format_url($gt)] = $gt;
        }
        
        $final = json_encode($alltags);
        file_put_contents($doctags, $final);
        file_put_contents($backupdoctags, $final);
    }
    
    private function addformelem($k, $v, $vals)
    {
	    if(empty($v['placeholder'])) $v['placeholder'] = '';
 	    
        echo '<div class="form-group">';
      
      
      
      if (!empty($v['type']) and $v['type'] == 'separation') {
	         
	         echo '<hr/><h3>' . $v['label'] . '</h3>';

	       
	         
	         }
        

      
      
        if (!empty($v['type']) and $v['type'] != 'separation')
            echo '<label for="' . $k . '">' . $v['label'] . '</label>';
        $vv = '';
        if (!empty($vals[$k])) {
            if (!is_array($vals[$k]))
                $vv = htmlspecialchars($vals[$k], ENT_QUOTES);
            else
                $vv = ($vals[$k]);
        }
        
        if (empty($v['type'])) {
            echo '
			<div class="row">
			    <div class="col-md-6">
			
			<label>' . $v['label'] . '</label></div> <div class="col-md-6"><input type="text" value="' . $vv . '" id="input' . $k . '" name="' . $k . '" class="form-control" placeholder="' . $v['label'] . '" /></div></div>';
        }
        
        if ($v['type'] == 'checkbox') {
            echo '<td>';
            foreach ($v['options'] as $key_o => $value_o) {
                
                $checked_o = 0;
                
                if (is_array($vv)) {
                    
                    if (in_array($key_o, $vv)) {
                        $checked_o = 1;
                    }
                }
                
                if ($checked_o == 0)
                    echo '<div  class="checkbox"><label><input type="checkbox" id="inputtitle_' . $k . '" name="' . $k . '[]" value="' . $key_o . '">' . $value_o . '</label></div>';
                else
                    echo '<div  class="checkbox"><label><input type="checkbox" checked id="inputtitle_' . $k . '" name="' . $k . '[]" value="' . $key_o . '">' . $value_o . '</label></div>';
            }
            
            echo '</select>';
            echo ' </td>';
        }
        
        
        if ($v['type'] == 'select') {
            echo '<td><select id="inputtitle_' . $k . '" name="' . $k . '" class="form-control">';
            foreach ($v['options'] as $key_o => $value_o) {
                if ($key_o == $vv)
                    echo '<option selected value="' . $key_o . '">' . $value_o . '</option>';
                else
                    echo '<option value="' . $key_o . '">' . $value_o . '</option>';
            }
            
            echo '</select>';
            echo ' </td>';
        }
        
        
        
        
        if ($v['type'] == 'tags') {
            echo '<input type="text" data-role="tagsinput"  value="' . $vv . '" id="input' . $k . '" name="' . $k . '" class="form-control"  placeholder="' . $v['placeholder'] . '" />';
            $this->saveTags($k, $vv);
        }
        
        if(!empty($v['height'])) $styleHeight = 'style="height:'. $v['height'].'px"'; else $styleHeight = '';
        
        if ($v['type'] == 'input_txt')
            echo '<input type="text" value="' . $vv . '" id="input' . $k . '" name="' . $k . '" class="form-control" placeholder="' . $v['placeholder'] . '" />';
       

        if ($v['type'] == 'link')
            echo ' <div class="input-group" style="width: 100%;"><input type="text" style="width: 30%; float: left;" value="' . $vv['text']. '" id="input' . $k . '_txt" name="' . $k . '[text]" class="form-control" placeholder="' . $v['placeholder'] . '" />
                  <input type="text" style="width: 30%; float: left; margin-right: 1%;" value="' . $vv['link'].'" id="input' . $k . '_lnk" name="' . $k . '[link]" class="form-control" placeholder="http://" /></div>		
            ';
            
           
            
            
            
        if ($v['type'] == 'textarea')
            echo '<textarea '.$styleHeight.' id="input' . $k . '" name="' . $k . '" rows="9" class="form-control" rows="10" placeholder="' . $v['placeholder'] . '">' . $vv . '</textarea>';
        if ($v['type'] == 'htmlarea')
            echo '<textarea '.$styleHeight.' id="input' . $k . '" name="' . $k . '" rows="9" class="form-control summernote" rows="20"  placeholder="' . $v['placeholder'] . '">' . $vv . '</textarea>';
        if ($v['type'] == 'blogarea')
            echo '<textarea '.$styleHeight.' id="input' . $k . '" name="' . $k . '" rows="25" class="form-control tinymce" rows="20"  placeholder="' . $v['placeholder'] . '">' . $vv . '</textarea>';
        
        
                 
                 
                 
                 
        
        if ($v['type'] == 'list') {
            $sub = $v['submenu'];
            
            
            echo '<div class="box-body table-responsive no-padding" style="padding-bottom: 20px !important;" id="dtable_listact_ref_' . $k . '">';
            
            
            if (!empty($v['fixewidth']))
                echo '<table class="table table-hover dtable" style="width:' . $v['fixewidth'] . 'px;" >';
            else
                echo '<table class="table table-hover dtable">';
            echo '<tbody><tr><th></th>';
            foreach ($sub as $o) {
                echo '<th>' . $o['label'] . '</th>';
            }
            
            echo '<th></th></tr>';
            $vvclean = array();
            if (is_array($vv)) {
                foreach ($vv as $tmpk => $tmpv) {
                    $vvclean[$tmpk] = $tmpv;
                }
            }
            
            $nitemax = count($vvclean);
            sort($vvclean);
            for ($i = 0; $i <= $nitemax; $i++) {
	           
	           $ia = $i;
	            
	            if ($i==$nitemax) $ia = "{tempID}";
	           
	           
	            
                echo '<tr vid="'.$i.'" id="liItem' . $k . '-' . $ia . '">';
                echo '<td class="movable"><i class="fa fa-fw fa-sort"></i> <input type="hidden" value="' . $ia . '" id="inputtitle" name="' . $k . '[' . $ia . '][position]" class="form-control" /></td>';
                foreach ($sub as $ok => $ov) {
                    $value = '';
                    if (!empty($vvclean[$i])) {
                        $value = htmlspecialchars($vvclean[$i][$ok]);
                    }
                    
                    
                    
                    /* Add a Textarea, Select and Picture in list */
                    if ($ov['type'] == 'select') {
                        echo '<td><select id="inputtitle_' . $ia . '_' . $ok . '" name="' . $k . '[' . $ia . '][' . $ok . ']" class="form-control">';
                        foreach ($ov['options'] as $key_o => $value_o) {
                            if ($key_o == $value)
                                echo '<option selected value="' . $key_o . '">' . $value_o . '</option>';
                            else
                                echo '<option value="' . $key_o . '">' . $value_o . '</option>';
                        }
                        
                        echo '</select>';
                        echo ' </td>';
                    }
                    
                    
                     if ($ov['type'] == 'related') {
	                  	                     
	                     if(!empty($ov['pubtype'])) $pubtype = $ov['pubtype']; else $pubtype = "blog";
	                     
	                     
	                     
	                   
					   $db = new swcnt_index($pubtype,$this->lang);
					   $products = $db->read(false,50);
                        
                           
	                     
                        echo '<td><select id="inputtitle_' . $ia . '_' . $ok . '" name="' . $k . '[' . $ia . '][' . $ok . ']" class="form-control">';
                        foreach ($products as $key_o => $value_o) {
	                     if($value_o['status']!=2) {   	  
	                        if ($key_o == $value)
                                echo '<option selected value="' . $key_o . '">' . $value_o['title'] . '</option>';
							else
                                echo '<option value="' . $key_o . '">' . $value_o['title'] . '</option>';
	                        };  
	                    }    
                        echo '</select>';
                        echo ' </td>';
                    }
                    
                    
                    
                    
                    if ($ov['type'] == 'picture') {
                        if (!empty($value))
                            $thumb = '../files/thumb/' . $value;
                        else
                            $thumb = 'assets/dist/img/boxed-bg.jpg';
                        if (!empty($value))
                            $prev = '../files/full/' . $value;
                        else
                            $prev = '';
                    
                            
                        echo '<td><div>
	              <iframe class="picturbtn" src="?uploader=' . $ia . '_' . $k . '-' . $ok . '" width="100px" frameborder="0" scrolling="no" height="35px"></iframe>
	              <a href="' . $prev . '" data-title="' . $v['label'] . '" data-toggle="lightbox"><img class="picturpreview" id="picturpreview-' . $ia . '_' . $k . '-' . $ok . '" width="40" height="40" src="' . $thumb . '" /></a>
	              <input name="' . $k . '[' . $ia . '][' . $ok . ']" value="' . $value . '" id="picturelement-' . $ia . '_' . $k . '-' . $ok . '" type="hidden" />
	              </div></td>';
                    }
                    
                    if ($ov['type'] == 'textarea')
                        echo '<td><textarea onclick="SetTempFormModal(\'#inputtitle_' . $ia . '_' . $k . '-' . $ok . '\')" id="inputtitle_' . $ia . '_' . $k . '-' . $ok . '" name="' . $k . '[' . $ia . '][' . $ok . ']" style="height: 34px;" class="form-control" placeholder="' . $ov['placeholder'] . '">' . $value . '</textarea></td>';
                    if ($ov['type'] == 'input_txt')
                        echo '<td><input type="text" value="' . $value . '" id="inputtitle_' . $ia . '_' . $ok . '" name="' . $k . '[' . $ia . '][' . $ok . ']" class="form-control" placeholder="' . $ov['placeholder'] . '"></td>';
                    
                    
                    //echo '</tr><tr>';
                    
                    
                }
                
                
                /*
                
                if ($i == $nitemax)
                    echo '<td><a href="#" onclick="$( \'#formeditor\').submit(); return false" class="btn bg-green color-palette" title="Remove"><i class="fa fa-save"></i> Sauver</a> 
</td></tr>';
                else
                
                */
                    echo '<td><a href="#" onclick="$(\'#liItem' . $k . '-' . $ia . '\').remove(); return false" class="btn btn_modern_red" title="Remove"><i class="fa fa-eraser"></i> Effacer</a> 
</td></tr>';
            }
            
            echo '</tbody></table>';
            
            
            echo '<a href="javascript:void(0)" class="addelemdtable btn  btn-sm btn_modern" data-widget="add" title="Add"><i class="fa fa-check"></i> Ajouter</a>    

                </div>
';
            
            
            
            
        }
        
        
        
        
        
        
        
        
        
        if ($v['type'] == 'user') {
            echo '<select id="input' . $k . '" name="' . $k . '" class="form-control" >';
            $admm  = new swcnt_sadmin();
            $users = $admm->getConfig('users');
            foreach ($users as $vu) {
                if ($vu['username'] == $vv)
                    echo '<option selected="selected" value="' . $vu['username'] . '">' . $vu['username'] . '</option>';
                else
                    echo '<option value="' . $vu['username'] . '">' . $vu['username'] . '</option>';
            }
            
            echo '</select>';
        }
        
        if ($v['type'] == 'datetime') {
            $date = '';
            $time = '';
            if (!empty($vv))
                $datetime = $vv;
            else if (!empty($v['default']))
                $datetime = $v['default'];
            if (isset($datetime)) {
                $date = date('d/m/Y', strtotime($datetime));
                $time = date('H:i', strtotime($datetime));
            }
            
            echo '

            <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                       <input type="text" value="' . $date . '" placeholder="' . $v['placeholder'] . '"  class="form-control" data-inputmask="\'alias\': \'dd/mm/yyyy\'" onchange="convdate(\'dt_' . $k . '\');" id="dt_' . $k . '_d" data-mask="">

                    </div>

            <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>

                      <input type="text"  value="' . $time . '"onchange="convdate(\'dt_' . $k . '\');" id="dt_' . $k . '_t"  placeholder="' . $v['placeholder'] . '"  class="form-control" data-inputmask="\'alias\': \'hh:mm\'" data-mask="">
                    </div>
                    <input name="' . $k . '" type="hidden" id="dt_' . $k . '"   value="' . $datetime . '">



                    ';
        }
        
        if ($v['type'] == 'picture') {
            if (!empty($vv))
                $thumb = '../files/thumb/' . $vv;
            else
                $thumb = 'assets/dist/img/boxed-bg.jpg';
            if (!empty($vv))
                $prev = '../files/full/' . $vv;
            else
                $prev = '';
            echo '<div>
<iframe class="picturbtn" src="?uploader=' . $k . '" width="150px" frameborder="0" scrolling="no" height="35px"></iframe>
<a href="' . $prev . '" data-title="' . $v['label'] . '" data-toggle="lightbox"><img class="picturpreview" id="picturpreview-' . $k . '" width="40" height="40" src="' . $thumb . '" /></a>
<input name="' . $k . '" value="' . $vv . '" id="picturelement-' . $k . '" type="hidden" />
</div>';
        }
        
        echo ' </div>';
    }
    
    
    private function previewUrl($vals) {
	    
	    if(!empty($vals['title']) and !empty($this->urlPreview)) { 
	     
	     $urlP = str_replace('{title}', $this->format_url($vals['title']), $this->urlPreview);
	     $urlP = str_replace('{lang}', $this->lang, $urlP);
	    
	     return '/'.$urlP;
	    
	    } else '';
	    
    }
    
    
    private function createform($structure, $vals)
    {
	     
	   
	     
        
        $sidebar = 0;
        
        echo ' <form action="" id="formeditor" method="post">  <div class="box-body">
		<div class="row">';
        
        foreach ($structure as $k => $v) {
            if (!empty($v['sidebar']))
                $sidebar = 1;
        }
        
        
        if ($sidebar == 1) {
            echo '<div class="col-md-9">';
            foreach ($structure as $k => $v) {
                if (empty($v['sidebar']))
                    $this->addformelem($k, $v, $vals);
            }
            
            echo '</div><div class="col-md-3">';
            foreach ($structure as $k => $v) {
                if (!empty($v['sidebar']))
                    $this->addformelem($k, $v, $vals);
            }
            
            echo '</div>';
            
        }
        
        else {
            
            echo '<div class="col-md-12">';
            foreach ($structure as $k => $v) {
                $this->addformelem($k, $v, $vals);
            }
            
            echo '</div>';
            
            
            
        }
       
        
        echo '</div></div><div class="box-footer"><div class="row"><div class="col-md-8">';
             
              
         if(!empty($this->previewUrl($vals))) echo  '<div class="input-group input-group-sm">
           
                      <span class="input-group-addon">Aperçu de la page</span>
                 
                <input type="text" disabled="disabled" value="'.$this->previewUrl($vals).'" class="form-control">
                
                  <span class="input-group-btn">
                      <button onclick="window.open(\'..'.$this->previewUrl($vals).'\')"  type="button" class="btn btn-default btn-flat"><i class="material-icons">&#xE89D;</i></button>
                    </span>
                  
              </div>';  
        
         
         echo  '</div><div class="col-md-4"><button type="submit" class="btn btn-primary pull-right">Sauver</button></div></div>';          
       
                    
                    
        echo '</div></form>';
    }
    
    public function showform()
    {
        if (!empty($this->structure)) {
            $structure = $this->structure;
            $doc       = $this->doc;
            $backupdoc = $this->backupdoc;
            $vals      = array();
            if (file_exists($doc)) {
                $d    = file_get_contents($doc);
                $vals = json_decode($d, true);
            }
            
            
            
            
            $this->createform($structure, $vals);
        }
    }
    
    
    public function showdatas()
    {
        if (!empty($this->structure)) {
            $structure = $this->structure;
            $doc       = $this->doc;
            $backupdoc = $this->backupdoc;
            $vals      = array();
            if (file_exists($doc)) {
                $d    = file_get_contents($doc);
                $vals = json_decode($d, true);
            }
            
            return $vals;
        }
    }
}

class swcnt_sblog extends swcnt_sforms
{
    private $actpage;
    private $actlang;
    public $mod;
    public $page;
    public $lang;
    public $pagetitle;
    public $pubtype;
    
    function __construct()
    {
        

        $this->pubtype = '';
        /* $pubtype = 'blog','catalog','portfolio', 'jobs', 'team', 'page' */
        if (!empty($_GET['type'])) $this->pubtype = trim($_GET['type']);
        
        global $swcnt_post;
        global $swcnt_options;

        if(!empty($swcnt_post[$this->pubtype])) {
        $pubtype = $this->pubtype;
        $this->ldb		 =  $swcnt_options['db_location'];   
        $swcnt_blog      =  $swcnt_post[$this->pubtype];
        $swcnt_languages = $swcnt_options['languages'];
        $actmode         = '';
        $actpage         = '';
        $actlang         = '';
        $actpostId       = date('ymdHis');
        if (isset($_GET['lang']) and in_array($_GET['lang'], $swcnt_languages))
            $actlang = htmlentities($_GET['lang']);
        else if (isset($swcnt_languages[0]))
            $actlang = $swcnt_languages[0];
        
        
        if (isset($_GET['item']))
            $actpage = htmlentities($_GET['item']);
        if (isset($_GET['item']) and !empty($_GET['post']))
            $actpostId = htmlentities($_GET['post']);
        
        
        if (!empty($swcnt_blog['sw_blocks'])) {
            $structure = $swcnt_blog['sw_blocks'];
            if(!empty($swcnt_blog['sw_url_preview'])) $url_preview = $swcnt_blog['sw_url_preview']; else $url_preview = '';
            
            if (!empty($structure['category']['options'])) {
                $sblog_cat = new swcnt_sblog_cat($pubtype);
                $xdatas    = $sblog_cat->showdatas();
                if(!empty($xdatas['elems'])) {
                foreach ($xdatas['elems'] as $v) {
                    $structure['category']['options'][$v['slug']] = $v['name'];
                	}
                }
            }
            
            /* créer les dossiers DB */

            if (!file_exists($this->ldb.'' . $pubtype)) {
                mkdir($this->ldb.'' . $pubtype, 0777, true);
            }
            if (!file_exists($this->ldb.'backup/')) {
                mkdir($this->ldb.'backup/', 0777, true);
            }
            if (!file_exists($this->ldb.'backup/' .date("Ym").'/'. $pubtype)) {
                mkdir($this->ldb.'backup/' .date("Ym").'/'. $pubtype, 0777, true);
            }
            if (!file_exists($this->ldb.'backup/'.date("Ym").'/'. $pubtype)) {
                mkdir($this->ldb.'backup/'.date("Ym").'/'. $pubtype, 0777, true);
            }


            $doc                 = $this->ldb.'' . $pubtype . '/' . $actpostId . '.source.json';
            $backupdoc           = $this->ldb.'backup/'.date("Ym").'/' . $pubtype . '/' . $actpostId . '.' . date("Y-m-d H:i") . 'source.json';
            $this->structure     = $structure;
            $this->urlPreview    = $url_preview;
            $this->doc           = $doc;
            $this->backupdoc     = $backupdoc;
            $doctags             = $this->ldb.'' . $actlang . '_tags_list.source.json';
            $backupdoctags       = $this->ldb.'backup/'.date("Ym").'/' . $actlang . '_tags_list.' . date("Y-m-d H:i") . 'source.json';
            $this->doctags       = $doctags;
            $this->backupdoctags = $backupdoctags;



        }
        
        $this->postId = $actpostId;
        $this->page   = $actpage;
        $this->lang   = $actlang;
        }
    }
}








class swcnt_index
{
	 
    function __construct($pubtype='blog',$lang='en')
    {
	   if(file_exists('../db.conf.php'))  include ('../db.conf.php');
	    
	    global $swcnt_options;
        $swcnt_languages     = $swcnt_options['languages'];
		$this->ldb			 =  $swcnt_options['db_location']; 
		$this->pubtype		= $pubtype; /* blog */	
		$this->iposts		= $pubtype.'_'.$lang.'_posts';
	    
	    
		try{
	      
		    if(empty($dbconnect)) {
		
			
				$pdo = new PDO('sqlite:../sw-db/db.sqlite');
		
			} else {
				
		
				
				$pdo = new PDO('mysql:host='.$dbconnect['server'].';dbname='.$dbconnect['dbname'], $dbconnect['user'], $dbconnect['pass']);
				
			}
			
			
	    
	    
	    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
		} catch(Exception $e) {
		    echo "Impossible d'accéder à la base de données : ".$e->getMessage();
		    die();
		}
		
		$pdo->query("CREATE TABLE IF NOT EXISTS ".$this->iposts." ( 
		    id            	BIGINT(16) PRIMARY KEY ,
		    title         	VARCHAR( 250 ),
		    urltxt         	VARCHAR( 250 ),
		    lang         	VARCHAR( 16 ),
		    status        	TINYINT,
		    body          	TEXT,
		    pubdate         DATETIME,
		    category		VARCHAR( 32 ),
		    keyword			VARCHAR( 32 ),
		    position		SMALLINT,
		    cover			VARCHAR( 250 )
			);");

		$this->pdo = $pdo;	
	}
	
	
	function savePositions() {
		
	
		
		if(!empty($_POST['elposition'])) {  
			  $idpos = 1;
			  $elposition = array();  
			  $pubtype = $this->pubtype;		  
			
				foreach($_POST['elposition'] as $elkey => $elpos) {	
				
					$values = array($idpos,$elkey);
					$stmt = $this->pdo->prepare("UPDATE ".$this->iposts." SET position = ? WHERE id = ?");
					$stmt = $stmt->execute($values);	
					$idpos++;	 
				
			}  
		}
	}
	
	function update($d) {	
		
		$default = array(
						'id'  		=> date("ymdHis"),
						'title'  	=> '',
						'urltxt'  	=> '',
						'status'  	=> 0,
						'lang'  	=> 'en',
						'body'  	=> ' ',
						'pubdate'=> date("Y-m-d H:i:s"),
						'category'  => '',
						'keyword'  	=> '',
						'position'  => 0,
						'cover'		=> '',
		);
		
		$pubtype = $this->pubtype;	
		$stmt = $this->pdo->prepare("SELECT * FROM  ".$this->iposts." WHERE id = ?"  ); 
		$stmt->execute(array($d['id']));
		$result = $stmt->fetchAll();
		foreach($result as $r) {
			foreach($r as $rk => $rv) {	
					$default[$rk] = $rv;	
			}		
		}
		foreach($d as $dk => $dv) {	
					$default[$dk] = $dv;	
		}		
		$stmt = $this->pdo->prepare("REPLACE INTO ".$this->iposts." (id, title, urltxt, status, lang, body, pubdate, category, keyword, position, cover) 
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");	
		
		$values = array(
		$default['id'], 
		$default['title'], 
		$default['urltxt'], 
		$default['status'], 
		$default['lang'], 
		$default['body'], 
		$default['pubdate'], 
		$default['category'], 
		$default['keyword'], 
		$default['position'], 
		$default['cover']);	
		
		
		$result = $stmt->execute($values);
		}
	
	
	
		function read($ordermode=false,$limit=1000) {
			$pubtype = $this->pubtype;
			
			if($ordermode) {
				$stmt = $this->pdo->prepare("SELECT * FROM  ".$this->iposts." ORDER BY position ASC LIMIT $limit" ); 
			} else {
				$stmt = $this->pdo->prepare("SELECT * FROM  ".$this->iposts."  ORDER BY pubdate DESC LIMIT $limit" ); 	
			}
			
			
			$stmt->execute(array());
			$result = $stmt->fetchAll();
			return $result;
			
	}

	
	
}

class swcnt_sblog_cat extends swcnt_sforms
{
    private $actpage;
    private $actlang;
    public $mod;
    
    public $page;
    
    public $lang;
    
    public $pagetitle;
    
    function __construct()
    {       
        $pubtype = '';
          if (!empty($_GET['type'])) $pubtype = trim($_GET['type']);
        /* $pubtype = 'blog','catalog','portfolio', 'page' */
        
        $this->pubtype = $pubtype;

        global $swcnt_post;
        global $swcnt_options;

        if(!empty($swcnt_post[$this->pubtype])) {
            $pubtype = $this->pubtype;
            $this->ldb		 =  $swcnt_options['db_location'];   
            $swcnt_blog      =  $swcnt_post[$this->pubtype];
            $swcnt_languages = $swcnt_options['languages'];
            $actmode         = '';
            $actpage         = '';
            $actlang         = '';
            $actpostId       = date('ymdHis');
            if (isset($_GET['lang']) and in_array($_GET['lang'], $swcnt_languages))
                $actlang = htmlentities($_GET['lang']);
            else if (isset($swcnt_languages[0]))
                $actlang = $swcnt_languages[0];
            if (isset($_GET[$pubtype]))
                $actpage = htmlentities($_GET[$pubtype]);
            
            
            
        
            $swcnt_blogcatform = array(
                'elems' => array(
                    'label' => $swcnt_blog['sw_cat_title'],
                    'type' => 'list',
                    'placeholder' => '',
                    'submenu' => array(
                        'slug' => array(
                            'label' => _tr('Slug name'),
                            'type' => 'input_txt',
                            'placeholder' => 'business,news,livestyle,...'
                        ),
                        'name' => array(
                            'label' => _tr('Name'),
                            'type' => 'input_txt',
                            'placeholder' => 'Business,News,Livestyle,...'
                        ), 
                        'description' => array(
                            'label' => _tr('Description'),
                            'type' => 'textarea',
                            'placeholder' => 'Blablabla'
                        )

                    )
                )
            );
            if (!empty($swcnt_blogcatform)) {
               
               
               
                $structure       = $swcnt_blogcatform;
                $doc             = $this->ldb.'' . $pubtype . '/' . $actlang . '_cats.source.json';
                $backupdoc       = $this->ldb.'backup/'.date("Ym").'/' . $pubtype . '/' . $actlang . '_cats' . date("Y-m-d H:i") . 'source.json';
                $this->structure = $structure;
                $this->doc       = $doc;
                $this->backupdoc = $backupdoc;
          
          
          
            }
            
            $this->page = $actpage;
            $this->lang = $actlang;
        }
    }

}
















class swcnt_smod extends swcnt_sforms
{
    private $actpage;
    private $actlang;
    public $mod;
    
    public $page;
    
    public $lang;
    
    public $pagetitle;
    
    function __construct()
    {
        global $swcnt_tree;
        global $swcnt_options;
        global $swcnt_plugins;
        
        $this->ldb			 =  $swcnt_options['db_location'];   
        $swcnt_pluglist = array();
        foreach ($swcnt_plugins as $pname) {
            include_once 'plugins/' . $pname . '/conf.php';
            
        }
        
        $swcnt_languages = $swcnt_options['languages'];
        $actmode         = '';
        $actpage         = '';
        $actlang         = '';
        $pagetitle       = '';
        $listmods        = array(
            'editor',
            'users',
            'setting',
            'update',
            'item',
            'uploader',
            'contact',
            'newsregisters',
            'translate',
            'plugin'
        );
        if (isset($_GET['lang']) and in_array($_GET['lang'], $swcnt_languages))
            $actlang = htmlentities($_GET['lang']);
        else if (isset($swcnt_languages[0]))
            $actlang = $swcnt_languages[0];
        foreach ($_GET as $k => $l) {
            if (in_array($k, $listmods)) {
                $actmode = $k;
                if ($actmode == 'editor')
                    $actpage = $l;
            }
        }
        
        if ($actlang == '')
            $actlang = 'nolang';
        if ($actpage != '' and !empty($swcnt_tree[$actpage]['sw_title'])) {
            $pagetitle = $swcnt_tree[$actpage]['sw_title'];
        }
        
        if ($actpage != '' and !empty($swcnt_tree[$actpage]['sw_blocks'])) {
            $structure           = $swcnt_tree[$actpage]['sw_blocks'];
            $doc                 = $this->ldb.'' . $actlang . '_' . $actpage . '.source.json';
            $backupdoc           = $this->ldb.'backup/'.date("Ym").'/' . $actlang . '_' . $actpage . '.' . date("Y-m-d H:i") . 'source.json';
            $doctags             = $this->ldb.'' . $actlang . '_tags_list.source.json';
            $backupdoctags       = $this->ldb.'backup/'.date("Ym").'/'. $actlang . '_tags_list.' . date("Y-m-d H:i") . 'source.json';
            $this->structure     = $structure;
            $this->doc           = $doc;
            $this->backupdoc     = $backupdoc;
            $this->doctags       = $doctags;
            $this->backupdoctags = $backupdoctags;
            $this->actpage       = $actpage;
        }
        
        $this->tree      = $swcnt_tree;
        $this->mod       = $actmode;
        $this->page      = $actpage;
        $this->lang      = $actlang;
        $this->listmods  = $listmods;
        $this->pagetitle = $pagetitle;
        $this->plugins   = $swcnt_pluglist;
    }
    
    public function listpages()
    {
        $actpage = $this->actpage;
        $tree    = $this->tree;
        $lang    = $this->lang;
        foreach ($tree as $t => $v) {
            $a_ = ($actpage == $t) ? 'class="active"' : '';
            echo ' <li ' . $a_ . '><a href="?editor=' . $t . '&lang=' . $lang . '"><i class="material-icons">&#xE86F;</i> ' . $v['sw_title'] . '</a></li>';
        }
    }
    
    public function listplugins()
    {
	    
	    
        if (!empty($_GET['plugin']))
            $this->plugact = $_GET['plugin'];
        else
            $this->plugact = '';
        if (!empty($_GET['plugpage']))
            $this->plugpage = $_GET['plugpage'];
        else
            $this->plugpage = '';
        $plugins = $this->plugins;
        foreach ($plugins as $t => $v) {
?>
			 <li class="treeview <?php
            echo ($this->plugact == $t) ? 'active' : '';
?>">
              <a href="?plugin=<?php
            echo $t . '&lang=' . $this->lang;
?>"><i class="material-icons">&#xE1BD;</i> <span><?php
            echo $v['name'];
?> </span> <?php
            if (!empty($v['pages'])) {
?><i class="fa fa-angle-left pull-right"></i><?php
            }
?></a>
              <?php
            if (!empty($v['pages'])) {
?>
		           <ul class="treeview-menu">   
		              <?php
                foreach ($v['pages'] as $tpage => $vpage) {
                    $a_ = ($this->plugpage == $tpage) ? 'class="active"' : '';
                    echo ' <li ' . $a_ . '><a href="?plugin=' . $t . '&lang=' . $lang . '&plugpage=' . $tpage . '"><i class="material-icons">&#xE146;</i> ' . $vpage . '</a></li>';
                }
?>
		                 </ul>
	            <?php
            }
?>
              
              
            </li>
			<?php
        }
    }
}







?>