<div id="modalinput-form" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="input_sedia">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header text-center">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h3 id="title" class="modal-title">Manual Input</h3>
      </div>
      <div class="modal-body">
								<div class="panel-body">
                  <form id="forminputmanual" action="/product/inputmanual" method="post" enctype="multipart/form-data" data-toggle="validator">
								  {{csrf_field()}}
                  <input id="inputhidden" type='hidden' name='_method' value='POST'>
									<table class="table table-bordered">
									<tr><td style="width:120px">Code </td><td colspan="2" id="codetext"></td></tr>
									<tr><td>Name </td><td colspan="2" id="nametext"></td></tr>
									<tr>
									<td >Quantity </td>
									<td><input type="text" id="in_out_qty" class="hitung form-control" style="text-align:right" autocomplete="off" name="in_out_qty" size="1" required></td>
									<td><input type="text" id="measure" class="form-control" style="text-align:center" autocomplete="off" name="measure" size="1" readonly></td>
								  </tr>
									<tr>
									<td>Purchase Price  </td>
									<td><input type="text" id="price"  class="hitung form-control" style="text-align:right" autocomplete="off" name="price" size="4" required></td>
                  <td><input type="text" id="sub_total"  class="hitung form-control" placeholder="Total" style="text-align:right" name="sub_total" size="4" readonly></td>
                  </tr>
                  <tr>
                  <td>Location </td>
									<td>
                    <div class="form-group">
                      <select class="form-control" name="warehouse">
                        @foreach($warehouses as $warehouse)
                          <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </td>
                  </tr>
                  <tr>
                  <td>Type </td>
									<td colspan="2">
                    <div class="form-group">
                      <select class="form-control" name="type">
                        @foreach($types as $type)
                          <option value="{{ $type->id }}">{{ $type->id }} - {{ $type->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </td>
                  </tr>
                  <tr>
                  <td>Note </td>
									<td colspan="2">
                    <div class="form-group">
                      <textarea id="note"  cols="30" rows="2" class="form-control" name="note" value="{{ old('note') }}">
                      </textarea>
                    </div>
                  </td>
                  </tr>
                  </tr>
                  <input id="id_product" type="hidden" name="id_product">
                  </tr>
									<tr><td colspan="3"><input id="submit" type="submit"  class="form-control btn btn-primary " value="Submit"></td></tr>



									</table>

</form>
			</div>
		</div>
	</div>
</div>
</div>
