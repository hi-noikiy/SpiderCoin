@extends("frontend.layout.main")
@section("content")
    <div class="row">
        <div class="col-md-12">
            <h1>开始定投</h1>
            <div class="form">

                <form class="form-horizontal" id="aip-form" action="{{ route('dingtou.store') }}" method="post">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="Aip_currency">货币</label>
                        <div class="col-sm-8">
                            <input id="ytAip_currency" type="hidden" value="" name="Aip[currency]">
                            <span id="Aip_currency">
                                <input class="col-sm-2 control-label" id="Aip_currency_0" value="btc_cny" {{  old('currency') == 'btc_cny' || empty( old('currency')) ? 'checked' : '' }} type="radio" name="currency">
                                <label for="Aip_currency_0">BTC</label><br>
                                <input class="col-sm-2 control-label" id="Aip_currency_1" value="ltc_cny" {{  old('currency') == 'ltc_cny' ? 'checked' : '' }} type="radio" name="currency">
                                <label for="Aip_currency_1">LTC</label>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="Aip_aip_type">定投类型</label>
                        <div class="col-sm-8">
                            <span id="Aip_aip_type">
                                <input class="col-sm-2 control-label" id="Aip_aip_type_0" value="1" {{  old('aip_type') == 1 || empty( old('aip_type')) ? 'checked' : '' }} type="radio" name="aip_type">
                                <label for="Aip_aip_type_0">价格定投</label>
                                <br>
                                <input class="col-sm-2 control-label" id="Aip_aip_type_1" value="2" {{  old('aip_type') == 2 ? 'checked' : '' }} type="radio" name="aip_type">
                                <label for="Aip_aip_type_1">价值定投</label>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="Aip_per_amount">定投金额
                            <span class="required">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input class="form-control" name="per_amount" value="{{  old('per_amount') }}" id="Aip_per_amount" type="number"></div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                每次购买的金额,由于OKCOIN的市场单有最低成交限制0.01BTC，因此金额需要高于40元，否则会出现购买失败
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="Aip_stop_profit_percentage">止盈百分比 <span
                                    class="required">*</span></label>
                        <div class="col-sm-4">
                            <input class="form-control" name="stop_profit_percentage" value="{{  old('per_amount') }}" id="Aip_stop_profit_percentage" type="text"></div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                止盈利润率，当您当前持有的币与定投的资金达到您设定的比率，系统将自动全部卖出本轮购买的BTC，并自动生成一个和当前金额与周期相同的定投。
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="Aip_fund">
                            资金
                            <span class="required">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input class="form-control" name="fund" value="{{  old('fund') }}" id="Aip_fund" type="text">
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                为定投准备的总资金，当您账户里的资金不足是，系统会发邮件通知您
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="Aip_drawdown">回撤金额</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="drawdown"  id="Aip_drawdown" type="number" value="{{  old('drawdown') }}">
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                如果设置了回撤金额，系统将按照回撤金额进行波峰回撤卖出。当到达止盈利润率时系统不触发卖出操作，如果币价继续上涨超过回撤金额，那么系统自动不断调高卖出价格，直至币价回落。
                            </p>
                            <p class="text-danger">
                                注意：由于市场价格与本系统不是实时同步，市场的价格的大幅波动会影响波峰回撤，风险自负 ：）
                            </p>
                        </div>
                    </div>


                    <!-- 	<div class="form-group">
                            <label class="col-sm-2 control-label" for="Aip_period">购买周期</label>		<div class="col-sm-4">

                                    <input id="ytAip_dayarrays" type="hidden" value="" name="Aip[dayarrays]" /><span id="Aip_dayarrays"><input id="Aip_dayarrays_0" value="1" checked="checked" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_0">1</label>&nbsp;<input id="Aip_dayarrays_1" value="2" checked="checked" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_1">2</label>&nbsp;<input id="Aip_dayarrays_2" value="3" checked="checked" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_2">3</label>&nbsp;<input id="Aip_dayarrays_3" value="4" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_3">4</label>&nbsp;<input id="Aip_dayarrays_4" value="5" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_4">5</label>&nbsp;<input id="Aip_dayarrays_5" value="6" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_5">6</label>&nbsp;<input id="Aip_dayarrays_6" value="7" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_6">7</label>&nbsp;<input id="Aip_dayarrays_7" value="8" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_7">8</label>&nbsp;<input id="Aip_dayarrays_8" value="9" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_8">9</label>&nbsp;<input id="Aip_dayarrays_9" value="10" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_9">10</label>&nbsp;<input id="Aip_dayarrays_10" value="11" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_10">11</label>&nbsp;<input id="Aip_dayarrays_11" value="12" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_11">12</label>&nbsp;<input id="Aip_dayarrays_12" value="13" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_12">13</label>&nbsp;<input id="Aip_dayarrays_13" value="14" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_13">14</label>&nbsp;<input id="Aip_dayarrays_14" value="15" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_14">15</label>&nbsp;<input id="Aip_dayarrays_15" value="16" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_15">16</label>&nbsp;<input id="Aip_dayarrays_16" value="17" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_16">17</label>&nbsp;<input id="Aip_dayarrays_17" value="18" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_17">18</label>&nbsp;<input id="Aip_dayarrays_18" value="19" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_18">19</label>&nbsp;<input id="Aip_dayarrays_19" value="20" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_19">20</label>&nbsp;<input id="Aip_dayarrays_20" value="21" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_20">21</label>&nbsp;<input id="Aip_dayarrays_21" value="22" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_21">22</label>&nbsp;<input id="Aip_dayarrays_22" value="23" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_22">23</label>&nbsp;<input id="Aip_dayarrays_23" value="24" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_23">24</label>&nbsp;<input id="Aip_dayarrays_24" value="25" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_24">25</label>&nbsp;<input id="Aip_dayarrays_25" value="26" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_25">26</label>&nbsp;<input id="Aip_dayarrays_26" value="27" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_26">27</label>&nbsp;<input id="Aip_dayarrays_27" value="28" type="checkbox" name="Aip[dayarrays][]" /> <label class="checkboxlabel" for="Aip_dayarrays_27">28</label></span>		</div>
                        </div> -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="Aip_period">购买周期</label>
                        <div class="col-sm-4">
                            <div class="col-sm-12 row">
                                <input type="radio" class="col-sm-2" name="period" value="1"  {{  old('period') == 1 || empty( old('aip_type')) ? 'checked' : '' }}  autocomplete="off" checked=""> 每月
                            </div>
                            <div class="col-sm-12 row">
                                @for($i = 1;$i<29;$i++)
                                    <input type="checkbox" name="day[]" value="{{ $i }}" autocomplete="off"> {{ $i }}
                                @endfor
                            </div>

                            <div class="col-sm-12 row">
                                <input type="radio" name="period" class="col-sm-2"  value="2"  {{  old('period') == 2 ? 'checked' : '' }}  autocomplete="off" checked=""> 每天
                            </div>

                            <div class="col-sm-12 row">
                                @for($i = 1;$i<24;$i++)
                                    <input type="checkbox" name="hour[]" value="{{ $i }}" > {{ $i }}
                                @endfor
                            </div>
                        </div>
                        <div class="col-sm-4">
                            解释：
                            <p class="text-success">
                                选择"每月"可以多选日期，如1,3,5,7,11号的某一固定时间购买固定金额的BTC。
                                这个固定时间会由系统随机选择，并固定为每一次的购买时间。
                            </p>
                            <p class="text-success">
                                选择"每日"则可以多选小时，如2,4,6,12等,
                                系统会在这些小时里的固定分钟购买固定金额。这个固定分钟会由系统随机选择，并固定为每次购买的分钟。
                            </p>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="Aip_keyid">市场授权KEY</label>
                        <div class="col-sm-8">
                            <span id="Aip_keyid">
                                <input class="col-sm-2 control-label" id="Aip_keyid_0" value="25" type="radio" name="keyid">
                                <label for="Aip_keyid_0">okcoin.cn|定投|d97085b4-f727-480b-b0fc-2fe7cd5a252f</label>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="Aip_amount_limit">最大购买倍数</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="amount_limit" id="Aip_amount_limit" type="text" value="{{  !empty( old('drawdown'))? old('drawdown') :-1  }}">
                        </div>
                        <div class="col-sm-4">
                            <p class="text-success">
                                仅在价值定投中使用。是定投金额的倍数，当标的物的价格下跌，会导致价值定投单次购买的金额被放大，特做此-1为无限制
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit"  value="Create">
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection