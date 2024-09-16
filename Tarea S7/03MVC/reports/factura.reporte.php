<?php
require('./fpdf/fpdf.php');

class PDF extends FPDF
{
    private $sub_total;
    private $total_iva;
    private $total_a_pagar;

    // Constructor para inicializar los totales
    public function __construct($sub_total, $total_iva, $total_a_pagar)
    {
        parent::__construct();
        $this->sub_total = $sub_total;
        $this->total_iva = $total_iva;
        $this->total_a_pagar = $total_a_pagar;
    }

    // Cabecera de página
    function Header()
    {
        // Información de la empresa
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 6, 'Empresa XYZ', 0, 1, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, 'RUC: 1234566890', 0, 1, 'L');
        $this->Cell(0, 6, utf8_decode('Dirección: Calle 1 234, Ambato, Ecuador'), 0, 1, 'L');
        $this->Cell(0, 6, 'Teléfono: +593 121 121 121', 0, 1, 'L');
        $this->Cell(0, 6, 'Email: info@empresa.com', 0, 1, 'L');
        $this->Ln(5); // Espacio en blanco

        // Datos de la factura
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 6, 'Factura', 0, 1, 'R');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, 'No. 001-001-000000001', 0, 1, 'R');
        $this->Cell(0, 6, 'Fecha de Emisión: ' . date('Y-m-d'), 0, 1, 'R');
        $this->Ln(10);

        // Datos del cliente
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 6, 'Datos del Cliente', 0, 1, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, utf8_decode('Nombre: Luis Lopez'), 0, 1, 'L');
        $this->Cell(0, 6, 'Cédula/RUC: 1234567890', 0, 1, 'L');
        $this->Cell(0, 6, utf8_decode('Dirección: Calle Numero 321, Ambato, Ecuador'), 0, 1, 'L');
        $this->Cell(0, 6, 'Teléfono: +593 989 123 345', 0, 1, 'L');
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-60);

        // Calcular y mostrar totales
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, 'Subtotal: $' . number_format($this->sub_total, 2), 0, 1, 'R');
        $this->Cell(0, 6, 'IVA (15%): $' . number_format($this->total_iva, 2), 0, 1, 'R');
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 6, 'Total a Pagar: $' . number_format($this->total_a_pagar, 2), 0, 1, 'R');

        $this->Ln(5);

        // Información de la forma de pago
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, 'Forma de pago: Transferencia Bancaria', 0, 1, 'L');
        $this->Cell(0, 6, 'Cuenta Bancaria: Banco Pichincha, Cta: 11223344', 0, 1, 'L');

        // Nota
        $this->Ln(5);
        $this->Cell(0, 6, 'Nota: Gracias por elegirnos.', 0, 1, 'C');
    }

    // Tabla de productos
    function ProductosTable($header, $data)
    {
        // Cabecera
        $this->SetFont('Arial', 'B', 12);
        foreach ($header as $col) {
            $this->Cell(32, 7, $col, 1);
        }
        $this->Ln();

        // Datos
        $this->SetFont('Arial', '', 12);
        foreach ($data as $row) {
            foreach ($row as $col) {
                $this->Cell(32, 7, utf8_decode($col), 1);
            }
            $this->Ln();
        }
    }
}

// Crear PDF y calcular totales
$sub_total = 6000;
$valor_iva = 15;
$total_iva = ($sub_total * $valor_iva) / 100;
$total_a_pagar = $sub_total + $total_iva;

// Crear instancia de la clase PDF
$pdf = new PDF($sub_total, $total_iva, $total_a_pagar);
$pdf->AliasNbPages();
$pdf->AddPage();

// Encabezados de la tabla
$header = ['Descripcion', 'Cantidad', 'Precio', 'Subtotal', 'IVA', 'Total'];

// Datos simulados (productos seleccionados)
$productos = [
    ['Producto 1', 2, 1000, 2000, 12, 2000],
    ['Producto 2', 2, 1000, 2000, 12, 2000],
    ['Producto 3', 2, 1000, 2000, 12, 2000]
];

// Llamar a la función para crear la tabla
$pdf->ProductosTable($header, $productos);

// Guardar el archivo PDF
$pdf->Output('I', 'factura.pdf');
?>
