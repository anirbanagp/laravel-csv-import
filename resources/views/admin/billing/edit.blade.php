@extends('admin.layout.adminlayout')
	@section('content')
		@include('admin.alert.form-validation-error')
		<div class="col-md-10 col-sm-10  col-md-offset-1 col-sm-offset-1">
    		<form class="form-horizontal upload_form" action="{{ route('admin-billing-update') }}" method="POST" enctype="multipart/form-data">
    			{{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $billing->id }}">
                {{-- <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Phone Number : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input type="text" value="" placeholder="Enter Phone number" name="ph_number" id="ph_number" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Patient Name : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input type="text" search-field="true" value="" placeholder="Enter patient name" name="patient_name" id="patient_name" class="form-control" >
                            <input type="hidden" name="patient_id" id="patient_id" value="" class="form-control" >
                            <div id="suggestion-box" class="sg_box" style="display:none;"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Doctors Name : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input type="text" value="" placeholder="Enter doctors name" name="doctor_name" id="doctor_name" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Collector Name : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <select name="collector_id" id="collector_id" data-live-search="true" class="show-tick">
                                <option value="" >SELECT</option>
                                @foreach ($collectors as  $collector)
                                    <option value="{{ $collector->id }}" >{{ $collector->visibleName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Tests : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <select multiple name="test_id[]" id="test_id" data-live-search="true" class="show-tick">
                                <option value="" >SELECT</option>
                                @foreach ($tests as  $test)
                                    <option value="{{ $test->id }}" >{{ $test->visibleName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Total Amount : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input disabled type="text" value="{{ $billing->total_amount }}" placeholder="Total Amount" name="total_amount" id="total_amount" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Dis./Comm. Amount : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input disabled  type="text" value="{{ $billing->discount_or_commission }}" placeholder="Enter Discount/Commission Amount" name="discount_or_commission" id="discount_or_commission" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Net Amount : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input disabled type="text" value="{{ $billing->net_amount }}" placeholder="Net Amount" name="net_amount" id="net_amount" class="form-control" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Paid Amount : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input type="text" value="{{ $billing->paid_amount }}" placeholder="Enter Paid Amount" name="paid_amount" id="paid_amount" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Status : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input {{ $billing->status =="due" ? 'checked' : '' }}  type="radio" value="due"  name="status" id="status_due" class="with-gap radio-col-blue-grey" >
                            <label for="status_due">Due</label>

                            <input type="radio" {{ $billing->status != "due" ? 'checked' : '' }} value="paid"  name="status" id="status_paid" class="with-gap radio-col-blue-grey" >
                            <label for="status_paid">Paid</label>
                        </div>
                    </div>
                </div>

                <div class="text-center cntr_btn">
                    <a href="{{ route('admin-billing-') }}" class="btn btn-info waves-effect" > Back to List </a>
                    <input type="submit" class="btn btn-primary waves-effect" value="Submit" />
                </div>
            </form>
        </div>
    @stop

@push('pageScripts')
    <script type="text/javascript">
        let searchPatient = false;
        $('#ph_number').blur(function() {
            let phNumber = $('#ph_number').val();
            if(phNumber.length && searchPatient ===  true) {
                $.ajax({
                    url : '{{route('admin-billing-get-patient-suggestion')}}'+'/'+phNumber,
                    success: function(data) {
                        $(document.body).find('#suggestion-box').show();
						$(document.body).find('#suggestion-box').html(data);
                        searchPatient = false;
                    },
                    error: function(error) {
                        showError('something went wrong');
                    }
                })
            }
        });
		$('#patient_name').focus(function() {
			$(document.body).find('#suggestion-box').show();
		});
        $('#ph_number').keyup(function() {
            searchPatient = true;
        });
        $(document.body).on('click', '.each-user', function() {
            var id	=	$(this).attr('data-id');
            var name	=	$(this).attr('data-val');
            $(document.body).find('#patient_id').val(id);
            $(document.body).find('#patient_name').val(name);
        });
        $('#test_id, #collector_id').change(function () {
            updatePrice();
        });
        $('#discount_or_commission').keyup(function () {
            let netAmount = parseFloat($('#total_amount').val()) - parseFloat($(this).val());
            $('#net_amount').val(netAmount);
        });
        function updatePrice() {
            let testIds = $('#test_id').val();
            let collectorId = $('#collector_id').val();
            $.ajax({
                url : '{{route('admin-billing-get-billing-amount')}}',
                dataType: 'json',
                data : { test_id : testIds, collector_id : collectorId  },
                success: function(result) {
                    $('#total_amount').val(result.total_amount);
                    $('#discount_or_commission').val(result.discount);
                    $('#net_amount').val(result.net_amount);
                },
                error: function(error) {
                    showError('something went wrong');
                }
            })
        }

    </script>
@endpush
