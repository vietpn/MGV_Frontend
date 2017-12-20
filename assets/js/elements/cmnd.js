$(document).ready(function () {
       
   
        // init images click
        $('#openUploadDialogFront').click(function(){ $('#imgFrontSide').trigger('click'); });
        $('#openUploadDialogBack').click(function(){ $('#imgBackSide').trigger('click'); });
        
        // add event for images id
         $("#imgFrontSide").change(function(){
				user.readURL(this, 'imagesFrontSide');
				$("#imagesFrontSide").css("display", "block"); 
		});
         $("#imgBackSide").change(function(){
			user.readURL(this, 'imagesBackSide');
			$("#imagesBackSide").css("display", "block"); 
		});
         
         
  

   
});

var user = {
    
    readURL: function(input, imgId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#' + imgId).attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
};
