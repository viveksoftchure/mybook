if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js').then(registration => {
            console.log('Service Worker registered with scope:', registration.scope);
        }).catch(error => {
            console.log('Service Worker registration failed:', error);
        });
    });
}

let deferredPrompt;

// Listen for the 'beforeinstallprompt' event, which Chrome triggers when the app is eligible to be installed
window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent Chrome's default mini-infobar prompt from appearing
    e.preventDefault();
    // Save the event for later use
    deferredPrompt = e;

    // Show the custom install button
    const installButton = document.getElementById('installButton');
    installButton.style.display = 'block';

    // Add a click event to the install button
    installButton.addEventListener('click', () => {
        // Show the prompt to install the PWA
        deferredPrompt.prompt();
        // Check what choice the user made
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the install prompt');
            } else {
                console.log('User dismissed the install prompt');
            }
            // Reset the deferredPrompt variable
            deferredPrompt = null;
            // Hide the install button after interaction
            installButton.style.display = 'none';
        });
    });
});


$(document).ready(function() {

    $('#modal').on('hide.bs.modal', function (event) {
        if (event.target === this) {
            $(this).find('form').empty();
        }
    });

    $('#modal').on('show.bs.modal', function (event) {
        var size = $(event.relatedTarget).data('modal-size');
        if (!size) {
            size = 'lg';
        }
        var dialog = $(this).find('.modal-dialog');
        dialog.removeClass('modal-md');
        dialog.removeClass('modal-lg');
        dialog.removeClass('modal-xl');
        dialog.addClass('modal-'+size);

        var button = $(event.relatedTarget);
        var form = button.data('form');
        var form_title = $(form).find('.form-title').html();
        if (form_title) {
            $(this).find('.modal-title').text(escapeHtml(form_title));
        }
        var form_subtitle = $(form).find('.form-subtitle').html();
        if (form_subtitle) {
            $(this).find('.modal-subtitle').text(form_subtitle);
        } else {
            $(this).find('.modal-subtitle').text('');
        }
        var form_img = $(form).find('.form-img').html();
        if (form_img) {
            $(this).find('.modal-img').html(form_img);
        }

        $(this).find('form').html($(form).html());

        var form_class = button.data('form-class');
        if (form_class) {
            $(this).find('form').addClass(form_class);
        }

        $(this).find('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });

        function toggleLabel(elem) {
            let container = $(elem).siblings('.label-container');
            if (elem.checked) {
                container.find('.no').hide();
                container.find('.yes').show();
            } else {
                container.find('.no').show();
                container.find('.yes').hide();
            }
        }

        $('.js-switch').change(function() {
            toggleLabel(this);
        });
    });

    $(document).on('click', '#save_form', function() {
        $("#block_form").validate().form();
        $("#block_form").submit();
    });

    $('body').on('change', 'select.custom_ph', function() {
        styleSelect(this);
    });

    $('select.custom_ph').each(function() {
        styleSelect(this);
    });

    $('.cat-checkbox').on('click', function(event){
        event.preventDefault();
    });

    $('#side-menu a.link').on('click', function () {
        window.location = this.href;
    });

    $('textarea.editable').each(function() {
        resizeTextarea($(this));
    });

    $('textarea.editable').on('input', function() {
        resizeTextarea($(this));
    });

    function resizeTextarea (el) {
        el.css('height', 'auto');
        if (el.val() && el[0].scrollHeight) {
            el.css('height', el[0].scrollHeight + 3 + 'px');
        }
    }

    $('#change-profile').click(function(event) {
        event.preventDefault();
        $("#profile-input").click();
    });

    $('#profile-input').change(function (){
        $(this).closest('form').submit();
    });

    $(document).on('click', '.collapse-control:not(.disabled-btn)', function () {
        let icon = $(this).find('i').not('.popup-info-icon');
        let control = $(this).find('.control-item');
        icon.removeClassWild('fa-*');

        if ($(this).attr('aria-expanded') == 'true') {
            icon.addClass('fa-chevron-left');
            control.addClass('active');
        } else {
            icon.addClass('fa-chevron-down');
            control.removeClass('active');
        }
    })

    $(document).on('click', '.clickable-row', function(event) {
       if (event.ctrlKey || event.metaKey) {
           window.open($(this).data('href'), '_blank');
       } else {
           window.location = $(this).data('href');
       }
    });

    /**
    * allow only number in number field
    */
    $(document).on('keyup', '.phone-number', function(e)
    {
        if (/\D/g.test(this.value))
        {
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
        }
    });

    // Copy to clipboard
    $(document).on('click', '.js-copy', function(event) {
        event.preventDefault();
        var $this = $(this);

        var copyText = $this.data("clipboard-text");
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(copyText).select();
        document.execCommand("copy");
        $temp.remove();

        $this.css('color', '#1db9ab');
        setTimeout(function(){ $this.css('color', '#686b6d'); }, 1200);
    });

    /*
    * toggle password type
    */
    $(document).on('click', '.toggle-password', function() {
        $(this).toggleClass("svg-eye-open svg-eye-close");
        input = $(this).parent().find("input");
        if (input.hasClass('password-visible')) {
            input.removeClass('password-visible');
        } else {
            input.addClass('password-visible');
        }
    });

    /*
    * Patch: Chrome is wrapping line in span with styling upon deleting previous line break
    */
    $(document).on("DOMNodeInserted", '.note-editable', function (e) {
        if (e.target.tagName === "SPAN") {
            $(e.target).replaceWith($(e.target).contents());
        }
    });

    $('.menu-button').on('click', function(e) {
        e.preventDefault();

        if ($('body').hasClass('main-show-temporary')) {
            $('body').removeClass('main-show-temporary');
        } else {
            $('body').addClass('main-show-temporary');
        }
    })
});

/*
* Escape html tags
*/
function escapeHtml(text) {
    return text
        .replace(/&amp;/g, '&')
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>')
        .replace(/&quot;/g, '"')
        .replace(/&#039;/g, "'");
}

/**
 * Format date standard
 */
function formatDateSt(date) {
    if (date=='' || date=='0000-00-00') {
        var date = '';
        return date;
    }
    var formattedDate = new Date(date);
    var d = formattedDate.getDate();
    var m =  formattedDate.getMonth();
    m += 1;  // JavaScript months are 0-11
    var y = formattedDate.getFullYear();

    d = d<10 ? '0'+d : d;
    m = m<10 ? '0'+m : m;

    return d + "." + m + "." + y;
}

/**
 * Format date default
 */
function formatDateDefault(date) {
    if (date=='' || date=='0000-00-00') {
        var date = '';
        return date;
    }
    var formattedDate = new Date(date);
    var d = formattedDate.getDate();
    var m =  formattedDate.getMonth();
    m += 1;  // JavaScript months are 0-11
    var y = formattedDate.getFullYear();

    d = d<10 ? '0'+d : d;
    m = m<10 ? '0'+m : m;

    return y + "-" + d + "-" + m;
}

/**
 * check if variable is email
 */
function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

/**
 * Convert the amount to INR format
 *
 * @param {string|number} amount
 * @return {string}
 */
function formatToINR(amount) {
    // Convert the amount to a float and round to 2 decimal places
    amount = parseFloat(amount).toFixed(2);

    // Split the amount into the integer and decimal parts
    let parts = amount.split('.');
    let integerPart = parts[0];
    let decimalPart = parts[1] || '00';

    // Add commas to the integer part
    let lastThree = integerPart.slice(-3);
    let otherNumbers = integerPart.slice(0, -3);
    if (otherNumbers) {
        lastThree = ',' + lastThree;
    }
    let formattedInteger = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ',') + lastThree;

    // Ensure the decimal part is two digits
    if (decimalPart.length < 2) {
        decimalPart += '0';
    }

    // Combine the integer and decimal parts
    let formattedAmount = '₹' + formattedInteger + '.' + decimalPart;

    return formattedAmount;
}