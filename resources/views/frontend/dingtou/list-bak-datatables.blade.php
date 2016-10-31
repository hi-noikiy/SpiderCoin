<div class="row">
    <div class="col-md-12">
        <h2>
            定投
        </h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>
                            定投金额
                        </th>
                        <th>
                            止盈百分比
                        </th>
                        <th>
                            货币
                        </th>
                        <th>
                            创建于
                        </th>
                        <th>
                            本金
                        </th>
                        <th>
                            利润
                        </th>
                        <th>
                            已购买BTC
                        </th>
                        <th>
                            利润率
                        </th>
                        <th>

                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@section('after.js')
<script>
    $(document).ready(function() {
        var table =  $('.table');
        var oTable = table.dataTable( {
            "bLengthChange": true, //改变每页显示数据数量
            "bFilter": true, //过滤功能
            "bProcessing": true, //开启读取服务器数据时显示正在加载中……特别是大数据量的时候，开启此功能比较好
            "bServerSide": true, //开启服务器模式，使用服务器端处理配置datatable。注意：sAjaxSource参数也必须被给予为了给datatable源代码来获取所需的数据对于每个画。 这个翻译有点别扭。
            "iDisplayLength": 30,//每页显示10条数据(会在ajax请求时发送由后台处理)
            //ajax地址
            "sAjaxSource": '/dingtou/list',// 请求路径
            "fnServerData": retrieveData,//执行方法(对应函数有三个参数分别是 url(sAjaxSource)、data(传入参数)、回调函数(接收返回数据并有插件作出处理))
            // "bJQueryUI" : true,
            // 表格填入数据
            "columns": [
                { "data": "per_amount" }, // 定投金额
                { "data": "stop_profit_percentage" }, // 止盈百分比
                { "data": "currency" }, // 货币
                { "data": "create_at" }, // 创建时间
                { "data": "used_cny_amount" }, // 本金
                { "data": "profit" }, // 利润
                { "data": "total_btc" }, // 已购买btc
                { "data": "used_cny_amount" }, // 利润率
                { "data": "us_uid" }
            ],
            //添加按钮
            "columnDefs" : [ {
                "targets" : 5,//操作按钮目标列
                "data" : null,
                "render" : function(data, type,row) {
                    //console.log(data,type,row)
                }
            },{
                "targets" : 6,//操作按钮目标列
                "data" : null,
                "render" : function(data, type,row) {
                    //console.log(data,type,row)

                }
            }  ]
        } );

        function retrieveData(sSource, aoData, fnCallback) {
            /* ajax 方法调用*/
            $.ajax({
                type: "get",
                // contentType: "text/json",
                url: sSource,
                dataType: "json",
                data: aoData,
                success: function (resp) {
                    if (resp.code === 200) {
                        if(resp.data.length == 0){
                            oTable.fnDestroy();
                            alert("没有数据");
                        }else {
                            fnCallback(resp.data);
                        }
                    } else {
                        alert(resp.msg);
                        oTable.fnDestroy();
                    }
                },
                "error":function (resp) {
                    alert("网络错误")
                }
            });
        }
    } );
</script>
    @endsection