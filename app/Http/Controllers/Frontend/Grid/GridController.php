<?php

namespace App\Http\Controllers\Frontend\Grid;

use App\Jobs\BCoin\OKCoinRpc\OKCoin_Exception;
use App\Models\ArbModel;
use App\Models\ArbOrdersModel;
use App\Models\GridModel;
use App\Models\GridOrdersModel;
use App\Models\MarketModel;
use App\Models\UserMarketModel;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GridController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{

            $uid = Auth::user()->id();
            // 获取用户的网格订单
            $dataProvider = GridModel::where('create_by' , $uid)
//                -> where('status' , 1)
                -> orderBy('create_at','desc')
                -> get();

            return view('frontend.grid.index',compact('dataProvider'));
        }catch (\Exception $e ){
            $resData['code']    = $e->getCode();
            $resData['msg']     = $e->getMessage();
            return view('frontend.grid.index')->withErrors( $resData['msg'] );
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
        // 进入网格创建页面
        return view('frontend.grid.create',compact('userMarketData','marketData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            // 验证参数
            $validator = \Validator::make($request->all(), [
                'fund'                  => 'required|int',
                'step'                  => 'required|int',
                'coins'                 => 'required|int',
                'amount'                => 'required|int',
                'user_market'           => 'required|int',
            ],[
                'fund.required'         => '请填写资金',
                'step.required'         => '请填写 间隔(元)',
                'coins.required'        => '请填写起始币数',
                'amount.required'       => '请填写购买量 ',
                'user_market.required'  => '请选择购买市场 ',
            ]);
            // 判断是否有错误
            if ($validator->fails()) {
                //重定向页面，并把错误信息存入一次性session里
                return $this->errorBackTo($validator);
            }

            $uid = Auth::user()->id();
            // 处理参数
            $model = new GridModel();
            $model->create_by = $uid;
            $model->create_at = time();

            $model->fund = $request->input('fund');
            $model->step = $request->input('step');
            $model->coins = $request->input('coins');
            $model->amount = $request->input('amount');
            $model->user_market = $request->input('user_market');
            $marketData = UserMarketModel::where('uid', $uid)
                ->where('id',$model->user_market)
                ->first();
            $model->key = $marketData->key;
            $model->secret = $marketData->secret;
            $model->save();
        }catch(OKCoin_Exception $e){
            return $this->errorBackTo( $e->getMessage());
        }catch(\Exception $ex){
            return $this->errorBackTo( $ex->getMessage());
        }
        return $this->successRoutTo('grid.index', "创建网格订单成功,静候爆发吧");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 查看网格的记录
        $gridData = GridModel::find($id);
        if(empty($gridData)){
            return $this->errorBackTo('订单不存在');
        }
        $order_provider = GridOrdersModel::where('gid',$id)
            ->where('status',1)
//            ->orderBy('create_at','desc')
            ->paginate( 100 );
        $order_done = GridOrdersModel::where('gid',$id)
            ->where('status',0)
//            ->orderBy('create_at','desc')
            ->paginate( 100 );
        return view('frontend.grid.show',compact('gridData','order_provider','order_done'));

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
        $data = GridModel::find($id);
        // 获取市场key
        $uid = Auth::user()->id();
        $userMarketData = UserMarketModel::where('uid',$uid)->get();
        // 如果还没有创建市场key,返回错误
        if(empty( $userMarketData )){
            return $this->errorBackTo("<a href='/market/create'>请创建市场key!</a>");
        }
        $marketData = MarketModel::getMarketName();
        return view('frontend.grid.edit',compact('data','userMarketData','marketData'));
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
                'fund'                  => 'required|int',
                'step'                  => 'required|int',
                'coins'                 => 'required|int',
                'amount'                => 'required|int',
                'user_market'           => 'required|int',
            ],[
                'fund.required'         => '请填写资金',
                'step.required'         => '请填写 间隔(元)',
                'coins.required'        => '请填写起始币数',
                'amount.required'       => '请填写购买量 ',
                'user_market.required'  => '请选择购买市场 ',
            ]);
            // 判断是否有错误
            if ($validator->fails()) {
                //重定向页面，并把错误信息存入一次性session里
                return $this->errorBackTo($validator);
            }
            $uid = Auth::user()->id();
            // 处理参数
            $model = GridModel::where('id' , $id)
                ->where('create_by',$uid)
                ->first();
            $model->fund = $request->input('fund');
            $model->step = $request->input('step');
            $model->coins = $request->input('coins');
            $model->amount = $request->input('amount');
            $model->user_market = $request->input('user_market');
            $marketData = UserMarketModel::where('uid',$uid)
                ->where('mid',$model->user_market)
                ->first();
            $model->key = $marketData->key;
            $model->secret = $marketData->secret;
            $model->save();

        }catch(OKCoin_Exception $e){
            return $this->errorBackTo( $e->getMessage());
        }catch(\Exception $ex){
            return $this->errorBackTo( $ex->getMessage());
        }
        return $this->successRoutTo('grid.index', "更新订单成功,静候爆发吧");
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
