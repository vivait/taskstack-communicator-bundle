var container            = $('#taskstack-help-button'),
    errorElement         = $('.help-error', container),
    loadingElement       = $('.loading', container),
    helpContainerElement = $('.help-form-container', container);

$('.ajaxForm', container).submit(function (e) {
    e.preventDefault();
    var $this = $(this);

    $.ajax({
        type:       $this.attr('method'),
        url:        $this.attr('action'),
        data:       $this.serialize(),
        beforeSend: function () {
            loadingElement.show();
            helpContainerElement.hide();
        }
    })
    .always(function () {
        helpContainerElement.show();
        loadingElement.hide()
    })
    .done(function (data) {
        errorElement.hide();
        helpContainerElement.html('<span class="taskstack-help-success"><i class="fa fa-check"></i> ' + data.message + '</span>');
    })
    .fail(function (jqXHR) {
        var responseJSON = jqXHR.responseJSON;

        if (responseJSON.hasOwnProperty('form')) {
            errorElement.show();
            helpContainerElement.html(responseJSON.form);
        }

        if (responseJSON.hasOwnProperty('message')) {
            errorElement.show();
            errorElement.text(responseJSON.message);
        }
    });
});
