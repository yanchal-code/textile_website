<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Design Details - {{ $design->design_id ?? 'N/A' }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #fff;
      font-size:13px;
    }
    .container {
      width: 90%;
      max-width: 800px;
      margin: 20px auto;
      border: 1px solid #ddd;
      padding: 20px;
    }
    .header {
      text-align: center;
      margin-bottom: 10px;
    }
    .header h2 {
      margin: 5px 0;
      font-size: 20px;
    }
    .top-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }
    .logo img {
      width: 120px;
      border: 1px solid #959595;
      border-radius: 5px;
    }
    .design-id {
      text-align: center;
      flex: 1;
    }
    .design-id h3 {
      margin: 0;
      font-size: 18px;
      color: #333;
      padding: 6px 12px;
      display: inline-block;
      background: #f9f9f9;
    }
    .qr img {
      width: 120px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f5f5f5;
    }
    .product-image {
      height: 300px;
      width: 300px;
      margin-top: 10px;
    }
    .right-info {
      width: 50%;
      margin-top: 20px;
    }
    /* PRINT STYLES */
    @media print {
      html, body {
        margin: 0;
        padding: 0;
        font-size: 11px;
      }
      .container {
        width: 100%;
        padding: 10px;
        border: none;
      }
      .no-print {
        display: none !important;
      }
      @page {
        size: A4;
        margin: 10mm;
      }
    }
  </style>
</head>
<body>
  <div class="no-print">
    <br>
    <center>
      <button onclick="window.print()"> 🖨️ Print</button>    
    </center>
  </div>

  <div class="container">
    <!-- HEADER -->
    <div class="header">
      <h2>Design Details</h2>
    </div>

    <!-- LOGO - DESIGN ID - QR -->
    <div class="top-section">
      <div class="logo">
        <img src="{{ asset('images/logo2.jpeg') }}" alt="Logo">
      </div>
      <div class="design-id">
        <h3> {{ $design->design_id ?? 'N/A' }}</h3>
      </div>
      <div class="qr">
        {!! $qr_code !!}
      </div>
    </div>

    <!-- Product Section -->
    <div class="product-section" style="display:flex; justify-content:space-between;">
      <div>
        @if($design->default_image)
          <img src="{{ getImageById($design->default_image) }}" alt="Design Image" class="product-image" />
        @else
          <span>No image uploaded</span>
        @endif
      </div>
      <div class="right-info">
        <table>
          <tbody>
            <tr>
              <th>Product Name</th>
              <td>{{ $design->product->name ?? '' }}</td>
            </tr>
            <tr>
              <th>Category Type</th>
              <td>{{ $design->designType->name ?? $design->custom_type ?? 'N/A' }}</td>
            </tr>
            <tr>
              <th>Master Name</th>
              <td>{{ $design->worker->name ?? 'N/A' }}</td>
            </tr>
            <tr>
              <th>Suggested By</th>
              <td>{{ $design->suggested_by }}</td>
            </tr>
            <tr>
              <th>Design Type</th>
              <td>{{ ucfirst($design->type ?? '-') }}</td>
            </tr>
            <tr>
              <th>Date</th>
              <td>{{ formatDate($design->date) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Measurement Table -->
    <p><strong>Design Measurements</strong></p>
    @php
      $measurementData = is_string($design->measurement) ? json_decode($design->measurement, true) : $design->measurement;
      $showUS = $showUK = $showOther = false;
      if(is_array($measurementData)){
        foreach($measurementData as $val){
          if(!empty($val['value_us'])) $showUS = true;
          if(!empty($val['value_uk'])) $showUK = true;
          if(!empty($val['value_other'])) $showOther = true;
        }
      }
    @endphp
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Attribute</th>
          @if($showUS)<th>US Value</th>@endif
          @if($showUK)<th>UK Value</th>@endif
          @if($showOther)<th>Other Value</th>@endif
        </tr>
      </thead>
      <tbody>
        @if(is_array($measurementData) && count($measurementData) > 0)
          @foreach($measurementData as $index => $value)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $value['label'] ?? '-' }}</td>
              @if($showUS)<td>{{ $value['value_us'] ?? '-' }}</td>@endif
              @if($showUK)<td>{{ $value['value_uk'] ?? '-' }}</td>@endif
              @if($showOther)<td>{{ $value['value_other'] ?? '-' }}</td>@endif
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="5" class="text-center">No measurement data</td>
          </tr>
        @endif
      </tbody>
    </table>

    <!-- Remarks -->
    <div class="row mb-5 mt-4" style="margin-top:15px">
      <div class="col-sm-2 mb-5 d-flex align-items-center">
        <strong>Remarks:</strong>
      </div>
      <div class="col-sm-8 d-flex align-items-center">
        {{ $design->remark ?? 'No remarks provided' }}
      </div>
    </div>
  </div>
</body>
</html>
