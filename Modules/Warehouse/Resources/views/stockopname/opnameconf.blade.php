<div id="opnameconf-form" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="opname_conf">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header text-center">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h3 class="modal-title">Stock Opname Confirmation</h3>
      </div>
      <div class="modal-body">
                  <form id="formopnameconf" class="inputsediaform" action="/opnamestore" method="post" enctype="multipart/form-data" data-toggle="validator">
								  {{csrf_field()}}
									<table class="table table-bordered">
									<tr><td style="width:170px">Code</td><td colspan="2" id="codetext"></td></tr>
									<tr><td>Name</td><td colspan="2" id="nametext"></td></tr>
									<tr>
									<td >Recorded</td>
									<td><input type="text" id="recorded" class="form-control" style="text-align:right" autocomplete="off" name="recorded" size="2" readonly></td>
									<td><input type="text" id="measure" class="form-control" style="text-align:center" autocomplete="off" name="measure" size="2" readonly></td>
								  </tr>
									<tr>
									<td>Stock After Checked </td>
									<td><input type="text" id="opname1"  class="hitung form-control" style="text-align:right" autocomplete="off" name="opname" size="2" readonly></td>
                  <td><input type="text" id="measure1"  class="form-control"  style="text-align:center" name="measure1" size="2" readonly></td>
                  </tr>
                  <tr>
                  <input id="id_product" type="hidden" name="id_product">
                  <input id="noopname" type="hidden" name="noopname">
                  <input id="stock" type="hidden" name="stock">
                  <input id="subtotal"  type="hidden" class="hitung form-control" name="subtotal">
                  <input id="idwh" type="hidden" name="warehouse" value="{{ $inventory->hashwh }}">
                  </tr>
									<tr><td colspan="3"><input id="submit" type="submit"  class="form-control btn btn-primary " value="Submit"></td></tr>



									</table>

</form>
	</div>
</div>
</div>
</div>
