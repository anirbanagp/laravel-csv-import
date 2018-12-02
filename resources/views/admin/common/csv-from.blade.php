@extends('admin.layout.adminlayout')
	@section('content')
		@include('admin.alert.form-validation-error')
		<div class="col-md-10 col-sm-10  col-md-offset-1 col-sm-offset-1">
    		<form class="form-horizontal upload_form" action="{{ $insert_url }}" method="POST" enctype="multipart/form-data">
    			{{ csrf_field() }}
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">CSV File : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input type="file" value="" name="csv_file" id="csv_file" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="text-center cntr_btn">
                    <a href="{{ $back_url }}" class="btn btn-info waves-effect" > Back to List </a>
                    <input type="submit" class="btn btn-primary waves-effect" value="Submit" />
                </div>
            </form>
        </div>
    @stop
