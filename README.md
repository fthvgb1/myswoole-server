# FanFramework

简单杂揉的框架，学习laravel和yii2框架部分原理用。目前只适合写点api接口。

话说event是不是 aop的一种哟，后面再研究下,
.env文件的值需要加" 双引号，因为用的parse_ini_file!==。

已完成功能：

#### DI、依赖注入
#### 简单的restful风格路由、像laravel那种映射，不过不是通过函数，而是通过数组配置
#### 引入symfony/http-foundation,
#### 引入 laravel的Eloquent
###todo
#### aop切面
#### 正则路由
#### 注解路由
#### 仿yii2生命周期中注册各种事件
#### 中间件，不过考虑用 event来实现


 ### run
 ```
php -S 127.0.0.1 index.php
