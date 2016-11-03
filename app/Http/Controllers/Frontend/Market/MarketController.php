<?php

namespace App\Http\Controllers\Frontend\Market;

use App\Models\MarketModel;
use App\Models\UserMarketModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class MarketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request)
    {
        // 查看key列表
        $uid = Auth::user()->id();
        $marketData = UserMarketModel::where('uid',$uid)->get();
        return view('frontend.market.index',compact('marketData' ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 进入创建页面
        $midData = MarketModel::getMarketName();
        return view('frontend.market.create',compact('midData' ));
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 创建
        try {
            // 验证参数
            $validator = \Validator::make($request->all(), [
                'mid'      => 'required|int',
                'key'      => 'required|string',
                'secret'   => 'required|string',
                'desc'     => 'string',
            ],[
                'mid.required'     => '请选择市场种类',
                'key.required'     => '请填写key',
                'secret.required'  => '请填写secret',
                'mid.int'          => '市场种类为整型',

            ]);
            // 判断是否有错误
            if ($validator->fails()) {
                //重定向页面，并把错误信息存入一次性session里
                return $this->errorBackTo($validator);
            }
            // 处理参数
            $model = new UserMarketModel();
            // TODO 用户id
            $model->uid = Auth::user()->id();
            $model->key = $request->input('key');
            $model->mid = $request->input('mid');
            $model->secret = $request->input('secret');
            $model->create_at = time();
            $model->desc = $request->input('desc');
            $model->save();
        }catch(\Exception $ex){
            return $this->errorBackTo( $ex->getMessage());
        }
        return $this->successRoutTo('market.index', "创建成功,快去创建第一个定投吧");
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
        // 进入编辑市场key页面
        $midData = MarketModel::getMarketName();
        $userMarketData = UserMarketModel::find( $id );
        return view('frontend.market.edit',compact('midData','userMarketData' ));
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
        // 创建
        try {
            // 验证参数
            $validator = \Validator::make($request->all(), [
                'mid'      => 'required|int',
                'key'      => 'required|string',
                'secret'   => 'required|string',
                'desc'     => 'string',
            ],[
                'mid.required'     => '请选择市场种类',
                'key.required'     => '请填写key',
                'secret.required'  => '请填写secret',
                'mid.int'          => '市场种类为整型',

            ]);
            // 判断是否有错误
            if ($validator->fails()) {
                //重定向页面，并把错误信息存入一次性session里
                return $this->errorBackTo($validator);
            }
            // 处理参数
            $model = UserMarketModel::where('id', $id)
                ->where('uid',Auth::user()->id())
                ->first();
            $model->mid = $request->input('mid');
            $model->key = $request->input('key');
            $model->secret = $request->input('secret');
            !empty($request->input('desc')) && $model->desc = $request->input('desc');
            $model->save();
        }catch(\Exception $ex){
            return $this->errorBackTo( $ex->getMessage());
        }
        return $this->successRoutTo('market.index', "更新成功,快去创建第一个定投吧");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            if(UserMarketModel::destroy($id)){
                return $this->successBackTo('删除定投成功');
            }
        } catch (\Exception $e) {
            return $this->errorBackTo(['error' => $e->getMessage()]);
        }
    }
}
