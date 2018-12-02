@extends('admin.layout.adminlayout')
	@section('content')
		@include('admin.alert.form-validation-error')
		<div class="col-md-12">
			<input type="button" onclick="clickSubmitButton()" class="btn btn-primary waves-effect fake-submit pull-right" value="Submit" />
		</div>
		<div class="clearfix"></div>
		<div class="col-md-10 col-sm-10  col-md-offset-1 col-sm-offset-1">
    		<form class="form-horizontal upload_form" action="{{ route('admin-collectors-commission-insert', $id) }}" method="POST" enctype="multipart/form-data">
    			{{ csrf_field() }}
                @foreach ($tests as $test)
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                            <label for="csv_file">{{ $test->visibleNameWithMRP }} : </label>
                        </div>

                        <div class="col-lg-8 col-md-8 col-sm-12 ">
                            <div class="form-group">
                                <input class="each-price" autocomplete="off" max="{{ $test->price }}" type="text" value="{{ $prices[$test->id] ?? $test->price }}" placeholder="" name="test_{{ $test->id }}" id="test_{{ $test->id }}" class="form-control" >
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="text-center cntr_btn">
                    <a href="{{ route('admin-billing-') }}" class="btn btn-info waves-effect" > Back to List </a>
                    <input type="submit" class="btn btn-primary waves-effect submit-button" value="Submit" />
                </div>
            </form>
        </div>
    @stop
	@push('pageScripts')
		<script type="text/javascript">
			function clickSubmitButton() {
				$('.submit-button').trigger('click');
			}
		</script>
	@endpush
