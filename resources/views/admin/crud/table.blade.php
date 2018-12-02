<div class="flex_button_ action_button">
	@foreach ($extra_buttons as  $each_button)
		<a href="{{$each_button['link']}}" class="{{$each_button['class']}} "><i class="material-icons">{{$each_button['icon']}}</i> {{ $each_button['label'] }}</a>
	@endforeach
	@if($add_button)
		<a href="{{$add_button['link']}}" class="{{$add_button['class']}} "><i class="material-icons">{{$add_button['icon']}}</i> {{ $add_button['label'] }}</a>
	@endif
</div>
<div class="table-responsive">
	<table class=" crud-table table table-bordered table-striped table-hover dataTable js-exportable">
		<thead>
			<tr>
			@forelse ($table_field as $key => $value)
				<th>{{ $value }} <i class="material-icons">swap_vert</i></th>
			@empty
				<th>{{ 'No Title Found' }}</th>
			@endforelse()
			</tr>
		</thead>
		<tbody>
			@forelse ($table_data as $key => $value)
				<tr>
					@forelse ($table_field as $field_name => $field_label)
						<td >{!! $value[$field_name] !!}</td>
					@empty
					@endforelse()
				</tr>
			@empty
			@endforelse()
		</tbody>
	</table>
</div>
