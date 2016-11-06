@extends("frontend.layout.main")
@section("content")
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                        <h3 class="box-title">网格交易 已完成</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>买价</th>
                            <th>购买金额</th>
                            <th>购买币数</th>
                            <th>购买状态</th>
                            <th>卖价</th>
                            <th>卖出金额</th>
                            <th>卖出币数	</th>
                            <th>卖出状态</th>
                            <th>利润</th>
                            <th>购买时间</th>
                            <th>卖出时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($order_provider))
                            @foreach($order_provider as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->buy_price }}</td>
                                    <td>{{ $item->buy_cny_amount }}</td>
                                    <td>{{ $item->buy_coin_amount }}</td>
                                    <td>{{ $item->buy_status }}</td>
                                    <td>{{ $item->sell_price }}</td>
                                    <td>{{ $item->sell_cny_amount }}</td>
                                    <td>{{ $item->sell_coin_amount }}</td>
                                    <td>{{ $item->sell_status }}</td>
                                    <td>{{ $item->profit }}</td>
                                    <td>{{ date("Y-m-d H:i", $item->buy_at) }}</td>
                                    <td>{{ date("Y-m-d H:i", $item->sell_at) }}</td>
                                </tr>
                            @endforeach

                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">网格交易 等待中</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>买价</th>
                            <th>购买金额</th>
                            <th>购买币数</th>
                            <th>购买状态</th>
                            <th>利润</th>
                            <th>购买时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($order_done))
                            @foreach($order_done as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->buy_price }}</td>
                                    <td>{{ $item->buy_cny_amount }}</td>
                                    <td>{{ $item->buy_coin_amount }}</td>
                                    <td>{{ $item->buy_status }}</td>
                                    <td>{{ $item->profit }}</td>
                                    <td>{{ date("Y-m-d H:i", $item->buy_at) }}</td>
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