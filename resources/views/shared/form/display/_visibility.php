<?php
// Make help
$help = (!empty($item) && $uri = $item->getUriAttribute()) ?
    __('support::display.visibility.help', ['uri' => $uri]) :
    __('support::display.visibility.alternate_help');

// Check if they have permission
if (!app('facilitador.user')->can('publish', $controller)) {
    $status = $item && $item->public ? __('support::display.visibility.published') : __('support::display.visibility.draft');
    echo Former::note('Status', $status)->blockHelp($help);
    return;
}

// Render radios
echo Former::radios('public', __('support::display.visibility.label'))->inline()->radios(
    array(
    __('support::display.visibility.public') => array('value' => '1', 'checked' => empty($item) ? true : $item->public),
    __('support::display.visibility.private') => array('value' => '0', 'checked' => empty($item) ? false : !$item->public),
    )
)->blockHelp($help);
