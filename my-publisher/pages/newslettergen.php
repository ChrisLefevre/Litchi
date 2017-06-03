<?php


//$smod -> save();


?>

<section class="content-header">
    <h1><?php echo _tr("Newsletter"); ?> <small><?php echo _tr("New template"); ?> </small></h1>

    <ol class="breadcrumb">
        <li>
            <a href="./"><?php echo _tr("Admin"); ?> </a>
        </li>

        <li class="active"><?php echo _tr("Newsletter"); ?> </li>
    </ol>
</section>




<section class="content">
    <div class='box box-primary'>
        <div class='box-header'>
            <h3 class='box-title'><?php echo $smod->pagetitle; ?> <small><?php echo $smod -> lang; ?></small></h3>
        </div>

        <div class='box-body'>
            <form action="" id="formeditor" method="post">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Photo d'illustration</label>
                                    </div>

                                    <div class="col-md-6">
                                        <select id="block_illu_top" name="block_illu_top" class="form-control">
                                            <option selected value="1">
                                                Oui
                                            </option>

                                            <option value="0">
                                                Non
                                            </option>

                                            <option value="2">
                                                2 illustrations
                                            </option>

                                            <option value="3">
                                                3 illustrations
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    
                     <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Article à la une</label>
                                    </div>

                                    <div class="col-md-6">
                                        <select id="block_news_top" name="block_news_top" class="form-control">
                                            <option selected value="1">
                                                1
                                            </option>

                                            <option value="2">
                                                2
                                            </option>

                                            <option value="3">
                                                3 
                                            </option>

                                            <option value="4">
                                                4
                                            </option>
                                            
                                            <option  value="0">
                                                Aucun
                                            </option>
                                            
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>





        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Articles illustrés</label>
                                    </div>

                                    <div class="col-md-6">
                                        <select id="block_illu_posts" name="block_illu_posts" class="form-control">
                                            <option selected value="1">
                                                1
                                            </option>

                                            <option value="2">
                                                2
                                            </option>

                                            <option value="3">
                                                3 
                                            </option>

                                            <option value="4">
                                                4
                                            </option>
                                            <option value="5">
                                                5 
                                            </option>

                                            <option value="6">
                                                6
                                            </option>                                            
                                            
                                            <option  value="0">
                                                Aucun
                                            </option>
                                            
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Recommandation</label>
                                    </div>

                                    <div class="col-md-6">
                                        <select id="block_recommandation" name="block_recommandation" class="form-control">
                                            <option selected value="1">
                                                1
                                            </option>

                                            <option value="2">
                                                2
                                            </option>
                                        
                                            
                                            <option  value="0">
                                                Aucun
                                            </option>
                                            
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




 <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Générer une newsletter</label>
                                    </div>

                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary">Générer</button>                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>










        <div class="row">
                        <div class="col-md-12">


 <ul class="nav nav-tabs push" data-toggle="tabs">
                    <li class="active"><a href="#" onclick="$('#btag1').hide(); $('#btag2').show(); return false">Code à récupérer</a></li>
                    <li><a href="" onclick="$('#btag2').hide(); $('#btag1').show(); return false">Aperçu de la newsletter</a></li>
                    </ul>



<div class="form-group" id="btag1" style="display: none">
	
	<?php //print_r($_POST); ?>
	<textarea id="inputarticle" style="height: 1000px"  name="article_pedit" class="form-control" rows="50"  placeholder=""><?php		
		
		
		// Array ( [block_illu_top] => 1 [block_news_top] => 1 [block_illu_posts] => 1 [block_recommandation] => 1
		include 'html_templates/nletter_header.html';
		
					
			for($i=0;$i<intval($_POST['block_illu_top']); $i++) {
				
				include 'html_templates/nletter_illutop.html';
			}
			
					
			for($i=0;$i<intval($_POST['block_news_top']); $i++) {
				
				include 'html_templates/nletter_article.html';
			}
			
				for($i=0;$i<intval($_POST['block_illu_posts']); $i++) {
			if($i==0 or $i==2 or $i==4 or $i==6)	include 'html_templates/nletter_article_gauche.html';
				else
				include 'html_templates/nletter_article_droite.html';
			}

	for($i=0;$i<intval($_POST['block_recommandation']); $i++) {
				
				include 'html_templates/nletter_artilce_reco.html';
	
			}

			
		
				
		
		
		include 'html_templates/nletter_footer.html';
		
		
		
		
		
		?></textarea> </div>






<div class="form-group" id="btag2" >
	
	<?php //print_r($_POST); ?>
	<textarea id="inputarticle" onclick="this.select()" name="article_preview" readonly=""  class="form-control tinymce" rows="100"  placeholder=""><?php		
		
		
		// Array ( [block_illu_top] => 1 [block_news_top] => 1 [block_illu_posts] => 1 [block_recommandation] => 1
		include 'html_templates/nletter_header.html';
		
					
			for($i=0;$i<intval($_POST['block_illu_top']); $i++) {
				
				include 'html_templates/nletter_illutop.html';
			}
			
					
			for($i=0;$i<intval($_POST['block_news_top']); $i++) {
				
				include 'html_templates/nletter_article.html';
			}
			
				for($i=0;$i<intval($_POST['block_illu_posts']); $i++) {
			if($i==0 or $i==2 or $i==4 or $i==6)	include 'html_templates/nletter_article_gauche.html';
				else
				include 'html_templates/nletter_article_droite.html';
			}

	for($i=0;$i<intval($_POST['block_recommandation']); $i++) {
				
				include 'html_templates/nletter_artilce_reco.html';
	
			}

			
		
				
		
		
		include 'html_templates/nletter_footer.html';
		
		
		
		
		
		?></textarea> </div>


                    
                    
                  </div>
                    </div>        
                    
                    
                    
                    
                    
                    
                    
                </div>
                
                
                               
            </form>
        </div>
    </div>
</section>







