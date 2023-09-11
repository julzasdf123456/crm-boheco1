<div class="row px-2">
    <div class="col-lg-6">
        <div class="card shadow-none" style="height: 420px;">
            <div class="card-header border-0">
                <span class="card-title">Monthly Collection Trend</span>
            </div>
            <div class="card-body">
                <div id="monthly-graph-holder">
                    <canvas id="monthly-collection" style="height: 330px;"></canvas>
                </div>                
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            graphMonthly('2023')
        })

        function graphMonthly(year) {
            $('#monthly-collection').remove()
            $('#monthly-graph-holder').append('<canvas id="monthly-collection" style="height: 330px;"></canvas>')

            var monthlyChartCanvas = $('#monthly-collection').get(0).getContext('2d')
            
            var months = [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sept',
                'Oct',
                'Nov',
                'Dec',
            ]

            $.ajax({
                url : "{{ route('disconnectionDatas.get-monthly-collection-graph') }}",
                type : 'GET',
                data : {
                    Year : year
                },
                success : function(res) {
                    console.log(res)
                    if (!jQuery.isEmptyObject(res)) {
                        var ticksStyle = { fontColor:'#495057', fontStyle:'bold'}

                        var datum = []

                        var plotPoints = [
                            jQuery.isEmptyObject(res['January']) ? 0 : Math.round((parseFloat(res['January']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['February']) ? 0 : Math.round((parseFloat(res['February']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['March']) ? 0 : Math.round((parseFloat(res['March']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['April']) ? 0 : Math.round((parseFloat(res['April']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['May']) ? 0 : Math.round((parseFloat(res['May']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['June']) ? 0 : Math.round((parseFloat(res['June']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['July']) ? 0 : Math.round((parseFloat(res['July']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['August']) ? 0 : Math.round((parseFloat(res['August']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['September']) ? 0 : Math.round((parseFloat(res['September']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['October']) ? 0 : Math.round((parseFloat(res['October']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['November']) ? 0 : Math.round((parseFloat(res['November']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['December']) ? 0 : Math.round((parseFloat(res['December']) + Number.EPSILON) * 100) / 100,
                        ]

                        

                        var clump = {}
                        clump['label'] = 'Year ' + year
                        clump['backgroundColor'] = "#0398fc00"
                        clump['borderColor'] = "#0398fc"
                        clump['pointRadius'] = 4
                        clump['pointColor'] = "#0398fc"
                        clump['pointStrokeColor'] = 'rgba(60,141,188,1)'
                        clump['pointHighlightFill'] = '#fff'
                        clump['pointHighlightStroke'] = 'rgba(60,141,188,1)'
                        clump['data'] = plotPoints

                        datum.push(clump)

                        // console.log(datum)

                        var collectionSummaryChartData = {
                            labels: months,
                            datasets: datum
                        }

                        var collectionSummaryChartOptions = {
                            maintainAspectRatio: false,
                            responsive: true,
                            legend: {
                                display: true
                            },
                            scales: {
                                xAxes: [{
                                    gridLines: {
                                        display: false
                                    },
                                    ticks : ticksStyle,
                                }],
                                yAxes: [{
                                    gridLines: {
                                        display: false
                                    },
                                    ticks : $.extend({
                                        beginAtZero:true,
                                        callback : function(value) { 
                                            if(value>=1000) { 
                                                value/=1000
                                                value+='k'
                                            }
                                            return '$'+value
                                        }}, ticksStyle)
                                }]
                            }
                        }

                        var collectionSummaryChart = new Chart(monthlyChartCanvas, { 
                            type: 'line',
                            data: collectionSummaryChartData,
                            options: collectionSummaryChartOptions
                        })
                    } else {
                        var datum = []

                        // console.log(datum)

                        var collectionSummaryChartData = {
                            labels: months,
                            datasets: datum
                        }

                        var collectionSummaryChartOptions = {
                            maintainAspectRatio: false,
                            responsive: true,
                            legend: {
                                display: true
                            },
                            scales: {
                                xAxes: [{
                                    gridLines: {
                                        display: false
                                    }
                                }],
                                yAxes: [{
                                    gridLines: {
                                        display: false
                                    }
                                }]
                            }
                        }

                        var collectionSummaryChart = new Chart(monthlyChartCanvas, { 
                            type: 'line',
                            data: collectionSummaryChartData,
                            options: collectionSummaryChartOptions
                        })
                    }
                },
                error : function(err) {
                    console.log(err)
                } 
            })
        }
    </script>
@endpush