<div class="many-to-many-form" data-js-view="many-to-many" data-controller-route="<?php echo FacilitadorURL::action($controller)?>" data-parent-id="<?php echo $parent_id?>" data-parent-controller="<?php echo $parent_controller?>">
    <input type="text" class="form-control <?php echo isset($layout)&&$layout=='sidebar'?'input-sm':null?>" placeholder="<?php echo __('facilitador::list.many_to_many.add')?>">
    <span class="glyphicon glyphicon-search"></span>
</div>