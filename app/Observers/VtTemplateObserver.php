<?php

namespace App\Observers;

use App\Models\VtTemplate;
use Illuminate\Support\Facades\Redis;

class VtTemplateObserver
{
    private $_redisConn;

    public function __construct()
    {
        $this->_redisConn = Redis::connection();
    }
    /**
     * Handle the VtTemplate "created" event.
     *
     * @param  \App\Models\VtTemplate  $vtTemplate
     * @return void
     */
    public function created(VtTemplate $vtTemplate)
    {
        $this->_redisConn->del('vt_templates');
    }

    /**
     * Handle the VtTemplate "updated" event.
     *
     * @param  \App\Models\VtTemplate  $vtTemplate
     * @return void
     */
    public function updated(VtTemplate $vtTemplate)
    {
        $this->_redisConn->del('vt_templates');
    }

    /**
     * Handle the VtTemplate "deleted" event.
     *
     * @param  \App\Models\VtTemplate  $vtTemplate
     * @return void
     */
    public function deleted(VtTemplate $vtTemplate)
    {
        $this->_redisConn->del('vt_templates');
    }

    /**
     * Handle the VtTemplate "restored" event.
     *
     * @param  \App\Models\VtTemplate  $vtTemplate
     * @return void
     */
    public function restored(VtTemplate $vtTemplate)
    {
        $this->_redisConn->del('vt_templates');
    }

    /**
     * Handle the VtTemplate "force deleted" event.
     *
     * @param  \App\Models\VtTemplate  $vtTemplate
     * @return void
     */
    public function forceDeleted(VtTemplate $vtTemplate)
    {
        $this->_redisConn->del('vt_templates');
    }
}
