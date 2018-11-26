<div class="col-md-9 ">
  <form id="form" class="purchaseform" action="{{ url('purchase') }}" method="post" enctype="multipart/form-data" data-toggle="validator">
    {{csrf_field()}}
    <input id="inputhidden" type='hidden' name='_method' value='POST'>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
              <label for="order_date" class="col-sm-5 control-label">Order Date : </label>
              <div class="col-sm-7">
              <div class='input-group date'>
                  <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                  <input type='text' class="form-control"  name="order_date" id="datetimepicker1" required/>
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
                  <input type="text" class="form-control" name="invoice_id" id="invoice_id"/>
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
                      <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                  </select>
                </div>
            </div>
        </div>
      </div><br>
    <table class="table table-bordered" id="addBarang">
        <thead><tr><th>Code</th><th>Name</th><th>Qty</th><th>Measure</th><th>Price</th><th>SubTotal</th><th>Action</th></tr></thead>
        <tbody>
        <tr id="trpajangan" class="item">
        <td>Choose From Table</td><td>Choose From Table</td>
        <td><input type="text" class="form-control" style="text-align:center" autocomplete="off" name="quantity" size="1"></td>
        <td><input type="text" class="form-control" style="text-align:center" autocomplete="off" name="quantity" size="1"></td>
        <td ><input type="text" class="form-control" style="text-align:center" autocomplete="off" name="price" size="4"></td>
        <td><input type="text" class="form-control" style="text-align:center" autocomplete="off" name="sub_total"  size="4" readonly></td>
        <td></td>
        </tr>
        <tbody>
    </table>

    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="sendto" class="col-sm-5 control-label">Send To :</label>
                <div class="col-sm-7">
                  <select class="form-control" name="sendto" id="warehouse_id">
                    @foreach($warehouse as $warehouse)
                      <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
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
                    <input type="text" class="form-control" style="text-align:right" name="total" id="total" readonly/>
                </div>
                </div>
            </div>
        </div>
      </div><br>
        <div class="form-group col-md-12">
            <label for="note">Note : </label>
            <textarea name="note" id="note" cols="30" rows="1" class="form-control">{{ old('note') }}</textarea>
        </div><br>
        <div class="form-group col-md-12">
              <input id="submit" type="submit" name="submit" class="form-control btn btn-primary " value="Submit">
          </diu>
      </div>
   </form>
</div>
