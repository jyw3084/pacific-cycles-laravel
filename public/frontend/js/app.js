// fixed nav bar in jquery
$(window).scroll(function() {
    if($(this).scrollTop() > 75) {
        $('.navbar').addClass('active');
    } else {
        $('.navbar').removeClass('active');
    };
});

$(document).ready(function() {
    /* map menu function */
    $(".navbar-toggler").on("click", function(event) {
        if($('.navbar').hasClass('active2')) {
            $('.navbar').removeClass('active2');
        } else {
            $('.navbar').addClass('active2');
        }
        event.preventDefault();
    });
});