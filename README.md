#框架说明文档：

###一、说明
	 windphp是一个轻量级的，高效的，安全的，组件化的php框架，它具有以下特性：
	（1）mvc模式，模型，视图，控制器分离
	（2）分层设计，用户请求==》控制器==》service服务层==》dao数据访问层 , 控制器获取到数据后==》视图
	（3）使用命名空间特性，框架可以自动加载组件，无需到处include
    （4）支持url rewrite，有利于seo
    （5）到控制器echo输出，性能不亚于yaf，接近原生类实例化
    （6）项目目录自动创建，减少手动创建目录文件烦恼，只需第一次访问加载框架即可自动创建
    （7）支持多个数据库分别操作，多个缓存实例分别操作
    （8）内置模板解析引擎，可以使用原生php语法也支持模板分离
    （9）支持swoole的http，tcp，webSocket服务创建，同时可以方便使用windphp提供的框架服务，后续会引入异步io操作，多进程，协程等
    （10）debug，exception，日志，翻页，http请求等常用开发组件支持
    （11）简单方便的魔法操作，使用该框架，你会发现各种操作真的很简单
	（12）安全，对各种输入进行了处理，目录安全隔离等
	

###二、创建web应用

	1、虚拟主机环境
	   （1）下载Windphp，放到虚拟主机web目录
	   （2）在web目录建立index.php，内容如下：
```php
 	<?php
		use Windphp\Windphp;
		$root_path = __DIR__ . DIRECTORY_SEPARATOR;
		require  $root_path.'Windphp/Windphp.php';
		Windphp::createWebApplication($root_path);
?>
```		
	   （3）访问index.php ，框架会在web目录下生成项目所需的目录
	   （4）这种方式就是框架和应用文件都放在了web目录，用户可以请求到web里面的文件，特别需要注意的是这种模式下线上环境不要
			开启sql日志存储，防止黑客请求利用，或者通过服务器配置禁止访问日志目录
	   
	   
	2、服务器环境（云，虚拟主机，本机等）
	   （1）下载Windphp，放到虚拟主机web目录
	   （2）参考demo的helloWorld，把域名绑定到webroot目录
	   （3）访问index.php ，框架会在web目录下生成项目所需的目录
	   （4）这样做的好处是，用户只能访问webroot目录的文件，不能访问框架和其应用文件，提高安全性，其实就是做安全隔离
	   
	3、应用目录说明
	  （1）components 第三方组件目录，例如短信通知，支付，七牛文件上传等
	  （2）config 文件配置目录
	  （3）controllers 控制器目录
	  （4）daos 数据访问层目录
	  （5）service 服务层目录
	  （6）runtimes 程序运行时产生的数据，例如日志，模板缓存，文件缓存等
	  （7）views 模板文件目录
	   
	2、url说明：
		（1）http://www.test.com/?index-index-name-lijinhuan.html
		（2）http://www.test.com/?controller=index&action=index&name=lijinhuan
 		上面的请求是一样的，表示请求Index控制器下的Index方法，并且带上参数name
 		控制器对应的文件是/controllers/IndexController.php
	
	
###三、创建控制台命令行执行应用
	1、查看demo的cliApp
	2、在命令行下执行，如/usr/local/php cli.php  CliIndex  Index  lijinhuan
	3、表示访问CliIndex控制器，Index方法，参数lijinhuan
	4、控制器对应的文件是/controllers/CliIndexController.php，注意控制器继承的是CliController控制器，
	5、具体可以参考demo/test/controllers/CliIndexController.php
	
	
###四、创建swoole应用
	1、swoole的并发能力很强，具体可以到其官网查看。
	
	2、查看demo的swoole
	
	3、如果创建的是http服务，执行：/usr/local/php swoole.php  Http
	   默认使用的端口是9501，可以在配置文件里面修改，然后访问http://www.test.com:9501/?controller=index&action=index&name=lijinhuan，
	   表示访问SwooleHttpIndexController控制器，Index方法，参数name值为lijinhuan，
	   控制器对应的文件是/controllers/SwooleHttpIndexController.php，注意控制器继承的是SwooleController控制器，
	   具体可以参考demo/test/controllers/SwooleHttpIndexController.php
	
	4、如果创建的是websocket服务，执行：/usr/local/php swoole.php  WebSocket
	   默认使用的端口是9502，可以在配置文件里面修改，然后使用html5的websocket测试请求连接http://www.test.com:9502，
	   默认表示访问SwooleWebSocketIndexController控制器，Index方法，WebSocket请求参数和其他不一一样，建立连接后，
	   使用json格式,发送{"controller","test"},表示访问test控制器
	   控制器对应的文件是/controllers/SwooleWebSocketIndexController.php，注意控制器继承的是SwooleController控制器，
	   具体可以参考demo/test/controllers/SwooleWebSocketIndexController.php
	   
	5、如果创建的是tcp服务，执行：/usr/local/php swoole.php  Tcp
	   默认使用的端口是9503，可以在配置文件里面修改，然后使用telnet  测试连接服务器 9502端口，
	   默认表示访问SwooleTcpIndexController控制器，Index方法，tpc请求参数和其他不一致，使用json格式,发送{"controller","test"},表示访问test控制器
	   控制器对应的文件是/controllers/SwooleTcpIndexController.php，注意控制器继承的是SwooleController控制器，
	   具体可以参考demo/test/controllers/SwooleTcpIndexController.php
	
	
###五、控制器说明
	1、在controllers下，如 IndexController.class.php 表示Index控制器。
	  同理，TestController.class.php 表示Test控制器。

	2、IndexController.class.php 定义方法 actionIndex() 表示Index方法。
	   同理，actionTest 表示Test方法。
```php
 	<?php
	namespace Controllers;
	use Windphp\Controller\CController;
	class IndexController extends CController {
			public function actionIndex(){
				echo '<h2>windphp framework hello world！</h2>';
			}
	}
	?>
```
    3、注意不同应用例如swoole和cli继承的控制器不一样，这里以php-fpm+nginx为例  
	4、参考demo/test
   

###六、服务层
	1、在控制器我们可以这样子调用服务，非常方便；
	   $this->userService->getUserInfo($uid);
	   表示访问services目录下的userService.php的userService类，所有服务类需要继承IService
	   
	2、参考demo/test
   
   
###七、dao层
	1、在服务层中我们可以这样子调用dao：
	   $this->userDao->fetchOne(array('where'=>array('uid'=>1)));
	   表示获取uid为1的用户数据
	   表示访问daos目录下的userDao.php的userDao类，所有dao类需要继承IDao类

	2、userDao里面的初始化
```
<?php
/**
 * Copyright (C) windphp framework
 * @todo ShopDao
 */
namespace Daos;


use Windphp\Dao\IDao;
class ShopDao extends IDao {
		
	
	public function init(){
		$this->database = 'user';
		$this->table = 'user';
	}
	
		
}
?>
```
	3、其实$this->database = 'user';表示访问user这个数据库
	4、其实$this->table = 'user';表示访问user数据库的user表
	5、其中数据库配置，在配置文件内如下
	
```php
	    'db' => array(
			'user' => array(
				'type' => 'mysqli',
 				'host'	=> 'localhost:3306',
 				'username'	=> 'root',
 				'password'	=> '123456',
 				'database'	=> 'user',
 				'_charset'	=> 'utf8',
			)
            ),
```
	  那么user就取自这里的'user' => array('type'=>...)的user属性
	  


	   
	   
###八、dao数据库操作
#####1、查询：
	（1）普通查询
```php
        $this->userDao->fetchAll(array(
				'where' => array('uid'=>2),
				'select' => 'id',
				'order' => 'sort desc',
				'limit' => '1,30',
		 ));
```
		 $this->userDao->fetchOne(array('uid'=>2));
		 说明：fetchOne表示只获取一条，fetchAll表示获取所有结果集
		 
	（2）in 条件查询，'where' => array('uid'=>array('in'=>array(3,4))) 查找uid为3，4的用户
	
	（3）<  条件查询，'where' => array('uid'=>array('lte'=>3))
	     查找uid小于等于3的用户，其中lte表示小于等于，gte大于等于，lt小于，gt大于，neq不等于
		
	
	（4）like 条件查询，'where' => array('name'=>array('like'=>'a%')) 查询a开头的用户
       
#####2、删除：
```php
	$this->userDao->delete(array('where' => array('uid'=>2),'limit'=>1)); 
```
		
#####3、修改：
	（1）普通更新
```php
		$this->userDao->update(array(
				'where' => array('uid'=>2),
				'set' => array('name'=>'fourm2')
		 ));
```

	（2）统计更新
```php
		$this->userDao->update(array(
				'where' => array('fid'=>2),
				'set' => array('num'=>array('count'=>'+5'),'updatetime'=>12346,'dig'=>array('count'=>'-1'))
		 ));
```
		前缀+表示增加，-表示减去
		 
#####4、增加：
	（1）添加主要使用insert
```php
		$this->userDao->insert(array(
				'set' => array('name'=>'fourm2')
		 ));
```

	（2）replace 同上$this->userDao->replace()

#####5、统计：
	其实后台翻页经常需要统计条数，我们可以这样子来获取统计
	$where = array('uid'=>1);
	$this->userDao->count($where);
	表示获取uid为1的用户数量，返回一个数字

#####5、其他说明
	（1）fetchOne，fetchAll 除了支持数组形式输入之外，还支持sql操作，
	     如 $this->userDao->fetchAll($sql)

	（2）$this->userDao->query($sql) 执行查询操作
	  
	  
###五、缓存操作
	（1）confing/conf.inc.php 配置
```php
		'memd'=> array(
        			'user' => array(
        					'servers'=>array(
        							'host'=>'127.0.0.1',
        							'port'=>11211,
        							'height'=>75,
        							'auth' => array(
        									//'user' => 'test',
        									//'password'=>'test',
        							),
        					)
        			),
        	 		'other' => array(
        	 				'servers'=>array(
        	 						'host'=>'127.0.0.1',
        	 						'port'=>11213,
        	 						'height'=>75,
        	 						'auth' => array(
        	 								//'user' => 'test',
        	 								//'password'=>'test',
        	 						),
        	 				)
        	 		),
        	 ),	
        	 'redis' => array(
        	 		'user' => array(
        	 				'servers'=>array(
        	 						'host'=>'127.0.0.1',
        	 						'port'=>6379,
        	 						'timeout'=>5,
        	 						'auth' => array(
        	 								//'user' => 'test',
        	 								//'password'=>'test',
        	 						),
        	 				)
        	 		),
        	 		'other' => array(
        	 				'servers'=>array(
        	 						'host'=>'127.0.0.1',
        	 						'port'=>6381,
        	 						'timeout'=>5,
        	 						'auth' => array(
        	 								//'user' => 'test',
        	 								//'password'=>'test',
        	 						),
        	 				)
        	 		),
        	 ),
```

	（2）在控制或者模型中使用缓存实例
		$this->userMemcache->get('name'); 获取key为name的缓存。‘userMemcache’，
		memcache表示使用memcache缓存，user表示使用user这个memcache实例，同理，使用redis
		$this->userRedis->get('name');
		默认缓存:$this->cache->get('name'),cache_type配置哪种类型，就使用哪种，redis会默认使用第一个实例，memcache支持分布式
		
	
	（3）缓存操作
		1、添加缓存数据，$this->cache->set('name','lijinhuan',3600);
		2、获取缓存数据，$this->cache->get('name');
		3、更新缓存数据，$this->cache->update('name','lijinhuan',3600);
		4、删除缓存数据，$this->cache->delete('name');
		5、其实redis支持更多的数据操作，具体可以看Windphp/Cache/RedisCache.php源码
		
	  
###五、视图操作
	1、在Index控制器的Index的方法中添加代码 $this->tpl->show() ; 
	   表示加载views下的default主题（在conf.inc.php中配置）下的Index目录的tpl.Index.php的文件
	   
	2、把控制器获取到的数据传到模板中使用，如 $this->tpl->assign('a',$a);
	   表示把$a表示渲染到模板中，在模板中可以{$a}这样子调用
	 
	3、支持原生模式，include $this->tpl->getFile();
	   则表示直接加载文件到控制器，在文件中可以 <?php echo $a;?>这样子使用，
	   而不需要做$this->tpl->assign('a',$a);渲染。
	
	4、模板语法：在模板文件中，如tpl.Index.php，如果我们是通过 $this->tpl->show() ;这种方式进渲染的，
	   那么使用控制器传过来的变量，就需要使用到模板语法进行调用了。
	   （1）默认使用{}大括号来进行标明是模板语句，如{$a}表示输出$a变量。这个符号可以在
	        confing/conf.inc.php文件中进行配置，添加'tpl_tag' => array('left'=>"\<\!\-\-\{",'right'=>"\}\-\-\>"),
	        则表示使用<!--{}-->来标明模板语句，<!--{$a}--> 表示输出$a变量，推荐使用这种方式(也是默认使用的)，避免与javascript的冲突
	      
	   （2）遍历数组
	   	<!--{loop $user $k $v}-->
	   		<li>第<!--{$k}-->个用户，名字叫<!--{$v['name']}--></li>
	   	<!--{/loop}-->
	   	
	   (3)判断语句
	   	<!--{if $a==1}-->
	   	    我等于1
	   	<!--{elseif $a==2}-->
	   	    我等于2
	   	<!--{else}-->
	   	    我什么都不是
	   	<!--{if}-->
		
	  （4）加载模板文件
	  	<!--{template 'Header','Common'}-->
	  	表示加载 /views/default/Common/tpl.Header.php 文件
	  	
	  （5）执行php代码
	  	<!--{php echo $a;}-->
	  	
	  （6）执行一个函数
	  	<!--{func phpinfo()}-->
	  	
	  （7）输出一个函数的返回值
	  	<!--{funcecho date('Y-m-d')}-->
	  	
	  （8）循环
	  	<!--{for $i=0;$i<10;$i++}-->
	  		<!--{$i}-->
		<!--{/for}-->
	
	  （9）读取配置文件中的参数
	  	<!--{$system_conf['app_url']}-->
		
		
###六、配置文件调用
	1、在控制或者模型中可以使用Conf::getSystem('autokey') 这样子调用参数
	2、又或者Conf::$systemConfig['autokey']这样子调用
	

###七、第三方组件
	1、自己写的或者第三方的扩展组件，需要放在components目录下，命名格式为MyComponet.php,My是扩展组件的类名称 
	   继承 IComponent 类 如
```php
namespace components;


use Windphp\Component\IComponent;
class MyComponet extends IComponent  {
		
		public function test(){
			echo 'aa';
		}
		
}
```
	
		

		 
		
	   
