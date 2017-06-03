<?php
$swcnt_form    = array();
$swcnt_plugins = array();
$swcnt_post    = array();

$swcnt_options = array(
    /* liste des langues  */
    'languages' => array(
        'fr'
    ),
    'languages_names' => array(
        'fr' => "Français",
        'en' => "English",
        'nl' => "Nederlands",
        'de' => "Deutsch"
    ),
	/* true si besoin de débugguer */
	'debug' => false,
    /* Clé de sécurité, doit être modifiée */
    'secure_key' => 'lichies763',
    /* true si url_rewiting */
    'urlrewriting' => true,
    /* your email */
    'contact_email' => '',
    /* complex crypt mode  1 or 0 */
    'crypt' => 0,
    /* db directory */
    'db_location' => '../sw-db/'
);

/* référencement des plugins  */
$swcnt_plugins = array();

/* liste et structure des blogs  */
$swcnt_post['blog'] = array(
    'sw_title' => 'Articles',
    'sw_cat_title' => 'Catégories',
    'sw_singleitem_name' => 'Publication',
    'sw_singleitem_title' => 'Nouvelle publication',
    'sw_items_title' => 'Toutes les publications',
    'sw_url_preview' => 'article/{title}',
    'sw_ordermode' => false,
    'sw_blocks' => array(
        'title' => array(
            'label' => 'Titre',
            'type' => 'input_txt',
            'placeholder' => ''
        ),
        'headline' => array(
            'label' => 'Mise en avant',
            'type' => 'input_txt',
            'placeholder' => ''
        ),
        
        'article' => array(
            'label' => 'Article',
            'type' => 'blogarea',
            'placeholder' => ''
        ),
        
        'pubdate' => array(
            'label' => 'Date de publication',
            'type' => 'datetime',
            'placeholder' => '',
            'default' => date("Y-m-d H:i:s"),
            'sidebar' => true
        ),
        
        'cover' => array(
            'label' => 'Couverture',
            'type' => 'picture',
            'placeholder' => '',
            'sidebar' => true
        ),
        'status' => array(
            'label' => 'État',
            'type' => 'select',
            'placeholder' => '',
            'options' => array(
                1 => 'Publié',
                0 => 'Brouillon',
                2 => 'Effacé'
            ),
            'sidebar' => true
        ),

        'author' => array(
            'label' => 'Auteur',
            'type' => 'user',
            'placeholder' => '',
            'sidebar' => true
        ),

        'category' => array(
            'label' => 'Catégorie',
            'type' => 'select',
            'placeholder' => '',
            'options' => array(
                '' => 'Aucune'
            ),
            'sidebar' => true
        )    
    )
);

/* Formulaires pour le site et admin  */
$swcnt_form[] = array();

/* liste des pages et blocks  */
$swcnt_tree['siteinfos'] = array(
    'sw_title' => 'Informations générales',
    'sw_blocks' => array(
        'title' => array(
            'label' => 'Titre',
            'type' => 'input_txt',
            'placeholder' => 'Mon site'
        ),

        'baseline' => array(
            'label' => 'Baseline',
            'type' => 'input_txt',
            'placeholder' => 'Un site cool pour tout le monde'
        ),

        'site_url' => array(
            'label' => 'URL du Site',
            'type' => 'input_txt',
            'placeholder' => 'http://mysite.com'
        ),

        'cover' => array(
            'label' => 'Couverture',
            'type' => 'picture',
            'placeholder' => '',
            'sidebar' => true
        ),

        'author' => array(
            'label' => 'Auteur',
            'type' => 'input_txt',
            'placeholder' => 'John Doo'
        ),   

        'navigation' => array(
            'label' => 'Navigation',
            'type' => 'list',
            'placeholder' => '',
            'submenu' => array(
                'name' => array(
                    'label' => 'Nom de la page',
                    'type' => 'input_txt',
                    'placeholder' => 'Mon page'
                ),
                'url' => array(
                    'label' => 'URL',
                    'type' => 'input_txt',
                    'placeholder' => 'mapage ou http://... si externe'
                ),
                'type' => array(
                    'label' => 'Type de lien',
                    'type' => 'select',
                    'options' => array(
                        'intern' => 'interne',
                        'extern' => 'externe'
                    )
                )             
            )
        ),

        'cover' => array(
            'label' => 'Couverture',
            'type' => 'picture',
            'placeholder' => '',
            'sidebar' => false
        ),   

        'sp_1s' => array(
            'label' => 'Social',
            'type' => 'separation'         
        ),  

        'social' => array(
            'label' => 'Icones du footer',
            'type' => 'list',
            'placeholder' => '',
            'submenu' => array(
                'type' => array(
                    'label' => 'Plateforme',
                    'type' => 'select',
                    'options' => array(
                        'facebook' => 'Facebook',
                        'twitter' => 'Twitter',
                        'linkedin' => 'Linkedin',
                        'github' => 'Github',
                        'youtube' => 'Youtube',
                        'instagram' => 'Instagram'
                    )
                ),
                'url' => array(
                    'label' => 'URL',
                    'type' => 'input_txt',
                    'placeholder' => 'http://'
                )             
            )
        )
           
    )
);



$swcnt_tree['about'] = array(
    'sw_title' => 'A Propos',
    'sw_blocks' => array(
        'title' => array(
            'label' => 'Titre',
            'type' => 'input_txt',
            'placeholder' => 'About Me'
        ),
        'baseline' => array(
            'label' => 'Sous titre',
            'type' => 'input_txt',
            'placeholder' => 'This is what I do.'
        ),
        
        'cover' => array(
            'label' => 'Couverture',
            'type' => 'picture',
            'placeholder' => '',
            'sidebar' => true
        ),
        'text' => array(
            'label' => 'Texte',
            'type' => 'htmlarea',
            'placeholder' => ''
        )
        
    )
);


$swcnt_tree['contact'] = array(
    'sw_title' => 'Contact Form',
    'sw_blocks' => array(
        
              'title' => array(
            'label' => 'Titre',
            'type' => 'input_txt',
            'placeholder' => 'Contact Us!'
        ),
        'baseline' => array(
            'label' => 'Sous titre',
            'type' => 'input_txt',
            'placeholder' => 'This is what I do.'
        ),
        
        'cover' => array(
            'label' => 'Couverture',
            'type' => 'picture',
            'placeholder' => '',
            'sidebar' => true
        ),
        'text' => array(
            'label' => 'Texte',
            'type' => 'htmlarea',
            'placeholder' => ''
        ),
        
        'sp_2s' => array(
            'label' => 'E-mail',
            'type' => 'separation'
            
        ),
        'mail_destinate' => array(
            'label' => 'Destinataire',
            'type' => 'input_txt',
            'placeholder' => 'you@provider.com'
        )
    )
);

?>
