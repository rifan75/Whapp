<div class="col-md-12 ">
  <form id="form" class="skuform" action="/kirim/{{$kirim->id}}" method="post" enctype="multipart/form-data" data-toggle="validator">
    {{csrf_field()}}

    <input id="inputhidden" type='hidden' name='_method' value='PATCH'>
    <input  type="hidden" name='from' value="{{$lokasi->warehouse_code}}">
    <input  type="hidden" name='fromid' value="{{$lokasi->id}}">
    <div class="row">
        <div class="col-md-7">
            <div class="form-group">
                <label for="no_letter" class="col-sm-4 control-label">No Surat :</label>
                <div class="col-sm-8">
                <div class="input-group">
                    <input type="text" class="form-control" name="no_letter" id="no_letter" value="{{$kirim->no_letter}}" required/>
                    <p style="color:red">{{ $errors->first('no_letter') }}</p>
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
              <label for="letter_date" class="col-sm-4 control-label">Tanggal : </label>
              <div class="col-sm-8">
              <div class='input-group date' >
                <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                  <input type='text' class="form-control" name="letter_date" id='datetimepicker' value="{{date('d-m-Y', strtotime($kirim->letter_date))}}" required/>
                  <p style="color:red">{{ $errors->first('letter_date') }}</p>
              </div>
              </div>
            </div>
        </div>

      </div><br>
      <b>&nbsp; Barang yang dikirim terdiri dari : </b>
    <table class="table table-bordered" id="addBarang">
        <thead><tr><th>Kode Barang</th><th>Nama Barang</th><th>Jumlah</th><th>Satuan</th><th>&nbsp;</th></tr></thead>
        <tbody>
        @foreach($kirimdetail as $detail)
        <tr id="inputtable{{$detail->skuproduct_id}}" class="item">
        <td>{{$detail->product->product_id}}</td><td>{{$detail->product->product_name}}</td>
        <td><input type="text" class="hitung form-control" style="text-align:center" autocomplete="off" name="quantity[]" size="1" value="{{$detail->quantity}}" readonly></td>
        <td><input type="text" class="hitung form-control" style="text-align:center" autocomplete="off" name="measure[]" size="1" value="{{$detail->measure}}" readonly></td>
        <!-- <td ><input type="text" class="hitung form-control" style="text-align:right" autocomplete="off" name="price[]" size="4" value="{{$detail->price}}"></td>
        <td><input type="text" class="hitung form-control" style="text-align:right" autocomplete="off" name="sub_total[]"  size="4" value="{{$detail->sub_total}}" readonly></td> -->
        <input id="product_id" type="hidden" name="product_id[]" value="{{$detail->product_id}}">
        <!-- <td><button class="btn btn-danger btn-xs" type="button" onclick="removeinput('{{$detail->product_id}}')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td> -->
        </tr>
        @endforeach
        <tbody>
    </table>
      <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="sendto" class="col-sm-4 control-label">Kirim Ke :</label>
                <div class="col-sm-8">
                  <select class="form-control" name="sendto" id="sendto">
                    @foreach($warehouse as $warehouse)
                      <option value="{{ $warehouse->warehouse_code }}">{{ $warehouse->warehouse_name }}</option>
                    @endforeach
                  </select>
                  <p style="color:red">{{ $errors->first('sendto') }}</p>
                </div>
            </div>
        </div>

        </div><br>
        <!-- <div class="form-group col-md-12">
            <label for="note">Catatan : </label>
            <textarea name="note" id="note" cols="30" rows="1" class="form-control"></textarea>
        </div> -->



            <button id="submit" type="submit" style="margin-left:15px;width: 96%;" class="form-control btn btn-primary ">Edit Pengiriman Barang </button>
   </form>
</div>
