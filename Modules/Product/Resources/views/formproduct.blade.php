<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="product_form">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header text-center">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h3 class="modal-title">Product</h3>
      </div>
      <div class="modal-body">
        <div class="box-body container-fluid">
		     <form id="form" action="{{ url('product') }}" method="post" enctype="multipart/form-data" data-toggle="validator">
           {{csrf_field()}}
           <input id="inputhidden" type='hidden' name='_method' value='POST'>
           <div class="form-group col-md-12">
               <label for="name" class=" control-label">Name : </label>
               <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
           </div>
           <div class="row">
             <div class="col-md-6">
              <div class="form-group col-md-12">
          			  <label for="measure">Measure : </label>
          			    <select class="form-control" name="measure" id="measure">
          				    @foreach($measure as $measure)
          					    <option value="{{ $measure->name }}">{{ $measure->name }}</option>
          				    @endforeach
          			    </select>
        			</div>
        			<div class="form-group col-md-12">
        			    <label for="brand">Brand : </label>
        			    <select class="form-control" name="brand" id="brand">
        				    @foreach($brand as $brand)
        					    <option value="{{ $brand->name }}">{{ $brand->name }}</option>
        				    @endforeach
        			    </select>
        			</div>
        			<div class="form-group col-md-12">
        			    <label for="model">Model : </label>
        			    <input id="model" type="text" class="form-control" name="model" value="{{ old('model') }}" required>
        			</div>
            </div>
            <div class="col-md-6">
        			<div class="form-group col-md-12">
        			    <label for="color">Colour : </label>
        			    <input name="color" id="color" type="text" placeholder="Dominant Colour" class="form-control " value="{{ old('color') }}" required>
        			</div>
        			<div class="form-group col-md-12">
        			    <label for="warranty_type">Guarantee : </label>
        			    <input name="warranty_type" id="warranty_type"  type="text" class="form-control " value="{{ old('warranty_type') }}" required>
        			</div>
    		    </div>
          </div>
          <div class="form-group col-md-12">
              <input name="code" id="code"  type="text" class="form-control " value="{{ old('code') }}" placeholder="Code" required>
          </div>
          <div class="form-group col-md-12">
              <a href="#" onclick="generatepi()" class="btn btn-success"role="button">Generate Product Id</a>
          </div>
            <div class="form-group col-md-12">
                <label for="hazardwarning">Hazard Warning : </label>
                <textarea name="hazardwarning" id="hazardwarning" cols="30" rows="1" class="form-control"  required>{{ old('hazardwarning') }}</textarea>
            </div>

        		    <div class="form-group col-md-12">
        			       <input id="submit" type="submit" class="form-control btn btn-primary prod-submit" value="Add Product">
        		    </div>
    		    </div>
		        <input type="hidden" name="_token" value="{{csrf_token()}}">
		      </form>
        </div>
		     </div>
       </div>
     </div>
