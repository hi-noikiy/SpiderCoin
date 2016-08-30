## laravel --- SpiderCoin
--------

### 功能特点

- 简单易用（Simple and easy to use）
- 日志管理（Log Manager）
- 用户验证（Auth Manager）
- 用户管理（User Manager）
- 菜单管理（Menu Manager）
- 操作管理（Action Manager）
- 中英文切换（Switch in Chinese and English）
- 角色与权限管理（Roles & Permissions Manager）
- 模型视图分层，代码解耦（Repository && Presenters && Services）

### 环境要求

- PHP : 5.6
- Laravel : 5.1.*
- Composer

### 开发安装步骤（Installation）

1. git clone https://github.com/ucfyao/SpiderCoin.git
2. cd SpiderCoin
3. sudo chmod -R 777 storage/
4. sudo composer install
5. sudo npm install
6. sudo vi .env
7. gulp
8. php artisan migrate:refresh --seed
9. php artisan serve
10. gulp watch

### 更新步骤（Update）

1. git pull
2. composer update
3. composer dump-autoload --optimize


### 线上部署（ProductionRelease）

1.停掉服务

    ```
    php artisan down
    
2.从github拉代码

    ```
    git pull
    
3.编译前端文件
    
    ```
    gulp --production
    
4.清理一下
    
    ```
    php artisan clear-compiled
    php artisan optimize
    
5.上线

    ```
    php artisan up

全新的项目不用停掉再开启。

1.拉代码
    
    ```
    git clone foo.git

2.安装依赖

    ```
    composer install --optimize-autoloader --no-dev
    composer dump-autoload --optimize

3.编译前端文件

    ```
    npm install
    gulp --production

4.清理

    ```
    php artisan clear-compiled
    php artisan optimize


### 线上优化（ProductionOptimize）

    ```
    php artisan optimize            优化
    php artisan config:cache        配置缓存
    php artisan route:cache         路由缓存
    
    php artisan clear-compiled      删除
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear


### 关于（About）

后台问题请联系:

    ```
    🐧    : 906961433
    博客  : www.yaozihao.cn# SpiderCoin
