<!-- Lưu tại resources/views/product/index.blade.php -->
@extends('layouts_admin.layoutadmin')
@section('title', 'Report')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="vin-title">VINFAST</h1>
            </div>

        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="report-container">
        <!-- Button trigger modal -->
        <button id="chart1" type="button" class="manage-option" data-toggle="modal" data-target="#donutmodel">
            <!-- click -->
            <div class="">
                <i class="fa-solid fa-chart-pie"></i>
                <p>Doanh Số Theo Khu Vực</p>
            </div>
        </button>
        <!-- Modal -->
        <div class="modal fade" id="donutmodel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Doanh Số Theo Khu Vưc</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Body -->
                        <div class="card-body">
                            <div class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand">
                                    <div class=""></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink">
                                    <div class=""></div>
                                </div>
                            </div>
                            <canvas id="donutChart" style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%; display: block; width: 425px;" width="600px" height="300" class="chartjs-render-monitor"></canvas>
                            <div>
                                <p></p>
                                <p class="report1"></p>
                                <p class="report1"> </p>
                                <p class="report1"> </p>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
        <button id="chart2" type="button" class="manage-option" data-toggle="modal" data-target="#linemodel">
            <!-- click -->
            <div class="">
                <i class="fa-solid fa-chart-line"></i>
                <p>Lượt Đặt Hàng Theo Quý</p>
            </div>
        </button>
        <!-- Modal -->
        <div class="modal fade" id="linemodel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Lượt Đặt Hàng Theo Qúy</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Body -->
                        <div class="card-body">
                            <div class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand">
                                    <div class=""></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink">
                                    <div class=""></div>
                                </div>
                            </div>
                            <canvas id="lineChart" style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%; display: block; width: 425px;" width="600px" height="300" class="chartjs-render-monitor"></canvas>
                            <div>
                                <p class="report2"></p>
                                <p class="report2"> </p>
                                <p class="report2"> </p>
                                <p class="report2"> </p>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>

        <button id="chart3" type="button" class="manage-option" data-toggle="modal" data-target="#piemodel">
            <!-- click -->
            <div class="">
                <i class="fa-solid fa-chart-pie"></i>
                <p>Tỷ Lệ Khách Hàng</p>
            </div>
        </button>
        <!-- Modal -->
        <div class="modal fade" id="piemodel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tỷ Lệ Khách Hàng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Body -->
                        <div class="card-body">
                            <div class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand">
                                    <div class=""></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink">
                                    <div class=""></div>
                                </div>
                            </div>
                            <canvas id="pieChart" style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%; display: block; width: 425px;" width="600px" height="300" class="chartjs-render-monitor"></canvas>
                            <div>
                                <p></p>
                                <p class="report3"> </p>
                                <p class="report3"> </p>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
    <div>
    </div>

</section>
@endsection
@section('script-section')

<script type="text/javascript">
    var warehouse = <?php echo $report1 ?>;
  
    var orders = <?php echo json_encode($report2, JSON_HEX_TAG); ?>;
console.log(orders);
    var customers = <?php echo $report3 ?>;
   //1
    var data = [0, 0, 0];
    var report1= document.getElementsByClassName('report1');
    var report2= document.getElementsByClassName('report2');
    var report3= document.getElementsByClassName('report3');
//2



//3
    var guest=0;
    var member=0;
// lay so liêu char 1
    for (var i = 0; i < warehouse.length; i++) {
        for (var j = 0; j < warehouse[i].cars.length; j++) {

            if (warehouse[i].cars[j].car_status == 'sold') {
                data[i]++
            }
        }

    }

//lay so lieu char3
for( var i=0;i<customers.length;i++){
    if (customers[i].customer_role=='member'){member++}
    else{guest++}
}





    var btn = document.getElementById("chart1");
    var btn1 = document.getElementById("chart2");
    var btn2 = document.getElementById("chart3");
    var donutChart = document.getElementById('donutChart');
    var lineChart = document.getElementById('lineChart');
    var pieChart = document.getElementById('pieChart');
    btn.addEventListener("click", function() {
      
      
        var myChart = new Chart(donutChart, {
            type: 'doughnut',
            data: {
                labels: [warehouse[0].warehouse_name, warehouse[1].warehouse_name, warehouse[1].warehouse_name],
                datasets: [{
                    data: [data[0], data[1], data[2]],

                    // thay so liệu vào đây
                    backgroundColor: ['#f56954', '#3c8dbc', '#d2d6de'],
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
            }
        });
 report1[0].innerHTML="Khu Vực "+warehouse[0].warehouse_name+":     "+data[0];
 report1[1].innerHTML="Khu Vực "+warehouse[1].warehouse_name+":     "+data[1];
 report1[2].innerHTML="Khu Vực "+warehouse[2].warehouse_name+":     "+data[2];
        
    
    
    });

    // chart 2
    btn1.addEventListener("click", function() {


        var myChart = new Chart(lineChart, {
            type: 'line',
            data: {
                labels: ['Quý 1', 'Quý 2', 'Quý 3', 'Quý 4'],
                datasets: [{
                    label: 'Lượt Đặt Hàng Năm 2021',
                    data: [orders[0], orders[1], orders[2], orders[3]],
                    backgroundColor: 'midnightblue',
                    borderColor: 'rgb(240, 132, 31);',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
            }
        });
    });
    report2[0].innerHTML="Lượng Đặt Hàng Quý 1 :"     +orders[0];
    report2[1].innerHTML="Lượng Đặt Hàng Quý 2 :"     +orders[1];
    report2[2].innerHTML="Lượng Đặt Hàng Quý 3 :"     +orders[2];
    report2[3].innerHTML="Lượng Đặt Hàng Quý 4 :"     +orders[3];
    //   chart 3


    btn2.addEventListener("click", function() {


        var myChart = new Chart(pieChart, {
            type: 'pie',
            data: {
                labels: ['Thành Viên', 'Khách'],
                datasets: [{
                    data: [member, guest],

                    // thay so liệu vào đây
                    backgroundColor: ['#3c8dbc', '#d63384'],
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
            }
        });
report3[0].innerHTML="Số Thành Viên:       "+member;
report3[1].innerHTML="Số Lượng Khách:      "+guest;

    });
</script>


@endsection