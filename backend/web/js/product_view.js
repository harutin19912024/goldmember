var url = window.location.pathname;
var language = url.split('/')[1];
function updateType(element) {
    var hidden = $(element).parent().parent().parent().next().find('input[type=hidden]');
    var selected = $(':selected', element);
    var label = selected.closest('optgroup').attr('label');
    if (label == 'Product') {
        $(hidden).val(0)
    }
    if (label == 'Part') {
        $(hidden).val(1)
    }

}
function removeProductDetails(details_id){
	$.ajax({
            url: '/' + language + '/product/remove-details',
            method: 'post',
            data: {details_id: details_id},
            success: function (res) {
				var obj = jQuery.parseJSON(res);
				console.log(obj.success, obj['success'])
				if(obj.success){
					$('tr#details-'+details_id).remove();
				}
                
            }
        });
}
function editBrandTr(lang, brand, isdefoult) {
    if (isdefoult === 1) {
        $(".tr-brand-form").hide();

    } else {
        $.ajax({
            url: '/' + language + '/tr-brand/update',
            method: 'post',
            data: {lang: lang, brand: brand},
            success: function (res) {
                $(".tab-pane").removeClass('active');
                $("#tr_brand").addClass('active');
                $('#tr_brand').html(res);
            }
        });
    }
}
function editPagesTr(lang, pages, isdefoult) {
    if (isdefoult === 1) {
        $(".tr-brand-form").hide();

    } else {
        $.ajax({
            url: '/' + language + '/tr-pages/update',
            method: 'post',
            data: {lang: lang, pages: pages},
            success: function (res) {
                $(".tab-pane").removeClass('active');
                $("#tr_pages").addClass('active');
                $('#tr_pages').html(res);
                CKEDITOR.replace('trpages-content');
            }
        });
    }
}
function editFaqTr(lang, faq, isdefoult) {
    if (isdefoult === 1) {
        $(".tr-brand-form").hide();

    } else {
        $.ajax({
            url: '/' + language + '/tr-faq/update',
            method: 'post',
            data: {lang: lang, faq: faq},
            success: function (res) {
                $(".tab-pane").removeClass('active');
                $("#tr_faq").addClass('active');
                $('#tr_faq').html(res);
                CKEDITOR.replace('trfaq-description');
            }
        });
    }
}
function editAttributeTr(lang, attribute, isdefoult) {
    if (isdefoult === 1) {
        $(".tr-brand-form").hide();
    } else {
        $.ajax({
            url: '/' + language + '/tr-attribute/update',
            method: 'post',
            data: {lang: lang, attribute: attribute},
            success: function (res) {
                $(".tab-pane").removeClass('active');
                $("#tr_attribute").addClass('active');
                $('#tr_attribute').html(res);
            }
        });
    }
}
function editCategoryTr(lang, category, isdefoult) {
    if (isdefoult === 1) {
        $(".tr-brand-form").hide();
    } else {
        $.ajax({
            url: '/' + language + '/tr-category/update',
            method: 'post',
            data: {lang: lang, category: category},
            success: function (res) {
                $(".tab-pane").removeClass('active');
                $("#tr_category").addClass('active');
                $('#tr_category').html(res);
                $('#admin-alerts').hide();
            }
        });
    }
}

function editNewsCategoryTr(lang, newscategory, isdefoult) {
    if (isdefoult === 1) {
        $(".tr-newscategory-form").hide();
    } else {
        $.ajax({
            url: '/' + language + '/tr-news-category/update',
            method: 'post',
            data: {lang: lang, newscategory: newscategory},
            success: function (res) {
                $(".tab-pane").removeClass('active');
                $("#tr_newscategory").addClass('active');
                $('#tr_newscategory').html(res);
                $('#admin-alerts').hide();
            }
        });
    }
}

function editAboutTr(lang, aboutus, isdefoult) {
    if (isdefoult === 1) {
        $("#aboutusUpdate").hide();
    } else {
        $.ajax({
            url: '/' + language + '/tr-aboutus/update',
            method: 'post',
            data: {lang: lang, aboutus: aboutus},
            success: function (res) {
                $(".tab-pane").removeClass('active');
                $("#tr_aboutus").addClass('active');
                $('#tr_aboutus').html(res);
                $('#admin-alerts').hide();
            }
        });
    }
}

function editProductTr(lang, product, isdefoult, defaultID) {
    if (isdefoult === 1) {

    } else {
        $.ajax({
            url: '/' + language + '/tr-product/update',
            method: 'post',
            data: {lang: lang, product: product},
            success: function (res) {
                // $(".tab-pane").removeClass('active');
                // $("#tr_product").addClass('active');
                // $('#tab_'+defaultID).hide();
                $('#tab_' + lang).html(res);
                $('#admin-alerts').hide();
            }
        });
    }
}
function editSliderTr(lang, slider, isdefoult) {
    if (isdefoult === 1) {
        $(".tr-slider-form").hide();
    } else {
        $.ajax({
            url: '/' + language + '/tr-slider/update',
            method: 'post',
            data: {lang: lang, slider: slider},
            success: function (res) {
                $(".tab-pane").removeClass('active');
                $("#tr_slider").addClass('active');
                $('#tr_slider').html(res);
                $('#admin-alerts').hide();
            }
        });
    }
}

function editBlogTr(lang, blog, isdefoult) {
    if (isdefoult === 1) {
        $(".tr-blog-form").hide();
    } else {
        $.ajax({
            url: '/' + language + '/tr-blog/update',
            method: 'post',
            data: {lang: lang, blog: blog},
            success: function (res) {
                $(".tab-pane").removeClass('active');
                $("#tr_blog").addClass('active');
                $('#tr_blog').html(res);
                $('#admin-alerts').hide();
            }
        });
    }
}

function editPartnersTr(lang, partners, isdefoult) {
    if (isdefoult === 1) {
        $(".tr-partners-form").hide();
    } else {
        $.ajax({
            url: '/' + language + '/tr-partners/update',
            method: 'post',
            data: {lang: lang, partners: partners},
            success: function (res) {
                $(".tab-pane").removeClass('active');
                $("#tr_partners").addClass('active');
                $('#tr_partners').html(res);
                $('#admin-alerts').hide();
            }
        });
    }
}

function editNewsTr(lang, news, isdefoult) {
    if (isdefoult === 1) {
        $(".tr-news-form").hide();
    } else {
        $.ajax({
            url: '/' + language + '/tr-news/update',
            method: 'post',
            data: {lang: lang, news: news},
            success: function (res) {
                $(".tab-pane").removeClass('active');
                $("#tr_news").addClass('active');
                $('#tr_news').html(res);
                if (typeof CKEDITOR !== 'undefined' && document.getElementById('trnews-content')) {
                    CKEDITOR.replace('trnews-content');
                }
                $('#admin-alerts').hide();
            }
        });
    }
}

function editMaterialTr(lang, material, isdefoult) {
    if (isdefoult === 1) {
        $(".tr-material-form").hide();
    } else {
        $.ajax({
            url: '/' + language + '/tr-material/update',
            method: 'post',
            data: {lang: lang, material: material},
            success: function (res) {
                $(".tab-pane").removeClass('active');
                $("#tr_material").addClass('active');
                $('#tr_material').html(res);
                $('#admin-alerts').hide();
            }
        });
    }
}

function editHomePageTr(lang, homepage, isdefoult) {
    if (isdefoult === 1) {
        $(".tr-homepage-form").hide();
    } else {
        $.ajax({
            url: '/' + language + '/tr-homepage/update',
            method: 'post',
            data: {lang: lang, homepage: homepage},
            success: function (res) {
                $(".tab-pane").removeClass('active');
                $("#tr_homepage").addClass('active');
                $('#tr_homepage').html(res);
                $('#admin-alerts').hide();
            }
        });
    }
}

function editPowerOfPennyTr(lang, powerofpenny, isdefoult) {
    if (isdefoult === 1) {
        $(".tr-powerofpenny-form").hide();
    } else {
        $.ajax({
            url: '/' + language + '/tr-power-of-penny/update',
            method: 'post',
            data: {lang: lang, powerofpenny: powerofpenny},
            success: function (res) {
                $(".tab-pane").removeClass('active');
                $("#tr_powerofpenny").addClass('active');
                $('#tr_powerofpenny').html(res);
                if (typeof CKEDITOR !== 'undefined' && document.getElementById('trpowerofpenny-content')) {
                    CKEDITOR.replace('trpowerofpenny-content');
                }
                $('#admin-alerts').hide();
            }
        });
    }
}

$(document).ready(function () {
    if(!$('#product-category_id').val()) {
        $('#product-category_id').val('6')
        getAttributes(6);
    }

    var $body = $('body');
 $('#callbacks').multiSelect({
  selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Search'>",
  selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Search'>",
  afterInit: function(ms){
    var that = this,
        $selectableSearch = that.$selectableUl.prev(),
        $selectionSearch = that.$selectionUl.prev(),
        selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
        selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
    .on('keydown', function(e){
      if (e.which === 40){
        that.$selectableUl.focus();
        return false;
      }
    });

    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
    .on('keydown', function(e){
      if (e.which == 40){
        that.$selectionUl.focus();
        return false;
      }
    });
  },
  afterSelect: function(){
    this.qs1.cache();
    this.qs2.cache();
  },
  afterDeselect: function(){
    this.qs1.cache();
    this.qs2.cache();
  }
});

	
    $("#tbl_customer .odd").on("click", function (event) {
        if (event.target.nodeName == 'INPUT' || event.target.nodeName == 'A' || event.target.nodeName == 'SPAN' || event.target.nodeName == 'LABEL') {
            return;
        } else {
            var id = $(this).attr('data-key');
            $.ajax({
                url: '/' + language + "/customer/index",
                method: "post",
                data: {id: id},
                success: function (res) {
                    $('#form_cont').html(res);

                }
            })
        }
    });

    $("#tbl_service .odd").on("click", function (event) {
        if (event.target.nodeName == 'INPUT' || event.target.nodeName == 'A' || event.target.nodeName == 'SPAN' || event.target.nodeName == 'LABEL') {
        } else {
            var id = $(this).attr('data-key');
            $.ajax({
                url: '/' + language + "/service/index",
                method: "post",
                data: {id: id},
                success: function (res) {
                    $('#service-form_cont').html(res);
                    if (jQuery('#service-status').data('select2')) {
                        jQuery('#service-status').select2('destroy');
                    }
                    jQuery.when(jQuery('#service-status').select2(select2_cc5be4bd)).done(initS2Loading('service-status', 's2options_d6851687'));

                    if (jQuery('#repairs-product_id').data('select2')) {
                        jQuery('#repairs-product_id').select2('destroy');
                    }
                    jQuery.when(jQuery('#repairs-product_id').select2(select2_19a1dec7)).done(initS2Loading('repairs-product_id', 's2options_d6851687'));

                    if (jQuery('#repairs-part_id').data('select2')) {
                        jQuery('#repairs-part_id').select2('destroy');
                    }
                    jQuery.when(jQuery('#repairs-part_id').select2(select2_19a1dec7)).done(initS2Loading('repairs-part_id', 's2options_d6851687'));

                    jQuery('#serviceUpdate').yiiActiveForm([{
                        "id": "service-name",
                        "name": "name",
                        "container": ".field-service-name",
                        "input": "#service-name",
                        "validate": function (attribute, value, messages, deferred, $form) {
                            yii.validation.required(value, messages, {"message": "Name cannot be blank."});
                            yii.validation.string(value, messages, {
                                "message": "Name must be a string.",
                                "max": 250,
                                "tooLong": "Name should contain at most 250 characters.",
                                "skipOnEmpty": 1
                            });
                        }
                    }, {
                        "id": "service-short_description",
                        "name": "short_description",
                        "container": ".field-service-short_description",
                        "input": "#service-short_description",
                        "validate": function (attribute, value, messages, deferred, $form) {
                            yii.validation.string(value, messages, {
                                "message": "Short Description must be a string.",
                                "max": 250,
                                "tooLong": "Short Description should contain at most 250 characters.",
                                "skipOnEmpty": 1
                            });
                        }
                    }, {
                        "id": "service-description",
                        "name": "description",
                        "container": ".field-service-description",
                        "input": "#service-description",
                        "validate": function (attribute, value, messages, deferred, $form) {
                            yii.validation.required(value, messages, {"message": "Description cannot be blank."});
                            yii.validation.string(value, messages, {
                                "message": "Description must be a string.",
                                "skipOnEmpty": 1
                            });
                        }
                    }, {
                        "id": "service-status",
                        "name": "status",
                        "container": ".field-service-status",
                        "input": "#service-status",
                        "validate": function (attribute, value, messages, deferred, $form) {
                            yii.validation.number(value, messages, {
                                "pattern": /^\s*[+-]?\d+\s*$/,
                                "message": "Status must be an integer.",
                                "skipOnEmpty": 1
                            });
                        }
                    }, {
                        "id": "repairs-category_id",
                        "name": "category_id",
                        "container": ".field-repairs-category_id",
                        "input": "#repairs-category_id",
                        "validate": function (attribute, value, messages, deferred, $form) {
                            yii.validation.required(value, messages, {"message": "Category ID cannot be blank."});
                            yii.validation.number(value, messages, {
                                "pattern": /^\s*[+-]?\d+\s*$/,
                                "message": "Category ID must be an integer.",
                                "skipOnEmpty": 1
                            });
                        }
                    }], []);
                }
            })
        }
    });

    /* $('body').on('submit', '#brandUpdate, #trbrandupdate', (function (e) {

        e.preventDefault();
        var id = $(this).attr('id');

        var form = $(this);
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
                    //document.getElementById(id).reset();
                    if (res) {
                        $('#admin-alerts div').html('Brand successfully updated');
                        $('#admin-alerts').show();
//                        $.pjax.reload({container: '#brandPjaxtbl'});
//                        $.pjax.reload({container: '#brandPjaxForm'});
                    }
                }
            }
        });
    })); */
  /*   $('body').on('submit', '#attrUpdate, #trattributeupdate', (function (e) {

        e.preventDefault();
        var id = $(this).attr('id');
//console.log(id);
        var form = $(this);
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
                    //document.getElementById(id).reset();
                    if (res) {
                        $('#admin-alerts div').html('Attribute successfully updated');
                        $('#admin-alerts').show();
//                        $.pjax.reload({container: '#brandPjaxtbl'});
//                        $.pjax.reload({container: '#brandPjaxForm'});
                    }
                }
            }
        });
    })); */
    $('body').on('submit', '#messageCreate,#messageUpdate,#updateSource', (function (e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var form = $(this);
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
                    if (res) {
                        $('#admin-alerts div').html('Translation successfully updated');
                        $('#admin-alerts').show();
                    }
                }
            }
        });
    }));
   /*  $('body').on('submit', '#categoryUpdate, #trcategoryUpdate', (function (e) {

        e.preventDefault();
        var id = $(this).attr('id');

        var form = $(this);
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
                if (res.success) {
                    $('#admin-alerts div').html('Category successfully updated');
                    $('#admin-alerts').show();
//                        $.pjax.reload({container: '#brandPjaxtbl'});
//                        $.pjax.reload({container: '#brandPjaxForm'});
                } else {
                    console.log(res.message);
                    var err = '';
                    
                    for (var i in res.message) {
                        err += '<br>' + res.message[i][0];
                    }
                    if($('#admin-alerts').length == 0){
                        $('#content').prepend('<div id="admin-alerts" class="alert alert-danger" style="">'+
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><div></div></div>')
                    }
                    $('#admin-alerts').removeClass('alert-success');
                    $('#admin-alerts').addClass('alert-danger');
                    $('#admin-alerts div').html(err);
                    $('#admin-alerts').show();
                }
            }
        });
    })); */
    $('body').on('submit', '#productUpdate, #trproductUpdate', (function (e) {

        e.preventDefault();
        var id = $(this).attr('id');

        var form = $(this);
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
                    //document.getElementById(id).reset();
                    if (res) {
                        $('#admin-alerts div').html('Product successfully updated');
                        $('#admin-alerts').show();
//                        $.pjax.reload({container: '#brandPjaxtbl'});
//                        $.pjax.reload({container: '#brandPjaxForm'});
                    }
                }
            }
        });
    }));

    /* $('body').on('submit', '#pagesUpdate, #trpagesUpdate', (function (e) {

        e.preventDefault();
        var id = $(this).attr('id');

        var form = $(this);
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
                    //document.getElementById(id).reset();
                    if (res) {
                        $('#admin-alerts div').html('Pages successfully updated');
                        $('#admin-alerts').show();
//                        $.pjax.reload({container: '#brandPjaxtbl'});
//                        $.pjax.reload({container: '#brandPjaxForm'});
                    }
                }
            }
        });
    })); */
    $('body').on('submit', '#faqUpdate, #trfaqUpdate', (function (e) {

        e.preventDefault();
        var id = $(this).attr('id');

        var form = $(this);
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
                    //document.getElementById(id).reset();
                    if (res) {
                        $('#admin-alerts div').html('Faq successfully updated');
                        $('#admin-alerts').show();
//                        $.pjax.reload({container: '#brandPjaxtbl'});
//                        $.pjax.reload({container: '#brandPjaxForm'});
                    }
                }
            }
        });
    }));

    // $("#tbl_category .odd").on("click", function (event) {
    //     if (event.target.nodeName == 'BUTTON' || event.target.nodeName == 'A' || event.target.nodeName == 'SPAN') {
    //     } else {
    //         var id = $(this).attr('data-key');
    //         $.ajax({
    //             url: '/'+language+"/category/getform",
    //             method: "post",
    //             data: {id: id},
    //             success: function (res) {
    //                 $('#category-form_cont').html(res);
    //             }
    //         })
    //     }
    // });


    $('a.Repairerlink').click(function () {
        var id = $(this).parent().parent().attr('data-key');
        console.log(id);
        $.ajax({
            url: '/' + language + "/repairer/index",
            method: "post",
            data: {id: id},
            success: function (res) {
                $('#repairer-form_cont').html(res);
                if (jQuery('#repairer-status').data('select2')) {
                    jQuery('#repairer-status').select2('destroy');
                }
                jQuery.when(jQuery('#repairer-status').select2(select2_cc5be4bd)).done(initS2Loading('repairer-status', 's2options_d6851687'));
                jQuery('#repairerCreate').yiiActiveForm([{
                    "id": "repairer-name",
                    "name": "name",
                    "container": ".field-repairer-name",
                    "input": "#repairer-name",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {"message": "Name cannot be blank."});
                        yii.validation.string(value, messages, {
                            "message": "Name must be a string.",
                            "max": 50,
                            "tooLong": "Name should contain at most 50 characters.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairer-surname",
                    "name": "surname",
                    "container": ".field-repairer-surname",
                    "input": "#repairer-surname",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {"message": "Surname cannot be blank."});
                        yii.validation.string(value, messages, {
                            "message": "Surname must be a string.",
                            "max": 50,
                            "tooLong": "Surname should contain at most 50 characters.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairer-email",
                    "name": "email",
                    "container": ".field-repairer-email",
                    "input": "#repairer-email",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {"message": "Email cannot be blank."});
                        yii.validation.string(value, messages, {
                            "message": "Email must be a string.",
                            "max": 50,
                            "tooLong": "Email should contain at most 50 characters.",
                            "skipOnEmpty": 1
                        });
                        yii.validation.email(value, messages, {
                            "pattern": /^[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/,
                            "fullPattern": /^[^@]*<[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?>$/,
                            "allowName": false,
                            "message": "Email is not a valid email address.",
                            "enableIDN": false,
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairer-phone",
                    "name": "phone",
                    "container": ".field-repairer-phone",
                    "input": "#repairer-phone",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {"message": "Phone cannot be blank."});
                        yii.validation.string(value, messages, {
                            "message": "Phone must be a string.",
                            "max": 50,
                            "tooLong": "Phone should contain at most 50 characters.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairer-percent",
                    "name": "percent",
                    "container": ".field-repairer-percent",
                    "input": "#repairer-percent",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.number(value, messages, {
                            "pattern": /^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/,
                            "message": "Percent must be a number.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairer-country",
                    "name": "country",
                    "container": ".field-repairer-country",
                    "input": "#repairer-country",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.string(value, messages, {
                            "message": "Country must be a string.",
                            "max": 50,
                            "tooLong": "Country should contain at most 50 characters.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairer-city",
                    "name": "city",
                    "container": ".field-repairer-city",
                    "input": "#repairer-city",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.string(value, messages, {
                            "message": "City must be a string.",
                            "max": 50,
                            "tooLong": "City should contain at most 50 characters.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairer-state",
                    "name": "state",
                    "container": ".field-repairer-state",
                    "input": "#repairer-state",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.string(value, messages, {
                            "message": "State must be a string.",
                            "max": 50,
                            "tooLong": "State should contain at most 50 characters.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairer-address",
                    "name": "address",
                    "container": ".field-repairer-address",
                    "input": "#repairer-address",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.string(value, messages, {
                            "message": "Address must be a string.",
                            "max": 50,
                            "tooLong": "Address should contain at most 50 characters.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairer-zip",
                    "name": "zip",
                    "container": ".field-repairer-zip",
                    "input": "#repairer-zip",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.string(value, messages, {
                            "message": "Zip must be a string.",
                            "max": 20,
                            "tooLong": "Zip should contain at most 20 characters.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairer-status",
                    "name": "status",
                    "container": ".field-repairer-status",
                    "input": "#repairer-status",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.number(value, messages, {
                            "pattern": /^\s*[+-]?\d+\s*$/,
                            "message": "Status must be an integer.",
                            "skipOnEmpty": 1
                        });
                    }
                }], []);

                jQuery(document).ready(function () {
                    $('#repairer-phone').mask('+33 9 99 99 99 99');
                    $('#repairersearch-phone').mask('+33 9 99 99 99 99');
                })

                if (jQuery('#repairersearch-status').data('select2')) {
                    jQuery('#repairersearch-status').select2('destroy');
                }
                jQuery.when(jQuery('#repairersearch-status').select2(select2_f9601a74)).done(initS2Loading('repairersearch-status', 's2options_d6851687'));

                jQuery('#repairer-search').yiiActiveForm([{
                    "id": "repairersearch-name",
                    "name": "name",
                    "container": ".field-repairersearch-name",
                    "input": "#repairersearch-name",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.string(value, messages, {"message": "Name must be a string.", "skipOnEmpty": 1});
                    }
                }, {
                    "id": "repairersearch-surname",
                    "name": "surname",
                    "container": ".field-repairersearch-surname",
                    "input": "#repairersearch-surname",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.string(value, messages, {
                            "message": "Surname must be a string.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairersearch-email",
                    "name": "email",
                    "container": ".field-repairersearch-email",
                    "input": "#repairersearch-email",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.string(value, messages, {
                            "message": "Email must be a string.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairersearch-phone",
                    "name": "phone",
                    "container": ".field-repairersearch-phone",
                    "input": "#repairersearch-phone",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.string(value, messages, {
                            "message": "Phone must be a string.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairersearch-country",
                    "name": "country",
                    "container": ".field-repairersearch-country",
                    "input": "#repairersearch-country",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.string(value, messages, {
                            "message": "Country must be a string.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairersearch-city",
                    "name": "city",
                    "container": ".field-repairersearch-city",
                    "input": "#repairersearch-city",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.string(value, messages, {"message": "City must be a string.", "skipOnEmpty": 1});
                    }
                }, {
                    "id": "repairersearch-state",
                    "name": "state",
                    "container": ".field-repairersearch-state",
                    "input": "#repairersearch-state",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.string(value, messages, {
                            "message": "State must be a string.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairersearch-address",
                    "name": "address",
                    "container": ".field-repairersearch-address",
                    "input": "#repairersearch-address",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.string(value, messages, {
                            "message": "Address must be a string.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairersearch-zip",
                    "name": "zip",
                    "container": ".field-repairersearch-zip",
                    "input": "#repairersearch-zip",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.string(value, messages, {"message": "Zip must be a string.", "skipOnEmpty": 1});
                    }
                }, {
                    "id": "repairersearch-percent",
                    "name": "percent",
                    "container": ".field-repairersearch-percent",
                    "input": "#repairersearch-percent",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.number(value, messages, {
                            "pattern": /^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/,
                            "message": "Percent must be a number.",
                            "skipOnEmpty": 1
                        });
                    }
                }, {
                    "id": "repairersearch-status",
                    "name": "status",
                    "container": ".field-repairersearch-status",
                    "input": "#repairersearch-status",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.number(value, messages, {
                            "pattern": /^\s*[+-]?\d+\s*$/,
                            "message": "Status must be an integer.",
                            "skipOnEmpty": 1
                        });
                    }
                }], []);
            }
        })
    });

    $body.on('mouseover', '.mix-container .mix', function () {
        $(this.children[0]).removeClass('hidden');
    });
    $body.on('mouseleave', '.mix-container .mix', function () {
        $(this.children[0]).addClass('hidden');
    });

    $body.on('click', '.icon-close', function () {
        var image_id = $(this).parent().next().find('.change_image').attr('data-key');
        var $cont = $(this).parent().parent();
        var is_default = $cont.hasClass('default-view');
        if (confirm("Are you sure you want to delete this item?")) {
            $.ajax({
                url: '/' + language + '/product/delete-image',
                data: {id: image_id},
                method: 'post',
                success: function (res) {
                    $('#image_' + image_id).remove();
                }
            });
        }

    });
    
    $body.on('click', '.icon-close-news', function () {
        var image_id = $(this).parent().next().find('.change_image_news').attr('data-key');
        var $cont = $(this).parent().parent();
        var is_default = $cont.hasClass('default-view');
        if (confirm("Are you sure you want to delete this item?")) {
            $.ajax({
                url: '/' + language + '/news/delete-image',
                data: {id: image_id},
                method: 'post',
                success: function (res) {
                    $('#image_' + image_id).remove();
                }
            });
        }

    });
	
	$body.on('click', '.icon-close-attr', function () {
        var image_id = $(this).parent().next().find('.change_image').attr('data-key');
        var $cont = $(this).parent().parent();
        var is_default = $cont.hasClass('default-view');
        if (confirm("Are you sure you want to delete this item?")) {
            $.ajax({
                url: '/' + language + '/attribute/delete-image',
                data: {id: image_id},
                method: 'post',
                success: function (res) {
                    $('#image_' + image_id).remove();
                }
            });
        }

    });
	
	$body.on('click', '.page-remove-image', function () {
        var image_id = $(this).parent().next().find('.change_image').attr('data-key');
        var $cont = $(this).parent().parent();
        var is_default = $cont.hasClass('default-view');
        if (confirm("Are you sure you want to delete this item?")) {
            $.ajax({
                url: '/' + language + '/pages/delete-image',
                data: {id: image_id},
                method: 'post',
                success: function (res) {
                    $('#image_' + image_id).remove();
                }
            });
        }

    });

    $body.on('click', '.change_image', function () {
        if (confirm("Are you sure you want set this image as default?")) {
            var parent = $(this).parent().parent().parent().parent().parent().parent();
            var newid = $(this).attr('data-key');
            var oldid = parent.find('.default-view').find('.change_image').attr('data-key');
			console.log(oldid);
			if(!oldid){
				oldid = 0;
			}
            parent.find('.default-view').removeClass('default-view');
            $(this).parent().parent().parent().addClass('default-view');
            console.log(newid, oldid);
            $.ajax({
                url: '/' + language + '/product/default-image',
                data: {newid: newid, oldid: oldid},
                method: 'post',
                dataType: 'json',
                success: function (res) {
                }
            })
        }
    });
    
    $body.on('click', '.change_image_news', function () {
        if (confirm("Are you sure you want set this image as default?")) {
            var parent = $(this).parent().parent().parent().parent().parent().parent();
            var newid = $(this).attr('data-key');
            var oldid = parent.find('.default-view').find('.change_image_news').attr('data-key');
			console.log(oldid);
			if(!oldid){
				oldid = 0;
			}
            parent.find('.default-view').removeClass('default-view');
            $(this).parent().parent().parent().addClass('default-view');
            console.log(newid, oldid);
            $.ajax({
                url: '/' + language + '/news/default-image',
                data: {newid: newid, oldid: oldid},
                method: 'post',
                dataType: 'json',
                success: function (res) {
                }
            })
        }
    });

    $('#serviceCreate').validate({
        rules: {},
        onfocusout: function (element) {
            $(element).valid();
        },
        errorClass: 'has-error',
        validClass: 'has-success',
        errorPlacement: function (error, element) {
            element.next('.help-block').html(error.text())
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parent().addClass(errorClass).removeClass(validClass);
            $(element).parent().addClass(errorClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parent().removeClass(errorClass).addClass(validClass);
            $(element).parent().removeClass(errorClass);
            $(element).next('.help-block').html('');
        }
    });
    $('#serviceUpdate').validate({
        rules: {},
        onfocusout: function (element) {
            $(element).valid();
        },
        errorClass: 'has-error',
        validClass: 'has-success',
        errorPlacement: function (error, element) {
            element.next('.help-block').html(error.text())
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parent().addClass(errorClass).removeClass(validClass);
            $(element).parent().addClass(errorClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parent().removeClass(errorClass).addClass(validClass);
            $(element).parent().removeClass(errorClass);
            $(element).next('.help-block').html('');
        }
    });

    $body.on('click', '#add-product', function () {

        var section = $(this).parent().parent().parent().clone();
        var last_section = $('#tab1_2').children().last();
        var id = last_section.find('.form-control').attr('id'),
            leng = id.match(/\d+/)[0];
        leng = parseInt(leng) + 1;
        console.log(leng);
        var button = section.find('#add-product');
        var select = section.find('#repairs-0-product_id'),
            hidden = section.find('#repairs-0-type'),
            hidden_id = hidden.attr('id'),
            res_hidden_id = hidden_id.replace('0', leng),
            hidden_name = hidden.attr('name'),
            res_hidden_name = hidden_name.replace('0', leng),
            group = select.parent(),
            group_class = group.attr('class'),
            res_grouo_class = group_class.replace('0', leng),
            select_id = select.attr('id'),
            res_select_id = select_id.replace('0', leng),
            select_name = select.attr('name'),
            res_select_name = select_name.replace('0', leng);
        hidden.attr('id', res_hidden_id);
        hidden.attr('name', res_hidden_name);
        select.attr('id', res_select_id);
        select.attr('name', res_select_name);
        // select.attr('onchange','updateType(this)');
        group.attr('class', res_grouo_class);
        group.removeClass('has-success');
        group.removeClass('has-error');
        button.removeAttr('id').addClass('remove-product');
        button.text('Remove');
        section.appendTo('#tab1_2');
        $('#repairs-' + leng + '-product_id').rules("add", {
            required: true
        });
    });

    $body.on('click', '.remove-product', function () {
        var section = $(this).parent().parent().parent(),
            input = section.find('.form-control').attr('id');
        section.remove();
        $('#' + input).rules("remove");
    });


    $('#tbl_blog tr').click(function () {
        var id = $(this).attr('data-key');
        $.ajax({
            url: '/' + language + "/blog/index",
            method: "post",
            data: {id: id},
            success: function (res) {
                $('#blog-form_cont').html(res);
                CKEDITOR.replace('blog-description', {
                    height: 210,
                    on: {
                        instanceReady: function (evt) {
                            $('.cke').addClass('admin-skin cke-hide-bottom');
                        }
                    },
                });
                if (jQuery('#blog-status').data('select2')) {
                    jQuery('#blog-status').select2('destroy');
                }
                if (language == 'de') {
                    jQuery.when(jQuery('#blog-status').select2(select2_ca59277d)).done(initS2Loading('blog-status', 's2options_d6851687'));
                }
                if (language == 'en') {
                    jQuery.when(jQuery('#blog-status').select2(select2_cc52e6ad)).done(initS2Loading('blog-status', 's2options_d6851687'));
                }
                if (language == 'se') {
                    jQuery.when(jQuery('#blog-status').select2(select2_028ab196)).done(initS2Loading('blog-status', 's2options_d6851687'));
                }
            }
        })
    });


    $('#tbl_message_send tr').click(function () {
        var id = $(this).attr('data-key');
        $.ajax({
            url: '/' + language + "/message-system/index",
            method: "post",
            data: {id: id},
            success: function (res) {
                $('#form_message').html(res);
                // $('#tbl_message_send tr[data-key="'+id+'"]').find('td.text-success').attr('class','text-danger').text('Read');
            }
        })
    })

    $('th input.select-on-check-all').click(function () {
        console.log(123)
        $('tbody input[type=checkbox]').attr('checked', true);
    });

    //$('#product-category_id').change(function(){
    //    var category_id = $(this).val();
    //
    //});

});


function showOnHomePage(productId,imageId) {
	if (confirm("Are you sure you want to show this image on home page?")) {
		
	}
}

function makeAsDefaultImage(productId,imageId) {
	if (confirm("Are you sure you want set this image as default?")) {
		
	}
}

function getAttributes(category_id) {
    $.ajax({
        url: '/' + language + '/attribute/get-attributes-by-category',
        data: {id: category_id},
        method: 'post',
        dataType: 'html',
        success: function (res) {
            $('#category_attributes').find('div.m10').html(res);
            $('#category_attributes span').removeClass('hidden');
        }
    })
}
function attributeChecked(attribute_id, checked) {
    if (checked) {
        $('#attribute_value_' + attribute_id).prop('disabled', false);
        $('#sub_attr_id_' + attribute_id).prop('disabled', false);
		$('#attribute_value_' + attribute_id).prop('disabled', false);
    } else {
        $('#attribute_value_' + attribute_id).prop('disabled', true);
        $('#sub_attr_id_' + attribute_id).prop('disabled', true);
        $('#attribute_value_' + attribute_id).prop('disabled', true);
    }
}
$('body').on('click','#select-all-users', function (event) {
    if (this.checked) {
        $('.chk-usr').each(function () {
            this.checked = true;
        });
    }
    else {
        $('.chk-usr').each(function () {
            this.checked = false;
        });
    }
});
$('body').on('click', '.chk-usr', function () {
    var checked = true;
    $('tbody>').find('.chk-usr').each(function (key, value) {
        if($(value).prop('checked')===false){
            checked = false;
            $('#select-all-users').prop('checked', false);
            return false;
        }
    });
    if(checked === true){
        $('#select-all-users').prop('checked', true);
    }
});

$('#product-stock').change(function(){
	console.log(13)
	if($('#product-new_price').next('label#label_new_price').find('.fa-rub').hasClass('hidden')){
		$('#product-new_price').attr('type','text');
		$('#product-new_price').next('label#label_new_price').find('.fa-rub').removeClass('hidden');
	}else{
		$('#product-new_price').attr('type','hidden');
		$('#product-new_price').next('label#label_new_price').find('.fa-rub').addClass('hidden');
	}
	
})

$('#product-sale').change(function(){
	if($('#product-new_price_sale').next('label#label_new_price_sale').find('.fa-rub').hasClass('hidden')){
		$('#product-new_price_sale').attr('type','text');
		$('#product-new_price_sale').next('label#label_new_price_sale').find('.fa-rub').removeClass('hidden');
	}else{
		$('#product-new_price_sale').attr('type','hidden');
		$('#product-new_price_sale').next('label#label_new_price_sale').find('.fa-rub').addClass('hidden');
	}
	
})

