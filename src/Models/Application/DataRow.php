<?php

namespace Support\Models\Application;

use Illuminate\Database\Eloquent\Model;
use Translation\Traits\HasTranslations;

class DataRow extends Model
{
    use HasTranslations;

    protected $translatable = ['display_name'];

    protected $table = 'data_rows';

    protected $guarded = [];

    public $timestamps = false;

    public function rowBefore()
    {
        $previous = self::where('data_type_id', '=', $this->data_type_id)->where('order', '=', ($this->order - 1))->first();
        if (isset($previous->id)) {
            return $previous->field;
        }

        return '__first__';
    }

    public function relationshipField()
    {
        return @$this->details->column;
    }

    /**
     * Check if this field is the current filter.
     *
     * @return bool True if this is the current filter, false otherwise
     */
    public function isCurrentSortField($orderBy)
    {
        return $orderBy == $this->field;
    }

    /**
     * Build the URL to sort data type by this field.
     *
     * @return string Built URL
     */
    public function sortByUrl($orderBy, $sortOrder)
    {
        $params = [];
        $isDesc = $sortOrder != 'asc';
        if ($this->isCurrentSortField($orderBy) && $isDesc) {
            $params['sort_order'] = 'asc';
        } else {
            $params['sort_order'] = 'desc';
        }
        $params['order_by'] = $this->field;

        return url()->current().'?'.http_build_query(array_merge(\Request::all(), $params));
    }

    public function setDetailsAttribute($value)
    {
        $this->attributes['details'] = json_encode($value);
    }

    public function getDetailsAttribute($value)
    {
        return json_decode(!empty($value) ? $value : '{}');
    }

    /**
     * Eu que criei. Nao tava no facilitador
     */

    public function getName()
    {
        return ucfirst($this->display_name);
    }

    public function getColumnName()
    {
        return $this->field;
    }

    public function displayFromModel(Model $resultModel)
    {
        $column = $this->getColumnName();

        $result = $resultModel->$column;

        if (is_array($result)) {
            return implode(' - ', $result);
        }

        return $result;
    }
}
