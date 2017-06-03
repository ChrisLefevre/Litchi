 </div>
 
 
 
 
 <div id="modal-temp" class="modal fade modal-primary" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
 
 <form action="" method="post">
     <div class="modal-dialog">
         <div class="modal-content">
   
 
             <div class="modal-header text-center">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
 
                 
             <input type="hidden"  id="form_temp_source" name="form_temp_source" class="form-control">
 
            <div class="form-group"> 
             <textarea id="form_temp_text" name="form_temp_text"  rows="9" class="form-control" placeholder=""></textarea> </div>
            
                 <button type="button" onclick="GetTempFormModal()" name="delete_user" value="1" class="btn btn-outline"><?php echo _tr("Save"); ?></button>
             </div>
         </div>
     </div>
 </form>
 </div>
 
 
 
 
 
	 <footer class="main-footer">
	 <div class="pull-right hidden-xs">
          Create by <a target="_blank" href="https://glose.media">Christophe Lefevre</a> with <i class="fa fa-heart text-red"></i>  
        </div>

        <strong>Copyright &copy; <?php echo date("Y"); ?> <a target="_blank" href="https://glose.media/swalize/">Swalize 2.0a</a>.</strong> All rights reserved.
      </footer>
     </div>
	<script src="assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/plugins/summernote-master/dist/summernote.js"></script>
    <script type="text/javascript" src="assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script type="text/javascript" src="assets/plugins/external/jquery.hotkeys.js"></script>
    <script type="text/javascript" src="assets/plugins/lightbox/ekko-lightbox.min.js"></script>
    
    <script src="assets/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
    <script src="assets/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
    <script src="assets/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
    <script src="assets/plugins/rowsorter/jquery.rowsorter.js" type="text/javascript"></script>
     <script src="assets/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="assets/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
   <script src="assets/plugins/tagsinput/bootstrap-tagsinput.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/plugins/tinymce/tinymce.min.js"></script>
  <script type="text/javascript" src="assets/plugins/tinymce/jquery.tinymce.min.js"></script>
<script type="text/javascript">




$('.tinymce').tinymce({
   theme: "modern",
    paste_data_images: true,
   plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste jbimages  imagetools"
    ],
    language : "fr_FR",
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages  media ",
     relative_urls: true,
      image_advtab: true,

})

</script>
<script type="text/javascript">
	    

	    
	$(function () {

        $("[data-mask]").inputmask(); 
        $( ".dtable > tbody > tr:last-child" ).each(function(index, value) { 
	        var tempLines = [];
		    var temptableelem = $(this);
		    tableId = "#"+temptableelem.parent().parent().parent().attr("id");  
		    var vid = $(this).attr("vid");
		    var vnid = $(this).attr("id");
			for(i=vid;i<200;i++) {
				tempLines[i]=temptableelem. html();    
			}
		    
		    $(this).remove();   
		    $(tableId+" .addelemdtable").click(function(index, value) { 
			   var newString = tempLines[vid];
			   newString = '<tr vid="'+vid+'" id="'+vnid+'">'+newString+'</tr>';  
			   newString = newString.toString();
			   var re = new RegExp('{tempID}','g');
			   newString2 = newString.replace(re, vid);
			   tableId =  "#"+ $(this).parent().attr("id");
			 	$( tableId+" > .dtable > tbody").append(newString2); 
			     vid++;
			   }); 
	        
		   });
	});
        

</script>   
<script type="text/javascript">
$(".dtable").rowSorter({
		handler: "td.movable",
		onDrop: function(tbody, row, index, oldIndex) {
			$(tbody).parent().find("tfoot > tr > td").html((oldIndex + 1) + ". row moved to " + (index + 1));
			
			
			$(tbody).find('tr td.movable input').each(function(index, value) { 
				$(this).val(index); 
			
			});	
		}
	});

	
</script>
        
<script type="text/javascript">

	$(".dtablepub").rowSorter({
		handler: "td.movable",
		onDrop: function(tbody, row, index, oldIndex) {
			$(tbody).parent().find("tfoot > tr > td").html((oldIndex + 1) + ". row moved to " + (index + 1));
			
			
			$(tbody).find('tr td.movable input').each(function(index, value) { 
				$(this).val(index); 
			
			});	
		}
	});


</script>  
    
    <script type="text/javascript">
      $(function () {
        $("#datatable").dataTable( {
    "pageLength": 25,
     "order": [[ 0, "desc" ]]
});
        });
    </script>
    
    
    <script src="assets/dist/js/app.js" type="text/javascript"></script>  
</body>
</html>