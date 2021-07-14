function initializeToast() {
    var toastEl = document.getElementById('bs_toast')
    var option = {
        animation: true,
        autohide: true,
        delay: 6000
    };
    var toast = new bootstrap.Toast(toastEl, option);
    toast.show();
}

$('.form-control').keypress(function (e) {
    if (e.which == 13) return false;
});

$('#noLivewire').submit(function (e) {
    $('button[type=submit]').attr('disabled', true).prepend('<span class="btn-spinner"></span> ');
});

function countrySelectState(state) {
    if (!state.id) {
        return state.text;
    }

    var $state = $(
        '<span><span style="margin-right: 10px"></span><small style="font-size: .8125rem"></small></span>'
    );

    // Use .text() instead of HTML string concatenation to avoid script injection issues
    $state.find("small").text(state.text);
    $state.find("span").addClass("flag-icon flag-icon-" + state.element.value.toLowerCase());

    return $state;
}

function countryState(state) {
    if (!state.id) {
        return state.text;
    }
    return $(
        '<span><span class="flag-icon flag-icon-' + state.element.value.toLowerCase() + '" style="margin-right: 10px"></span>' + state.text + '</span>'
    );
}

function currencySelectState(state) {
    if (!state.id) {
        return state.text;
    }

    var value = 'eu';

    if (state.element.label !== 'EUR') {
        value = state.element.label.toLowerCase().substr(0, 2)
    }

    var $state = $(
        '<span><span style="margin-right: 10px"></span><small style="font-size: .8125rem"></small></span>'
    );

    // Use .text() instead of HTML string concatenation to avoid script injection issues
    $state.find("small").text(state.text);
    $state.find("span").addClass("flag-icon flag-icon-" + value);

    return $state;
}

function currencyState(state) {
    if (!state.id) {
        return state.text;
    }

    var value = 'eu';

    if (state.element.label !== 'EUR') {
        value = state.element.label.toLowerCase().substr(0, 2)
    }

    return $(
        '<span><span class="flag-icon flag-icon-' + value + '" style="margin-right: 10px"></span>' + state.text + '</span>'
    );
}

function phoneSelectState(state) {
    if (!state.id) {
        return state.text;
    }

    var $state = $(
        '<span><span style="margin-right: 10px"></span><small style="font-size: .8125rem"></small></span>'
    );

    // Use .text() instead of HTML string concatenation to avoid script injection issues
    $state.find("small").text(state.text);
    $state.find("span").addClass("flag-icon flag-icon-" + state.element.label.toLowerCase());

    return $state;
}

function phoneState(state) {
    if (!state.id) {
        return state.text;
    }
    return $(
        '<span><span class="flag-icon flag-icon-' + state.element.label.toLowerCase() + '" style="margin-right: 10px"></span>' + state.text + '</span>'
    );
}
