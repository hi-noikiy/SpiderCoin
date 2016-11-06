 <nav containeroptions="1" class="navbar navbar-default" role="navigation">
    <div class="navbar-header">
        <button class="navbar-toggle" data-toggle="collapse" data-target="#yw5" type="button">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/index.php">
            <span class="glyphicon glyphicon-home"></span>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="yw5">
        <ul id="yw3" class="nav navbar-nav" role="menu">
            <li><a tabindex="-1" href="/market">市场</a></li>
            <li><a tabindex="-1" href="/dingtou">定投</a></li>
            <li><a tabindex="-1" href="/grid">网格</a></li>

        </ul>
        <ul id="yw4" class="navbar-right nav navbar-nav" role="menu">
            <li visible="1" class="pull-right">
                @if ( !empty( $userInfo['name'] ) )
                    <a tabindex="-1" href="/frontend/auth/logout"> Logout ({{ $userInfo['name']}})</a>
                @else
                    <a tabindex="-1" href="/frontend/auth/login"> Login </a>
                @endif
            </li>
        </ul>
    </div>
</nav>