@extends('admin.layout.adminlayout')
	@section('content')
        <div class="flex_button_">
        	@if($add)
        		<a href="{{ route('admin-billing-add') }}" class="btn btn-success pull-right btn_top"><i class="material-icons">add_circle_outline</i>Add</a>
        		<div class="devider"></div>
        	@endif
        </div>
        <div class="table-responsive">
			<table id="billing-table" class=" crud-table table table-bordered table-striped table-hover dataTable">
        		<thead>
        			<tr>
        				<th>Date</th>
        				<th>Code</th>
        				<th>Patient Name</th>
        				<th>Associates Name</th>
        				<th>Total Amount</th>
        				<th>Dis/Com. Amount</th>
        				<th>Net Amount</th>
        				<th>Paid Amount</th>
        				{{-- <th>Status</th> --}}
        				<th>Action</th>
        			</tr>
        		</thead>
        	</table>
        </div>
	@stop
	@push('pageScripts')
	<script type="text/javascript">
		$('#billing-table').DataTable({
		processing: true,
		serverSide: true,
		ajax: '{!! route('admin-billing-data') !!}',
		columns: [
			{data: 'created_at', name: 'created_at'},
			{data: 'code', name: 'code'},
			{data: 'patient_id', name: 'patient.name'},
			// {data: 'doctor_name', name: 'doctor_name'},
			{data: 'collector_id', name: 'collector.name'},
			// {data: 'tests', name: 'billing_details.test_name'},
			{data: 'total_amount', name: 'total_amount'},
			{data: 'discount_or_commission', name: 'discount_or_commission'},
			{data: 'net_amount', name: 'net_amount'},
			{data: 'paid_amount', name: 'paid_amount'},
			// {data: 'status', name: 'status'},
			{data: 'action', name: 'action'}
		],
		createdRow: function (row, data, index) {
			let netAmount	= parseFloat(data.net_amount.substr(1).replace(',',''));
			let paidAmount	= parseFloat(data.paid_amount.substr(1).replace(',',''));
		    if (paidAmount < netAmount) {
		        $(row).addClass("label-due");
		    } else {
				$(row).addClass("label-paid");
			}
    }
	});
	</script>
	@endpush
