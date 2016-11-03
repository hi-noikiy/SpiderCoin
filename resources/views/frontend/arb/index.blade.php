@extends("frontend.layout.main")
@section("content")
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                        <h3 class="box-title">搬砖</h3>
                        <a class="btn btn-primary" href="{{route('arb.create')}}" role="button">创建搬砖</a>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>国内市场</th>
                            <th>国际市场</th>
                            <th>国内转国际</th>
                            <th>国际转国内</th>
                            <th>国内资本</th>
                            <th>国内Btc</th>
                            <th>国际资本</th>
                            <th>国际Btc</th>
                            <th>创建于</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($dataProvider))
                            @foreach($dataProvider as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->cnmarket }}</td>
                                    <td>{{ $item->commarket }}</td>
                                    <td>{{ $item->cn2com }}</td>
                                    <td>{{ $item->com2cn }}</td>
                                    <td>{{ $item->cn_capital }}</td>
                                    <td>{{ $item->cn_btc }}</td>
                                    <td>{{ $item->com_capital }}</td>
                                    <td>{{ $item->com_btc }}</td>
                                    <td>{{ is_null( $item->create_at )? "" : date("Y-m-d H:i", $item->create_at) }}</td>

                                    <td class="button-column">
                                        <a class="view" data-title="查看" title="" data-toggle="tooltip" href="{{ route('arb.show' , ['id'=> $item->id] )}} " data-original-title="查看"><span class="glyphicon glyphicon-eye-open"></span></a>
                                        <a class="update" data-title="更新" title="" data-toggle="tooltip" href="{{route('arb.edit', ['id'=> $item->id] )}} " data-original-title="更新"><span class="glyphicon glyphicon-pencil"></span></a>
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