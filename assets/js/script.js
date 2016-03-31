(function ($) {
    var ul = $('#globo-import ul');
    $('#globo_hide').hide();
    $('.globo-exim-icon').hide();
    $('#drop a').click(function(e){
        // Simulate a click on the file input button
        // to show the file browser dialog
        $(this).parent().find('input').trigger("click");
    });

    // Initialize the jQuery File Upload plugin
    $('#globo-import').fileupload({
        // dataType: 'text/csv',

        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),

        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {

            $('ul#show-upload').empty();
            var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#0073aa" /><p></p><span></span></li>');
            console.log(data.files[0].name);
            if(data.files[0].type == 'text/csv') {

                // Append the file name and file size
                tpl.find('p').text(data.files[0].name)
                             .append('<i>' + formatFileSize(data.files[0].size) + '</i>');
                // // Add the HTML to the UL element
                data.context = tpl.appendTo(ul);

                // // Initialize the knob plugin
                tpl.find('input').knob();

                // Listen for clicks on the cancel icon
                tpl.find('span').click(function(){

                    if(tpl.hasClass('working')){
                        jqXHR.abort();
                    }

                    tpl.fadeOut(function(){
                        tpl.remove();
                    });

                });

                // Automatically upload the file once it is added to the queue
                var jqXHR = data.submit();
                

            } else {

                alert('Upload a CSV file!');

            }
            
            
        },

        progress: function(e, data){

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if(progress == 100){
                data.context.removeClass('working');
            }
        },

        fail:function(e, data){
            // Something has gone wrong!
            data.context.addClass('error');
        },
        
        done: function (e, data) {
            console.log(data);
            if(data.files[0].type == 'text/csv') {

                $('#import-csv-file').val(data.result);
                $('#globo_hide').slideDown();

            } else {

                alert('Upload a CSV file!');

            }
            // data.context.text('Upload finished.');
        }

    });


    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }

    $('#read-csv').click(function(e){
        e.preventDefault();
        var fileName = $('#import-csv-file').val();

        $('.globo-exim-icon').show();
        // This does the ajax request
        $.ajax({
            method: 'post',
            url: gei.ajaxurl,
            data: {
                'action': 'globo_exim_csv_load',
                'filename' : fileName,
            },
            success:function(data) {
                // This outputs the result of the ajax request
                console.log(data);
                $('#import-result').html(data);
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });  

    });


    $('#import-industry').click(function(e){
        e.preventDefault();
        var fileName = $('#import-csv-file').val();

        $('.globo-exim-icon').show();
        // This does the ajax request
        $.ajax({
            method: 'post',
            url: gei.ajaxurl,
            data: {
                'action': 'globo_exim_industry_import_csv',
                'filename' : fileName,
            },
            success:function(data) {
                // This outputs the result of the ajax request
                console.log(data);
                $('#import-result').html(data);
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });  

    });
}(jQuery));
