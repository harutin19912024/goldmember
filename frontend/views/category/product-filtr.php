<div class="container box products_filter">
    <div class="row">
        <div class="filter-item pull-left">
            <div class="filter-inner">
                <p class="pd20 hidden-sm ">Sort By:</p>
            </div>
        </div>
        <ul class="filters pull-right">
            <li class="ftrb">
                <div class="filter-item pull-right">
                    <div class="filter-inner">
                        <div class="dropdown">
                            <a class="dropdown-toggle" id="dropmatch" data-toggle="dropdown">BEST MATCH</a><i
                                class="material-icons">keyboard_arrow_down</i>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropmatch">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">lorem</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">ispum</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
            <li class="">
                <div class="filter-item">
                    <div class="filter-inner">
                        <form class="form-inline">
                            <div class="form-group">
                                <label for="keyword">KEYWORDS</label>
                                <input type="text" class="form-control" id="keyword" placeholder="Wiskey">
                            </div>
                        </form>
                    </div>
                </div>
            </li>
            <li class="">
                <div class="filter-item">
                    <div class="filter-inner">
                        <span class="hidden-sm hidden-xs">price:</span> <b>$1</b>
                        <input id="ex2" type="text" class="span2" value="" data-slider-min="1"
                               data-slider-max="1000" data-slider-step="5" data-slider-value="[1,1000]"/>
                        <b>$1000</b>
                    </div>
                </div>
            </li>
            <li class="">
                <div class="filter-item">
                    <div class="filter-inner">
                        <label class="filter-shipping control control--checkbox">Free Shipping
                            <input type="checkbox" checked="checked">
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
            </li>
            <li class="pull-right">
                <div class="filter-item">
                    <div class="filter-inner">
                        <input type="hidden" id="current-view" value="grid">
                        <div class="gl_view"><a href="javascript:void(0)" onclick="changeToGrid();"  data-toggle="tooltip" title="grid view"><i id="grid-icon" class="material-icons <?php if($active == 'grid'):?>active<?php endif;?>">view_module</i></a></div>
                        <div class="gl_view"><a href="javascript:void(0)" onclick="changeToList();" data-toggle="tooltip" title="list view"><i id="list-icon" class="material-icons <?php if($active == 'list'):?>active<?php endif;?> ">view_list</i></a></div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>