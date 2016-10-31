var elixir = require('laravel-elixir');

elixir(function(mix)  {
    // jQuery
    mix.copy('node_modules/jquery/dist/jquery.min.js', 'resources/assets/js/');

    // Bootstrap
    mix.copy('node_modules/bootstrap/dist/js/bootstrap.min.js', 'resources/assets/js/');
    mix.copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'resources/assets/css/');
    mix.copy('node_modules/bootstrap/dist/fonts/', 'public/assets/backend/fonts/');
    mix.copy('node_modules/bootstrap/dist/fonts/', 'public/assets/frontend/fonts/');
    mix.copy('node_modules/bootstrap/fonts/', 'public/build/assets/frontend/fonts/');

    // DataTables
    mix.copy('node_modules/datatables.net/js/jquery.dataTables.js', 'resources/assets/js/');
    mix.copy('node_modules/datatables.net-dt/css/jquery.dataTables.css', 'resources/assets/css/');
    mix.copy('node_modules/datatables.net-dt/images/', 'public/build/assets/frontend/images/');

    // AdminLTE
    mix.copy('node_modules/admin-lte/dist/img/', 'public/assets/backend/images/');
    mix.copy('node_modules/admin-lte/dist/js/app.min.js', 'resources/assets/js/adminlte.min.js');
    mix.copy('node_modules/admin-lte/dist/css/AdminLTE.min.css', 'resources/assets/css/adminlte.min.css');
    mix.copy('node_modules/admin-lte/dist/css/skins/skin-black.min.css', 'resources/assets/css/adminlte-skin.min.css');

    mix.copy('node_modules/admin-lte/plugins/*', 'public/assets/backend/plugins/');
    mix.copy("node_modules/admin-lte/plugins/select2/select2.min.css", "resources/assets/css/");
    mix.copy("node_modules/admin-lte/plugins/select2/select2.full.min.js", "resources/assets/js/");
    mix.copy("node_modules/admin-lte/plugins/daterangepicker/moment.min.js", "resources/assets/js/");
    mix.copy("node_modules/admin-lte/plugins/daterangepicker/daterangepicker.js", "resources/assets/js/");
    mix.copy("node_modules/admin-lte/plugins/daterangepicker/daterangepicker.css", "resources/assets/css/");

    // Font-Awesome
    mix.copy('node_modules/font-awesome/css/font-awesome.min.css', 'resources/assets/css/');
    mix.copy('node_modules/font-awesome/fonts/', 'public/build/assets/backend/fonts/');
    mix.copy('node_modules/font-awesome/fonts/', 'public/build/assets/frontend/fonts/');

    // Login Background Images
    mix.copy('resources/assets/images/background/', 'public/assets/backend/images/background/');

    // SweetAlter
    mix.copy("node_modules/sweetalert/dist/sweetalert.css", "resources/assets/css");
    mix.copy("node_modules/sweetalert/dist/sweetalert.min.js", "resources/assets/js");

    // DropzoneJS
    mix.copy("node_modules/dropzone/dist/min/basic.min.css","public/assets/backend/plugins/dropzone/");
    mix.copy("node_modules/dropzone/dist/min/dropzone.min.js","public/assets/backend/plugins/dropzone/");
    mix.copy("node_modules/dropzone/dist/min/dropzone.min.css","public/assets/backend/plugins/dropzone/");
    mix.copy("node_modules/dropzone/dist/min/dropzone-amd-module.min.js","public/assets/backend/plugins/dropzone/");


    // 合并后台指定文件夹的CSS样式文件
    mix.styles([
            'select2.min.css',
            'daterangepicker.css',
            'bootstrap.min.css',
            'font-awesome.min.css',
            'adminlte.min.css',
            'adminlte-skin.min.css',
            'sweetalert.css',
            'common.css'
        ],
        'public/assets/backend/css/app.min.css',
        'resources/assets/css'
    );
    // 合并前台指定文件夹的CSS样式文件
    mix.styles([
            'select2.min.css',
            'daterangepicker.css',
            'bootstrap.min.css',
            'font-awesome.min.css',
            'sweetalert.css',
            'jquery.dataTables.css',
            'common.css'
        ],
        'public/assets/frontend/css/app.min.css',
        'resources/assets/css'
    );

    // 合并后台指定文件夹的Javascript脚本文件
    mix.scripts([
            'jquery.min.js',
            'bootstrap.min.js',
            'select2.full.min.js',
            'moment.min.js',
            'daterangepicker.js',
            'adminlte.min.js',
            'sweetalert.min.js',
            'common.js'
        ],
        'public/assets/backend/js/app.min.js',
        'resources/assets/js'
    );
    // 合并前台指定文件夹的Javascript脚本文件
    mix.scripts([
            'jquery.min.js',
            'bootstrap.min.js',
            'moment.min.js',
            'daterangepicker.js',
            'select2.full.min.js',
            'sweetalert.min.js',
            'jquery.dataTables.js',
            'common.js'
        ],
        'public/assets/frontend/js/app.min.js',
        'resources/assets/js'
    );

    // 监控文件变动，自动刷新浏览器
    mix.browserSync({
        files: [
            'app/**/*',
            'public/**/*',
            'resources/views/**/*'
        ],
        port: 5000,
        proxy: '127.0.0.1:1028'
    });

    // 生成版本和缓存清除
    mix.version([
        'assets/backend/js/app.min.js',
        'assets/backend/css/app.min.css',
        'assets/frontend/js/app.min.js',
        'assets/frontend/css/app.min.css'
    ]);
});
