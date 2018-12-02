@extends('admin.layout.adminlayout')
    @section('content')
        <div class="body filter-div">
            <div class="row clearfix">
                <div class="col-sm-3">
                    <p class="control-label"><b>Start Date</b></p>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="reportStartDate form-control" value="{{ $startDate }}" placeholder="Please choose a start date...">
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <p class="control-label"><b>End Date</b></p>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="reportEndDate form-control" value="{{ $endDate }}" placeholder="Please choose an end date...">
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <p><b>Associates</b></p>
                    <select multiple class="form-control show-tick" data-live-search="true" id='collector' >
                        {{-- <option value="all" selected>All</option> --}}
                        @foreach ($collectors as $collector)
                        <option value="{{ $collector->id }}">{{ $collector->visibleName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <p><b>Status</b></p>
                    <select class="form-control show-tick" data-live-search="true" id='status' >
                        <option value="" selected>All</option>
                        <option value="due">Due</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-success" id="generate-report" name="search">Generate Report</button>
                    <button type="button" class="btn btn-success" id="download-report" name="search">Download Report</button>
                </div>
            </div>
        </div>
        <div class="loder_cover align-center" style="display: none;" data-original-title="" title="">
            <img width="150" src="{{ asset('new_admin/images/loader.svg') }}" >
        </div>
        <br/>
        <div class="col-sm-12">
            <div class="resultDiv">
            </div>
        </div>

    @stop
@push('pageScripts')
    <script>
        $(window).load(function(){
          getData();
        });
        $('.reportStartDate').bootstrapMaterialDatePicker({
              maxDate : new Date(),
              clearButton: false,
              weekStart: 1,
              time: false
        }).on('change', function(e, date)
        {
            var startDate = $('.reportStartDate').val();
            var endDate = $('.reportEndDate').val();

            if( (startDate !='' & endDate !='') && (startDate > endDate))
            {
                $('.reportEndDate').val('');
                $('.resultDiv').html('<p>Please select end date!</p>');
            }

            $('.reportEndDate').bootstrapMaterialDatePicker('setMinDate', date);
        });

        $('.reportEndDate').bootstrapMaterialDatePicker({
              maxDate : new Date(),
              clearButton: false,
              weekStart: 1,
              time: false
        });

        function getData(download=false)
        {
            var startDate = $('.reportStartDate').val();
            var endDate = $('.reportEndDate').val();
            var status = $('#status').val();
            var collectorId = $('#collector').val();
            $(".loder_cover").show();
            $.ajax({
                url : '{{ route('admin-financial-report-generate') }}',
                data : {
                    startDate : startDate,
                    endDate : endDate,
                    collectorId : collectorId,
                    status : status
                },
                success : function(result) {
                    $('.resultDiv').html(result);
                    $(".loder_cover").hide();
                    if(download) {
                        exportReport()
                    }
                },
                error : function(err) {
                    showError('Something went wrong');
                }
            })
        }

        $('#generate-report').click(function() {
            getData();
        })
        $('#download-report').click(function(){
            getData(true);
        })
        function exportReport() {
            var downloadLink;
           var dataType = 'application/vnd.ms-excel';
           var tableSelect = document.getElementById('report-table');
           var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

           // Specify file name
           let filename = 'report_data.xls';

           // Create download link element
           downloadLink = document.createElement("a");

           document.body.appendChild(downloadLink);

           if(navigator.msSaveOrOpenBlob){
               var blob = new Blob(['\ufeff', tableHTML], {
                   type: dataType
               });
               navigator.msSaveOrOpenBlob( blob, filename);
           }else{
               // Create a link to the file
               downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

               // Setting the file name
               downloadLink.download = filename;

               //triggering the function
               downloadLink.click();
           }
       }
    </script>
@endpush
