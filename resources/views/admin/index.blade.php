@extends('admin.layouts.app')
@section('content')
    <!-- eCommerce statistic -->
    <div class="row">

        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="warning">{{ $subsciber_all }}</h3>
                                    <h6>{{ trans("admin.subsciber_all") }}</h6>
                            </div>
                            <div>
                                <i class="la la-credit-card warning fa-3x float-right"></i>
                            </div>
                        </div>
                        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                            <div class="progress-bar bg-gradient-x-warning" role="progressbar" style="width: 100%" aria-valuenow="80" aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="success">{{ $subsciber_agree }}</h3>
                                    <h6>{{ trans("admin.subsciber_agree") }}</h6>
                            </div>
                            <div>
                                <i class="la la-credit-card success la-3x float-right"></i>
                            </div>
                        </div>
                        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                            <div class="progress-bar bg-gradient-x-success" role="progressbar" style="width: 100%" aria-valuenow="80" aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="danger">{{ $subsciber_waiting }}</h3>
                                    <h6>{{ trans("admin.subsciber_waiting") }}</h6>
                            </div>
                            <div>
                                <i class="la la-credit-card danger fa-3x float-right"></i>
                            </div>
                        </div>
                        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                            <div class="progress-bar bg-gradient-x-danger" role="progressbar" style="width: 100%" aria-valuenow="80" aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-12">
            <div class="card pull-up">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="media-body text-left">
                                <h3 class="info">{{ $subsciber_today }}</h3>
                                    <h6>{{ trans("admin.subsciber_today") }}</h6>
                            </div>
                            <div>
                                <i class="la la-credit-card info fa-3x float-right"></i>
                            </div>
                        </div>
                        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                            <div class="progress-bar bg-gradient-x-info" role="progressbar" style="width: 100%" aria-valuenow="80" aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--/ eCommerce statistic -->
    <!-- Column Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">الاشتراكات</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <canvas id="column-chart" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(window).on("load", function(){

            //Get the context of the Chart canvas element we want to select
            var ctx = $("#column-chart");

            // Chart Options
            var chartOptions = {
                // Elements options apply to all of the options unless overridden in a dataset
                // In this case, we are setting the border of each bar to be 2px wide and green
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: 'rgb(0, 255, 0)',
                        borderSkipped: 'bottom'
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                responsiveAnimationDuration:500,
                legend: {
                    position: 'top',
                },
                scales: {
                    xAxes: [{
                        display: true,
                        gridLines: {
                            color: "#f3f3f3",
                            drawTicks: false,
                        },
                        scaleLabel: {
                            display: true,
                        }
                    }],
                    yAxes: [{
                        display: true,
                        gridLines: {
                            color: "#f3f3f3",
                            drawTicks: false,
                        },
                        scaleLabel: {
                            display: true,
                        }
                    }]
                },
                title: {
                    display: true,
                    text: '{{ trans("admin.home chart") }}'
                }
            };

            // Chart Data
            var chartData = {
                labels: [10,11,12],
                datasets: [{
                    label: " subscriptions",
                    data:[@foreach ($cities as $city){{ $city->city_count }},@endforeach],
                    backgroundColor: "#28D094",
                    hoverBackgroundColor: "rgba(22,211,154,.9)",
                    borderColor: "transparent"
                }]
            };

            var config = {
                type: 'bar',

                // Chart Options
                options : chartOptions,

                data : chartData
            };

            // Create the chart
            var lineChart = new Chart(ctx, config);
        });
    </script>
@endsection
