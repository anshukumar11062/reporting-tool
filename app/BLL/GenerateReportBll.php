<?php

namespace App\Bll;

use App\Traits\Api\PdfHelpers;
use Codedge\Fpdf\Fpdf\Fpdf;


/**
 * | Author-Anshu Kumar
 * | Created on-21-07-2023 
 * | Created for Reports layout previews
 */

class GenerateReportBll extends Fpdf
{
    use PdfHelpers;
    private $_fPdf;
    // Initialization
    public function __construct()
    {
        $this->_fPdf = new Fpdf();
    }

    // Create Report
    public function createReport($req)
    {
        // Check template look is default or according to the tempalte attributs for styling
        if ($req->template['isDefault'] == false) {
            $ori = ($req->template['isLandscape'] == true) ? 'L' : 'P';
            $size = ($req->template['paperSizeEnum']) ? $req->template['paperSizeEnum'] : '210,297';
            $this->_fPdf = new Fpdf($ori, 'mm', explode(',', $size));
            $this->_fPdf->Ln($req->template['footerHeight']);
        }

        $this->_fPdf->AliasNbPages();
        $this->_fPdf->AddPage();

        if (isset($req->layouts)) {                                         // Layout 
            foreach ($req->layouts as $layout) {
                $layout = (object)$layout;
                if ($layout->fieldType == 'caption')                       // Caption
                    $this->generateCaption($layout);
                elseif ($layout->fieldType == 'resourse')
                    $this->generateResource($layout);

                $this->_fPdf->Ln();                                         // Set it new line
            }
        }

        $response = $this->_fPdf->Output();
        return $response;
    }

    // generate Caption
    public function generateCaption($layout)
    {
        $fontWeight = $this->style($layout->isBold, $layout->isItalic, $layout->isUnderline);
        $this->_fPdf->SetTextColor(hexdec($layout->color));
        $this->_fPdf->SetFont($layout->fontName, $fontWeight, $layout->fontSize);
        $this->_fPdf->Cell($layout->x, $layout->y, $layout->caption, 0, 0,  $layout->alignment);
    }

    // Generate Resourse
    public function generateResource($layout)
    {
        $this->_fPdf->Image($layout->resoursePath, $layout->x, $layout->y, $layout->width, $layout->height);
    }
}
