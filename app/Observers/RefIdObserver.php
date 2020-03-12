<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class RefIdObserver
{
    /**
     * Handle the "creating" event.
     *
     * @param  mixed $item
     * @return void
     */
    public function creating(Model $item)
    {
        $item->refid = $this->getRefId($item);
    }

    /**
     * Handle the "updating" event.
     *
     * @param  mixed $item
     * @return void
     */
    public function updating(Model $item)
    {
        if (!$item->refid) {
            $item->refid = $this->getRefId($item);
        }
    }

    /**
     * Generate refference ID.
     *
     * @param Model $item
     * @return int $id
     */
    private function getRefId(Model $item)
    {
        $prefix = '';
        $nextRefId = 1000;

        $lastItem = $item->where('refid', '<>', '')
            ->orderBy('refid', 'DESC')->first();

        switch ($item->getTable()) {
            case 'clients':
                $prefix = 'deb';
                break;
            case 'server_apps':
                $prefix = 'app';
                break;
            case 'server_users':
                $prefix = 'user';
                break;
            case 'server_auth_keys':
                $prefix = 'key';
                break;
            case 'server_firewall_rules':
                $prefix = 'fw';
                break;
            case 'server_databases':
                $userId = isset($item->app) ? $item->app->user_id : $item->user_id;
                $prefix = (isset($item->app) ? $item->app->user->refid : $item->user->refid) . '_db';
                $lastItem = $item->where('user_id', $userId)
                    ->where('refid', '<>', '')->orderBy('refid', 'DESC')->first();
                $nextRefId = 10;
                break;
            case 'server_hosts':
                $prefix = $item->group->name;
                $lastItem = $item->where('group_id', $item->group_id)
                    ->where('refid', '<>', '')->orderBy('refid', 'DESC')->first();
                $nextRefId = 10;
                break;
        }

        if ($lastItem && isset($lastItem->refid)) {
            preg_match_all('!\d+!', $lastItem->refid, $matches);
            if (isset($matches[0][0]) && is_numeric($matches[0][0])) {
                $nextRefId = $matches[0][0] + 1;
            }
        }

        return $prefix . $nextRefId;
    }
}
