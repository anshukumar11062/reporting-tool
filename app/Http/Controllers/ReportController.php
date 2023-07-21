<?php

namespace App\Http\Controllers;

/**
 * | Author - Anshu Kumar
 * | Created On-21-07-2023 
 * | Creation for - Report Creation 
 */

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private $_fPdf;
    public function __construct()
    {
        $this->_fPdf = new Fpdf();
    }

    //
    public function reportGenerate(Request $req)
    {


        $this->_fPdf->AliasNbPages();
        $this->_fPdf->AddPage();
        // Set Header
        $this->_fPdf->SetFont('Arial', 'B', 15);
        /* Move to the right */
        $this->_fPdf->Cell(60);
        $this->_fPdf->Cell(70, 10, 'Page Heading', 0, 0, 'C');

        // Set Footer
        /* Position at 1.5 cm from bottom */
        $this->_fPdf->SetY(-31);
        /* Arial italic 8 */
        $this->_fPdf->SetFont('Arial', 'I', 8);
        /* Page number */
        $this->_fPdf->Cell(0, 10, 'Page ' . $this->_fPdf->PageNo() . '/{nb}', 0, 0, 'C');

        $this->_fPdf->SetFont('Times', '', 12);

        $response = $this->_fPdf->Output();

        return response($response, 200, [
            'Content-type'        => 'application/pdf',
        ]);
    }
}
