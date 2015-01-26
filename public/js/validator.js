var Validator = {
    registerValidator: function () {
        $('.choose-prices').click(function () {
            var tb = $(this);
            var pk = $('input#prices');
            var all = $('.choose-prices');
            var prices = tb.attr('data-id');

            if (tb.hasClass('selected')) {
                pk.attr('value', '');
                all.html('Wybierz Pakiet').removeClass('disabled');
                tb.remove('selected');

            } else {
                pk.attr('value', prices);
                all.addClass('disabled');
                tb.addClass('selected').removeClass('disabled').html('Zmień Pakiet');
            }
        });
        $('#registerCompany').validate({
            ignore: "input[type='text']:hidden",
            rules: {
                first_name: "required",
                last_name: "required",
                username: {
                    required: true,
                    minlength: 2
                },
                password: {
                    required: true,
                    minlength: 5
                },
                password2: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password"
                },
                email: {
                    required: true,
                    email: true
                },
                name: {
                    required: true,
                    minlength: 3
                },
                nip: {
                    required: true,
                    nip: true
                },
                regon: {
                    required: true,
                    regon: true
                },
                address: {
                    required: true
                },
                city: {
                    required: true
                },
                post_code: {
                    required: true,
                    regex: '[0-9]{2}-[0-9]{3}'
                },
                rules: {
                    required: true
                },
                prices: {
                    required: true
                }
            },
            messages: {
                post_code: {
                    regex: 'Niepoprawny kod pocztowy'
                },
                prices: {
                    required: 'Musisz wybrać pakiet'
                }
            }
        });
        jQuery.extend(jQuery.validator.messages, {
            required: "To pole jest wymagane.",
            remote: "Please fix this field.",
            email: "Proszę wprowadzić poprawny adres email.",
            url: "Please enter a valid URL.",
            date: "Please enter a valid date.",
            dateISO: "Please enter a valid date (ISO).",
            number: "Please enter a valid number.",
            digits: "Please enter only digits.",
            creditcard: "Please enter a valid credit card number.",
            equalTo: "Proszę ponownie wprowadzić tę samą wartość.",
            accept: "Please enter a value with a valid extension.",
            maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
            minlength: jQuery.validator.format("Prosze wprowadzić minimum {0} znak."),
            rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
            range: jQuery.validator.format("Please enter a value between {0} and {1}."),
            max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
            min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
        });
        jQuery.validator.addMethod("nip", function (value, element) {
            var verificator_nip = new Array(6, 5, 7, 2, 3, 4, 5, 6, 7);
            var nip = value.replace(/[\ \-]/gi, '');
            if (nip.length != 10) {
                return false;
            } else {
                var n = 0;
                for (var i = 0; i < 9; i++) {
                    n += nip[i] * verificator_nip[i];
                }
                n %= 11;
                if (n != nip[9]) {
                    return false;
                }
            }
            return true;
        }, "Proszę o podanie prawidłowego numeru NIP");

        jQuery.validator.addMethod("regon", function (value, element) {
            var regon = value.replace(/[\ \-]/gi, '');
            if (regon.length != 9) {
                return false;
            }
            else {
                var arrSteps = new Array(8, 9, 2, 3, 4, 5, 6, 7);
                var intSum = 0;
                for (var i = 0; i < 8; i++) {
                    intSum += arrSteps[i] * regon[i];
                }
                var intb = intSum % 11;
                var intControlNr = (intb == 10) ? 0 : intb;
                if (intControlNr == regon[8]) {
                    return true;
                }
            }

        }, "Proszę o podanie prawidłowego numeru REGON");

        $.validator.addMethod(
            "regex",
            function (value, element, regexp) {
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            },
            "Podana wartość jest nieprawidłowa."
        );
    },
    addAdvertValidator: function () {
        $('#addAdvertForm').validate({
            ignore: "",
            rules: {
                advert_type: {
                    required: true
                },
                sell_option: {required: function(element) {
                    return $('.advert_type').val() == 1;
                }},
                name: {
                    required: true,
                    minlength: 5
                },
                category_id : "required",
                amount: {
                    required: true,
                    pattern: /^\d{1,11}(\,\d{0,2})?$/
                },
                pieces: {
                    required: true,
                    number: true
                },
                description: {
                    required: true,
                    tinymce: true
                },
                location: {
                    required: true,
                    minlength: 3
                },
                transport: {
                    required: true
                },
                transport_amount: {required: function(element) {
                        return $('.transport').val() == 1;
                    },
                    pattern: /^\d{1,11}(\,\d{0,2})?$/
                }
            },
            messages: {
                transport_amount: {
                    regex: 'Podana kwota jest nieprawidłowa'
                },
                amount: {
                    regex: 'Podana kwota jest nieprawidłowa'
                }
            }
        });
        jQuery.extend(jQuery.validator.messages, {
            required: "To pole jest wymagane.",
            pattern: "Podana kwota jest niepoprawna.",
            remote: "Please fix this field.",
            email: "Proszę wprowadzić poprawny adres email.",
            url: "Please enter a valid URL.",
            date: "Please enter a valid date.",
            dateISO: "Please enter a valid date (ISO).",
            number: "Please enter a valid number.",
            digits: "Please enter only digits.",
            creditcard: "Please enter a valid credit card number.",
            equalTo: "Proszę ponownie wprowadzić tę samą wartość.",
            accept: "Please enter a value with a valid extension.",
            maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
            minlength: jQuery.validator.format("Prosze wprowadzić minimum {0} znak."),
            rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
            range: jQuery.validator.format("Please enter a value between {0} and {1}."),
            max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
            min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
        });

        $.validator.addMethod("tinymce", function() {
            if(tinyMCE) tinyMCE.triggerSave();
            return true;
        });
    }
}

$(document).ready(function () {
    Validator.registerValidator();
    Validator.addAdvertValidator();
});