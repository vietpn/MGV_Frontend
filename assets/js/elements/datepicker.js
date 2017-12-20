$(function () {
		$('.cmnd-timepicker').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true,
			orientation: "top auto",
			endDate: new Date(),
			disableTouchKeyboard: true,
			Readonly: true
	});

	
	$('.has-tooltip').tooltip();
	
	$('.has-datepicker').datepicker({
		format: 'dd/mm/yyyy',
		autoclose: true,
		orientation: "top auto",
		endDate: new Date(),
		disableTouchKeyboard: true,
	    Readonly: true
	});
	$('input[name="fdate"]').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        orientation: "top auto",
		endDate: new Date(),
		disableTouchKeyboard: true,
	    Readonly: true
	}).on('changeDate', function(){
	        var toDate = $(this).closest('form').find('input[name="tdate"]');
	        var fromDateValue = $(this).val().split('/').reverse().join('/');
	        toDate.datepicker('setStartDate', new Date(fromDateValue));
	    });

	$('.pagination li a').click(function(){
        var page = $(this).text();
        if ($(this).parent().hasClass('next') || $(this).parent().hasClass('prev')) {
        	page = $(this).attr('href').split("/").slice(-1)[0];
		}
        $('[name=page]').val(page);
		
		var pathname = window.location;
		
		$("form[action='" + pathname + "']").submit();
        
        return false;
    });

});