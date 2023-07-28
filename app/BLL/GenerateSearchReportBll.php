<?php

namespace App\BLL;

use Illuminate\Support\Facades\DB;

/**
 * | Author-Anshu Kumar
 * | Created On-28-07-2023 
 * | Get Template Details by id
 */
class GenerateSearchReportBll
{
    private $_parameters;
    /**
     * | Generate
     * | @param request
     * | @param template 
     */
    public function generate($req, $template)
    {
        $this->_parameters = $template['parameters'];

        $linkName = array();
        foreach ($req->params as $item) {
            $item = (object)$item;
            $parameter = $this->_parameters->where('id', $item->id)->first();
            $linkName = $parameter->link_name;
            ${$linkName} = $item->controlValue;
        }
        $query = $template['templates']->detail_sql;
        dd($query);
        $queryResult = DB::select($query);
        dd($queryResult);
        dd($this->_parameters->toArray());
    }
}
