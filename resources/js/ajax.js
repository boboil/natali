$('body').on('click', 'a.btn-quickview', function (e) {
    e.preventDefault();
/*    Porto.ajaxLoading();*/

    var url = $(this).attr('href');

    $("#modal-global").modal('show');

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'html',
    })
        .done(function(response) {
            $("#modal-global").find('.modal-content').html(response);
        });
});

/*
$('#modal-global').on('show.bs.modal', function () {
    $('.modal-content').html('<img class="mx-auto" src="/img/fish.gif" height="100" width="100" />');
})

$('#modal-global').on('hidden.bs.modal', function (e) {
    $('.modal-content').empty();
})*/


/*
$('body').on('click', 'a.btn-quickview', function (e) {
    e.preventDefault();
    Porto.ajaxLoading();
    var ajaxUrl = $(this).attr('href');
    setTimeout(function () {
        $.magnificPopup.open({
            type: 'ajax',
            mainClass: "mfp-ajax-product",
            tLoading: '',
            preloader: false,
            removalDelay: 350,
            items: {
                src: ajaxUrl
            },
            callbacks: {
                open: function() {
                    if($('.sticky-header.fixed').css('margin-right')) {
                        var newMargin = Number($('.sticky-header.fixed').css('margin-right').slice(0, -2))+17+'px';

                        $('.sticky-header.fixed').css('margin-right', newMargin);
                        $('.sticky-header.fixed-nav').css('margin-right', newMargin);
                        $('#scroll-top').css('margin-right', newMargin);
                    }
                },
                ajaxContentAdded: function () {
                    Porto.owlCarousels();
                    Porto.quantityInputs();
                    if (typeof addthis !== 'undefined') {
                        addthis.layers.refresh();
                    }
                    else {
                        $.getScript("https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5b927288a03dbde6");
                    }
                },
                beforeClose: function () {
                    $('.ajax-overlay').remove();
                },
                afterClose: function() {
                    if($('.sticky-header.fixed').css('margin-right')) {
                        var newMargin = Number($('.sticky-header.fixed').css('margin-right').slice(0, -2))-17+'px';

                        $('.sticky-header.fixed').css('margin-right', newMargin);
                        $('.sticky-header.fixed-nav').css('margin-right', newMargin);
                        $('#scroll-top').css('margin-right', newMargin);
                    }
                }
            },
            ajax: {
                tError: '',
            }
        });
    }, 500);
});*/
