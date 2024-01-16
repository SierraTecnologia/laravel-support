<?php

namespace Muleta\Traits\Models;

use Log;
use Muleta\Models\Base;
use Informate\Models\System\Archive;

class ArchiveTrait extends Base
{
    /**
     * Model contructuor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (\Illuminate\Support\Facades\Config::get('siravel.db-prefix', '')) {
            $this->table = $this->table;
        }
    }

    /**
     * After the item is saved to the database.
     *
     * @param object $payload
     *
     * @return void
     */
    public function afterSaved($payload): void
    {
        if (!request()->is('admin/revert/*') && !request()->is('admin/rollback/*/*')) {
            unset($payload->attributes['created_at']);
            unset($payload->attributes['updated_at']);
            unset($payload->original['created_at']);
            unset($payload->original['updated_at']);

            if ($payload->attributes != $payload->original) {
                Archive::create(
                    [
                    'token' => md5(time()),
                    'entity_id' => $payload->attributes['id'],
                    'entity_type' => get_class($payload),
                    'entity_data' => json_encode($payload->attributes),
                    ]
                );
                Log::info(get_class($payload).' #'.$payload->attributes['id'].' was archived');
            }
        }
    }

    /**
     * When the item is being deleted.
     *
     * @param object $payload
     *
     * @return void
     */
    public function beingDeleted($payload): void
    {
        $type = get_class($payload);
        $id = $payload->id;

        Translation::where('entity_id', $id)->where('entity_type', $type)->delete();
        Archive::where('entity_id', $id)->where('entity_type', $type)->delete();

        Archive::where('entity_type', 'Informate\Models\System\Translation')
            ->where('entity_data', 'LIKE', '%"entity_id":'.$id.'%')
            ->where('entity_data', 'LIKE', '%"entity_type":"'.$type.'"%')
            ->delete();
    }
}
