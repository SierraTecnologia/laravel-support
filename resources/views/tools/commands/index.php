<div id="routers">
    <?php foreach($routers as $subrouters): ?>
        <div class='col-sm-6 col-lg-4'>
            <div class="standard-list fieldset">
                <div class="legend sidebar-header"><?php echo $namespace?></div>
                <table>
                    <?php foreach($subrouters as $name => $router): ?>
                        <tr data-js-view="task-method">
                            <tr>
                                <td width='10%'><h4>HTTP Method</h4></td>
                                <td width='10%'><h4>Route</h4></td>
                                <td width='80%'><h4>Corresponding Action</h4></td>
                            </tr>
                            <td>
                                <?php 
                                    echo $value->getMethods()[0];
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $value->getPath();
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $value->getActionName();
                                ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </div>
    <?php endforeach ?>
</div>
