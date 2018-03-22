$.fn.stars = function() {
    return $(this).each(function() {
        // Get the value
        var val = parseFloat($(this).html());
        // Make sure that the value is in 0 - 5 range, multiply to get width
        var size = Math.max(0, (Math.min(5, val))) * 16;
        // Create stars holder
        var $span = $('<span />').width(size);
        // Replace the numerical value with stars
        $(this).html($span);
    });
}

$(function() {
    $('.stars').stars();
    $('.testrating').testrating();
});


function singleStar(limit){
    return $('.stars').slice('-'+limit).each(function() {
        // Get the value
        var val = parseFloat($(this).html());
        // Make sure that the value is in 0 - 5 range, multiply to get width
        var size = Math.max(0, (Math.min(5, val))) * 16;
        // Create stars holder
        var $span = $('<span />').width(size);
        // Replace the numerical value with stars
        $(this).html($span);
    });
}

$.fn.testrating = function(){
    return $(this).each(function(){
        var val = parseFloat($(this).html());
        var percent = val * 20;
        $(this).html(Math.round(percent)+'%');
        emoticon(percent);
    });
}

function emoticon($percent){
    if($percent >= 90){
        loadSvg('.emoticon','happy');
        $('.ratingdiv').addClass("rating-great");
    } else if ($percent >= 80) {
        loadSvg('.emoticon','smile');
        $('.ratingdiv').addClass("rating-good");
    } else if ($percent >= 60) {
        loadSvg('.emoticon','meh');
        $('.ratingdiv').addClass("rating-meh");
    } else {
        loadSvg('.emoticon','sad');
        $('.ratingdiv').addClass("rating-bad");
    }
}

function loadSvg(selector, url) {
    var target = document.querySelector(selector);
    // Request the SVG file
    var ajax = new XMLHttpRequest();
    ajax.open("GET", "imageLocation/" + url + ".svg", true);
    ajax.send();

    // Append the SVG to the target
    ajax.onload = function(e) {
      target.innerHTML = ajax.responseText;
    }
}