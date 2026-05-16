<?php if (!empty($data)): ?>
    <?php foreach ($data as $key => $value): ?>
        <?php if ($key == 0) {
            $legend = 'Product Part';
            $cont_id = 'pr_parts';

        } else {
            $legend = 'Product Part' . $key;
            $cont_id = '';
        }
        $cont_class = 'a_' . $key;
        ?>
        <div class="<?php echo $cont_class ?> ar_" id="<?php echo $cont_id; ?>">
            <div class="section-divider mb40" id="spy1">
                <span><?php echo $legend ?></span>
            </div>
            <input type="hidden" id="productparts-<?php echo $key; ?>-id" name="ProductParts[<?php echo $key; ?>][id]" value="<?php echo $value['id'] ?>">
            <div class="section row mbn">
                <div class="col-md-6 pt15">
                    <div class="form-group field-productparts-<?php echo $key; ?>-name required">
                        <div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                <input type="text" id="productparts-<?php echo $key; ?>-name" class="form-control"
                                       name="ProductParts[<?php echo $key; ?>][name]" placeholder="Part Name"
                                       value="<?php echo $value['name'] ?>">
                                <label for="customer-name" class="field-icon">
                                    <i class="fa fa-tags"></i></label></label>
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 pt15">
                    <div class="form-group field-productparts-<?php echo $key; ?>-price">
                        <div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                <input type="text" id="productparts-<?php echo $key; ?>-price" class="form-control"
                                       name="ProductParts[<?php echo $key; ?>][price]" placeholder="Part Price"
                                       value="<?php echo $value['price'] ?>"><label
                                    for="customer-name" class="field-icon"><i class="fa fa-euro"></i></label></label>
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section row mbn">
                <div class="col-md-4 pt15">
                    <div class="form-group field-productparts-<?php echo $key; ?>-status">
                        <div class="col-md-12" style="padding: 0">
                            <label for="customer-name" class="field prepend-icon">
                                <input type="text" id="productparts-<?php echo $key; ?>0-in_stock" class="form-control"
                                       name="ProductParts[<?php echo $key; ?>][in_stock]" placeholder="Amount in ctock"
                                       value="<?php echo $value['in_stock'] ?>">
                                <label for="customer-name" class="field-icon">
                                    <i class="fa fa-tags"></i>
                                </label>
                            </label>
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 pt15">
                    <div class="form-group field-productparts-<?php echo $key; ?>-description required">
                        <div class="col-md-12" style="padding: 0">
                            <label for="customer-name" class="field prepend-icon">
                            <textarea id="productparts-<?php echo $key; ?>-description" class="form-control"
                                      name="ProductParts[<?php echo $key; ?>][description]"
                                      placeholder="Part Description"><?php echo $value['description'] ?></textarea>
                                <label for="customer-name" class="field-icon">
                                    <i class="fa fa-comments"></i>
                                </label>
                            </label>
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section row mbn">
                <div class="col-md-6 pt15 imgf">
                    <div class="form-group field-productparts-<?php echo $key; ?>-parts_imagefiles">
                        <div>
                            <div class="box">
                                <input type="hidden" name="ProductParts[<?php echo $key; ?>][parts_imageFiles][]"
                                       value="">
                                <input type="file" id="productparts-<?php echo $key; ?>-parts_imagefiles" class="inputfile inputfile-6"
                                       name="ProductParts[<?php echo $key; ?>][parts_imageFiles][]" multiple="" accept="image/*"
                                       onchange="showMyImage(this, <?php echo $key; ?>)"
                                       data-multiple-caption="{count} files selected">
                                <label class="" for="productparts-<?php echo $key; ?>-parts_imagefiles">
                                    <span></span>
                                    <strong class="btn btn-primary btn-file">
                                        <i class="glyphicon glyphicon-folder-open"></i>
                                        &ensp;Brows…
                                    </strong>
                                </label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden" id="defaultimg_part_<?php echo $key; ?>">
                        <input type="radio" id="def_img_part_<?php echo $key; ?>"
                               name="PartsDefImgs[defaultImagePart_<?php echo $key; ?>]" value=""
                               class="hidden radio_inp">
                    </div>
                    <div class="col-md-12 pt15" id="selectedFiles_<?php echo $key; ?>">
                    </div>
                </div>

                <?php $PartImages = \backend\models\ProductParts::getImageByPartId($value['id']) ?>
                <div class="col-md-6 pl15 pull-right">
                    <div class="gallery-page sb-l-o sb-r-c onload-check">
                        <div class="">
                            <div class="mix-container">
                                <div class="fail-message">
                                    <span>No images were found for the selected product</span>
                                </div>

                                <?php if (!empty($PartImages)) : ?>
                                    <?php foreach ($PartImages as $item => $partImage): ?>
                                        <div style="display: inline-block;"
                                             class="mix label1 folder1 <?= ($partImage['default_image_id'] == 1) ? 'default-view' : '' ?>">
                                                    <span class="close remove hidden">
                                                        <i class="fa fa-close icon-close"></i>
                                                    </span>
                                            <div class="panel p6 pbn">
                                                <div class="of-h">
                                                    <?php echo \yii\helpers\Html::img('/' . $partImage['name'],
                                                        [
                                                            'class' => 'img-responsive',
                                                            'title' => $value['name'],
                                                            'alt' => '',
                                                        ]) ?>
                                                    <div class="row table-layout change_image"
                                                         data-key="<?php echo $partImage['id'] ?>">
                                                        <div class="col-xs-8 va-m pln">
                                                            <h6><?php echo $value['name'] . '.png' ?></h6>
                                                        </div>
                                                        <div class="col-xs-4 text-right va-m prn">
                                                                    <span
                                                                        class="fa fa-eye-slash fs12 text-muted"></span>
                                                            <span
                                                                class="fa fa-circle fs10 text-info ml10"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <div class="gap"></div>
                                <div class="gap"></div>
                                <div class="gap"></div>
                                <div class="gap"></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
