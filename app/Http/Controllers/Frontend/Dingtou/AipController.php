<?php

namespace App\Http\Controllers\Frontend\Dingtou;

use App\Jobs\BCoin\OKCoin;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_ApiKeyAuthentication;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_Exception;
use App\Models\AipModel;
use App\Models\MarketModel;
use App\Models\UserMarketModel;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            // 验证参数
            $validator = \Validator::make($request->all(), [
                'status'    => 'integer',
            ]);
            if ( $validator->fails() ) {
                throw new \Exception( $validator->errors()->first() ,422 );
            }
            $status = $request->input('status') ? intval($request->input('status')) : 1 ;
            //TODO 获取当前登录的用户
            $uid = Auth::user()->id();
            // 获取用户的定投
            $aipModel = AipModel::where('create_by',$uid)
                ->orderBy('create_at','desc')
            ;
//            $resData['data'] ['recordsFiltered']  =  $resData['data'] ['recordsTotal']  =  $aipModel->count();
            $dList = $aipModel -> where('status', $status )
                ->get();

            // 获取用户暂停的定投
            $pList = $aipModel -> where('status', 2 )
                ->get();
            // 获取用户结束的定投
            $sList = $aipModel -> where('status', -1 )
                ->get();
            return view('frontend.dingtou.index',compact('dList','pList','sList'));
        }catch (\Exception $e ){
            $resData['code']    = $e->getCode();
            $resData['msg']     = $e->getMessage();
            return view('frontend.dingtou.index')->withErrors( $resData['msg'] );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 获取市场key
        $uid = Auth::user()->id();
        $userMarketData = UserMarketModel::where('uid',$uid)->get();
        // 如果还没有创建市场key,返回错误
        if(empty( $userMarketData )){
            return $this->errorBackTo("<a href='/market/create'>请创建市场key!</a>");
        }
        $marketData = MarketModel::getMarketName();
        // 进入定投创建页面
        return view('frontend.dingtou.create',compact('userMarketData','marketData'));
    }

    /**
     * Store a newly created resource in storage.
     * 创建定投
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            // 验证参数
            $validator = \Validator::make($request->all(), [
                'currency'      => 'required|string',
                'aip_type'      => 'required|int',
                'per_amount'    => 'required|int',
                'stop_profit_percentage'    => 'required|int',
                'fund'          => 'required|int',
                'drawdown'      => 'required|int',
                'period'        => 'required|int',
                'day'           => 'array',
                'hour'          => 'array',
                'amount_limit'  => 'required|int',
                'keyid'         => 'required|int',
            ],[
                'currency.required'     => '请选择货币种类',
                'aip_type.required'     => '请选择定投类型',
                'per_amount.required'   => '请填写定投金额',
                'stop_profit_percentage.required' => '请填写定投止盈百分比',
                'fund.required'         => '请填写总资金数',
                'drawdown.required'     => '请填写回撤资金',
                'period.required'       => '请选择购买周期',
                'day.array'             => '请选择购买日',
                'hour.array'            => '请选择购买小时',
                'keyid.required'        => '请选择市场授权KEY',
                'amount_limit.required' => '请填写最大购买倍数',

            ]);
            // 判断是否有错误
            if ($validator->fails()) {
                //重定向页面，并把错误信息存入一次性session里
                return $this->errorBackTo($validator);
            }

            // 处理参数
            $model = new AipModel();
            $model->status = 1;
            // TODO 用户id
            $model->create_by = 1;
            $model->create_at = time();
            $model->start_at = time();
            $model->keyid = $request->input('keyid');
            $model->currency = $request->input('currency');
            $model->aip_type = intval( $request->input('aip_type'));
            $model->per_amount = intval( $request->input('per_amount'));
            $model->fund = intval( $request->input('fund'));
            $model->drawdown = intval( $request->input('drawdown'));
            $model->period = intval( $request->input('period'));
            $model->stop_profit_percentage = intval( $request->input('stop_profit_percentage'));
            $model->amount_limit = intval( $request->input('amount_limit'));

            $hour = $request->input('hour');
            $day = $request->input('day');
            $keyid = $request->input('keyid');
            
            // 根据价值还是价格进行参数判断
            if (  $model->period  == 2) {
                if ( empty($hour)) {
                    throw new \Exception('周期为每日时，小时不应为空');
                }
                $model->hour = implode(',', $hour);
                $model->minute = rand(0, 11) * 5;
            }
            if (   $model->period  == 1) {
                if (empty($day)) {
                    throw new \Exception('周期为每月，日不应为空');
                }
                $model->day = implode(',', $day);
                $model->hour = rand(0, 23);
                $model->minute = rand(0, 11) * 5;
            }
            // 验证OKCoin的秘钥
            $userMarket = UserMarketModel::where('id',$keyid)->first();
            $client = new OKCoin(new OKCoin_ApiKeyAuthentication($userMarket->key, $userMarket->secret));
            $params = array('api_key' => $userMarket->key);
            $result = $client->userinfoApi( $params );
            if($result['result'] == false){
                throw new OKCoin_Exception('key与secret连接交易所失败，请检查这两个值是否正确');
            }

            if($result['info']['funds']['free']['cny'] < $model->fund){
                throw new OKCoin_Exception('账户中的资金不充足');
            }
            $model->key = $userMarket->key ;
            $model->secret = $userMarket->secret ;
            $model->save();
        }catch(OKCoin_Exception $e){
            return $this->errorBackTo( $e->getMessage());
        }catch(\Exception $ex){
            return $this->errorBackTo( $ex->getMessage());
        }
        return $this->successRoutTo('frontend.dingtou.index', "创建定投成功,静候爆发吧");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 根据id查询出相关信息。进入编辑定投页面
        $aipData = AipModel::find($id);
        if(!empty($aipData['hour'])){
            $aipData['hour'] = explode(',',$aipData['hour']);
        }
        if(!empty($aipData['day'])){
            $aipData['day'] = explode(',',$aipData['day']);
        }
        // 获取市场key
        $uid = Auth::user()->id();
        $userMarketData = UserMarketModel::where('uid',$uid)->get();
        // 如果还没有创建市场key,返回错误
        if(empty( $userMarketData )){
            return $this->errorBackTo("<a href='/market/create'>请创建市场key!</a>");
        }
        $marketData = MarketModel::getMarketName();
        return view('frontend.dingtou.edit',compact('aipData','userMarketData','marketData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            // 验证参数
            $validator = \Validator::make($request->all(), [
                'currency'      => 'required|string',
                'aip_type'      => 'required|int',
                'per_amount'    => 'required|int',
                'stop_profit_percentage'    => 'required|int',
                'fund'          => 'required|int',
                'drawdown'      => 'required|int',
                'period'        => 'required|int',
                'day'           => 'array',
                'hour'          => 'array',
                'amount_limit'  => 'required|int',
                'keyid'         => 'required|int',
            ],[
                'currency.required'     => '请选择货币种类',
                'aip_type.required'     => '请选择定投类型',
                'per_amount.required'   => '请填写定投金额',
                'stop_profit_percentage.required' => '请填写定投止盈百分比',
                'fund.required'         => '请填写总资金数',
                'drawdown.required'     => '请填写回撤资金',
                'period.required'       => '请选择购买周期',
                'day.array'             => '请选择购买日',
                'hour.array'            => '请选择购买小时',
                'keyid.required'        => '请选择市场授权KEY',
                'amount_limit.required' => '请填写最大购买倍数',

            ]);
            // 判断是否有错误
            if ($validator->fails()) {
                //重定向页面，并把错误信息存入一次性session里
                return $this->errorBackTo($validator);
            }

            // 处理参数
            $model = AipModel::find($id);

            // 判断是否是登录用户的定投订单
//            if (Auth::id() !== $model->u_id ) {
//                //重定向页面，并把错误信息存入一次性session里
//                return $this->errorBackTo('不允许修改其他人的订单哦');
//            }
            $model->keyid = $request->input('keyid');
            $model->currency = $request->input('currency');
            $model->aip_type = intval( $request->input('aip_type'));
            $model->per_amount = intval( $request->input('per_amount'));
            $model->fund = intval( $request->input('fund'));
            $model->drawdown = intval( $request->input('drawdown'));
            $model->period = intval( $request->input('period'));
            $model->stop_profit_percentage = intval( $request->input('stop_profit_percentage'));
            $model->amount_limit = intval( $request->input('amount_limit'));

            $hour = $request->input('hour');
            $day = $request->input('day');
            $keyid = $request->input('keyid');

            // 根据价值还是价格进行参数判断
            if (  $model->period  == 2) {
                if ( empty($hour)) {
                    throw new \Exception('周期为每日时，小时不应为空');
                }
                $model->hour = implode(',', $hour);
                $model->minute = rand(0, 11) * 5;
            }
            if (   $model->period  == 1) {
                if (empty($day)) {
                    throw new \Exception('周期为每月，日不应为空');
                }
                $model->day = implode(',', $day);
                $model->hour = rand(0, 23);
                $model->minute = rand(0, 11) * 5;
            }
            // 验证OKCoin的秘钥
            $userMarket = UserMarketModel::where('id',$keyid)->first();
            $client = new OKCoin(new OKCoin_ApiKeyAuthentication($userMarket->key, $userMarket->secret));
            $params = array('api_key' => $userMarket->key);
            $result = $client->userinfoApi( $params );
            if($result['result'] == false){
                throw new OKCoin_Exception('key与secret连接交易所失败，请检查这两个值是否正确');
            }

            if($result['info']['funds']['free']['cny'] < $model->fund){
                throw new OKCoin_Exception('账户中的资金不充足');
            }
            $model->key = $userMarket->key ;
            $model->secret = $userMarket->secret ;
            $model->save();
        }catch(OKCoin_Exception $e){
            return $this->errorBackTo( $e->getMessage());
        }catch(\Exception $ex){
            return $this->errorBackTo( $ex->getMessage());
        }
        return $this->successRoutTo('dingtou.index', "更新定投成功,静候爆发吧");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
