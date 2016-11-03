<?php

namespace App\Http\Controllers\Frontend\Arb;

use App\Jobs\BCoin\OKCoinRpc\OKCoin_Exception;
use App\Models\ArbModel;
use App\Models\ArbOrdersModel;
use App\Models\MarketModel;
use App\Models\UserMarketModel;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ArbController extends Controller
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
//            $validator = \Validator::make($request->all(), [
//                'status'    => 'integer',
//            ]);
//            if ( $validator->fails() ) {
//                throw new \Exception( $validator->errors()->first() ,422 );
//            }
//            $status = $request->input('status') ? intval($request->input('status')) : 1 ;
            $uid = Auth::user()->id();
            // 获取用户的搬砖订单
            $dataProvider = ArbModel::where('create_by',$uid)
                -> where('status' , 1)
                -> orderBy('create_at','desc')
                -> get();
            $done = ArbModel::where('create_by',$uid)
                -> where('status' , 2)
                -> orderBy('create_at','desc')
                -> get();
            $suspended = ArbModel::where('create_by',$uid)
                -> where('status' , -3)
                -> orderBy('create_at','desc')
                -> get();

            return view('frontend.arb.index',compact('dataProvider','done','suspended'));
        }catch (\Exception $e ){
            $resData['code']    = $e->getCode();
            $resData['msg']     = $e->getMessage();
            return view('frontend.arb.index')->withErrors( $resData['msg'] );
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
        return view('frontend.arb.create',compact('userMarketData','marketData'));
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
                'cnmarket'          => 'required|string',
                'commarket'         => 'required|string',
                'cn2com_address'    => 'required|string',
                'com2cn_address'    => 'required|string',
                'cn2com'            => 'required|string',
                'com2cn'            => 'required|string',
                'cn_capital'        => 'required|string',
                'cn_btc'            => 'required|string',
                'com_capital'       => 'required|string',
                'com_btc'           => 'required|string',
            ],[
                'cnmarket.required'         => '请选择国内市场',
                'commarket.required'        => '请选择国际市场 ',
                'cn2com_address.required'   => '请填写转国际 Address ',
                'com2cn_address.required'   => '请填写转国内 Address ',
                'cn2com.required'           => '请填写国内转国际 ',
                'com2cn.required'           => '请填写国际转国内 ',
                'cn_capital.required'       => '请填写国内资本 ',
                'cn_btc.array'              => '请填写国内Btc ',
                'com_capital.array'         => '请填写国际资本 ',
                'com_btc.required'          => '请填写国际Btc ',

            ]);
            // 判断是否有错误
            if ($validator->fails()) {
                //重定向页面，并把错误信息存入一次性session里
                return $this->errorBackTo($validator);
            }

            // 处理参数
            $model = new ArbModel();
            $model->status = 1;
            $model->create_by = Auth::user()->id();
            $model->create_at = time();

            $model->cnmarket = $request->input('cnmarket');
            $model->commarket = $request->input('commarket');
            $model->cn2com_address = $request->input('cn2com_address');
            $model->com2cn_address = $request->input('com2cn_address');
            $model->cn2com = $request->input('cn2com');
            $model->com2cn = $request->input('com2cn');
            $model->cn_capital = $request->input('cn_capital');
            $model->cn_btc = $request->input('cn_btc');
            $model->com_capital = $request->input('com_capital');
            $model->com_btc = $request->input('com_btc');
            $model->save();
        }catch(OKCoin_Exception $e){
            return $this->errorBackTo( $e->getMessage());
        }catch(\Exception $ex){
            return $this->errorBackTo( $ex->getMessage());
        }
        return $this->successRoutTo('arb.index', "创建搬砖订单成功,静候爆发吧");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 查看搬砖的记录
        $arb = ArbModel::find($id);
        if(empty($arb)){
            return $this->errorBackTo('订单不存在');
        }
        $order_provider = ArbOrdersModel::where('arbid',$arb->id)
            ->where('status',1)
            ->orderBy('cn2com_at','desc')
            ->paginate( 100 );
        $order_done = ArbOrdersModel::where('arbid',$arb->id)
            ->where('status',2)
            ->orderBy('cn2com_at','desc')
            ->paginate( 100 );
        return view('frontend.arb.show',compact('arb','order_provider','order_done'));

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
        $data = ArbModel::find($id);
        // 获取市场key
        $uid = Auth::user()->id();
        $userMarketData = UserMarketModel::where('uid',$uid)->get();
        // 如果还没有创建市场key,返回错误
        if(empty( $userMarketData )){
            return $this->errorBackTo("<a href='/market/create'>请创建市场key!</a>");
        }
        $marketData = MarketModel::getMarketName();
        return view('frontend.arb.edit',compact('data','userMarketData','marketData'));
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
                'cnmarket'          => 'required|string',
                'commarket'         => 'required|string',
                'cn2com_address'    => 'required|string',
                'com2cn_address'    => 'required|string',
                'cn2com'            => 'required|string',
                'com2cn'            => 'required|string',
                'cn_capital'        => 'required|string',
                'cn_btc'            => 'required|string',
                'com_capital'       => 'required|string',
                'com_btc'           => 'required|string',
            ],[
                'cnmarket.required'         => '请选择国内市场',
                'commarket.required'        => '请选择国际市场 ',
                'cn2com_address.required'   => '请填写转国际 Address ',
                'com2cn_address.required'   => '请填写转国内 Address ',
                'cn2com.required'           => '请填写国内转国际 ',
                'com2cn.required'           => '请填写国际转国内 ',
                'cn_capital.required'       => '请填写国内资本 ',
                'cn_btc.array'              => '请填写国内Btc ',
                'com_capital.array'         => '请填写国际资本 ',
                'com_btc.required'          => '请填写国际Btc ',

            ]);
            // 判断是否有错误
            if ($validator->fails()) {
                //重定向页面，并把错误信息存入一次性session里
                return $this->errorBackTo($validator);
            }
            // 处理参数
            $model = ArbModel::find($id);
            // TODO 判断是否该用户创建的,不是不允许更新
            // 处理参数
            $model->cnmarket = $request->input('cnmarket');
            $model->commarket = $request->input('commarket');
            $model->cn2com_address = $request->input('cn2com_address');
            $model->com2cn_address = $request->input('com2cn_address');
            $model->cn2com = $request->input('cn2com');
            $model->com2cn = $request->input('com2cn');
            $model->cn_capital = $request->input('cn_capital');
            $model->cn_btc = $request->input('cn_btc');
            $model->com_capital = $request->input('com_capital');
            $model->com_btc = $request->input('com_btc');
            $model->save();
        }catch(OKCoin_Exception $e){
            return $this->errorBackTo( $e->getMessage());
        }catch(\Exception $ex){
            return $this->errorBackTo( $ex->getMessage());
        }
        return $this->successRoutTo('arb.index', "更新搬砖订单成功,静候爆发吧");
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
