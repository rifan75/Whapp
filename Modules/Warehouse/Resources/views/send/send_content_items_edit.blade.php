<div class="col-md-9 ">
  <form id="form"  action="/warehouse/send_edit/{{$send->hashid}}" method="post" enctype="multipart/form-data" data-toggle="validator">
    {{csrf_field()}}

    <input id="inputhidden" type='hidden' name='_method' value='PATCH'>
    <input  type="hidden" name='from' value="{{$location->code}}">
    <input  type="hidden" name='fromid' value="{{$location->hashid}}">
    <div class="row">
        <div class="col-md-7">
            <div class="form-group">
                <label for="no_letter" class="col-sm-4 control-label">No Surat :</label>
                <div class="col-sm-8">
                <div class="input-group">
                    <input type="text" class="form-control" name="no_letter" id="no_letter" value="{{$send->no_letter}}" required/>
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
                  <input type='text' class="form-control" name="letter_date" id='datetimepicker' value="{{date('d-m-Y', strtotime($send->letter_date))}}" required/>
                  <p style="color:red">{{ $errors->first('letter_date') }}</p>
              </div>
              </div>
            </div>
        </div>

      </div><br>
      <b>&nbsp; Barang yang disend terdiri dari : </b>
    <table class="table table-bordered" id="addBarang">
        <thead><tr><th>Code</th><th>Name</th><th>Qty</th><th>Measure</th><th>Maksimum</th></tr></thead>
        <tbody>
        @foreach($senddetail as $detail)
        <tr id="inputtable{{$detail->hashproduct}}" class="item">
        <td>{{$detail->product->code}}</td><td>{{$detail->product->name}}</td>
        <td><input type="text" class="hitung form-control" style="text-align:right" autocomplete="off" name="quantity[]" size="1" value="{{$detail->quantity}}"></td>
        <td><input type="text" class="hitung form-control" style="text-align:center" autocomplete="off" name="measure[]" size="1" value="{{$detail->measure}}" readonly></td>
        <td><input id="maksimum" type="text" class="hitung form-control" style="text-align:right" autocomplete="off" name="maksimum[]" size="4" value="{{$detail->maks->quantity + $detail->quantity}}" readonly/></td>
        <input id="product_id" type="hidden" name="product_id[]" value="{{$detail->hashproduct}}">
        <input id="hargabeli" type="hidden" name="price[]" value="{{$detail->sub_total/$detail->quantity}}">
        <input id="sub_total" type="hidden" name="sub_total[]" value="{{$detail->sub_total}}">
        <td><button class="btn btn-danger btn-xs" type="button" onclick="removeinput('{{$detail->hashproduct}}')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>
        </tr>
        @endforeach
        <tbody>
    </table>
      <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="sendto" class="col-sm-4 control-label">Send To :</label>
                <div class="col-sm-8">
                  <select class="form-control" name="sendto" id="sendto">
                    @foreach($warehouse as $warehouse)
                      <option value="{{ $warehouse->code }}">{{ $warehouse->name }}</option>
                    @endforeach
                  </select>
                  <p style="color:red">{{ $errors->first('sendto') }}</p>
                </div>
            </div>
        </div>

        </div><br>
        <div class="form-group col-md-12">
            <label for="note">Catatan : </label>
            <textarea name="note" id="note" cols="30" rows="1" class="form-control">{{ $send->note }}</textarea>
        </div>



            <button id="submit" type="submit" style="margin-left:15px;width: 96%;" class="form-control btn btn-primary ">Submit </button>
   </form>
</div>
