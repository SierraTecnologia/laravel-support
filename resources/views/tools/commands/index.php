<div id="commands">
    <?php $quantidade = count($commands); $contador = 0; ?>
    <div class='col-sm-12 col-lg-6'>
        <?php foreach($commands as $namespace => $subcommands): ?>
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

            <?php $contador += 1;
            if (($quantidade/2) <= $contador){ ?>
                </div>
                <div class='col-sm-12 col-lg-6'>
            <?php } ?>
        <?php endforeach ?>
    </div>
</div>
