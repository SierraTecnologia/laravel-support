<?php

namespace Support\Template\Actions;

class EditAction extends AbstractAction
{
    public function getTitle()
    {
        return __('support::generic.edit');
    }

    public function getIcon()
    {
        return 'facilitador-edit';
    }

    public function getPolicy()
    {
        return 'edit';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-primary pull-right edit',
        ];
    }

    public function getDefaultRoute()
    {
        return \Support\Routing\UrlGenerator::managerRoute($this->dataType->slug, 'edit', $this->data->{$this->data->getKeyName()});
        // return route('facilitador.'.$this->dataType->slug.'.edit', $this->data->{$this->data->getKeyName()});
    }
}
