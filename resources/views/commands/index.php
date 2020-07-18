<div id="commands">
    <?php foreach($commands as $namespace => $subcommands): ?>
        <div class='col-sm-6 col-lg-4'>
            <div class="standard-list fieldset">
                <div class="legend sidebar-header"><?php echo $namespace?></div>
                <table>
                    <?php foreach($subcommands as $name => $command): ?>
                        <tr data-js-view="task-method">
                            <td>
                                <a data-action="<?php echo route('facilitador.commands@execute', $command->getName())?>"
                                   class="btn btn-default"><?php echo __('facilitador::commands.execute')?></a>
                            </td>
                            <td>
                                <p>
                        <?php echo $name?>
                                    <div class='spinner-46'></div>
                                </p>
                                <p><small><?php echo $command->getDescription()?></small></p>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </div>
    <?php endforeach ?>
</div>
