@extends("frontend.layout.main")
@section("content")
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                        <h3 class="box-title">网格投资</h3>
                        <a class="btn btn-primary" href="{{route('grid.create')}}" role="button">创建网格</a>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>资金</th>
                            <th>起始币数</th>
                            <th>间隔(元)</th>
                            <th>购买量</th>
                            <th>利润</th>
                            <th>发起时间</th>
                            <th>终止时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($dataProvider))
                            @foreach($dataProvider as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->fund }}</td>
                                    <td>{{ $item->coins }}</td>
                                    <td>{{ $item->step }}</td>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{ $item->profit }}</td>
                                    <td>{{ is_null( $item->create_at )? "" : date("Y-m-d H:i", $item->create_at) }}</td>
                                    <td>{{ is_null( $item->end_at )? "" : date("Y-m-d H:i", $item->end_at) }}</td>

                                    <td class="button-column">
                                        <a class="view" data-title="查看" title="" data-toggle="tooltip" href="{{ route('grid.show' , ['id'=> $item->id] )}} " data-original-title="查看"><span class="glyphicon glyphicon-eye-open"></span></a>
                                        <a class="update" data-title="更新" title="" data-toggle="tooltip" href="{{route('grid.edit', ['id'=> $item->id] )}} " data-original-title="更新"><span class="glyphicon glyphicon-pencil"></span></a>
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