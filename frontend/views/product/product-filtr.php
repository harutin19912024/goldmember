<div class="container box products_filter">
    <div class="row">
        <div class="filter-item pull-left">
            <div class="filter-inner">
                <p class="pd20 hidden-sm ">Sort By:</p>
            </div>
        </div>
        <ul class="filters pull-right">

            <li class="">
                <div class="filter-item">
                    <div class="filter-inner">
                        <select class="form-control">
                            <option>Sort by: Shop's order</option>
                            <option>Sort by: Product name</option>
                            <option>Sort by: Price</option>
                            <option>Sort by: Art.no</option>
                            <option>Sort by: Art.no</option>
                        </select>
                    </div>
                </div>
            </li>
            <li class="">
                <div class="filter-item">
                    <div class="filter-inner">
                        <select class="form-control">
                            <option>Descending</option>
                            <option>Ascending</option>
                        </select>
                    </div>
                </div>
            </li>
            <li class="">
                <div class="filter-item">
                    <div class="filter-inner">

                    </div>
                </div>
            </li>
            <li class="pull-right">
                <div class="filter-item">
                    <div class="filter-inner">
                        <input type="hidden" id="current-view" value="grid">
                        <div class="gl_view"><a href="javascript:void(0)" onclick="changeView('grid');"  data-toggle="tooltip" title="grid view"><i id="grid-icon" class="material-icons <?php if($active == 'grid'):?>active<?php endif;?>">view_module</i></a></div>
                        <div class="gl_view"><a href="javascript:void(0)" onclick="changeView('list');" data-toggle="tooltip" title="list view"><i id="list-icon" class="material-icons <?php if($active == 'list'):?>active<?php endif;?> ">view_list</i></a></div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>