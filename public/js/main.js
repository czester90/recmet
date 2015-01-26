var RecMetals = {
    init: function () {
        tinymce.init({
            selector: "textarea.editor",
            plugins: [
                "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern"
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
                $('.place-image').append("<div class='col-xs-6 col-md-4 mar-top15 add-image"+ ids +"' data-id='"+ ids +"'><img alt='100%x180' id='previewimg" + ids + "' data-src='holder.js/100%x180' style='height: 180px; width: 100%; display: block;' src='' data-holder-rendered='true'></div>");

                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);

                $(this).hide();
                $(".add-image"+ ids).append('<button type="button" class="close img-remove" aria-label="Close"><span aria-hidden="true">&times;</span></button>').click(function() {
                    var removeId = $(this).attr('data-id');
                    $(".add-image"+ removeId).remove();
                    $(".add-file"+ removeId).remove();
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
    advertAdd: function() {
        //$("[data-toggle='tooltip']").tooltip();

        $('.advert_type').click(function(){
            visibilityBox($(this).val(), 1, '.sell-option-box');
            if($(this).val() == 2){
                $('.amount-type-box').css('display', 'none');
                $('.sell_option').attr('checked',false);
            }else{
                $('.amount-type-box').css('display', 'block');
            }
        });

        $('.transport').click(function(){
            visibilityBox($(this).val(), 2, '.transport-amount-box');
        });

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
                data: {id: id}
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
                    })

                    function generateTree(value, view) {
                        var html = '';
                        if(typeof(value.sub) != 'undefined' || value.sub != null){
                            $.each( value.sub, function( index, value2 ){
                                var result = generateTree(value2, true);
                                var entSub = result == '' ? '' : '<ul>'+result+'</ul>';
                                var class_sb = view ? 'class="view"' : 'class="tree"';
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
    }
}

$(document).ready(function () {
    RecMetals.init();
    RecMetals.ajaxSender();
    RecMetals.categoryView();
    RecMetals.advertAdd();

    RecMetals.offerSend();
    RecMetals.offerAmount();
});