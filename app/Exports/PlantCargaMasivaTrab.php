<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class PlantCargaMasivaTrab implements FromView, WithEvents
{

    protected $id_cliente;

    public function __construct($id_cliente)
    {
        $this->id_cliente = $id_cliente;
    }
    public function view(): View
    {
        return view('exports.cmTrab', [
            'headers' => ['#', 'Nombre Completo', 'R.U.N.', 'Móvil', 'Correo', 'Área']

        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->setRightToLeft(false);
                Log::info('id cliente en el export: ' . $this->id_cliente);
                // Configurar opciones para el combo desplegable
                $options = DB::table('ubk2_areas')->where('id_cliente', $this->id_cliente)->pluck('nombre')->toArray();

                // Crear un objeto DataValidation y configurarlo
                $dataValidation = new DataValidation();
                $dataValidation->setType(DataValidation::TYPE_LIST)
                    ->setErrorStyle(DataValidation::STYLE_STOP)
                    ->setAllowBlank(false)
                    ->setShowInputMessage(true)
                    ->setShowErrorMessage(true)
                    ->setShowDropDown(true)
                    ->setErrorTitle('Error')
                    ->setError('El valor no es válido')
                    ->setPromptTitle('Elige una opción')
                    ->setPrompt('Por favor, elige una opción de la lista')
                    ->setFormula1('"' . implode(',', $options) . '"');

                // Aplicar la validación de datos a la celda A1
                // $event->sheet->getCell('A1')->setDataValidation($dataValidation);
                for ($row = 2; $row <= 101; $row++) {
                    $cell = $event->sheet->getCell('F' . $row);
                    $cell->setValue('Seleccione un área'); // Placeholder
                    $cell->setDataValidation(clone $dataValidation);
                }
                $numbers = range(1, 100);
                $row = 2;
                foreach ($numbers as $number) {
                    $event->sheet->setCellValue('A' . $row, $number);
                    $row++;
                }
                //$event->sheet->getCell('c2')->getStyle()->getNumberFormat()->setFormatCode('00.000.000-[\dkK]$');
                $event->sheet->getCell('D2')->getStyle()->getNumberFormat()->setFormatCode('+00-0-0000-0000');

                $event->sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(40);

// todos los bordes
$event->sheet->getStyle('A1:F101')->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000'], // Color negro
        ],
    ],
]);

// relleno y color cabecera
 $event->sheet->getStyle('A1:F1')->applyFromArray([
    'font' => [
        'bold' => true,
        'color' => [
            'rgb' => 'FFFFFF', // Color blanco
        ],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '063970', // Color azul
        ],
    ],
]);

//---


            }
        ];
    }
}
