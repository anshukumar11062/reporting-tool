<?php

namespace App\Observers;

use App\Models\VtSearchGroup;
use Illuminate\Support\Facades\Redis;

class VtSearchGroupObserver
{
    private $_redisConn;

    public function __construct()
    {
        $this->_redisConn = Redis::connection();
    }
    /**
     * Handle the VtSearchGroup "created" event.
     *
     * @param  \App\Models\VtSearchGroup  $vtSearchGroup
     * @return void
     */
    public function created(VtSearchGroup $vtSearchGroup)
    {
        $this->_redisConn->del('vt_search_groups');
    }

    /**
     * Handle the VtSearchGroup "updated" event.
     *
     * @param  \App\Models\VtSearchGroup  $vtSearchGroup
     * @return void
     */
    public function updated(VtSearchGroup $vtSearchGroup)
    {
        $this->_redisConn->del('vt_search_groups');
    }

    /**
     * Handle the VtSearchGroup "deleted" event.
     *
     * @param  \App\Models\VtSearchGroup  $vtSearchGroup
     * @return void
     */
    public function deleted(VtSearchGroup $vtSearchGroup)
    {
        $this->_redisConn->del('vt_search_groups');
    }

    /**
     * Handle the VtSearchGroup "restored" event.
     *
     * @param  \App\Models\VtSearchGroup  $vtSearchGroup
     * @return void
     */
    public function restored(VtSearchGroup $vtSearchGroup)
    {
        $this->_redisConn->del('vt_search_groups');
    }

    /**
     * Handle the VtSearchGroup "force deleted" event.
     *
     * @param  \App\Models\VtSearchGroup  $vtSearchGroup
     * @return void
     */
    public function forceDeleted(VtSearchGroup $vtSearchGroup)
    {
        $this->_redisConn->del('vt_search_groups');
    }
}
