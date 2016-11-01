@extends("frontend.layout.main")
@section("content")
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                        <h3 class="box-title">定投</h3>
                        <a class="btn btn-primary" href="{{route('dingtou.create')}}" role="button">创建定投</a>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>定投金额</th>
                            <th>止盈百分比</th>
                            <th>货币</th>
                            <th>创建于</th>
                            <th>本金</th>
                            <th>利润</th>
                            <th>已购买BTC</th>
                            <th>利润率</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($dList))
                            @foreach($dList as $item)
                                <tr>
                                    <td>{{$item->per_amount}}</td>
                                    <td>{{$item->stop_profit_percentage}}</td>
                                    <td>{{$item->currency}}</td>
                                    <td>{{ is_null( $item->create_at )? "" : date("Y-m-d H:i", $item->create_at) }}</td>
                                    <td>{{ $item->used_cny_amount }}</td>
                                    <td>{{ $item->profit }}</td>
                                    <td>{{ $item->total_btc }}</td>
                                    <td>{{ $item->used_cny_amount == 0 ? 0 : round($item->profit/$item->used_cny_amount * 100 , 2) }}</td>
                                    <td class="button-column">
                                        <a class="view" data-title="查看" title="" data-toggle="tooltip" href="{{ route('dingtou.bill' , ['id'=> $item->id] )}} " data-original-title="查看"><span class="glyphicon glyphicon-eye-open"></span></a>
                                        <a class="update" data-title="更新" title="" data-toggle="tooltip" href="{{route('dingtou.edit', ['id'=> $item->id] )}} " data-original-title="更新"><span class="glyphicon glyphicon-pencil"></span></a>
                                    </td>
                                </tr>
                            @endforeach

                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="box-header">
                    <h3 class="box-title">暂停的定投</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>定投金额</th>
                            <th> 止盈百分比</th>
                            <th>货币</th>
                            <th>创建于</th>
                            <th>本金</th>
                            <th>利润</th>
                            <th>已购买BTC</th>
                            <th>利润率</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($pList))
                            @foreach($pList as $item)
                                <tr>
                                    <td>{{$item->per_amount}}</td>
                                    <td>{{$item->stop_profit_percentage}}</td>
                                    <td>{{$item->currency}}</td>
                                    <td>{{ is_null($item->create_at)? "" : date("Y-m-d H:i", $item->create_at) }}</td>
                                    <td>{{ $item->used_cny_amount }}</td>
                                    <td>{{ $item->profit }}</td>
                                    <td>{{ $item->total_btc }}</td>
                                    <td>{{ $item->used_cny_amount == 0 ? 0 : round($item->profit/$item->used_cny_amount * 100 , 2) }}</td>
                                    <td>{{ $item->coins }}</td>
                                </tr>
                            @endforeach

                        @endif
                        </tbody>
                    </table>
                </div>

                <div class="box-header">
                    <h3 class="box-title">已结束的定投</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>定投金额</th>
                            <th>止盈百分比</th>
                            <th>货币</th>
                            <th>创建于</th>
                            <th>本金</th>
                            <th>利润</th>
                            <th>已购买BTC</th>
                            <th>利润率</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($sList))
                            @foreach($sList as $item)
                                <tr>
                                    <td>{{$item->per_amount}}</td>
                                    <td>{{$item->stop_profit_percentage}}</td>
                                    <td>{{$item->currency}}</td>
                                    <td>{{ is_null($item->create_at)? "" : date("Y-m-d H:i", $item->create_at) }}</td>
                                    <td>{{ $item->used_cny_amount }}</td>
                                    <td>{{ $item->profit }}</td>
                                    <td>{{ $item->total_btc }}</td>
                                    <td>{{ $item->used_cny_amount == 0 ? 0 : round($item->profit/$item->used_cny_amount * 100 , 2) }}</td>
                                    <td>{{ $item->coins }}</td>
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