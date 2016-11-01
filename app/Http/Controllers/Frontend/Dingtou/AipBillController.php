<?php

namespace App\Http\Controllers\Frontend\Dingtou;

use App\Models\AipModel;
use App\Models\AipOrdersModel;
use App\Models\TickerModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class AipBillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $aipId , Request $request)
    {
        try{
            // 获取当前登录的用户
            $uid = Auth::user()->id();

            // 获取用户定投表单数据
            $aipData = AipModel::find($aipId);
            // 初始化模型
            $aipBillModel = AipOrdersModel::where('aip_id',$aipId);
//                -> where('uid', $uid );

            // 获取用户的定投账单
            $aipBillData = $aipBillModel -> orderBy('create_at','desc')
                ->paginate( 100 );
            // 获取用户定投总和
            $total_btc = $aipBillModel->where('status',2)->sum('deal_amount');
            $total_cny = $aipBillModel->where('status',2)->sum('deal_cny_amount');
            // 获取现在价格
            $tickerData = TickerModel::where('mid',1)
                ->where('symbol',$aipData->currency)
                ->orderBy('date','desc')
                ->first();
            $real_cny = !empty($total_btc) ?  round($tickerData->buy * $total_btc,2) : 0;
            return view('frontend.dingtouBill.index',compact('aipData','aipBillData','tickerData','real_cny','total_btc','total_cny'));
        }catch (\Exception $e ){
            $resData['code']    = $e->getCode();
            $resData['msg']     = $e->getMessage();
            return view('frontend.dingtouBill.index')->withErrors( $resData['msg'] );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
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
        //
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
        //
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
