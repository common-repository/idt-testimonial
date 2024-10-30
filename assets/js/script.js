  // Uploading files
var file_frame;

  jQuery('#upload_image_button').live('click', function( event ){

    event.preventDefault();

    // If the media frame already exists, reopen it.
    if ( file_frame ) {
      file_frame.open();
      return;
    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: jQuery( this ).data( 'uploader_title' ),
      button: {
        text: jQuery( this ).data( 'uploader_button_text' ),
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });

    // When an image is selected, run a callback.
    file_frame.on( 'select', function() {
      // We set multiple to false so only get one image from the uploader
      attachment = file_frame.state().get('selection').first().toJSON();

      jQuery('#idt_logo').val(attachment.id);
      jQuery("#idt_img_url").attr('src', attachment.url);


    });

    // Finally, open the modal
    file_frame.open();
  });
  jQuery('.idt_testimonials_wrap').flexslider({
        selector: ".idt_testimonials > li",
        animation: "slide",
        // controlNav: false,
        slideshow: true,
        smoothHeight: true,
        directionNav: true,
  });
