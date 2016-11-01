@extends("frontend.layout.main")
@section("content")

    <div class="row">
        <div class="col-md-3 col-sm-6">	<h4>定投金额 : ￥{{ $aipData-> per_amount }}</h4></div>
        <div class="col-md-3 col-sm-6">	<h4>起始时间 : {{ date("Y-m-d H:i", $aipData->create_at)}}</h4></div>
        <div class="col-md-3 col-sm-6">	<h4>止盈率 : {{ $aipData-> stop_profit_percentage }}%</h4></div>

    </div>
    <div class="row">
        <div class="col-md-3 col-sm-6">	<h4>已投入金额 : ￥{{ round($total_cny,2) }}</h4></div>
        <div class="col-md-3 col-sm-6">	<h4>已获得 : {{ round($total_btc,4) }}</h4></div>
        <div class="col-md-3 col-sm-6">	<h4>当前牌价 : ￥{{ $tickerData-> buy }} </h4></div>

    </div>
    <div class="row {{ $real_cny<$total_cny ? 'text-danger' : 'text-success' }}">
            <div class="col-md-3 col-sm-6">	<h4>当前价值 : ￥{{ $real_cny }}</h4></div>
            <div class="col-md-3 col-sm-6">	<h4>利润: {{ round($real_cny - $total_cny,4) }}</h4></div>
            <div class="col-md-3 col-sm-6">	<h4>利润率: {{ $total_cny == 0 ? 0: round(($real_cny - $total_cny)/$total_cny*100,4) }} %</h4></div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>购买金额</th>
                <th>平均价格</th>
                <th>成交数量</th>
                <th>成交金额</th>
                <th>状态</th>
                <th>购买时间</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($aipBillData))
                @foreach($aipBillData as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->price}}</td>
                        <td>{{$item->avg_price}}</td>
                        <td>{{ $item->deal_amount }}</td>
                        <td>{{ $item->deal_cny_amount }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ is_null($item->create_at)? "" : date("Y-m-d H:i", $item->create_at) }}</td>

                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    @if(!empty($aipBillData))

        @if($aipBillData->render())
            <div class="box-footer clearfix">
                {!! $aipBillData->render() !!}
            </div>
        @endif
    @endif


@endsection