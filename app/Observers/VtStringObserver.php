<?php

namespace App\Observers;

use App\Models\VtString;
use Illuminate\Support\Facades\Redis;

class VtStringObserver
{
    private $_redisConn;
    public function __construct()
    {
        $this->_redisConn = Redis::connection();
    }
    /**
     * Handle the VtString "created" event.
     *
     * @param  \App\Models\VtString  $vtString
     * @return void
     */
    public function created(VtString $vtString)
    {
        $this->_redisConn->del('vt_strings');
    }

    /**
     * Handle the VtString "updated" event.
     *
     * @param  \App\Models\VtString  $vtString
     * @return void
     */
    public function updated(VtString $vtString)
    {
        $this->_redisConn->del('vt_strings');
    }

    /**
     * Handle the VtString "deleted" event.
     *
     * @param  \App\Models\VtString  $vtString
     * @return void
     */
    public function deleted(VtString $vtString)
    {
        $this->_redisConn->del('vt_strings');
    }

    /**
     * Handle the VtString "restored" event.
     *
     * @param  \App\Models\VtString  $vtString
     * @return void
     */
    public function restored(VtString $vtString)
    {
        $this->_redisConn->del('vt_strings');
    }

    /**
     * Handle the VtString "force deleted" event.
     *
     * @param  \App\Models\VtString  $vtString
     * @return void
     */
    public function forceDeleted(VtString $vtString)
    {
        $this->_redisConn->del('vt_strings');
    }
}
