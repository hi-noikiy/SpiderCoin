@extends("frontend.layout.main")
@section("content")
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                        <h3 class="box-title">国内转国际</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>cn买</th>
                            <th>CN BTC</th>
                            <th>CN金额</th>
                            <th>COM BTC卖</th>
                            <th>Com 卖价</th>
                            <th>USD金额</th>
                            <th>搬砖率</th>
                            <th>Cn2com At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($order_provider))
                            @foreach($order_provider as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->cnbuy }}</td>
                                    <td>{{ $item->cnbtc_buy }}</td>
                                    <td>{{ $item->rmbbuy_amount }}</td>
                                    <td>{{ $item->combtc_sell }}</td>
                                    <td>{{ $item->com_sell }}</td>
                                    <td>{{ $item->usdsell_amount }}</td>
                                    <td>{{ $item->buysell_rate }}</td>
                                    <td>{{ date("Y-m-d H:i", $item->cn2com_at) }}</td>
                                </tr>
                            @endforeach

                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">国际转国内，已结束</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>cn买</th>
                            <th>CN BTC</th>
                            <th>CN金额</th>
                            <th>COM BTC卖</th>
                            <th>Com 卖价</th>
                            <th>USD金额</th>
                            <th>搬砖率</th>
                            <th>Cn2com At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($order_done))
                            @foreach($order_done as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->cnbuy }}</td>
                                    <td>{{ $item->cnbtc_buy }}</td>
                                    <td>{{ $item->rmbbuy_amount }}</td>
                                    <td>{{ $item->usdsell_amount }}</td>
                                    <td>{{ $item->buysell_rate }}</td>
                                    <td>{{ date("Y-m-d H:i", $item->cn2com_at) }}</td>
                                    <td>{{ $item->cnsell }}</td>
                                    <td>{{ $item->rmbsell_amount }}</td>
                                    <td>{{ $item->usdbuy_amount }}</td>
                                    <td>{{ date("Y-m-d H:i", $item->com2cn_at) }}</td>
                                    <td>{{ $item->profit }}</td>
                                </tr>
                            @endforeach

                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection