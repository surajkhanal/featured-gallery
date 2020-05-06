/**
 * Callback function for the 'click' event of the 'CDX Gallery Images'
 * button in its meta box.
 *
 * Displays the media uploader for selecting an image.
 *
 * @since 0.1.0
 */
function renderMediaUploader() {
    // 'use strict';
    var file_frame, image_data;
    
    /**
     * If an instance of file_frame already exists, then we can open it
     * rather than creating a new instance.
     */
    if ( undefined !== file_frame ) {
        console.log('instance is already defind');
        file_frame.open();
        return;
 
    }

    /**
     * If we're this far, then an instance does not exist, so we need to
     * create our own.
     *
     * Here, use the wp.media library to define the settings of the Media
     * Uploader. We're opting to use the 'post' frame which is a template
     * defined in WordPress core and are initializing the file frame
     * with the 'insert' state.
     *
     * We're also not allowing the user to select more than one image.
     */
    file_frame = wp.media.frames.file_frame = wp.media({
        // frame: 'post',
        title: 'Add Media',
        multiple: true
    });

    /**
     * Setup an event handler for what to do when an image has been
     * selected.
     *
     * Since we're using the 'view' state when initializing
     * the file_frame, we need to make sure that the handler is attached
     * to the select event.
     */
    file_frame.on( 'select', function() {
        let attachment = file_frame.state().get( 'selection' ).toJSON();
        
        if(attachment.length > 0) {
            $('.no__img').remove();
           
            attachment.map(img => {
                addImage(img);
            });
        }
    });

    // Now display the actual file_frame
    file_frame.open();
}

/**
 * construct the image grid from the selected image
 * 
 **/ 
function addImage(img) {
    console.log(img);
    $('.img__grid').append(`
                <div class="img-block" id="img-${img.id}">
                    <button type="button" class="btn-remove" onclick="removeImg(${img.id})"><i class="dashicons dashicons-trash"></i></button>
                    <input type="hidden" name="cdx_img[]" value='${JSON.stringify(img)}'>
                    <div class="soft-wrap">
                        <img src="${img.url}">
                    </div>
                </div>`);
}

// remove image from the grid
function removeImg(id) {
    $(`#img-${id}`).fadeOut(800).remove();
}

(function( $ ) {
    'use strict';
 
    $(function() {
       
        $('.btn-add-images').on('click', function(e) {
            e.preventDefault();
            
            // Display the media uploader
            renderMediaUploader();
        
        });
 
    });
 
})( jQuery );