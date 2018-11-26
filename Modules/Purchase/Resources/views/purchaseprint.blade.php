<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

  <style>
    body {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
        font-weight: 300;
        overflow-x: hidden;
        overflow-y: auto;
        font-size: 13px;
    }
    .amount_due {
        font-size: 20px;
        font-weight: 500;
    }
    .invoice_title{
        color: #2e3e4e;
        font-weight: bold;
    }
    .text-right{
        text-align: right;
    }
    .text-center{
        text-align: center;
    }
    .from_address{
        width: 300px;
        float: left;
        height: 200px;
    }
    .to_address{
        width: 300px;
        height: 200px;
        float: right;
    }
    .col-md-12{
        width: 100%;
    }
    .col-md-6{
        width: 50%;
        float: left;
    }
    table {
        border-spacing: 0;
        border-collapse: collapse;
    }
    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
    }
    hr.separator{
        border-color:  #2e3e4e;
        margin-top: 10px;
        margin-bottom: 10px;
    }
    tbody#items > tr > td{
        border: 3px solid #fff !important;
        vertical-align: middle;
    }
    #items{
        background-color: #f1f1f1;
    }

    .invoice_status_cancelled
    {
        font-size : 20px;
        text-align : center;
        color: #cc0000;
        border: 1px solid #cc0000;
    }
    .invoice_status_paid
    {
        font-size : 25px;
        text-align : center;
        color: #82b440;
        border: 1px solid #82b440;
    }

</style>
</head>
<body>
<div class="container">
      <div class="text-right" style="width:600px;height:180px;float:right;">
            <div class="text-right"> <h2>Purchase Invoice (Order # {{$purchase->invoice_id}})</h2></div>
            <table style="width: 100%">
              <tr>
                  <td class="text-right">To : {{$purchase->supplier->name}}</td>
              </tr>
              <tr>
                  <td class="text-right">{{$purchase->supplier->address}}</td>
              </tr>
              <tr>
                  <td class="text-right">
                    {{$purchase->supplier->city}}&nbsp;{{$purchase->supplier->state}}
                  </td>
              </tr>
              <tr>
                  <td class="text-right">{{$purchase->supplier->country}}-{{$purchase->supplier->pos_code}}</td>
              </tr>
              <tr>
                  <td class="text-right">Telp : {{$purchase->supplier->phone}}</td>
              </tr>
              <tr>
                  <td class="text-right">E-Mail : {{$purchase->supplier->email}}</td>
              </tr>

            </table>
        </div>
        <div style="clear: both"></div>
      <div class="col-md-12">
        <div class="from_address">
            <h4 class="invoice_title">Send Address :</h4><hr class="separator"/>
            <b>{{$purchase->warehouse->name}}</b><br>
            {{$purchase->warehouse->address}}<br>
            {{$purchase->warehouse->city}}&nbsp;{{$purchase->warehouse->state}}
            <br>{{$purchase->warehouse->country}}-{{$purchase->warehouse->pos_code}}<br>
            Telp : {{$purchase->warehouse->phone}}<br>
            E-mail : {{$purchase->warehouse->email}}
        </div>

      </div>
      <div style="clear: both"></div>
        <div class="col-md-12">
            <table class="table">
                <thead style="margin-bottom:30px;background: #2e3e4e;color: #fff;">
                <tr>
                    <th style="width:50%">Name</th>
                    <th style="width:10%" class="text-center">Qty</th>
                    <th style="width:15%" class="text-right">Measure</th>
                    <th style="width:10%" class="text-center">Price</th>
                    <th style="width:15%" class="text-right">Sub Total</th>
                </tr>
                </thead>
                <tbody id="items">
                @foreach ($purchase->purchasedetail as $detail)
                <tr>
                    <td>{{$detail->product->name}}</td>
                    <td class="text-center">{{$detail->quantity}}</td>
                    <td class="text-right">{{$detail->measure}}</td>
                    <td class="text-center">{{number_format($detail->price,2)}},-</td>
                    <td class="text-right">{{number_format($detail->sub_total,2)}},-</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div><!-- /.col -->
        <div class="col-md-12">
          <table class="table">
              <tbody>
              <tr class="amount_due">
                  <th class="text-right">Total</th>
                  <td class="text-right">
                      <span id="grandTotal">{{number_format($purchase->total,2)}},-</span>
                  </td>
              </tr>
              </tbody>
          </table>

            <h4 class="invoice_title">Note :</h4><hr class="separator"/>
            {{$purchase->note}} <br/><br/>
          <div class="to_address">
                  <h4>Best Regards,</h4>
                  <br><br><br><br><br>
                  <strong>[ ___________________________________ ]</strong><br>
          </div>
 </div>
</body>
</html>
