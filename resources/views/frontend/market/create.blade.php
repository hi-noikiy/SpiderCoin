@extends("frontend.layout.main")
@section("content")
    <div class="row">
        <div class="col-md-12">
            <h1>创建市场key</h1>
            <div class="form">
                <form class="form-horizontal" id="aip-form" action="{{ route('market.store', ['id'=> old("id")]) }}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="Aip_currency">市场 *</label>
                        <div class="col-sm-8">
                            <span id="Aip_mid">
                                @for($i = 1;$i<=count($midData);$i++)
                                    <input class="col-sm-2 control-label" id="marker_{{$i}}" value="{{$i}}" {{ $i == old("mid") ? 'checked' : '' }} type="radio" name="mid">
                                    <label for="marker_{{$i}}">{{ $midData[$i]}}</label><br>
                                @endfor
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="marker_key">Key *</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="marker_key" value="{{old("key") }}" type="text" name="key">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="marker_secret">Secret *</label>
                        <div class=" col-sm-8">
                            <input class="form-control" id="marker_secret" value="{{ old("secret")  }}" type="text" name="secret">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="marker_desc">备注</label>
                        <div class="col-sm-8">
                            <input class="form-control" id="marker_desc" value="{{old("desc") }}" type="text" name="desc">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit"  value="创建">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection