var url = window.location.pathname;
var language = url.split('/')[1];
var product_language;
$(document).ready(function () {
    var body = $('body');

    if(!$('#product-category_id').val()) {
        setTimeout(function () {
            $('#product-category_id').val(0);
            $('#product-category_id').trigger('change')
        }, 500)
    }


    var $select2Event = $('#product-category_id');
    //$select2Event.on('change', function () {
    //    var category_id = $(this).val();
    //    if (category_id == '') {
    //        $('.area').html('')
    //    } else {
    //
    //    }
    //});

    var spl_url = url.split("/"), controller = spl_url[1];
    $('li.menu-link a').each(function (i, v) {
        var href = $(v).attr('href').split("/");
        if (href[1] == controller) {
            $('li.menu-link').removeClass('active');
            $(this).parent('li.menu-link').addClass('active');
        }
    });
    $('.menu-link a').click(function () {
        $('.menu-link').removeClass('active');
        $(this).parent('.menu-link').addClass('active');
    });
    $('.image-preview').click(function () {
        $('.image-preview').removeClass('default-view');
        $(this).addClass('default-view');
    });
    $(function () {
        function updateOrdering(selector) {
            var ordering = {};
            $('#tbl_' + selector+' .ui-sortable tr').each(function (i, v) {
                $(this).attr('data-pjax', i + 1);
                ordering[i] = {
                    id: $(this).attr('data-key'),
                    ordering: $(this).attr('data-pjax')
                }
            });
            $.ajax({
                method: 'post',
                data: ordering,
                url: '/' + language + '/' + selector + '/update-ordering',
                success: function (res) {
                }
            });

        }
        //for inline ordering
        var tbl_category = $("#tbl_category> tbody");
        var tbl_cities = $("#tbl_cities> tbody");
        tbl_category.sortable();
        // tbody.disableSelection();
        tbl_category.sortable({
            stop: function (event, ui) {
                updateOrdering('category')
            }
        });
		
		tbl_cities.sortable({
            stop: function (event, ui) {
                updateOrdering('cities')
            }
        });
		
        // $( "#tbl_product> tbody" ).disableSelection();
    });

    $("#customerCreate").validate({
        rules: {},
        onfocusout: function (element) {
            $(element).valid();
        },
        errorClass: 'has-error',
        validClass: 'has-success',
        errorPlacement: function (error, element) {
            element.parent().next('.help-block').html(error.text())
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parent().parent().parent().addClass(errorClass).removeClass(validClass);
            $(element).parent().parent().parent().addClass(errorClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parent().parent().parent().removeClass(errorClass).addClass(validClass);
            $(element).parent().parent().parent().removeClass(errorClass);
        }
    });
    $.validator.addMethod(
            "regex",
            function (value, element, regexp) {
                var re = new RegExp(regexp);
                console.log(re);
                return this.optional(element) || value.match(re);
            },
            "Please check your input."
            );

    // add new address start!
    body.on('click', '#add-address', function () {
        var c = 0;
        $('#tab1_2').find('.br_').each(function () {
            c += 1;
        });
        var def_addr = $('.b_' + (c - 1)).clone();
        var val = [];

        def_addr.find('input[type=text]').each(function (i, v) {
            var b = v.value;
            var t = typeof b;
            if ((b != '') && (t !== 'undefined')) {
                val.push(b);
            }
            var str = $(v).attr('name'),
                    name = 'default';
            if (c > 1) {
                name = 'address' + (c - 1);
            }
            var sid = $(v).attr('id'),
                    id = '';
            if (c > 1) {
                id = c - 1
            }
            var res_id = sid.replace(id, c);
            $(v).attr('id', res_id);
            var res = str.replace(name, 'address' + c);
            $(v).attr('name', res);
            $(v).val('');
        });
        if (val.length >= 1) {
            var cl = def_addr.attr('class');
            var newcl = cl.replace('b_' + (c - 1), 'b_' + c);
            def_addr.attr('class', newcl);
            def_addr.find('.has-success').removeClass('has-success');
            def_addr.find('.has-error').removeClass('has-error');
            def_addr.removeAttr('id');
            def_addr.find('.section-divider > span').text("Address" + c);
            c += 1;
            $(def_addr).appendTo($('#default-address').parent());
            $('.addr-err').remove();
            console.log("#customeraddress-lat" + (c - 1));
            $("#customeraddress-lat" + (c - 1)).rules("add", {
                regex: "^[a-zA-Z ]*$",
                messages: {
                    regex: "Enter valid lat."
                }
            });
            $("#customeraddress-long" + (c - 1)).rules("add", {
                regex: "^[a-zA-Z ]*$",
                messages: {
                    required: "Enter valid long."
                }
            });
            $("#customeraddress-city" + (c - 1)).rules("add", {
                regex: "^[a-zA-Z ]*$",
                messages: {
                    regex: "Enter valid city name."
                }
            });
            $("#customeraddress-address" + (c - 1)).rules("add", {
                regex: "^[A-Za-z0-9'\.\-\s\,\\]",
                messages: {
                    regex: "Amount in stock must be an integer"
                }
            });
            $("#customeraddress-state" + (c - 1)).rules("add", {
                regex: "^[a-zA-Z ]*$",
                messages: {
                    regex: "Enter valid state name."
                }
            });
            $("#customeraddress-country" + (c - 1)).rules("add", {
                regex: "^[a-zA-Z ]*$",
                messages: {
                    regex: "Enter valid country name."
                }
            });
        } else {
            if ($('.addr-err').length == 0) {
                $('.b_' + (c - 1) + ' > #spy1').append('<p class="addr-err">Please write in fields before add new address!</p>');
            }
        }


    });
    // add address end
    // remove address
    body.on('click', '#remove-address', function () {
        var c = 0;
        $('#tab1_2').find('.br_').each(function () {
            c += 1;
        });
        if (c != 1) {
            c -= 1;
            $('.b_' + c).remove();
            $("#customeraddress-lat" + c).rules("remove");
            $("#customeraddress-long" + c).rules("remove");
            $("#customeraddress-city" + c).rules("remove");
            $("#customeraddress-address" + c).rules("remove");
            $("#customeraddress-state" + c).rules("remove");
            $("#customeraddress-country" + c).rules("remove");
            return true;
        } else {
            return false;
        }
    });
    // remove address end

    body.on('click', '.image-preview1', function () {
        $('.image-preview1').removeClass('default-img');
        $(this).addClass('default-img');
        var key = $(this).attr('data-id');
        $('#def_img_part_-1').val(key);
        $('#def_img_part_-1').prop('checked', true);

    });

    body.on('submit', '#productCreate', (function (e) {
        e.preventDefault();
        var form = $('#productCreate');
        var url = form.attr('action');
        var formData = new FormData(this);
        $.ajax({
            method: 'POST',
            url: url,
            data: formData,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell
            success: function (res) {
                if (res) {
                    document.getElementById("productCreate").reset();
                    $.pjax.reload({container: '#productPjaxtbl'});
                }
            }
        });
    }));

//    body.on('submit', '#productUpdate', (function (e) {
//        e.preventDefault();
//        var form = $('#productUpdate');
//        var url = form.attr('action');
//        if ($('input#product_id').val() != "") {
//            var product_id = $('input#product_id').val();
//        } else {
//            var product_id = "";
//        }
//        var formData = new FormData(this);
//        formData.append('product_id',product_id);
//        formData.append('language', product_language);
//        $.ajax({
//            method: 'POST',
//            url: url,
//            data: formData,
//            cache: false,
//            dataType: 'json',
//            processData: false, // Don't process the files
//            contentType: false, // Set content type to false as jQuery will tell
//            success: function (res) {
//                if (res) {                  
//                    document.getElementById("productUpdate").reset();
//                    $.pjax.reload({container: '#productPjaxtbl'});
//                }
//            }
//        });
//    }));
    body.on('submit', '#repairerCreate', (function (e) {
        e.preventDefault();
        var form = $('#repairerCreate');
        var url = form.attr('action');
        var formData = form.serialize();
        $.ajax({
            method: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function (res) {
                if (res) {
                    document.getElementById("repairerCreate").reset();
                    $.pjax.reload({container: '#repaierPjaxtbl'});
                }
            }
        });
    }));
    body.on('submit', '#repairerUpdate', (function (e) {
        e.preventDefault();
        var form = $('#repairerUpdate');
        var url = form.attr('action');
        var formData = form.serialize();
        $.ajax({
            method: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function (res) {
                if (res) {
                    document.getElementById("repairerUpdate").reset();
                    $.pjax.reload({container: '#repaierPjaxtbl'});
                }
            }
        });
    }));

    body.on('submit', '#customerCreate', (function (e) {
        e.preventDefault();
        var form = $('#customerCreate');
        var url = form.attr('action');
        var formData = form.serialize();
        $.ajax({
            method: 'POST',
            url: url,
            data: formData,
            success: function (res) {
                if (res) {
                    document.getElementById("customerCreate").reset();
                    $.pjax.reload({container: '#customerPjaxtbl'});
                }
            }
        });
    }));

    body.on('submit', '#repairCreate', (function (e) {
        e.preventDefault();
        var form = $('#repairCreate');
        var url = form.attr('action');
        var formData = form.serialize();
        $.ajax({
            method: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function (res) {
                if (res) {
                    document.getElementById("repairCreate").reset();
                    $.pjax.reload({container: '#repairPjaxtbl'});
                }
            }
        });
    }));
    body.on('submit', '#repairUpdate', (function (e) {
        e.preventDefault();
        var form = $('#repairUpdate');
        var url = form.attr('action');
        var formData = form.serialize();
        $.ajax({
            method: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function (res) {
                if (res) {
                    document.getElementById("repairUpdate").reset();
                    $.pjax.reload({container: '#repairPjaxtbl'});
                }
            }
        });
    }));
    /* body.on('submit', '#brandCreate', (function (e) {
        e.preventDefault();
       // e.stopImmediatePropagation();
        var form = $('#brandCreate');
        var url = form.attr('action');
        var formData = new FormData(this);
        $.ajax({
            method: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function (res) {
                if (res) {
                    $('#admin-alerts div').html('Brand successfully created');
                    $('#admin-alerts').show();
                    document.getElementById("brandCreate").reset();
                    $.pjax.reload({container: '#brandPjaxtbl'});
                }
            }
        });
    })); */

    body.on('submit', '#serviceCreate', (function (e) {
        e.preventDefault();
        var form = $('#serviceCreate');
        var url = form.attr('action');
        var formData = new FormData(this);
        $.ajax({
            method: 'POST',
            url: url,
            data: formData,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell
            success: function (res) {
                if (res) {
                    document.getElementById("serviceCreate").reset();
                    $.pjax.reload({container: '#servicePjaxtbl'});
                }
            }
        });
    }));

    body.on('submit', '#serviceUpdate', (function (e) {
        e.preventDefault();
        var form = $('#serviceUpdate');
        var url = form.attr('action');
        var formData = new FormData(this);
        $.ajax({
            method: 'POST',
            url: url,
            data: formData,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell
            success: function (res) {
                if (res) {
                    document.getElementById("serviceUpdate").reset();
                    $.pjax.reload({container: '#servicePjaxtbl'});
                }
            }
        });
    }));

    /* body.on('submit', '#attrCreate', (function (e) {
        e.preventDefault();
        var form = $('#attrCreate');
        var url = form.attr('action');
        var formData = new FormData(this);
        $.ajax({
            method: 'POST',
            url: url,
            data: formData,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell
            success: function (res) {
                if (res) {
                    document.getElementById("attrCreate").reset();
                    $.pjax.reload({container: '#attrPjaxtbl'});
                }
            }
        });
    })); */
    
    /* body.on('submit', '#attrUpdate', (function (e) {
        e.preventDefault();
        var form = $('#attrUpdate');
        var url = form.attr('action');
        var formData = new FormData(this);
        $.ajax({
            method: 'POST',
            url: url,
            data: formData,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell
            success: function (res) {
                if (res) {
                    document.getElementById("attrUpdate").reset();
                    //$.pjax.reload({container: '#attrPjaxtbl'});
                    $.pjax.reload({container: '#attrPjaxForm'});
                }
            }
        });
    })); */

    // body.on('submit', '#categoryCreate', (function (e) {
    //     e.preventDefault();
    //     var form = $('#categoryCreate');
    //     var url = form.attr('action');
    //     var formData = form.serialize();
    //     $.ajax({
    //         method: 'POST',
    //         url: url,
    //         data: formData,
    //         dataType: 'json',
    //         success: function (res) {
    //             if (res) {
    //                 document.getElementById("categoryCreate").reset();
    //                 $.pjax.reload({container: '#categoryPjaxtbl'});
    //             }
    //         }
    //     });
    // }));
//    body.on('submit', '#categoryUpdate', (function (e) {
//        e.preventDefault();
//        var form = $('#categoryUpdate');
//        var url = form.attr('action');
//        var formData = form.serialize();
//        $.ajax({
//            method: 'POST',
//            url: url,
//            data: formData,
//            dataType: 'json',
//            success: function (res) {
//                if (res) {
//                    document.getElementById("categoryUpdate").reset();
//                    $.pjax.reload({container: '#categoryPjaxtbl'});
//                }
//            }
//        });
//    }));

    $('#product-imageFiles').on('fileselect', function (event, numFiles, label) {
        var first = $('.file-live-thumbs').find('.kv-file-content').first();
        first.parent().addClass('default-view');
        var index = first.parent().attr('data-fileindex');
        var key = parseInt(index);
        $('#def_img').val(key + 1);
        $('#def_img').prop('checked', true);
    });
});

jQuery(document).ready(function () {
    var body = $('body');
    "use strict";


    // Add Gallery Item to Lightbox
    $('.mix img').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
        },
        image: {
            titleSrc: false
        },
        callbacks: {
            beforeOpen: function (e) {
                // we add a class to body to indicate overlay is active
                // We can use this to alter any elements such as form popups
                // that need a higher z-index to properly display in overlays
                body.addClass('mfp-bg-open');

                // Set Magnific Animation
                this.st.mainClass = 'mfp-zoomIn';

                // Inform content container there is an animation
                this.contentContainer.addClass('mfp-with-anim');
            },
            afterClose: function (e) {

                setTimeout(function () {
                    body.removeClass('mfp-bg-open');
                    $(window).trigger('resize');
                }, 1000)

            },
            elementParse: function (item) {
                // Function will fire for each target element
                // "item.el" is a target DOM element (if present)
                // "item.src" is a source that you may modify
                item.src = item.el.attr('src');
            },
        },
        overflowY: 'scroll',
        removalDelay: 200, //delay removal by X to allow out-animation
        prependTo: $('#content_wrapper')
    });
	
	    $('.mix2 img').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
        },
        image: {
            titleSrc: false
        },
        callbacks: {
            beforeOpen: function (e) {
                // we add a class to body to indicate overlay is active
                // We can use this to alter any elements such as form popups
                // that need a higher z-index to properly display in overlays
                body.addClass('mfp-bg-open');

                // Set Magnific Animation
                this.st.mainClass = 'mfp-zoomIn';

                // Inform content container there is an animation
                this.contentContainer.addClass('mfp-with-anim');
            },
            afterClose: function (e) {

                setTimeout(function () {
                    body.removeClass('mfp-bg-open');
                    $(window).trigger('resize');
                }, 1000)

            },
            elementParse: function (item) {
                // Function will fire for each target element
                // "item.el" is a target DOM element (if present)
                // "item.src" is a source that you may modify
                item.src = item.el.attr('src');
            },
        },
        overflowY: 'scroll',
        removalDelay: 200, //delay removal by X to allow out-animation
        prependTo: $('#content_wrapper')
    });

    $('.cust_change_status').click(function () {
        var data = $(this).attr('data-pjax');
        $.ajax({
            url: '/' + language + '/customer/changestatus',
            method: 'post',
            data: {data: data},
            success: function (res) {
                if (res == 'true') {
                    $.pjax.reload({container: '#customerPjaxtbl'});
                }
            }
        });
    });

    $('.prod_change_status ').click(function () {
        var data = $(this).find('a').attr('data-pjax');

        $.ajax({
            url: '/' + language + '/product/changestatus',
            method: 'post',
            data: {data: data},
            success: function (res) {
                if (res == 'true') {
                    $.pjax.reload({container: '#productPjaxtbl'})
                }
            }
        });
        return false;
    });

    $('.rep_change_status').click(function () {
        var data = $(this).attr('data-pjax');

        $.ajax({
            url: '/' + language + '/repairer/changestatus',
            method: 'post',
            data: {data: data},
            success: function (res) {
                if (res == 'true') {
                    $.pjax.reload({container: '#repaierPjaxtbl'})
                }
            }
        });
    });
//    $('.br_change_status').click(function () {
//        var data = $(this).attr('data-pjax');
//
//        $.ajax({
//            url: '/' + language + '/brand/changestatus',
//            method: 'post',
//            data: {data: data},
//            success: function (res) {
//                if (res == 'true') {
//                    $.pjax.reload({container: '#brandPjaxtbl'})
//                }
//            }
//        });
//    });
    $('.ser_change_status').click(function () {
        var data = $(this).attr('data-pjax');

        $.ajax({
            url: '/' + language + '/service/changestatus',
            method: 'post',
            data: {data: data},
            success: function (res) {
                if (res == 'true') {
                    $.pjax.reload({container: '#servicePjaxtbl'})
                }
            }
        });
    });
    $('.delete-product').click(function () {
        var data = $(this).find('a').attr('data-pjax');
        $.ajax({
            url: '/' + language + '/product/delete',
            method: 'post',
            data: {id: data},
            success: function (res) {
                if (res == '1') {
                    location.reload();
                }
            }
        });
    });
    $('button[type="reset"]').click(function () {
        var form = $(this).closest('form');
        form[0].reset();
        location.href = form.attr('action');
    })
});

function searchCategoryChange(elem) {
    let cat = $(elem).val();
    if(cat == 2 || cat == 4) {
        $('#search-land-size-container').show()
    } else {
        $('#search-land-size-container').hide()
        $('input[name=land-size-from]').val(0)
        $('input[name=land-size-to]').val(0)
    }
}

function getProductAttr(category_id, language) {
    $.ajax({
        method: 'post',
        url: '/' + language + '/product/product-details',
        data: {category_id: category_id},
        success: function (res) {
            var input = '';
            for (var key in res) {
                var uc = res[key].charAt(0).toUpperCase() + res[key].slice(1).toLowerCase();
                input += '<div class="form-group field-productattribute-value">' +
                        '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">' +
                        '<input class="form-control" name="ProductAttribute[value][' + key + ']" placeholder="' + uc + '" type="text">' +
                        '<label for="customer-name" class="field-icon"><i class="fa fa-tags"></i></label>' +
                        '</label><div class="help-block"></div></div></div>';
            }
            $('.area').html(input);
        }
    });
}

function translatePage(category, language) {
    if (category == 'category') {
        var category_id = "";
        if ($('input#ctegory_id').val() != "") {
            var category_id = $('input#ctegory_id').val();
        }
        var category_name = $('#trcategory-name').val();
        var category_short_description = $('#trcategory-short_description').val();
        var category_description = $('#trcategory-description').val();
        var json_data = {
            'name': category_name,
            'short_description': category_short_description,
            'description': category_description,
            'language': language,
            'category_id': category_id
        }
        $.ajax({
            method: 'post',
            url: '/' + language + '/category/create',
            data: {formData: json_data},
            success: function (res) {
                var obj = jQuery.parseJSON(res);
                console.log(obj)
                $('input#ctegory_id').val(obj.category_id);
                $('.table-responsive').html(obj.html);
            }
        });
    }
    if (category == 'attribute') {
        var attribute_id = "";
        if ($('input#ctegory_id').val() != "") {
            var attribute_id = $('input#attribute_id').val();
        }
        var attribute_name = $('#trattribute-name').val();
        var tr_category_id = $('#trattribute-category_id').val();
        var json_data = {
            'name': attribute_name,
            'tr_category_id': tr_category_id,
            'language': language,
            'attribute_id': attribute_id
        }
        $.ajax({
            method: 'post',
            url: '/' + language + '/attribute/create',
            data: {formData: json_data},
            success: function (res) {
                var obj = jQuery.parseJSON(res);
                console.log(obj)
                $('input#attribute_id').val(obj.attribute_id);
                $('.table-responsive').html(obj.html);
            }
        });
    }

    if (category == 'product') {
       console.log(132)
        product_language = language;
        $('form#productUpdate').submit();
        
    }

}
function changeStatus(data){
        $.ajax({
            url: '/' + language + '/brand/changestatus',
            method: 'post',
            data: {data: data},
            success: function (res) {
                if (res == 'true') {
                    $.pjax.reload({container: '#brandPjaxtbl'})
                }
            }
        });
}
function changeCoutryStatus(data){
    $.ajax({
        url: '/' + language + '/countries/changestatus',
        method: 'post',
        data: {data: data},
        success: function (res) {
            if (res == 'true') {
                $.pjax.reload({container: '#countryPjaxtbl'})
            }
        }
    });
}
function addPrice(){
    var price = $("input[name=price]").val();
    var weight_from = $("input[name=weight_from]").val();
    var weight_to = $("input[name=weight_to]").val();
    var zone = $("input[name=zone]").val();

    $.ajax({
        url: '/' + language + '/zones/add-price',
        method: 'post',
        data: {price:price,weight_from:weight_from,weight_to:weight_to,zone_id:zone},
        success: function (res) {
             $('#zone_prices').html(res);
        }
    });
}

function updatePrice(id){
    var price = $("input[name='price_"+id+"']").val();
    var weight_from = $("input[name='weight_from_"+id+"']").val();
    var weight_to = $("input[name='weight_to_"+id+"']").val();
    var zone = $("input[name=zone]").val();

    $.ajax({
        url: '/' + language + '/zones/update-price',
        method: 'post',
        data: {price:price,weight_from:weight_from,weight_to:weight_to,zone_id:zone, id: id},
        success: function (res) {
            $('#zone_prices').html(res);
        }
    });
}

function deletePrice(id){
    var zone = $("input[name=zone]").val();
       $.ajax({
        url: '/' + language + '/zones/delete-price',
        method: 'post',
        data: {zone_id:zone,id:id},
        success: function (res) {
            $('#zone_prices').html(res);
        }
    });
}


/* $(document).ready(function () {
    fillAddress('#productaddress-city_id', false);
}) */

function seacrhFillRegion(element) {
    var cityId = $(element).val()
    console.log(cityId);
    if(!cityId && cityId !== 0) {
        $('#productaddress-city').html('');
        $('#productaddress-city').trigger('change');

        $('#productaddress-address').html('');
        $('#productaddress-address').val('');
        $('#productaddress-address').trigger('change');
        return;
    }

    $.ajax({
        url: '/' + language + '/address/get-city',
        method: 'post',
        data: {statId:cityId},
        success: function (res) {
            var obj = JSON.parse(res);
            var str = "";
            for (var key in obj) {
                str += "<option value=" + key + ">" + obj[key] + "</option>"
            }
            $('#productaddress-city').html(str)
            $('#productaddress-city').prop('disabled', false);
            $('#productaddress-city').trigger('change');

            // $('#productaddress-city_id').material_select();
        }
    });
}

function searchFillAddress(element, isSingle=false) {
    var cityIds = $(element).val()

    console.log(cityIds);
    if(!cityIds) {
        $('#productaddress-address').trigger('change');
        return;
    }
    if(isSingle) {
        cityIds = [cityIds];
    }
    $.ajax({
        url: '/' + language + '/address/get-address-multiple',
        method: 'post',
        data: {cityIds:cityIds},
        success: function (res) {
            var obj = JSON.parse(res);

            var str = "";
            for (var key in obj) {
                str += "<option value=" + key + ">" + obj[key] + "</option>"
            }

            if(isSingle) {
                $('#fix-new-product-road').html(str)
                $('#fix-new-product-road').prop('disabled', false);
                $('#fix-new-product-road').trigger('change');
            } else {
                $('#productaddress-address').html(str)
                $('#productaddress-address').prop('disabled', false);
                $('#productaddress-address').trigger('change');
            }
        }
    });
}

function uncheckCheckbox(element) {
	if ($(element).is(':checked')) {
		$(element).val(1)
	} else {
		$(element).val(0)
	}
}

function fixNewProductAdd() {
    if($('#fix-new-product-accept-button').hasClass('disabled')) {
        return;
    }
    $('#fix-new-product-accept-button').addClass('disabled')
    $('#fix-new-product-error').hide();

    let data = {
        state : $('#fix-new-product-city').val(),
        road : $('#fix-new-product-road').val(),
        building_number : $('#fix-new-product-building-number').val(),
        app_number : $('#fix-new-product-appartment-number').val(),
        mobile : $('#fix-new-mobile').val(),
        source : $('#fix-new-source').val(),
        buyer : $('#buyer-name').val(),
        price : $('#fix-new-product-appartment-price').val(),
        level : $('#fix-new-product-appartment-level').val(),
		room : $('#fix-new-product-appartment-room').val(),
    }

    let errorText = {
        state : 'Լրացրեք համայնքը',
        road : 'Լրացրեք փողոցը',
        building_number : 'Լրացրեք Շենք Համարը',
        app_number : 'Լրացրեք ԲՆ. Համարը',
        mobile : 'Լրացրեք Հեռախոսահամարը',
        source : 'Լրացրեք Աղբյուրը',
        buyer : 'Լրացրեք Վաճառողի Անունը',
        price : 'ԲՆ. Գին',
        level : 'ԲՆ. Հարկ',
		room : 'Սենյակների Քանակը',
    }
    function error(txt) {
        $('#fix-new-product-error').html(txt);
        $('#fix-new-product-error').show();
    }

    for(var prop in data) {
        if(!data[prop].length) {
            error(errorText[prop]);
            return;
        }
    }
    $.ajax({
        url: '/' + language + '/product/fix-new-product',
        method: 'post',
        data: data,
        success: function (res) {
            if(JSON.parse(res).success) {
                window.location.reload();
            } else {
                error('Տեղի է ունեցել սխալ, խնդրում ենք փորձել մի փոքր ուշ')
            }
        }
    });
}

function openRateModal() {
    $("#fix-new-product-modal").modal({
        escapeClose: false,
        clickClose: false,
    });
    $("#fix-new-product-modal").removeClass('fade');
}

function fillCity(element, div = false) {
	var statId = $(element).val()
    //$('#product-address').val('');
    if(!div && !statId) {
	    jQuery('#productaddress-city_id').prop('disabled', true);
	    return;
    }

    $.ajax({
        url: '/' + language + '/address/get-city',
        method: 'post',
        data: {statId:statId},
        success: function (res) {
             var obj = JSON.parse(res);
            var str = "";
            for (var key in obj) {
                str += "<option value=" + key + ">" + obj[key] + "</option>"
            }
            if(div) {
                jQuery(div).html(str)
                jQuery(div).prop('disabled', false);
                //jQuery(div).trigger('change');
            } else {
                jQuery('#productaddress-city_id').html(str)
                jQuery('#productaddress-city_id').prop('disabled', false);
                //jQuery('#productaddress-city_id').trigger('change');
            }

            // $('#productaddress-city_id').material_select();
        }
    });
}

function fillAddress(element, del) {
	var cityId = $(element).val()
    if(del) $('#product-address').val('');

    if(!cityId) {
	    jQuery('#product-address').prop('disabled', true);
        return;
    }

	 $.ajax({
        url: '/' + language + '/address/get-address',
        method: 'post',
        data: {cityId:cityId},
        success: function (res) {
             var obj = JSON.parse(res);
            var str = "";
            let arr = [];
            for (var key in obj) {
                console.log(key, obj[key])
                arr.push(obj[key]);
                str += "<option value=" + key + ">" + obj[key] + "</option>"
            }

            jQuery('#productaddress-address_id').html(str)
            jQuery('#productaddress-address_id').prop('disabled', false);
			jQuery('#productaddress-address_id').trigger('change');

            //$('#product-address').autocomplete({source: arr});
            // $('#productaddress-address_id').material_select();
        }
    });
}

function saveBrokerAddress() {
	var address = $('#broker_address').val();
	var broker = $('#brokerIds').val();
	$.ajax({
        url: '/' + language + '/brocker-addresses/save-broker-address',
        method: 'post',
        data: {address:address,broker:broker},
        success: function (res) {
             var obj = JSON.parse(res);
            console.log(obj['success'],obj['message'])
			if(!obj['success']) {
				$('#admin-alerts').removeClass('alert-success').addClass('alert-warning');
			} else {
				$('#admin-alerts').removeClass('alert-warning').addClass('alert-success');
			}
			$('#admin-alerts').find('div').html(obj['message']);
			$('#admin-alerts').show();
        }
    });
	return false;
}

function allowToAddProdcut(userId,allow) {
	$.ajax({
        url: '/' + language + '/user/allow-to-add-product',
        method: 'post',
        data: {userId:userId,allow:allow},
        success: function (res) {
			var obj = JSON.parse(res);
			if(obj['success']) {
				if(allow) {
					$('#allowCreateProdutc-'+userId).removeClass('btn-system').addClass('btn-warning');
				} else {
					$('#allowCreateProdutc-'+userId).removeClass('btn-warning').addClass('btn-system');
				}
				$('#admin-alerts').removeClass('alert-warning').addClass('alert-success');
				$('#admin-alerts').find('div').html('Successfully Updated');
				$('#admin-alerts').show();
			} else {
				
				$('#admin-alerts').removeClass('alert-success').addClass('alert-warning');
				$('#admin-alerts').find('div').html('Something Went Wrong');
				$('#admin-alerts').show();
			}
        }
    });
}

var imageId = null;

function allowDrop(ev, imageIdDrag) {
  ev.preventDefault();
  console.log(imageIdDrag,imageId)
  if(imageIdDrag) { 
	imageId = imageIdDrag;
  }
  console.log(imageId)
  ev.dataTransfer.setData("text/plain", imageId)
}

function drag(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
	ev.preventDefault();
	var data = ev.dataTransfer.getData("text");
	
	$.ajax({
		method: 'post',
		data: {imageId : imageId},
		url: '/' + language + '/product/update-image-type',
		success: function (res) {
				
		}
	});
	imageId = null;
}

function moveToSecure(imageId)
{
	var data = $('#product-image-'+imageId).attr('src');
	$.ajax({
		method: 'post',
		data: {imageId : imageId},
		url: '/' + language + '/product/update-image-type',
		success: function (res) {
				$('#subimages').append('<div class="col-md-1 mix label1 folder1" style="height: 240px;"><div class="panel p6 pbn"><div class="of-h"><img src="'+data+'" id="new-image-'+imageId+'" class="img-responsive"></div></div></div>')
				$('#product-image-'+imageId).parent().parent().parent().remove();
		}
	});
}

$(document).ready(function () {
    // PNotify Plugin Event Init
    $('.notification').on('click', function(e) {
        if ($(this).attr('title') == 'Available Product'){
            e.preventDefault();
        }
        var noteStyle = $(this).data('note-style');
        var noteShadow = $(this).data('note-shadow');
        var noteOpacity = $(this).data('note-opacity');
        var noteStack = $(this).data('note-stack');
        var notetitle = $(this).attr('title');
        var width = "220px";

        // If notification stack or opacity is not defined set a default
        var noteStack = noteStack ? noteStack : "stack_top_right";
        var noteOpacity = noteOpacity ? noteOpacity : "1";

        // We modify the width option if the selected stack is a fullwidth style
        function findWidth() {
            if (noteStack == "stack_bar_top") {
                return "100%";
            }
            if (noteStack == "stack_bar_bottom") {
                return "70%";
            } else {
                return "220px";
            }
        }
        PNotify.prototype.options.styling = "fontawesome";
        // Create new Notification
        new PNotify({
            title: notetitle,
            text: 'Product Available',
            shadow: noteShadow,
            opacity: noteOpacity,
            addclass: noteStack,
            type: noteStyle,
            // stack: Stacks[noteStack],
            width: findWidth(),
            delay: 1400
        });

    });
	
	
})