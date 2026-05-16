$(document).ready(function () {
    var body = $('body');
    $("#productCreate").validate({
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
            // $(element).parent().parent().parent().addClass(errorClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parent().parent().parent().removeClass(errorClass).addClass(validClass);
            $(element).parent().next('.help-block').text('');
        }
    });

    body.on('click', '#add-part', function () {
        var a = 0;
        $('#tab1_4').find('.ar_').each(function () {
            a += 1;
        });
        var newPart = $('.a_' + (a - 1)).clone();
        var val = [];

        newPart.find('input[type=text]').each(function (i, v) {
            var b = v.value;
            var t = typeof b;
            if ((b != '') && ( t !== 'undefined')) {
                val.push(b);
            }
            var str_name = $(v).attr('name'),
                name = '0';
            if (a > 1) {
                name = (a - 1);
            }
            var res_name = str_name.replace(name, a);
            $(v).attr('name', res_name);

            var str_id = $(v).attr('id'),
                id = '0';
            if (a > 1) {
                id = (a - 1);
            }
            var res_id = str_id.replace(id, a);
            $(v).attr('id', res_id);

            var str_class = $(v).parent().parent().parent().attr('class'),
                clas = '0';
            if (a > 1) {
                clas = (a - 1);
            }
            var res_clas = str_class.replace(clas, a);
            $(v).parent().parent().parent().attr('class', res_clas);
            $(v).addClass('validate');
            v.value = '';
        });
        var img = newPart.find('#productparts-' + (a - 1) + '-parts_imagefiles');
        var img_name = img.attr('name');
        img.attr("onchange", "showMyImage(this," + a + ")");
        var im_name = '0';
        if (a > 1) {
            im_name = (a - 1);
        }

        var res_img_name = img_name.replace(im_name, a);
        img.attr('name', res_img_name);
        var img_id = img.attr('id');
        var im_id = '0';
        if (a > 1) {
            im_id = (a - 1);
        }
        var res_im_id = img_id.replace(im_id, a);
        img.attr('id', res_im_id);

        var img_class = img.parent().parent().parent().attr('class');
        var im_clas = '0';
        if (a > 1) {
            im_clas = (a - 1);
        }
        var res_im_clas = img_class.replace(im_clas, a);
        img.parent().parent().parent().attr('class', res_im_clas);
        img.val('');

        var partId = newPart.find('#productparts-' + (a - 1) + '-id');
        if (partId.length > 0) {
            var pn = partId.attr('name'),
                pi = partId.attr('id'),
                res_pn = pn.replace(im_clas, a),
                res_pi = pi.replace(im_clas, a);
            partId.attr('name', res_pn);
            partId.attr('id', res_pi);
            partId.val(0);


        }
        // img.next('label').find('span').html('');
        var selDiv = newPart.find("#selectedFiles_" + im_clas);
        var sel_id = selDiv.attr('id');
        var res_sel_id = sel_id.replace(im_clas, a);
        selDiv.attr('id', res_sel_id);
        selDiv.html('');
        img.attr('onchange', 'showMyImage(this, ' + a + ')');
        var rDiv = newPart.find('#defaultimg_part_' + im_clas);
        var rdiv_id = rDiv.attr('id');
        var res_rDiv_id = rdiv_id.replace(im_clas, a);
        rDiv.attr('id', res_rDiv_id);
        var rb = newPart.find('#def_img_part_' + im_clas);
        var rb_id = rb.attr('id');
        var res_rb_id = rb_id.replace(im_clas, a);
        rb.attr('id', res_rb_id);
        var rb_name = rb.attr('name');
        var res_rb_name = rb_name.replace(im_clas, a);
        rb.attr('name', res_rb_name);
        img.next('label').attr('for', res_im_id);
        img.next('label').find('span').html('');

        var desc = newPart.find('#productparts-' + (a - 1) + '-description');
        var desc_name = desc.attr('name');
        var dsc_val = desc.val();
        var dsc_val_t = typeof dsc_val;
        if ((dsc_val != '') && ( dsc_val_t !== 'undefined')) {
            val.push(dsc_val);
        }
        var de_name = '0';
        if (a > 1) {
            de_name = (a - 1);
        }
        var res_desc_name = desc_name.replace(de_name, a);
        desc.attr('name', res_desc_name);
        var desc_id = desc.attr('id');
        var de_id = '0';
        if (a > 1) {
            de_id = (a - 1);
        }
        var res_de_id = desc_id.replace(de_id, a);
        desc.attr('id', res_de_id);
        var desc_class = desc.parent().parent().parent().attr('class');
        var de_clas = '0';
        if (a > 1) {
            de_clas = (a - 1);
        }
        var res_de_clas = desc_class.replace(de_clas, a);
        desc.parent().parent().parent().attr('class', res_de_clas);
        desc.val('');
        desc.addClass('validate');

        if (newPart.find('.gallery-page').length > 0) {
            newPart.find('.gallery-page').remove()
        }
        if (val.length >= 4) {
            var cl = newPart.attr('class');
            var newcl = cl.replace('a_' + (a - 1), 'a_' + a);
            newPart.attr('class', newcl);
            newPart.find('.has-success').removeClass('has-success');
            newPart.find('.has-error').removeClass('has-error');
            newPart.removeAttr('id');
            newPart.find('.section-divider > span').text("Product part" + a);
            a += 1;
            $(newPart).appendTo($('#pr_parts').parent());
            $("#productparts-" + (a - 1) + "-name").rules("add", {
                required: true,
                messages: {
                    required: "Name cannot be blank."
                }
            });
            $("#productparts-" + (a - 1) + "-description").rules("add", {
                required: true,
                messages: {
                    required: "Description cannot be blank."
                }
            });
            $("#productparts-" + (a - 1) + "-price").rules("add", {
                number: true,
                messages: {
                    number: "Price must be an integer"
                }
            });
            $("#productparts-" + (a - 1) + "-in_stock").rules("add", {
                integer: true,
                messages: {
                    integer: "Amount in stock must be an integer"
                }
            });
            $('.part-err').remove();
        } else {
            if ($('.part-err').length == 0) {
                $('.a_' + (a - 1) + ' > #spy1').append('<p class="part-err">Please write in fields before add new part!</p>');
            }
        }
    });
    // add address end
    // remove address
    body.on('click', '#remove-part', function () {
        var a = 0;
        $('#tab1_4').find('.ar_').each(function () {
            a += 1;
        });

        if (a != 1) {
            a -= 1;
            $("#productparts-" + (a - 1) + "-name").rules("remove");
            $("#productparts-" + (a - 1) + "-description").rules("remove");
            $("#productparts-" + (a - 1) + "-price").rules("remove");
            $("#productparts-" + (a - 1) + "-in_stock").rules("remove");
            $('.a_' + a).remove();
            return true;
        } else {
            return false;
        }
    });

    body.on('click', '.image-preview-1', function () {
        console.log(a);
        var parent = $(this).parents('div.ar_');
        console.log(parent);
        var def = parent.find('.default-img');
        def.removeAttr('title');
        def.removeClass('default-img');
        $(this).addClass('default-img');
        $(this).attr('title', 'Default Image');
        var val = $(this).attr('data-id');
        parent.find('input.radio_inp').val(val)

    });

    $(function () {
        var fileinput = document.getElementById('productparts-0-parts_imagefiles');
        if (fileinput) {
            showMyImage(fileinput, 0);
        }

    });
    body.on('click', '.image-preview', function () {
        var parent = $(this).parents('div.ar_');
        var def = parent.find('.default-img');
        def.removeAttr('title');
        def.removeClass('default-img');
        $(this).addClass('default-img');
        $(this).attr('title', 'Default Image');
        var val = $(this).attr('data-id');
        parent.find('input.radio_inp').val(val)
    });

});



function showMyImage(fileInput, W,isProductImage) {
    var files = fileInput.files;
    var selDiv_id = 'selectedFiles_' + W;
    var rb = document.getElementById('def_img_part_' + W);
    var selDiv = document.getElementById(selDiv_id);
    var filesArr = Array.prototype.slice.call(files);
    var label = fileInput.nextElementSibling;
    var s = '';

    var fileName = '';

    selDiv.innerHTML = '';
    rb.checked = false;
    if (W == '-1') {
        s = 1;
    }
    if (filesArr && filesArr.length > 1) {
        fileName = ( fileInput.getAttribute('data-multiple-caption') || '' ).replace('{count}', filesArr.length);
    } else if (filesArr.length == 1) {
        fileName = filesArr[0].name;
    } else {
        fileName = ''
    }
    if (fileName != '') {
        fileName = '<i class="glyphicon glyphicon-file"></i>' + fileName;
        label.querySelector('span').innerHTML = fileName;
    } else {
        label.querySelector('span').innerHTML = '';
    }
	
    filesArr.forEach(function (f, i) {
        if (!f.type.match("image.*")) {
            return;
        }

        var reader = new FileReader();
        reader.onload = function (e) {
			var html = "<div class='col-md-3 image-preview" + s + "' data-id='" + (i + 1) + "'><img class='img-responsive' src=\"" + e.target.result + "\">" + f.name + "</div>";
				selDiv.innerHTML += html;
				var list = selDiv.children[0].className;
				if (list.indexOf('default-img') == -1) {
					selDiv.children[0].className += ' default-img';
					selDiv.children[0].title = 'Default Image';
					rb.value = 1;
					rb.checked = true
				}
			/* if(isProductImage){
				$.ajax({
					url: '/' + language + '/colors/get-colors-list',
					method: 'post',
					success: function (res) {
						var html = "<div class='col-md-3 image-preview" + s + "' data-id='" + (i + 1) + "'><img class='img-responsive' src=\"" + e.target.result + "\">" + f.name + "</div>";
						html+= '<div>'+res+'</div>';
						selDiv.innerHTML += html;
						var list = selDiv.children[0].className;
						if (list.indexOf('default-img') == -1) {
							selDiv.children[0].className += ' default-img';
							selDiv.children[0].title = 'Default Image';
							rb.value = 1;
							rb.checked = true
						}
					}
				});
			}else{
				var html = "<div class='col-md-3 image-preview" + s + "' data-id='" + (i + 1) + "'><img class='img-responsive' src=\"" + e.target.result + "\">" + f.name + "</div>";
				selDiv.innerHTML += html;
				var list = selDiv.children[0].className;
				if (list.indexOf('default-img') == -1) {
					selDiv.children[0].className += ' default-img';
					selDiv.children[0].title = 'Default Image';
					rb.value = 1;
					rb.checked = true
				}
			} */
			
            
        };
        reader.readAsDataURL(f);
    });
}