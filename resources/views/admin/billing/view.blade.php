@extends('admin.layout.adminlayout')
	@section('content')
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover">
				<tbody>
					<tr>
						<td >Code</td>
						<td >{{ $billing->code}}</td>
					</tr>
                    <tr>
                        <td >Date</td>
                        <td >{{ date('d M, y H:i', strtotime($billing->created_at)) }}</td>
                    </tr>
					<tr>
						<td >Patient Name</td>
						<td >{{ $billing->patient->name}}</td>
					</tr>
					<tr>
						<td >Doctor Name</td>
						<td >{{ $billing->doctor_name}}</td>
					</tr>
					<tr>
						<td >Associates Name</td>
						<td >{{ optional($billing->collector)->visibleName}}</td>
					</tr>
					<tr>
                        <td colspan="2">
    						<table  class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#sl</th>
                                        <th>Test Name</th>
                                        <th>Price</th>
                                        <th>Net Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($billing->billing_details as $key => $test)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $test->test_name }}</td>
                                            <td>{{ priceFormat($test->test_price) }}</td>
                                            <td>{{ priceFormat($test->net_price) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
					</tr>
                    <tr>
                        <td >Total Amount</td>
						<td >{{ priceFormat($billing->total_amount) }}</td>
                    </tr>
                    <tr>
                        <td >Dis./Comm. Amount</td>
						<td >{{ priceFormat($billing->discount_or_commission) }}</td>
                    </tr>
                    <tr>
                        <td >Net Amount</td>
						<td >{{ priceFormat($billing->net_amount) }}</td>
                    </tr>
                    <tr>
                        <td >Paid Amount</td>
						<td >{{ priceFormat($billing->paid_amount) }}</td>
                    </tr>
                    <tr>
                        <td >Status</td>
						<td >{{ $billing->status }}</td>
                    </tr>
				</tbody>
			</table>
			<div class="cntr_btn">
				@if ($edit)
				<a href="{{ $edit_url }}" class="btn btn-info waves-effect">Edit</a>
				@endif
				@if (!empty($back_url))
				<a href="{{ $back_url }}" class="btn btn-primary waves-effect">Back to list</a>
				@endif
			</div>
		</div>
	@stop
