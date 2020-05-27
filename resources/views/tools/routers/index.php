<div id="routers">
        <div class='col-sm-6 col-lg-4'>
            <div class="standard-list fieldset">
                <div class="legend sidebar-header">Rotas</div>
                <table>
                     <?php foreach($routers as $router): ?>
                        <tr data-js-view="task-method">
                            <tr>
                                <td width='10%'><h4>HTTP Method</h4></td>
                                <td width='10%'><h4>Route</h4></td>
                                <td width='80%'><h4>Corresponding Action</h4></td>
                            </tr>
                            <td>
                                <?php 
                                    echo $router->methods()[0];
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $router->getName();
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $router->getActionName();
                                ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </div>
</div>
