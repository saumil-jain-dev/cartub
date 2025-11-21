<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Wash Invoice</title>
     <meta name="description"
    content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
  <meta name="keywords"
    content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
  <meta name="author" content="pixelstrap">
    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">
 <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">
  <title>{{ $pageTitle }}</title>
  <!-- html2pdf.js CDN for PDF generation -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 3px solid #2196F3;
            padding-bottom: 20px;
        }
        
        .company-info h1 {
            color: #2196F3;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .company-info p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .invoice-title {
            text-align: right;
        }
        
        .invoice-title h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .detail-block h3 {
            color: #2196F3;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .detail-block p {
            color: #555;
            font-size: 14px;
            line-height: 1.8;
            margin-bottom: 5px;
        }
        
        .services-section {
            margin-bottom: 40px;
        }
        
        .services-section h3 {
            color: #2196F3;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        thead {
            background: #f9f9f9;
            border-top: 2px solid #2196F3;
            border-bottom: 2px solid #2196F3;
        }
        
        th {
            padding: 12px;
            text-align: left;
            color: #2196F3;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            color: #555;
            font-size: 14px;
        }
        
        .quantity, .price, .total {
            text-align: right;
        }
        
        tbody tr:hover {
            background: #f9f9f9;
        }
        
        .summary {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }
        
        .summary-box {
            width: 300px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
            color: #555;
        }
        
        .summary-row.subtotal {
            border-bottom: 1px solid #eee;
            margin-bottom: 8px;
            padding-bottom: 8px;
        }
        
        .summary-row.total {
            font-size: 18px;
            font-weight: bold;
            color: #2196F3;
            margin-top: 8px;
            padding-top: 8px;
        }
        
        .notes {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        
        .notes h4 {
            color: #2196F3;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        
        .notes p {
            color: #666;
            font-size: 13px;
            line-height: 1.6;
        }
        
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #999;
        }
        
        .thank-you {
            text-align: center;
            color: #2196F3;
            font-weight: 600;
        }
        
        .promo-section {
        display: block;
        margin-bottom: 30px;
        padding: 15px;
        background: rgba(41, 197, 246, 1);
        border-left: 5px solid rgba(41, 197, 246, 1);
        }
    
    .promo-icon {
        display: inline-block;
        font-size: 28px;
        margin-right: 15px;
    }
    
    .promo-content {
        display: inline-block;
        width: calc(100% - 50px);
    }
    
    .promo-code {
        display: inline-block;
        background: white;
        padding: 6px 12px;
        border-radius: 3px;
        font-weight: bold;
        color: rgba(41, 197, 246, 1);
        font-size: 12px;
        margin-right: 10px;
        border: 2px dashed rgba(41, 197, 246, 1);
        letter-spacing: 1px;
    }
    
    .promo-text {
        display: inline-block;
        color: #333;
        font-size: 13px;
        font-weight: bold;
    }

    .summary-row.discount {
        color: #4CAF50;
        font-weight: bold;
    }
        
        @media (max-width: 600px) {
            .invoice-container {
                padding: 20px;
            }
            
            .invoice-header {
                flex-direction: column;
                border-bottom: 2px solid #2196F3;
            }
            
            .invoice-title {
                text-align: left;
                margin-top: 20px;
            }
            
            .invoice-details {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            table {
                font-size: 12px;
            }
            
            th, td {
                padding: 8px;
            }
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .invoice-container {
                box-shadow: none;
                max-width: 100%;
            }
        }
        /* Action Buttons Container */
.action-buttons {
    max-width: 800px;
    margin: 0 auto 20px;
    display: flex;
    gap: 15px;
    justify-content: flex-end;
}

/* Base Button Styles */
.btn {
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Download Button */
.btn-download {
    background: #2196F3;
    color: white;
}

.btn-download:hover {
    background: #1976D2;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
}

/* Print Button */
.btn-print {
    background: #4CAF50;
    color: white;
}

.btn-print:hover {
    background: #45a049;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
}

/* Mobile Responsive */
@media (max-width: 600px) {
    .action-buttons {
        flex-direction: column;
    }
}

/* Print Media - Hide buttons when printing */
@media print {
    .action-buttons {
        display: none;
    }
}
    </style>
</head>
<body>
    <div class="invoice-container" id="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <img src="{{ asset('assets/images/logo.png') }}" alt="CarTub Logo" style="height: 50px; margin-bottom: 10px;">
                <p>Mobile Car Wash & Detailing</p>
                <p>Phone: 020 8200 7777</p>
                <p>Email: info@cartub.uk</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p><strong>Invoice #:</strong> {{ $bookingDetails->booking_number }}</p>
                <p><strong>Date:</strong> {{ $bookingDetails->created_at->format('d M, Y') }}</p>
            </div>
        </div>
        
        <!-- Details -->
        <div class="invoice-details">
            <div class="detail-block">
                <h3>Bill To</h3>
                <p><strong> {{ $bookingDetails->customer?->name }}</strong></p>
                <p>{{ $bookingDetails->address }}</p>
                <p>Phone: ({{ $bookingDetails->customer?->country_code }}) {{ $bookingDetails->customer?->phone }}</p>
            </div>
            <div class="detail-block">
                <h3>Service Details</h3>
                <p><strong>Service Date:</strong> {{ $bookingDetails->created_at->format('d M, Y') }}</p>
                <p><strong>Service Location:</strong> {{ $bookingDetails->address }} </p>
                 <p><strong>Vehicle:</strong> {{ $bookingDetails->vehicle->year }} {{ $bookingDetails->vehicle->make }} {{ $bookingDetails->vehicle->model }}</p>
                <p><strong>License Plate:</strong> {{  $bookingDetails->vehicle->license_plate }} </p>
            </div>
        </div>
        
        <!-- Services -->
        <div class="services-section">
            <h3>Services Provided</h3>
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Description</th>
                        <th class="total">Total</th>
                    </tr>
                </thead>
                <tbody>

                    @if($bookingDetails->service)
                        <tr>
                          <td>{{ $bookingDetails->service->name ?? 'N/A' }}</td>
                          <td>{{ $bookingDetails->service->description ?? 'N/A' }}</td>
                          <td class="total">£{{ $bookingDetails->service->price ?? '0.00' }}</td>
                        </tr>
                    @endif
                        
                   
                    <!-- Add-ons/Package -->
                    @if($bookingDetails->addOns)
                        <tr>
                            <td>{{ $bookingDetails->addOns->name ?? 'N/A' }}</td>
                            <td>{{ $bookingDetails->addOns->description ?? 'N/A' }}</td>
                            <td class="total">£{{ $bookingDetails->addOns->price ?? '0.00' }}</td>
                        </tr>
                    @endif
                    
                </tbody>
            </table>
        </div>
        
        
        <!-- Summary -->
        <div class="summary">
            <div class="summary-box">
                <div class="summary-row subtotal">
                    <span class="summary-row-label">Subtotal:</span>
                    <span class="summary-row-value">${{ number_format($bookingDetails->gross_amount ?? 00.00, 2) }}</span>
                </div>
                @if($bookingDetails->discount_amount ?? false)
                <div class="summary-row discount">
                    <span class="summary-row-label">Discount ({{ $bookingDetails->discount_amount ?? 'SUMMER20' }}):</span>
                    <span class="summary-row-value">-${{ number_format($bookingDetails->discount_amount, 2) }}</span>
                </div>
                @endif
                <div class="summary-row total">
                    <span class="summary-row-label">TOTAL:</span>
                    <span class="summary-row-value">${{ number_format($bookingDetails->total_amount ?? 00, 2) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Notes -->
        <div class="notes">
            <h4>Notes</h4>
            <p>Thank you for choosing Cartub! We appreciate your business. Payment is due upon completion of service. We accept cash, credit cards, and digital payments.</p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div>Invoice Date:  {{ $bookingDetails->created_at->format('d M, Y') }}</div>
            <div class="thank-you">Thank You for Your Business!</div>
            <div>www.cartub.uk</div>
        </div>
    </div>
    
        <!-- Action Buttons -->
    <div class="action-buttons">
        <button class="btn btn-download" onclick="downloadInvoice()">
            <span>📥</span> Download PDF
        </button>
        <button class="btn btn-print" onclick="printInvoice()">
            <span>🖨️</span> Print Invoice
        </button>
    </div>
</body>
  <script>
// Download function
function downloadInvoice() {

    const element = document.getElementById("invoice-container");

    const opt = {
        scale: 2, // high quality
        useCORS: true,
        logging: false
    };

    html2canvas(element, opt).then(canvas => {

        const imgData = canvas.toDataURL("image/png");

        // A4 size in pixels at 96 DPI
        const pdfWidth = 595.28;
        const pdfHeight = 841.89;

        // Image width = pdf width
        const imgWidth = pdfWidth;
        const imgHeight = canvas.height * (imgWidth / canvas.width);

        const pdf = new window.jspdf.jsPDF("p", "pt", "a4");

        let position = 0;

        // If content > one page → split automatically
        if (imgHeight < pdfHeight) {
            pdf.addImage(imgData, "PNG", 0, 0, imgWidth, imgHeight);
        } else {
            while (position < imgHeight) {
                pdf.addImage(imgData, "PNG", 0, position * -1, imgWidth, imgHeight);
                position += pdfHeight;

                if (position < imgHeight) pdf.addPage();
            }
        }

        pdf.save("{{ $bookingDetails->booking_number }}.pdf");
    });
}


// Print function
function printInvoice() {
    window.print();
}
  </script>
</html>