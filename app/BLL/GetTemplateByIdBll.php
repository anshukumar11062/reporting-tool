<?php

namespace App\BLL;

use App\Models\VtSearchGroup;
use App\Models\VtTemplate;
use App\Models\VtTemplateDeatil;
use App\Models\VtTemplateFooter;
use App\Models\VtTemplateParameter;
use Exception;

use function PHPUnit\Framework\isEmpty;

/**
 * | Author-Anshu Kumar
 * | Created On-28-07-2023 
 * | Get Template Details by id
 */
class GetTemplateByIdBll
{
    private $_mVtSearchGroup;
    private $_mVtTemplates;
    private $_mVtTemplateDetails;
    private $_mVtTemplateFooters;
    private $_mVtTemplateParameters;
    private $_templateId;
    private $_GRID;
    /**
     * | Initializing Variables
     */
    public function __construct()
    {
        $this->_mVtSearchGroup = new VtSearchGroup();
        $this->_mVtTemplates = new VtTemplate();
        $this->_mVtTemplateDetails = new VtTemplateDeatil();
        $this->_mVtTemplateFooters = new VtTemplateFooter();
        $this->_mVtTemplateParameters = new VtTemplateParameter();
    }

    /**
     * | Get Template 
     * | @param id TemplateID
     */
    public function getTemplate($id)
    {
        $this->_templateId = $id;
        $template = $this->_mVtTemplates::find($id);
        if (isEmpty($template))
            throw new Exception("Template Not Found");
        return $template;
    }
}
