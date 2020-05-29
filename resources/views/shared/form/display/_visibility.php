<?php
// Make help
$help = (!empty($item) && $uri = $item->getUriAttribute()) ?
    __('facilitador::display.visibility.help', ['uri' => $uri]) :
    __('facilitador::display.visibility.alternate_help');

// Check if they have permission
if (!app('facilitador.user')->can('publish', $controller)) {
    $status = $item && $item->public ? __('facilitador::display.visibility.published') : __('facilitador::display.visibility.draft');
    echo Former::note('Status', $status)->blockHelp($help);
    return;
}

// Render radios
echo Former::radios('public', __('facilitador::display.visibility.label'))->inline()->radios(
    array(
    __('facilitador::display.visibility.public') => array('value' => '1', 'checked' => empty($item) ? true : $item->public),
    __('facilitador::display.visibility.private') => array('value' => '0', 'checked' => empty($item) ? false : !$item->public),
    )
)->blockHelp($help);
