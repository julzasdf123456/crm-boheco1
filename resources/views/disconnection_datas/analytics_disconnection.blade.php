<div class="row px-2">
    <div class="col-lg-6">
        <div class="card shadow-none" style="height: 420px;">
            <div class="card-header border-0">
                <span class="card-title">Annual Collection Trend</span>
            </div>
            <div class="card-body p-4">
                <div class="position-relative mb-4" id="monthly-graph-holder">
                    <canvas id="monthly-collection" style="height: 280px;"></canvas>
                </div>  
                <div class="d-flex flex-row justify-content-end">
                    <span class="mr-2">
                        <i class="fas fa-square text-primary"></i> This Year
                    </span>
                    <span>
                        <i class="fas fa-square text-gray"></i> Last Year
                    </span>
                </div>         
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            graphAnnual('2023')
        })  

        function graphAnnual(year) {
            $('#monthly-collection').remove()
            $('#monthly-graph-holder').append('<canvas id="monthly-collection" style="height: 280px;"></canvas>')

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

                        var plotPointsPresent = [
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

                        var plotPointsPrevious = [
                            jQuery.isEmptyObject(res['JanuaryPrev']) ? 0 : Math.round((parseFloat(res['JanuaryPrev']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['FebruaryPrev']) ? 0 : Math.round((parseFloat(res['FebruaryPrev']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['MarchPrev']) ? 0 : Math.round((parseFloat(res['MarchPrev']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['AprilPrev']) ? 0 : Math.round((parseFloat(res['AprilPrev']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['MayPrev']) ? 0 : Math.round((parseFloat(res['MayPrev']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['JunePrev']) ? 0 : Math.round((parseFloat(res['JunePrev']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['JulyPrev']) ? 0 : Math.round((parseFloat(res['JulyPrev']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['AugustPrev']) ? 0 : Math.round((parseFloat(res['AugustPrev']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['SeptemberPrev']) ? 0 : Math.round((parseFloat(res['SeptemberPrev']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['OctoberPrev']) ? 0 : Math.round((parseFloat(res['OctoberPrev']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['NovemberPrev']) ? 0 : Math.round((parseFloat(res['NovemberPrev']) + Number.EPSILON) * 100) / 100,
                            jQuery.isEmptyObject(res['DecemberPrev']) ? 0 : Math.round((parseFloat(res['DecemberPrev']) + Number.EPSILON) * 100) / 100,
                        ]

                        // PRESENT YEAR
                        var presentYear = {}
                        presentYear['label'] = year
                        presentYear['backgroundColor'] = "#0398fc00"
                        presentYear['borderColor'] = "#0398fc"
                        presentYear['pointRadius'] = 4
                        presentYear['pointColor'] = "#0398fc"
                        presentYear['pointStrokeColor'] = 'rgba(60,141,188,1)'
                        presentYear['pointHighlightFill'] = '#fff'
                        presentYear['pointHighlightStroke'] = 'rgba(60,141,188,1)'
                        presentYear['data'] = plotPointsPresent

                        // PREVIOUS YEAR
                        var previousYear = {}
                        previousYear['label'] = year
                        previousYear['backgroundColor'] = "#c9c7c700"
                        previousYear['borderColor'] = "#c9c7c7"
                        previousYear['pointRadius'] = 4
                        previousYear['pointColor'] = "#c9c7c7"
                        previousYear['pointStrokeColor'] = 'rgba(60,141,188,1)'
                        previousYear['pointHighlightFill'] = '#fff'
                        previousYear['pointHighlightStroke'] = 'rgba(60,141,188,1)'
                        previousYear['data'] = plotPointsPrevious

                        datum.push(presentYear)
                        datum.push(previousYear)

                        // console.log(datum)

                        var collectionSummaryChartData = {
                            labels: months,
                            datasets: datum
                        }

                        var collectionSummaryChartOptions = {
                            maintainAspectRatio: false,
                            responsive: true,
                            legend: {
                                display: false
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
                                        }}, ticksStyle
                                    )
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