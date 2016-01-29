#框架说明文档：


###一、启动
	1、访问 index.php
	
	2、url说明：
		（1）http://shop.tongpai.tv/?index-index-name-lijinhuan.html
		（2）http://shop.tongpai.tv/?action=index&do=index&name=lijinhuan
 		上面的请求是一样的，表示请求Index控制器下的Index方法，并且带上参数name
 		控制器对应的文件是/controllers/IndexController.class.php
 		
	3、index.php DEBUG 生产环境请改为0，开发环境为1或者2
	
	4、项目案例：https://github.com/lijinhuan/backend，可参考此代码进行开发，该案例暂缺少缓存使用，
	   可以看本文后面文档说明如何使用缓存。

	
###二、控制器
	1、在controllers下，如 IndexController.class.php 表示Index控制器。
	  同理，TestController.class.php 表示Test控制器。

	2、IndexController.class.php 定义方法 actionIndex() 表示Index方法。
	   同理，actionTest 表示Test方法。
```php
 	<?php
		class IndexController extends BaseController {
			public function actionIndex(){
				echo '<h2>windphp framework hello world！</h2>';
			}
		}
	?>
```
        
        
###三、模型
	1、在控制器和模型中我们可以这样子调用模型：
	   $this->bbs_thread->fetchOne(array('where'=>array('tid'=>1)));
	   表示获取tid为1的数据

	2、$this->bbs_thread->fetchOne()，‘bbs_thread’ 说明，bbs表示bbs这台数据库实例，
	   它需要对应配置文件里面的配置。如下
	    'db' => array(
			'bbs' => array(
				'type' => 'mysqli',
 				'host'	=> 'localhost:3306',
 				'username'	=> 'root',
 				'password'	=> '123456',
 				'database'	=> 'bbs',
 				'_charset'	=> 'utf8',
			)
            ),
            那么bbs就取自这里的'bbs' => array() 定义
            ‘bbs_thread’ 里面的thread，当models目录下没有对应的BbsThreadModel.class.php文件时，
            thread就表示bbs数据库里面的一张表。如果有BbsThreadModel.class.php文件时，它就表示
            这里的Thread，命名的一种规则。至于操作哪张表由文件里面的$this->table 表示

```php
	   <?php
		   if(!defined('FRAMEWORK_PATH')) {exit('access error !');}
		   class BbsThreadModel extends DbModel {
				public function __construct($conf){
					parent::__construct($conf);
					$this->table = 'thread'; //表
				}
		   }
	   ?>
```	   
	   
	   
###四、数据库操作
#####1、查询：
	（1）普通查询， 在模型中 
                $this->bbs_thread->fetchAll(array(
				'where' => array('fid'=>2),
				'select' => 'id',
				'order' => 'sort desc',
				'limit' => '1,30',
		 ));
		 $this->bbs_thread->fetchOne(array('tid'=>2));
		 说明：fetchOne表示只获取一条，fetchAll表示获取所有结果集
		 
	（2）in 条件查询，'where' => array('fid'=>array('in'=>array(3,4))) 查找fid为3，4的板块
	
	（3）<  条件查询，'where' => array('fid'=>array('lte'=>3))
	     查找fid小于等于3的板块，其中lte表示小于等于，gte大于等于，lt小于，gt大于，neq不等于
	
	（4）like 条件查询，'where' => array('name'=>array('like'=>'a%')) 查询a开头的板块
       
#####2、删除：
	$this->bbs_thread->delete(array('where' => array('fid'=>2),'limit'=>1)); 
		
#####3、修改：
	（1）普通更新
		$this->bbs_thread->update(array(
				'where' => array('fid'=>2),
				'set' => array('name'=>'fourm2')
		 ));
	（2）统计更新
		$this->bbs_thread->update(array(
				'where' => array('fid'=>2),
				'set' => array('num'=>array('count'=>'+5'),'updatetime'=>12346,'dig'=>array('count'=>'-1'))
		 ));
		前缀+表示增加，-表示减去
		 
#####4、增加：
	（1）添加主要使用insert
		$this->bbs_thread->insert(array(
				'set' => array('name'=>'fourm2')
		 ));
		 
	（2）replace 同上$this->bbs_thread->replace()

#####5、统计：
	其实后台翻页经常需要统计条数，我们可以这样子来获取统计
	$where = array('fid'=>1);
	$this->bbs_thread->count($where);
	表示获取fid为1的帖子数量，返回一个数字

#####5、其他说明
	（1）fetchOne，fetchAll 除了支持数组形式输入之外，还支持sql操作，
	     如 $this->bbs_thread->fetchAll("select * from thread")

	（2）$this->bbs_thread->query($sql) 执行查询操作
	  
	  
###五、缓存操作
	（1）confing/conf.inc.php 配置
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
        	 
	（2）在控制或者模型中使用缓存实例
		$this->memcache_user->get('name'); 获取key为name的缓存。‘memcache_user’，
		memcache表示使用memcache缓存，user表示使用user这个memcache实例，同理，使用redis
		$this->redis_user->get('name');而使用文件缓存，直接$this->file->get('name')即可
	
	（3）缓存操作
		1、添加缓存数据，$this->memcache_user->set('name','lijinhuan',3600);
		2、获取缓存数据，$this->memcache_user->get('name');
		3、更新缓存数据，$this->memcache_user->update('name','lijinhuan',3600);
		4、删除缓存数据，$this->memcache_user->delete('name');
		5、其实redis支持更多的数据操作，具体可以看RedisCache.class.php源码
		
	  
###五、视图操作

	 1、在Index控制器的Index的方法中 $this->tpl->show() ; 表示加载views下的default主题（在conf.inc.php中配置）下的Index目录的tpl.Index.php的文件
	 2、数据渲染：$this->tpl->assign('a',$a);表示把$a表示渲染到模板中，在模板中可以{$a}这样子调用
	 3、支持原生模式
		如 include $this->tpl->getFile();则直接加载文件到控制器，在文件中可以 <?php echo $a;?>这样子使用，不需要做$this->tpl->assign('a',$a);渲染
		
		
###六、配置文件调用

	在控制或者模型中可以使用$this->conf['autokey'] 这样子调用参数
	

###七、自定义类与系统帮助类

	...
		

		 
		
	   
