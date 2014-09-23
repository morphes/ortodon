jQuery.noConflict();

jQuery(document).ready(function($){
    if (jQuery('.'+'c'+'l'+'c'+'o'+'p'+'y'+'r'+'i'+'g'+'h'+'t').length <= 0 ) {
        if (jQuery('.'+'mcenter'+'-'+'item').length > 0) {
            jQuery('.'+'mcenter'+'-'+'item').hide();
        }
        if (jQuery('.'+'mcenter').length > 0) {
            jQuery('.'+'mcenter').hide();
        }
    }
});