     
<div class="standard-list <?php echo $layout!='form'?'fieldset':null?>"
    data-js-view="standard-list"
    data-controller-route="<?php echo URL::to(SupportURL::action($controller))?>"
    data-position-offset="<?php echo $paginator_from?>"
    data-with-trashed="<?php echo $with_trashed?>"
    <?php if ($parent_controller) :?> data-parent-controller="<?php echo $parent_controller?><?php 
    endif?>"
  >

    <?php
    // Create the page title for the sidebar layout
    if ($layout == 'sidebar') {
        echo View::make('support::shared.list._sidebar_header', $__data)->render();

        // Create the page title for a full page layout
    } else if ($layout == 'full') {
        echo View::make('support::shared.list._full_header', $__data)->render();
    }
    ?>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="box box-info card card-info">
            <div class="box-header card-header with-border">
                <h3 class="box-title card-title">Dispositivos</h3>
            </div>
            <!-- /.box-header card-header -->
            <div class="box-body card-body table-responsive p-0">
            
       
<?php
    // Render the full table.  This could be broken up into smaller chunks but
    // leaving it as is until the need arises
    echo '<div class="listing-wrapper">'
    .View::make('support::shared.list._table', $__data)->render()
    .'</div>';

    // Add sidebar pagination
    if (!empty($layout) && $layout != 'full' && $count > count($listing)) : ?>
        <a href="<?php echo SupportURL::relative('index', $parent_id, $controller)?>" class="btn btn-default btn-sm btn-block full-list"><?php echo __('facilitador::list.standard.related', ['title' => title_case($title)]) ?></b></a>
    <?php endif ?>


<?php
// Render pagination
echo View::make('support::shared.pagination.index', $__data)->render();

?>
            </div>
        </div>
    </div>
</div>
