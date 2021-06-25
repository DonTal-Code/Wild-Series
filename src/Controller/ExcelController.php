<?php

namespace App\Controller;

use App\Entity\Season;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class ExcelController extends AbstractController
{

    private $entityManager;

    public function __construct( EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }


    private function getData(): array
    {
        /**
         * @var $seasons Season[]
         */
        $list = [];
        $seasons = $this->entityManager->getRepository(Season::class)->findAll();

        foreach ($seasons as $season) {

            $list[] = [
                $season->getProgram(),
                $season->getNumber(),
                $season->getYear(),
                $season->getDescription()
            ];
        }
        return $list;
    }

    /**
     * @Route("/excel", name="excel")
     */
    public function index()
    {
        $spreadsheet = new Spreadsheet();
        /* @var $sheet Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getCell('A1')->setValue('Program');
        $sheet->getCell('B1')->setValue('Number');
        $sheet->getCell('C1')->setValue('Year');
        $sheet->getCell('D1')->setValue('Description');
        $sheet->setTitle("Seasons");

        // Increase row cursor after header write
        $sheet->fromArray($this->getData(),null, 'A2', true);
        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);
        // Create a Temporary file in the system
        $fileName = 'seasons.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);
        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }


}


