@extends("frontend.layout.main")
@section("content")
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                        <h3 class="box-title">市场</h3>
                        <a class="btn btn-primary" href="{{route('market.create')}}" role="button">添加交易Key</a>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Key</th>
                                <th>Secret</th>
                                <th>备注</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!empty($marketData))
                            @foreach($marketData as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->mid}}</td>
                                    <td>{{$item->key}}</td>
                                    <td>{{$item->secret}}</td>
                                    <td>{{$item->desc}}</td>
                                    <td>
                                        <a href="{{route('market.edit',['id'=>$item->id])}}" class="btn bg-orange btn-flat">编辑</a>
                                        <a class="btn btn-danger btn-flat"
                                           data-url="{{route('market.destroy',['id'=>$item->id])}}"
                                           data-toggle="modal"
                                           data-target="#delete-modal"
                                        >
                                            删除
                                        </a>
                                    </td>
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
@section("after.js")
    @include('backend.components.modal.delete',['title'=>'操作提示','content'=>'你确定要删除这条市场信息吗?'])
@endsection