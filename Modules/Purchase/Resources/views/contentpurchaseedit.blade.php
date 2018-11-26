<div class="col-md-9 ">
  <form id="form" class="purchaseform" action="/purchase/{{$purchase->hashid}}" method="post" enctype="multipart/form-data" data-toggle="validator">
    {{csrf_field()}}
    <input id="inputhidden" type='hidden' name='_method' value='PATCH'>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
              <label for="order_date" class="col-sm-5 control-label">Order Date : </label>
              <div class="col-sm-7">
              <div class='input-group date'>
                  <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>

                  <input type='text' class="form-control"  name="order_date" id='datetimepicker1' value="{{date('d-m-Y H:i:s', strtotime($purchase->order_date))}}" required/>
              </div>
              </div>
            </div>
        </div>
    </div><br>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="invoice_id" class="col-sm-5 control-label">Order Code :</label>
                <div class="col-sm-7">
                <div class="input-group">
                    <input type="text" class="form-control" name="invoice_id" id="invoice_id" value="{{$purchase->invoice_id}}"/>
                    <a href="#" onclick="generatepur()" style="margin-top:5px" class="btn btn-success"role="button">Generate Code</a>
                    <p style="color:red">{{ $errors->first('invoice_id') }}</p>
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="supplier_id" class="col-sm-4 control-label">Supplier :</label>
                <div class="col-sm-8">
                  <select class="form-control" name="supplier_id" id="supplier_id">
                    @foreach($supplier as $supplier)
                      <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected="selected"' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                  </select>
                </div>
            </div>
        </div>
      </div><br>
    <table class="table table-bordered" id="addBarang">
        <thead><tr><th>Code</th><th>Name</th><th>Qty</th><th>Measure</th><th>Price </th><th>SubTotal </th><th>Action</th></tr></thead>
        <tbody>
        @foreach($purchasedetail as $detail)
        <tr id="inputtable{{$detail->hashproductid}}" class="item">
        <td>{{$detail->product->code}}</td><td>{{$detail->product->name}}</td>
        <td><input type="text" class="hitung form-control" style="text-align:center" autocomplete="off" name="quantity[]" size="1" value="{{$detail->quantity}}"></td>
        <td><input type="text" class="hitung form-control" style="text-align:center" autocomplete="off" name="measure[]" size="1" value="{{$detail->measure}}" readonly></td>
        <td ><input type="text" class="hitung form-control" style="text-align:right" autocomplete="off" name="price[]" size="4" value="{{$detail->price}}"></td>
        <td><input type="text" class="hitung form-control" style="text-align:right" autocomplete="off" name="sub_total[]"  size="4" value="{{$detail->sub_total}}" readonly></td>
        <input id="product_id" type="hidden" name="product_id[]" value="{{$detail->hashproductid}}">
        <td><button class="btn btn-danger btn-xs" type="button" onclick="removeinput('{{$detail->hashproductid}}')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>
        </tr>
        @endforeach
        <tbody>
    </table>

    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="sendto" class="col-sm-5 control-label">Send To :</label>
                <div class="col-sm-7">
                  <select class="form-control" name="sendto" id="warehouse_id">
                    @foreach($warehouse as $warehouse)
                      <option value="{{ $warehouse->id }}"  {{ $purchase->sendto == $warehouse->id ? 'selected="selected"' : '' }}>{{ $warehouse->name }}</option>
                    @endforeach
                  </select>
                </div>

            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group">
                <label for="total" class="col-sm-6 control-label">Total Purchase :</label>
                <div class="col-sm-6">
                <div class="input-group">
                    <div class="input-group-addon"></div>
                    <input type="text" class="form-control" style="text-align:right" name="total" id="total" value="{{$purchase->total}}" readonly/>
                </div>
                </div>
            </div>
        </div>
      </div><br>
        <div class="form-group col-md-12">
            <label for="note">Note : </label>
            <textarea name="note" id="note" cols="30" rows="1" class="form-control">{{$purchase->note}}</textarea>
        </div><br>
        <div class="form-group col-md-12">
              <input id="submit" type="submit" name="submit" class="form-control btn btn-primary " value="Submit">
        </div>
      </form>
    </div>
