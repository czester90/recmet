var Urls = {
    previewAdvert: '/advert/getAdvert'
}

var RecMetals = {
    tools: {
        generateRadomId: function(str) {
            var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
            var randomstring = '';
            for (var i=0; i<str; i++) {
              var rnum = Math.floor(Math.random() * chars.length);
              randomstring += chars.substring(rnum,rnum+1);
            }
            return randomstring;
        },
        sendAjax: function(href, details) {
            return $.ajax({
                type: "POST",
                url: href,
                dataType: "json",
                data: details,
                async: false
            })
            .success(function (msg) {
                if(msg.success){
                    return msg.param;
                }
            })
            .error(function(msg) {
                console.log(msg);
            }).responseJSON;
        },
        serializeObject: function(form)
        {
            var o = {};
            var a = form.serializeArray();
            $.each(a, function() {
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        }
    },
    init: function () {
        tinymce.init({
            selector: "textarea.editor",
            plugins: [
                "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons template textcolor paste textcolor colorpicker textpattern"
            ],

            toolbar1: "cut copy paste | bullist numlist | outdent indent blockquote preview | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify",

            menubar: false,
            toolbar_items_size: 'small',

            style_formats: [
                {title: 'Bold text', inline: 'b'},
                {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                {title: 'Example 1', inline: 'span', classes: 'example1'},
                {title: 'Example 2', inline: 'span', classes: 'example2'},
                {title: 'Table styles'},
                {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
            ],

            templates: [
                {title: 'Test template 1', content: 'Test 1'},
                {title: 'Test template 2', content: 'Test 2'}
            ]
        });

        var ids = 0;
        var file_ids = 2;

        $('body').on('change', '#photo', function(){
            if (this.files && this.files[0]) {
                ids += 1; //increementing global variable by 1

                var z = ids - 1;
                var x = $(this).parent().find('#previewimg' + z).remove();
                $('.place-image').append("<div class='col-xs-6 col-md-4 mar-top15 image-box add-image"+ ids +"' data-id='"+ ids +"'><img alt='100%x180' id='previewimg" + ids + "' data-src='holder.js/100%x180' style='height: 180px; width: 100%; display: block;' src='' data-holder-rendered='true'></div>");

                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);

                $(this).hide();
                $('.add-image'+ ids).append('<button type="button" data-id="' + ids + '" class="close img-remove" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                $('.img-remove').click(function() {
                    var removeId = $(this).attr('data-id');
                    var parent = $(this).parent();
                    $("div.image-box").each(function(index, value) {
                        var item = $(value);
                        if(parent.data('id') == item.data('id')){
                            $('input#profile-image').val('');
                            return;
                        }
                    });
                    $(".add-image"+ removeId).remove();
                    $(".add-file"+ removeId).remove();
                });

                $('.place-image img').click(function(){
                    var parent = $(this).parent();
                    $('.profile-image').remove();
                    $('.place-image img').removeClass('select-image');
                    $(this).addClass('select-image');
                    parent.append('<span class="profile-image">Zdjęcie profilowe ogłoszenia</span>');
                    $("div.image-box").each(function(index, value) {
                        var item = $(value);
                        if(parent.data('id') == item.data('id')){
                            var nr = index +1;
                            $('input#profile-image').val(nr);
                            return;
                        }
                    });
                });

                $('.addimage').fadeIn('slow').append('<div id="filediv add-file'+ file_ids +'"><input name="photo[]" type="file" id="photo"/></div>');
                file_ids++;
            }
        });

        function imageIsLoaded(e) {
            $('#previewimg' + ids).attr('src', e.target.result);
        };

        $('button.buttonPoint').click(function () {
            var link = $(this).attr('link');
            window.location = link;
        });
    },
    ajaxSender: function () {
        $('.ajaxSender').click(function (event) {
            var href = $(this).data('href');
            var id = $(this).data('id');
            var details = {id: id};
            var object = this;
            $.ajax({
                type: "POST",
                url: href,
                dataType: "json",
                data: details
            })
            .success(function (msg) {
                if(msg.success){
                    $(object).html(msg.param.html);
                }
            })
            .error(function(msg) {
                console.log(msg);
            });
        })
    },
    preview: function() {
        $('#preview-advert').click(function(event){
            event.preventDefault();
            var description = tinyMCE.get('description').getContent();
            $('#description').val(description);
            var formValid = $('#addAdvertForm').valid();
            if(formValid){
                var fields = RecMetals.tools.serializeObject($('#addAdvertForm'));
                var photos = new Array();
                $('.place-image img').each(function(index, value){
                    var item = $(value);
                    photos[index] = item.attr('src');
                });
                fields.photos = photos;
                fields.description = description;
                var dateTime = new Date();
                var day = (dateTime.getDate() < 10 ? "0" : "") + dateTime.getDate(),
                    month = (dateTime.getMonth() < 10 ? "0" : "") + dateTime.getMonth(),
                    year = dateTime.getFullYear(),
                    hours = (dateTime.getHours() < 10 ? "0" : "") + dateTime.getHours(),
                    minute = (dateTime.getMinutes() < 10 ? "0" : "") + dateTime.getMinutes(),
                    secunte = (dateTime.getSeconds() < 10 ? "0" : "") + dateTime.getSeconds()
                    fields.date = day + '-' + month + '-' + year + ' ' + hours + ':' + minute + ':' + secunte;
                $.get('/views/preview-add-advert.htm', {requestId : RecMetals.tools.generateRadomId(24)}, function(template) {
                    $.tmpl(template,
                        fields
                    ).appendTo('body');

                    $('.back-modal').click(function(){
                        $(this).remove();
                        $('.ad-modal').remove();
                    });
                });
            }
        });
    },
    advertAdd: function() {
        $('form#form_advert').submit(function(e){
            var emptyinputs = $(this).find('input').filter(function(){
                return !$.trim(this.value).length;  // get all empty fields
            }).prop('disabled',true);
        });

        $('.selectpicker').selectpicker({
            showSubtext: true,
            showIcon: false,
            size: false
        });

        $('.advert_type').click(function(){
            visibilityBox($(this).val(), 1, '.sell-option-box');
            if($(this).val() == 2){
                $('.prices-text').html('Za całość');
                $('.sell_option').attr('checked',false);
            }
        });

        $('.sell_option').click(function(){
            if($(this).val() == 3){
                var amountType = document.getElementById("amount_type").value;
                amountType = checkAmountType(amountType);
                $('.prices-text').html(amountType);
            }else{
                $('.prices-text').html('Za całość');
            }
        });

        $('.sortpicker').on('change', function() {
            $('#sortInput').val($(this).val());
            $('form#form_advert').submit();
        });

        $('ul#resultPage li a').click(function(event) {
            event.preventDefault();
            $('#resultsInput').val($(this).html());
            $('form#form_advert').submit();
            return false;
        });

        $('#amount_type').on('change', function() {
            var isChecked = $("input[name='sell_option']:checked").val()
            if(isChecked == 3){
                $('.prices-text').html(checkAmountType($(this).val()));
            }
        });

        $('.transport').click(function(){
            visibilityBox($(this).val(), 1, '.transport-amount-box');
        });

        function checkAmountType(amountType){
            if(amountType == 'Metr2'){
                amountType = 'Metr <sup>2</sup>';
            }

            if(amountType == 'Metr3'){
                amountType = 'Metr <sup>3</sup>';
            }
            return amountType;
        }

        $('#attach').on('change', function() {
            var input = document.getElementById("attach");
            var ul = document.getElementById("fileList");
            while (ul.hasChildNodes()) {
                ul.removeChild(ul.firstChild);
            }
            $('.attach-list').css('display', 'block');

            for (var i = 0; i < input.files.length; i++) {
                var li = document.createElement("li");
                li.innerHTML = input.files[i].name;
                ul.appendChild(li);
            }
            if(!ul.hasChildNodes()) {
                var li = document.createElement("li");
                li.innerHTML = 'No Files Selected';
                ul.appendChild(li);
            }
        });

        function visibilityBox(input, value, box) {
            var active = input == value ? 'block' : 'none';
            $(box).css('display', active);
        }
    },
    categoryView: function () {
        $('li span.tree').click(function (event) {
            event.preventDefault();
            category(this);
        })

        function category(ids) {
            var tree = $('#category-tree-view');
            var href = tree.attr('data-url');
            var id = $(ids).attr('data-id');
            $.ajax({
                type: "POST",
                url: href,
                dataType: "json",
                data: {id: id},
                async: true
            })
                .success(function (msg) {
                    tree.html(' ');
                    $.each( msg.param, function( index, value ){
                        var result = generateTree(value, false);

                        var entMain = result == '' ? '' : '<ul>'+result+'</ul>';
                        tree.append('<li><span class="tree" data-id="'+value.id+'">'+value.name+'</span>'+entMain+'</li>');
                    });

                    $('li span.tree').click(function (event) {
                        category(this);
                    })

                    $('li span.view').click(function (event) {
                        $('li span.view').removeClass('active_category');
                        $('#category_id').val($(this).attr('data-id'));
                        $(this).addClass('active_category');
                        $('#select-category').css('display', 'block');
                        $('#select-category span.category-name').html($(this).text());
                    })

                    $('#select-category span.delete').click(function(){
                        $('li span.view').removeClass('active_category');
                        $('#select-category').css('display', 'none');
                        $('#select-category span.category-name').html('');
                        $('#category_id').val('');
                    });

                    function generateTree(value, view) {
                        var html = '';
                        if(typeof(value.sub) != 'undefined' || value.sub != null){
                            $.each( value.sub, function( index, value2 ){
                                var result = generateTree(value2, true);
                                var entSub = result == '' ? '' : '<ul>'+result+'</ul>';
                                var class_sb = value2.child == false || value2.child == 'false'  ? 'class="view"' : 'class="tree"';
                                html += '<li><span '+class_sb+' data-id="'+value2.id+'">'+value2.name+'</span>'+entSub+'</li>';
                            });
                        }
                        return html;
                    }
                })
        }
    },
    offerSend: function() {
        $('.acceptOffer').click(function(){
            $("#offerSend").submit();
        });
    },
    offerAmount: function() {
        $("#offerAmount").submit(function( event ) {
            var current = $(this.currentAmount).val();
            var amount = $(this.amount).val();

            var reg = /^\d+(\,\d{1,2})?$/g;

            $('.flash-messages-offer').html(' ');
            if(!reg.test(amount)){
                $('.flash-messages-offer').html('<div class="alert alert-danger text-center">Wpisałeś niepoprawną wartość.</div>');
                event.preventDefault();
                return false;
            }
            if(current > amount){
                $('.flash-messages-offer').html('<div class="alert alert-danger text-center">Cena jest zbyt niska.</div>');
                event.preventDefault();
                return false;
            }
        });
    },
    plugins: function() {
        $(".ibuttonCheck").iButton({
            labelOn: "Włączone",
            labelOff: "Wyłączone",
            enableDrag: false
        });

        $(document).ajaxStart(function(){
            $('body').append('<div id="loadingProgressG"><div id="loadingProgressG_1" class="loadingProgressG"></div>');
        });
        $(document).ajaxComplete(function(){
            $("#loadingProgressG").remove();
        });
    }
}

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    RecMetals.init();
    RecMetals.ajaxSender();
    RecMetals.categoryView();
    RecMetals.advertAdd();
    RecMetals.preview();

    RecMetals.offerSend();
    RecMetals.offerAmount();
    RecMetals.plugins();
});