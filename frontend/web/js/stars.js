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
        var emoticonEl = $(this).prev();
        var ratingdivEl = $(this).parent();
        $(this).html(Math.round(percent)+'%');
        emoticon(percent,emoticonEl,ratingdivEl);
    });
}

function emoticon($percent,$emoticonEl,$ratingdivEl){
    if($percent >= 90){
        loadSvg($emoticonEl,'happy');
        $($ratingdivEl[0]).addClass("rating-great");
    } else if ($percent >= 80) {
        loadSvg($emoticonEl,'smile');
        $($ratingdivEl[0]).addClass("rating-good");
    } else if ($percent >= 60) {
        loadSvg($emoticonEl,'meh');
        $($ratingdivEl[0]).addClass("rating-meh");
    } else {
        loadSvg($emoticonEl,'sad');
        $($ratingdivEl[0]).addClass("rating-bad");
    }
}

function loadSvg(selector, url) {
    var path = $('.ratingdiv').attr('data-path');
    var target = selector[0];
    // Request the SVG file
    var ajax = new XMLHttpRequest();
    ajax.open("GET", path + "/imageLocation/" + url + ".svg", true);
    ajax.send();

    // Append the SVG to the target
    ajax.onload = function(e) {
      target.innerHTML = ajax.responseText;
    }
}