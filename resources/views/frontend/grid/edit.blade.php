@extends("frontend.layout.main")
@section("content")
    <div class="row">
        <div class="col-md-12">
            <h1>网格</h1>
            <div class="form">
                <form class="form-horizontal" id="grid_form" action="{{ route('grid.update') }}" method="post">
                    {{csrf_field()}}

                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="grid_fund">
                            资金
                            <span class="required">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input class="form-control" name="fund" value="{{  $data['fund'] }}" id="grid_fund" type="text">
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                XXX
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="grid_coins">
                            起始币数
                            <span class="required">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input class="form-control" name="coins" value="{{ $data['coins'] }}" id="grid_coins" type="text">
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                XXX
                            </p>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="grid_step">
                            间隔(元)
                            <span class="required">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input class="form-control" name="step" value="{{  $data['step'] }}" id="grid_step" type="text">
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                XXX
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="grid_amount">
                            购买量 *
                            <span class="required">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input class="form-control" name="amount" value="{{  $data['amount'] }}" id="grid_amount" type="text">
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                XXX
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="arb_user_market">市场 *</label>
                        <div class="col-sm-8" id="arb_user_market">
                            @foreach( $userMarketData as $item )
                                <input class="col-sm-2 control-label" id="arb_user_market_{{ $item['id'] }}" value="{{ $item['id'] }}" {{  $data['user_market'] == $item['id']  ? 'checked' : '' }} type="radio" name="user_market">
                                <label for="arb_user_market_{{ $item['id'] }}">
                                    {{ $marketData[$item['mid']] .'|'.$item['desc'].'|'.$item['key'] }}
                                </label><br>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit"  value="确定">
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection