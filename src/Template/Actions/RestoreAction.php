<?php

namespace Support\Template\Actions;

class RestoreAction extends AbstractAction
{
    public function getTitle()
    {
        return __('support::generic.restore');
    }

    public function getIcon()
    {
        return 'facilitador-trash';
    }

    public function getPolicy()
    {
        return 'restore';
    }

    public function getAttributes()
    {
        return [
            'class'   => 'btn btn-sm btn-success pull-right restore',
            'data-id' => $this->data->{$this->data->getKeyName()},
            'id'      => 'restore-'.$this->data->{$this->data->getKeyName()},
        ];
    }

    public function getDefaultRoute()
    {
        return \Support\Routing\UrlGenerator::managerRoute($this->dataType->slug, 'restore', $this->data->{$this->data->getKeyName()});
        // return route('facilitador.'.$this->dataType->slug.'.restore', $this->data->{$this->data->getKeyName()});
    }
}
