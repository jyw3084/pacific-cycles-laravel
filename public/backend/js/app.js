$(document).ready(function() {
    // nicescroll plugin
    new PerfectScrollbar(('.aside_nav'), {
        wheelSpeed: 0.4
    });

    // print media
    $('#print').click(function(){
        window.print();
    });

    /* map menu function */
    $(".product_card_area .view_more_btn.one a").on("click", function(event) {
        if($('.product_card_area .load_more, .view_more_btn.one a').hasClass('load')) {
            $('.product_card_area .load_more, .view_more_btn.one a').removeClass('load');
        } else {
            $('.product_card_area .load_more, .view_more_btn.one a').addClass('load');
        }
        event.preventDefault();
    });
    $(".product_card_area .view_more_btn.two a").on("click", function(event) {
        if($('.product_card_area .load_more2, .view_more_btn.two a').hasClass('load')) {
            $('.product_card_area .load_more2, .view_more_btn.two a').removeClass('load');
        } else {
            $('.product_card_area .load_more2, .view_more_btn.two a').addClass('load');
        }
        event.preventDefault();
    });
    
});
