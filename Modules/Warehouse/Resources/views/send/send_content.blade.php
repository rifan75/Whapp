<div class="col-md-9 ">
  <form id="form" action="/warehouse/send" method="post" enctype="multipart/form-data" data-toggle="validator">
    {{csrf_field()}}

    <input id="inputhidden" type='hidden' name='_method' value='POST'>
    <input  type="hidden" name='from' value="{{$location->code}}">
    <input  type="hidden" name='fromid' value="{{$location->hashid}}">
    <div class="row">
        <div class="col-md-7">
            <div class="form-group">
                <label for="no_letter" class="col-sm-4 control-label">Letter No :</label>
                <div class="col-sm-8">
                <div class="input-group">
                    <input type="text" class="form-control" name="no_letter" id="no_letter" required/>
                    <a href="#" onclick="generatekir()" style="margin-top:5px" class="btn btn-success"role="button">Generate Code</a>
                    <p style="color:red">{{ $errors->first('no_letter') }}</p>
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
              <label for="letter_date" class="col-sm-4 control-label">Date : </label>
              <div class="col-sm-8">
              <div class='input-group date' >
                <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                  <input type='text' class="form-control" name="letter_date" id='datetimepicker' required/>
                  <p style="color:red">{{ $errors->first('letter_date') }}</p>
              </div>
              </div>
            </div>
        </div>

      </div><br>
      <b>&nbsp; The Items Will be Send Such As : </b>
    <table class="table table-bordered" id="addBarang">
        <thead><tr><th>Code </th><th>Name </th><th>Qty</th><th>Measure</th><th>Maks</th><th>&nbsp;</th></tr></thead>
        <tbody>
        <tr id="trpajangan">
        <td>Choose from table..</td>
        <td>Choose from table..</td>
        <td><input type="text" style="text-align:center" class="form-control"  autocomplete="off" name="quantity"  size="1" readonly/></td>
        <td><input type="text" style="text-align:center" class="form-control"  autocomplete="off" size="1" readonly/></td>
        <td>Maksimum can be send</td>
        <td></td>
        </tr>
        <tbody>
    </table>
      <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="sendto" class="col-sm-4 control-label">Send To :</label>
                <div class="col-sm-8">
                  <select class="form-control" name="sendto" id="sendto">
                    @foreach($warehouses as $warehouse)
                      <option value="{{ $warehouse->code }}">{{ $warehouse->name }}</option>
                    @endforeach
                  </select>
                  <p style="color:red">{{ $errors->first('sendto') }}</p>
                </div>
            </div>
        </div>

        </div><br>
        <div class="form-group col-md-12">
            <label for="note">Note : </label>
            <textarea name="note" id="note" cols="30" rows="1" class="form-control"></textarea>
        </div>
        <div class="form-group col-md-12">
            <button id="submit" type="submit" class="form-control btn btn-primary ">Submit </button>
        </div>
   </form>
</div>
