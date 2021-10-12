function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function updateParameterUrl(parameter, value) {
    var search = window.location.search;
    if (search.length === 0) {
        window.history.pushState("", "Home", "?" + parameter + "=" + value);
        return;
    } 
    // search params greater then 0
    var getParamByName = getParameterByName(parameter);

    // If the param already exists, replace the value
    if (getParamByName !== null) {
        window.history.pushState("", "Home", search.replace(getParamByName, value));
        return;
    } 
    
    // search not 0 and param not exist
    window.history.pushState("", "Home", search + "&" + parameter + "=" + value);
}

jQuery( document ).ready(function() {
    /*
     *  Google Analitics and Tag Manager
     */

    // Category Box Clicked 
    jQuery(document).on('click', '.domain-modul-box', function () {
        console.log('GA>> ', jQuery(this).find('h3').text());
        ga('send', 'event', 'jobs', 'בחירת תחום', jQuery(this).find('h3').text());
    });
    
    // Menu Item Clicked
    jQuery('ul.main-menu a').on('click', function(){
        console.log("GA>> Menu Item clicked: ", jQuery(this).text());
        ga('send', 'event', 'jobs', 'תפריט', jQuery(this).text());
    });
    
    //Joblist Apply
    jQuery(document).on('click', '#category_list_container button.joblist-apply', function(){
        console.log("GA joblist-apply>> ", jQuery(this).parents('div.mfp-content').find('.popup_head_numb').text());
        ga('send', 'event', 'jobs', 'כפתור הגשת קורות חיים', jQuery(this).parents('div.mfp-content').find('.popup_head_numb').text());
    });
    
    // Joblist Details
    jQuery(document).on('click', '#category_list_container button.joblist-details', function(){
        console.log("GA joblist-details>> ", jQuery(this).parents('div.mfp-content').find('.popup_head_numb').text());
        ga('send', 'event', 'jobs', 'הצג משרה', jQuery(this).parents('div.mfp-content').find('.popup_head_numb').text());

    });
    
    // Resume Submission
    jQuery(document).on('click', 'div.mfp-wrap .category .apply-job .apply-job-submit', function(){
        console.log("GA resume submission>> ", jQuery(this).parents('div.mfp-content').find('.popup_head_numb').text());
        ga('send', 'event', 'jobs', 'הגשת קורות חיים', jQuery(this).parents('div.mfp-content').find('.popup_head_numb').text()),
        dataLayer.push({'event':'resume submission','profession':jQuery(this).parents('div.mfp-content').find('.popup_head_numb').text()});
        
       // File Uploaded
        var dropbox = jQuery("input[name='apply-job-dropbox-fileName']").val().length && jQuery("input[name='apply-job-dropbox-fileUrl']").val().length;
        var localfile = jQuery('.apply-job-localFile').val().length;
        var skydrive = jQuery("input[name='apply-job-skydrive-fileName']").val().length && jQuery("input[name='apply-job-skydrive-fileUrl']").val().length;
        if (dropbox || localfile || skydrive){
            console.log("GA resume submission - file upload>> ", jQuery(this).parents('div.mfp-content').find('.popup_head_numb').text());
            ga('send', 'event', 'jobs', 'העלאת קורות חיים', jQuery(this).parents('div.mfp-content').find('.popup_head_numb').text()),
            dataLayer.push({'event':'resume submission','profession':jQuery(this).parents('div.mfp-content').find('.popup_head_numb').text()});
        }

    });
});