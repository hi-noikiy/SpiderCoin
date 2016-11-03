@extends("frontend.layout.main")
@section("content")
    <div class="row">
        <div class="col-md-12">
            <h1>搬砖</h1>
            <div class="form">
                <form class="form-horizontal" id="aip-form" action="{{ route('arb.update', ['id'=> $data['id']]) }}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="_method" value="put">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="arb_cnmarket">国内市场 *</label>
                        <div class="col-sm-8" id="arb_cnmarket">
                            @foreach( $userMarketData as $item )
                                <input class="col-sm-2 control-label" id="arb_cnmarket_{{ $item['id'] }}" value="{{ $item['id'] }}" {{  $data['cnmarket'] == $item['id']  ? 'checked' : '' }} type="radio" name="cnmarket">
                                <label for="arb_cnmarket_{{ $item['id'] }}">
                                    {{ $marketData[$item['mid']] .'|'.$item['desc'].'|'.$item['key'] }}
                                </label><br>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="arb_commarket">国际市场 *</label>
                        <div class="col-sm-8" id="arb_commarket">
                            @foreach( $userMarketData as $item )
                                <input class="col-sm-2 control-label" id="arb_commarket_{{ $item['id'] }}" value="{{ $item['id'] }}" {{  $data['commarket'] == $item['id']  ? 'checked' : '' }} type="radio" name="commarket">
                                <label for="arb_commarket_{{ $item['id'] }}">
                                    {{ $marketData[$item['mid']] .'|'.$item['desc'].'|'.$item['key'] }}
                                </label><br>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="arb_cn2com_address">
                            转国际 Address
                            <span class="required">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input class="form-control" name="cn2com_address" value="{{  $data['cn2com_address'] }}" id="arb_cn2com_address" type="text">
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                XXX
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="arb_com2cn_address">
                            转国内 Address *
                            <span class="required">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input class="form-control" name="com2cn_address" value="{{  $data['com2cn_address'] }}" id="arb_com2cn_address" type="text">
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                XXX
                            </p>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="arb_cn2com">
                            国内转国际
                            <span class="required">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input class="form-control" name="cn2com" value="{{  $data['cn2com'] }}" id="arb_cn2com" type="text">
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                XXX
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="arb_com2cn">
                            国际转国内
                            <span class="required">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input class="form-control" name="com2cn" value="{{  $data['com2cn'] }}" id="arb_com2cn" type="text">
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                XXX
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="arb_cn_capital">
                            国内资本
                            <span class="required">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input class="form-control" name="cn_capital" value="{{  $data['cn_capital'] }}" id="arb_cn_capital" type="text">
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                XXX
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="arb_cn_btc">
                            国内Btc *
                            <span class="required">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input class="form-control" name="cn_btc" value="{{  $data['cn_btc'] }}" id="arb_cn_btc" type="text">
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                XXX
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="arb_com_capital">
                            国际资本
                            <span class="required">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input class="form-control" name="com_capital" value="{{  $data['com_capital'] }}" id="arb_com_capital" type="text">
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                XXX
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="arb_com_btc">
                            国际Btc
                            <span class="required">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input class="form-control" name="com_btc" value="{{ $data['com_btc'] }}" id="arb_com_btc" type="number">
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                XXX
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit"  value="更新">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection