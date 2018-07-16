define(['require', 'jquery', 'elgg'], function(require, $, elgg) {
	function responsive() {
	
        // Make the logo container into a perfect square
        $('.demyx').css('height', $('.demyx').width() + 'px');

		if ($(window).width() > 1024) {
            
            // Color picker
            $('#primary_color').ColorPicker({
            	onSubmit: function(hsb, hex, rgb, el) { 
            		$(el).ColorPickerHide();
            	},
            	onChange: function(hsb, hex, rgb){
                    $("#primary_color").val(hex);
                    $('#icon-preview-1, #icon-preview-2, #icon-preview-3').css('background-color', '#' + hex);  
                }
            });
            $('#secondary_color').ColorPicker({
            	onSubmit: function(hsb, hex, rgb, el) { 
            		$(el).ColorPickerHide();
            	},
            	onChange: function(hsb, hex, rgb){
                    $("#secondary_color").val(hex);
                    $('#icon-preview-1, #icon-preview-2, #icon-preview-3').css('color', '#' + hex); 
                }
            });
            $('#member_icon_color').ColorPicker({
            	onSubmit: function(hsb, hex, rgb, el) { 
            		$(el).ColorPickerHide();
            	},
            	onChange: function(hsb, hex, rgb){
                    $("#member_icon_color").val(hex); 
                }
            });
            $('#general_text_color').ColorPicker({
            	onSubmit: function(hsb, hex, rgb, el) { 
            		$(el).ColorPickerHide();
            	},
            	onChange: function(hsb, hex, rgb){
                    $("#general_text_color").val(hex); 
                }
            });

		}

		if ($(window).width() < 1024) {

			
		}
		
	}
	
	responsive();

	$(window).resize(function() {
	    responsive();
	});

    // Remove plugin title
    $('.elgg-head').remove();
    
    // Live preview for icons
    $('#icon-preview-text1').on('keyup', function(){
        var text1 = 'fa fa-' + $(this).val();
            $('#icon-preview-1').removeClass();
            $('#icon-preview-1').addClass(text1);
    });
    $('#icon-preview-text2').on('keyup', function(){
        var text2 = 'fa fa-' + $(this).val();
            $('#icon-preview-2').removeClass();
            $('#icon-preview-2').addClass(text2); 
    });
    $('#icon-preview-text3').on('keyup', function(){
        var text3 = 'fa fa-' + $(this).val();
            $('#icon-preview-3').removeClass();
            $('#icon-preview-3').addClass(text3);
    });
    
    // Show update topbar when you scroll down and then update when clicked
    $('.landr-update-bar').appendTo($('body'));
    $('.landr-update-bar').click(function() {
       $('.elgg-button-submit').click();   
    });
    var top = $('.landr-right').offset().top - parseFloat($('.landr-right').css('marginTop').replace(/auto/, 500));

	$(window).scroll(function (event) {
		var y = $(this).scrollTop();
		if (y >= top) {
			$('.landr-update-bar').show(); 
		} else {
			$('.landr-update-bar').hide();
		}
	});

	// Slider ajax uploader
	function ajaxFileUpload() {
		$("#loading")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});

		$.ajaxFileUpload
		(
			{
				url: elgg.normalize_url('ajax/view/landr/upload'), 
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
				data:{name:'logan', id:'id'},
				success: function (data, status) {
	                if (typeof(data.error) != 'undefined') {
						if (data.error == 'exist') {
							if(confirm("Image already exist, do you want to use it again?")) {
	                            $('#slider_image').val(data.proxy);
	                        } else { 
	                            $('#upload-message').addClass('upload-error').show();
	                            $('#upload-message').html('Nothing was uploaded'); 
	                        }
						} else if (data.error == 'nothing') {
						    $('#upload-message').addClass('upload-error').show(); 
	                        $('#upload-message').html('No image was selected');  
					    } else if (data.error == 'allowed') {
						    $('#upload-message').addClass('upload-error').show(); 
	                        $('#upload-message').html('Sorry, only jpg, png & gif files are allowed');   
					    } else if (data.error == 'size') {
						    $('#upload-message').addClass('upload-error').show(); 
	                        $('#upload-message').html('Sorry, the file limit size is 2mb (2048)');    
					    } else {
					        $('#upload-message').removeClass('upload-error').addClass('upload-success').show();
					        $('#upload-message').html('Success!'); 
					        $('#uploaded-image').attr('src', data.proxy);
					        $('#upload-image').show();
							$('#slider_image').val(data.proxy); 
						} 
						setTimeout(function() {
	                        $('#upload-message').fadeOut('slow', function() {
	                            $(this).hide();
	                        });
	                    }, 2000);
					} 
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		
		return false;

	}

	$('#landr-slider-upload').on('click', function() {
		$('input[name=landr-slider]').click();
	});
	$('input[name=landr-slider]').liteUploader({
		script: elgg.normalize_url('ajax/view/landr/imgur')
	})
	.on('lu:before', function (e, files) {
		$('#landr-slider-upload').val('Uploading...');
	})
	.on('lu:success', function (e, response) {
		var response = $.parseJSON(response);
		$('#landr-slider-tmp').val(response.url);
		$('#landr-slider-upload').val('Upload');
		$('.current-slider-image').attr('src', response.url);
	});
});