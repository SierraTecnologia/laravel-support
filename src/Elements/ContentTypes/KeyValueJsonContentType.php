<?php

namespace Support\Elements\ContentTypes;
use Support\Elements\ContentTypes\BaseType;

class KeyValueJsonContentType extends BaseType
{
    /**
     * @return null|string
     */
    public function handle()
    {
        $value = $this->request->input($this->row->field);

        $new_parameters = array();
        foreach ($value as $key => $val) {
            if($value[$key]['key']) {
                $new_parameters[] = $value[$key];
            }
        }
        
        return json_encode($new_parameters);
    }
}
