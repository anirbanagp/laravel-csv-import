<div class="table-responsive">
	<table id="report-table"  class=" crud-table table table-bordered table-striped table-hover dataTable js-exportable">
		<thead>
			<tr>
                <th>Date</th>
                <th>Code</th>
                <th>Patient Name</th>
                <th>Net Amount</th>
                <th>Dis/Com. Amount</th>
                <th>Paid Amount</th>
                <th>Due Amount</th>
                <th>Status</th>
				<th>Associates Name</th>
			</tr>
		</thead>
		<tbody>
            @php
                $net_amount = 0;
                $commission = 0;
                $paid = 0;
                $due = 0;
            @endphp
			@foreach ($billingReport as $key => $billing)
                @php
                    $net_amount += $billing->net_amount;
                    $commission += $billing->discount_or_commission;
                    $paid += $billing->paid_amount;
                    $due += ($billing->net_amount - $billing->paid_amount);
                @endphp
				<tr>
                    <th>{{ date('d M, y', strtotime($billing->created_at)) }}</th>
                    <th>{{ $billing->code }}</th>
                    <th>{{ optional($billing->patient)->visibleName }}</th>
					<td >{{ priceFormat($billing->net_amount) }}</td>
					<td >{{ priceFormat($billing->discount_or_commission) }}</td>
					<td >{{ priceFormat($billing->paid_amount) }}</td>
                    <th>{{ priceFormat($billing->net_amount - $billing->paid_amount) }}</th>
                    <th>{{ $billing->status }}</th>
					<th>{{ optional($billing->collector)->visibleName }}</th>
				</tr>
			@endforeach
		</tbody>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th>TOTAL :</th>
                <th>{{ priceFormat($net_amount) }}</th>
                <th>{{ priceFormat($commission) }}</th>
                <th>{{ priceFormat($paid) }}</th>
                <th>{{ priceFormat($due) }}</th>
                <th></th>
            </tr>
        </tfoot>
	</table>

</div>
<script type="text/javascript">
    $('.crud-table').DataTable({
        responsive: true
    });
</script>
