<?php

// Import external libraries
require('libs/fpdf181/fpdf.php');
require('libs/qrcode/qrcode.class.php');
require('libs/Hashids/Hashids.php');

// Logo path
define('OWEEKONLINE_LOGO', 'img/oweeklogotext2-transparent.png');

// Name of the document
define('DOCUMENT_TITLE', 'tickets');

// Get POST values
$eventName = $_POST['event_name'];
$venueAddress = $_POST['venue'];
$iterations = intval($_POST['number_of_tickets']);

// For generating unique IDs
$hashids = new Hashids\Hashids("Salt:$eventName", 8);

// Generate IDs
$ids = [];
for ($i = 1; $i <= $iterations; $i++) {
  $ids[] = $hashids->encode($i);
}

// If "Generate" button is clicked
// --> Generate tickets as pdf
if (isset($_POST['generatepdf'])) {
  // Set up new document
  $pdf = new FPDF();
  $pdf->SetTitle(DOCUMENT_TITLE);

  // Add new page for each ticket
  foreach ($ids as $id) {
    addTicket($pdf, $id, "http://example.com/$id", $eventName, $venueAddress);
  }

  // Output the ticket
  $pdf->Output('D', DOCUMENT_TITLE . '.pdf');
}

// If 'Export IDs as .txt' button is clicked
// --> Generate a list of IDs as .txt file
if (isset($_POST['export'])) {
  header('Content-Type: text/plain');
  header('Content-Disposition: attachment; filename=IDs.txt');

  foreach ($ids as $id) {
    echo $id . "\n";
  }
}

// This function adds a new page containing ticket details
// on to the pdf.
function addTicket(&$pdf, $ticketId, $ticketValidationUrl, $eventName, $eventAddress)
{
  $pdf->AddPage();

  // LOGO
  $pdf->Image(OWEEKONLINE_LOGO, null, null, 50);

  // FULL NAME
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetXY(65, 15);
  $pdf->Cell(0, 0, "Name:");

  // EMAIL ADDRESS
  $pdf->SetXY(65, 23);
  $pdf->Cell(0, 0, "Email:");

  // TICKET ID
  $pdf->SetXY(65, 31);
  $pdf->Cell(0, 0, "Ticket ID: $ticketId");

  // QR CODE
  $qrcode = new QRcode($ticketValidationUrl, 'H');
  $qrcode->displayFPDF($pdf, 155, 10, 45);

  // HORIZONTAL LINE
  $pdf->Line(10, 60, 200, 60);

  // EVENT TITLE
  $pdf->SetXY(9, 70);
  $pdf->SetFont('Arial', 'B', 18);
  $pdf->Cell(0, 0, $eventName);

  // EVENT VENUE
  $pdf->SetXY(9, 78);
  $pdf->SetFont('Arial', '', 17);
  $pdf->Cell(0, 0, $eventAddress);

  return $pdf;
}