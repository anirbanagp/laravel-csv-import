@extends('admin.layout.adminlayout')
	@section('content')
		@include('admin.alert.form-validation-error')
		<div class="col-md-10 col-sm-10  col-md-offset-1 col-sm-offset-1">
    		<form class="form-horizontal upload_form" action="{{ route('admin-billing-insert') }}" method="POST" enctype="multipart/form-data">
    			{{ csrf_field() }}
				<input type="hidden" name="id" value="{{ $billing->id }}">
				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
						<label for="csv_file">Date : </label>
					</div>

					<div class="col-lg-8 col-md-8 col-sm-12 ">
						<div class="form-group">
							<input autocomplete="off" type="text" value="{{ old('created_at', $billing->billDate) }}"  placeholder="Enter Billing Date" name="created_at" id="created_at" class="form-control " >
						</div>
					</div>
				</div>
				<div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Patient Code : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input autocomplete="off" type="text" value="{{ old('patient_code', optional($billing->patient)->code) }}"  placeholder="Enter patient code" name="patient_code" id="patient_code" class="form-control" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Phone Number : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input value="{{ old('ph_number', optional($billing->patient)->ph_number) }}" autocomplete="off" type="text" placeholder="Enter Phone number" name="ph_number" id="ph_number" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Patient Name : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input autocomplete="off" type="text" search-field="true" value="{{ old('patient_name', optional($billing->patient)->name) }}" placeholder="Enter patient name" name="patient_name" id="patient_name" class="form-control" >
                            <input type="hidden" name="patient_id" id="patient_id" value="{{ old('patient_id', optional($billing->patient)->id) }}" class="form-control" >
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
                            <input type="text" value="{{ old('doctor_name', $billing->doctor_name) }}" placeholder="Enter doctors name" name="doctor_name" id="doctor_name" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Associate Name : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <select name="collector_id" id="collector_id" data-live-search="true" class="show-tick">
                                @foreach ($collectors as  $collector)
                                    <option {{ $loop->index == 0 || old('collector_id', $billing->collector_id) == $collector->id ? 'selected' : ''}} value="{{ $collector->id }}" >{{ $collector->visibleName }}</option>
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
                            <select multiple="multiple" name="test_id[]" id="test_id" class="ms form-control ms-test">
                                @foreach ($tests as  $test)
                                    <option {{ in_array($test->id, old('test_id', optional(optional($billing->billing_details)->pluck('path_test_id'))->toArray() ?? []))  ? 'selected' : ''}} value="{{ $test->id }}" >{{ $test->visibleName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Total Amount : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input readonly type="text" value="{{ old('total_amount', $billing->total_amount) }}" placeholder="Total Amount" name="total_amount" id="total_amount" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Dis./Assoc. Amount : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input  type="text" value="{{ old('discount_or_commission', $billing->discount_or_commission) }}" placeholder="Enter Discount/Commission Amount" name="discount_or_commission" id="discount_or_commission" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Net Amount : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input readonly type="text" value="{{ old('net_amount', $billing->net_amount) }}" placeholder="Net Amount" name="net_amount" id="net_amount" class="form-control" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Paid Amount : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input type="text" value="{{ old('paid_amount', $billing->paid_amount ?? 0) }}" placeholder="Enter Paid Amount" name="paid_amount" id="paid_amount" class="form-control" >
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12  form-control-label">
                        <label for="csv_file">Status : </label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 ">
                        <div class="form-group">
                            <input checked type="radio" value="due"  name="status" id="status_due" class="with-gap radio-col-blue-grey" >
                            <label for="status_due">Due</label>

                            <input type="radio" value="paid"  name="status" id="status_paid" class="with-gap radio-col-blue-grey" >
                            <label for="status_paid">Paid</label>
                        </div>
                    </div>
                </div> --}}

                <div class="text-center cntr_btn">
                    <a href="{{ route('admin-billing-') }}" class="btn btn-info waves-effect" > Back to List </a>
                    <input style="display:none;" type="submit" class="btn btn-primary waves-effect submit-button" value="Submit" />
                    <input type="button" class="btn btn-primary waves-effect fake-submit" value="Submit" />
                </div>
            </form>
        </div>
    @stop

@push('pageScripts')
	<script src="{{ asset('new_admin/js/search.js')}}" charset="utf-8"></script>
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
			if ($(this).attr('id') == 'collector_id') {
				let selectedCollector = $(this).val();
				if(selectedCollector != 1) {
					$('#discount_or_commission').attr('readonly', true);
				} else {
					$('#discount_or_commission').attr('readonly', false);
				}
			}
        });
        $('#discount_or_commission').keyup(function () {
            let netAmount 	= parseFloat($('#total_amount').val()) - parseFloat($(this).val());
			netAmount		=	isNaN(netAmount) ? $('#total_amount').val() : netAmount;
            $('#net_amount').val(netAmount);
        });
		$('.fake-submit').click(function () {
			if($('#test_id').val()) {
				showConfirm('Are you confirm?',getSelectedTestsName(),function () {
					$('.submit-button').click();
				});
			} else {
				throwValidationError(['test field is required.']);
			}
		})
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
		function getSelectedTestsName() {
			let names = '<ul>';
			$('#test_id option:selected').each(function(){
				names += `<li>${$(this).html()}</li>`;
			});
			names += '</ul>';
			return `to continue with following selected testes <br>
			 ${names}
			  <br> It will cost {{getCurrencySymbol()}}${ $('#net_amount').val()}`;
		}
		$('#test_id').multiSelect({
			  selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='search test name'>",
			  selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='search test name'>",
			  afterInit: function(ms){
			    var that = this,
			        $selectableSearch = that.$selectableUl.prev(),
			        $selectionSearch = that.$selectionUl.prev(),
			        selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
			        selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

			    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
			    .on('keydown', function(e){
			      if (e.which === 40){
			        that.$selectableUl.focus();
			        return false;
			      }
			    });

			    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
			    .on('keydown', function(e){
			      if (e.which == 40){
			        that.$selectionUl.focus();
			        return false;
			      }
			    });
			  },
			  afterSelect: function(){
			    this.qs1.cache();
			    this.qs2.cache();
			  },
			  afterDeselect: function(){
			    this.qs1.cache();
			    this.qs2.cache();
			  }
			});
			$('#created_at').bootstrapMaterialDatePicker({
		        format: 'DD-MM-Y',
		        clearButton: true,
		        weekStart: 1,
		        time: false,
				maxDate : moment().format('DD-MM-Y')
		    });
    </script>
@endpush
