<?php
require_once('../pdf/tcpdf/tcpdf.php');

// Function to generate the PDF invoice
function generateInvoice($user, $shippingInfo, $cartItems, $subtotal, $tax, $total)
{
    // Create a new TCPDF instance
    $pdf = new TCPDF();

    // Set document information
    $pdf->SetCreator('PcPulse');
    $pdf->SetAuthor('PcPulse');
    $pdf->SetTitle('Invoice');

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 12);

    // Set header background color
    $pdf->SetFillColor(23, 162, 184); // Light Blue Accent
    $pdf->Rect(0, 0, 210, 30, 'F');

    // Output current date on the right of the header
    $pdf->Cell(0, 10, 'Date: ' . date('Y-m-d'), 0, 1, 'R');

    // Output the logo on the left of the header
    $pdf->Image('../images/logo.png', 20, 10, 60);

    // Add some spacing
    $pdf->Ln(25);

    // Output user details
    $pdf->SetFont('helvetica', 'B', 12); // Set font to bold
    $pdf->Cell(0, 10, 'User Information:', 0, 1);
    $pdf->Cell(0, 10, 'Username: ' . $user['username'], 0, 1);
    $pdf->Cell(0, 10, 'Email: ' . $user['email'], 0, 1);
    $pdf->Cell(0, 10, 'Address: ' . $shippingInfo['address'], 0, 1);
    $pdf->SetFont('helvetica', '', 12); // Reset font to regular


    // Add some spacing
    $pdf->Ln(10);

    // Output order summary
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Order Summary:', 0, 1);

    // Output the table header
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(50, 10, 'Product', 1);
    $pdf->Cell(30, 10, 'Quantity', 1);
    $pdf->Cell(30, 10, 'Price', 1);
    $pdf->Cell(40, 10, 'Total', 1);
    $pdf->Ln();

    // Reset font for table content
    $pdf->SetFont('helvetica', '', 12);

    // Output each item in the cart
    foreach ($cartItems as $item) {
        $pdf->Cell(50, 10, $item['productName'], 1);
        $pdf->Cell(30, 10, $item['quantity'], 1);
        $pdf->Cell(30, 10, '$' . number_format($item['price'], 2), 1);
        $pdf->Cell(40, 10, '$' . number_format($item['price'] * $item['quantity'], 2), 1);
        $pdf->Ln();
    }

    // Add some spacing
    $pdf->Ln(10);

    // Output subtotal, tax, and total
    $pdf->Cell(0, 10, 'Subtotal: $' . number_format($subtotal, 2), 0, 1);
    $pdf->Cell(0, 10, 'Tax (13%): $' . number_format($tax, 2), 0, 1);
    $pdf->Cell(0, 10, 'Total: $' . number_format($total, 2), 0, 1);

    // Add some spacing
    $pdf->Ln(20);

    // Set footer background color
    $pdf->SetFillColor(52, 58, 65); // #343a41
    $pdf->Rect(0, $pdf->GetY(), 210, 50, 'F');

    // Set footer font color
    $pdf->SetTextColor(255, 255, 255); // White

    // Set footer position
    $footerY = $pdf->GetY();

    // Output company information in a three-column layout
    $columnWidth = 70;
    $pdf->Cell($columnWidth, 10, 'PcPulse', 0, 0, 'C');
    $pdf->Cell($columnWidth, 10, 'pcpulse.com', 0, 0, 'C');
    $pdf->Cell($columnWidth, 10, '120 Market Street', 0, 1, 'C');
    $pdf->Cell($columnWidth, 10, 'Brantford, ON, Canada N3S0K5', 0, 0, 'C');
    $pdf->Cell($columnWidth, 10, '564-555-1234', 0, 0, 'C');
    $pdf->Cell($columnWidth, 10, 'info@pcpulse.com', 0, 0, 'C');

    // Reset font color
    $pdf->SetTextColor(0, 0, 0); // Reset to Black

    // Set footer position to the bottom of the page
    $pdf->SetY($pdf->GetPageHeight() - 50);

    // Output the PDF content
    $pdfContent = $pdf->Output('', 'S');
    return $pdfContent;
}

// Sample data (replace it with actual data)
$user = json_decode($_POST['user'], true);
$shippingInfo = json_decode($_POST['shippingInfo'], true);
$cartItems = json_decode($_POST['cartItems'], true);
$subtotal = floatval($_POST['subtotal']);
$tax = floatval($_POST['tax']);
$total = floatval($_POST['total']);

// Call the function to generate the PDF
$pdfContent = generateInvoice($user, $shippingInfo, $cartItems, $subtotal, $tax, $total);

// Return the PDF content
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="invoice.pdf"');
echo $pdfContent;
?>