(function($){

	'use_strict';

	$("#globo_export_download_btn").click(function() { // click the link to download
	    // lock(); // start indicator

	    $.ajax({
			type: "GET",				
			url: gei.ajaxurl,
			data: {
				'download_type': $('#globo_export_download').val(),
				'action' : 'globo_exim_export_template',
			},
			success: function(result){
        		$("#filedata").val(result); // insert into the hidden form
        		// unlock(); // update indicator

        		$("#hiddenform").submit(); // submit the form data to the download page
			}
		});
	});

	$("#globo_export_company_download_btn").click(function(e) { // click the link to download
	    // lock(); // start indicator
	    e.preventDefault();
	    $.ajax({
			type: "GET",				
			url: gei.ajaxurl,
			data: {
				'action' : 'globo_exim_export_companies',
			},
			success: function(result){
        		$("#filedata").val(result); // insert into the hidden form
        		// unlock(); // update indicator

        		$("#hiddenform").submit(); // submit the form data to the download page
			}
		});
	});


	
	function lock() {
	    $("#wait").text("Creating File...");
	}

	function unlock() {
	    $("#wait").text("Done");
	}

	

})(jQuery);