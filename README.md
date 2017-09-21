# [Litchi Blog](https://glose.media/litchi/) 


[Litchi Blog](https://glose.media/litchi/) est un CMS ultra léger pour installer un blog rapidement.

Litchi est construit sur le Framework Swalize 2.0 Beta de [Glose Media](https://glose.media/) et le Theme Bootstrap 4 Alpha [Clean Blog](http://startbootstrap.com/template-overviews/clean-blog/).

Le Swalize engine permet de stocker les publications dans des fichiers json pour obtenir plus de flexibilité. Ainsi, SQL n’est utilisé que pour améliorer l’indexation des données. 

Ce Blog a été testé en PHP 5.6 et PHP 7.0. Il nécéssite Apache2 et SQlite. 

C’est un projet Open Source disponible sur GitHub sur lequel vous pouvez contribuer.


La démo du blog est visible [ici](https://staging.glose.media/litchi/)


## Comment débuter


Pour commencer, vous devez télécharger la dernière version de Litchi sur GitHub

* Clonez le repo: `git clone https://github.com/ChrisLefevre/Litchi.git`
* Ou en téléchargeant le dossier [Litchi](https://github.com/ChrisLefevre/Litchi/archive/master.zip) sur votre PC 

Ensuite, vous devez accéder à l’admin du blog qui se situe dans le dossier my-publisher, par exemple http://localhost/my-publisher/. 

Connectez-vous avec le compte par défaut :
* Email : hello@litchi.blog
* Password : TheBlog_Is_Perfect!

Créez directement un nouvel utilisateur admin et sauvez. Ensuite, détruisez le compte précédent et reconnectez-vous !

Dans « Informations générales », vous devez préciser l’adresse du site (avec un / à la fin)

C’est terminé, le site est installé. 

Grâce au Swalize Engine, il n’est pas obligatoire de relier le blog à une base de données. Mais si votre blog ou wiki risque d’atteindre une centaine de pages, je vous recommande de vous connecter à une base de données MySQL. 

Il vous suffit d’ajouter les paramètres comme ceci dans db.conf.php 

 `$dbconnect = array(
	'server' => 'localhost',
	'dbname' => 'litchi',
	'user' => 'root',		
	'pass' => 'root'	
	);`

## Vous avez repéré des bugs ?

[Ouvrir un nouveau problème](https://github.com/ChrisLefevre/Litchi/issues) sur GitHub ou contactez-moi sur [Glose Media](http://glose.media/).

## Créateur

Litchi est créé et maintenu par **[Chris Lefevre](http://glose.media/)** .

* https://twitter.com/chrislefevre


## Copyright et License

Copyright 2017 Glose Media.


