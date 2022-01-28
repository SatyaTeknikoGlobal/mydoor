<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<div class="after-add-more">
  
    <div class="col-md-4">                                
        <div class="form-group">
            <label class="control-label">Logger Name</label>
            <input maxlength="200" type="text" class="form-control" placeholder="Enter Logger Name" name="lg_name[]" />
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Logger Serial Number</label>
            <input maxlength="200" type="text" class="form-control" placeholder="Enter Serial Number" name="lg_sl[]" />
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Modem Serial Number</label>
            <input maxlength="200" type="text" class="form-control" placeholder="Enter Modem Serial Number" name="lg_md_sl[]" />
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group change">
            <label for="">&nbsp;</label><br/>
            <a class="btn btn-success add-more">+ Add More</a>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
    $("body").on("click",".add-more",function(){ 
        var html = $(".after-add-more").first().clone();
      
        //  $(html).find(".change").prepend("<label for=''>&nbsp;</label><br/><a class='btn btn-danger remove'>- Remove</a>");
      
          $(html).find(".change").html("<label for=''>&nbsp;</label><br/><a class='btn btn-danger remove'>- Remove</a>");
      
      
        $(".after-add-more").last().after(html);
      
     
       
    });

    $("body").on("click",".remove",function(){ 
        $(this).parents(".after-add-more").remove();
    });
});
</script>