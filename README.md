#框架说明文档：


###一、启动
	1、访问 index.php
	
	2、url说明：
		（1）http://shop.tongpai.tv/?index-index-name-lijinhuan.html
		（2）http://shop.tongpai.tv/?action=index&do=index&name=lijinhuan
 		上面的请求是一样的，表示请求Index控制器下的Index方法，并且带上参数name
 		
	3、index.php DEBUG 生产环境请改为0，开发环境为1或者2
	
	4、项目案例：https://github.com/lijinhuan/backend，可参考此代码进行开发，该案例暂缺少缓存使用，
	   可以看本文后面文档说明如何使用缓存。

	
###二、控制器
	1、在controllers下，如 IndexController.class.php 表示Index控制器

	2、IndexController.class.php 定义方法 actionIndex() 表示Index方法
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

	   1、在models下，如 BbsThreadModel.class.php 表示bbs数据库服务器的thread模型，
	      可以在控制器中$this->bbs_thread->fetchAll()这样子调用。当然也可以不需要
	      在models目录下建立BbsThreadModel.class.php模型类，系统会自动默认操作bbs对应数据库中的thread表。
	   
	   2、在控制器中调用模型，$this->bbs_thread->fetchOne(array('where'=>array('tid'=>1))); 表示获取tid为1的帖子
	   
	   3、模型前缀，主要用于区分操作哪一个数据库实例，在confing/conf.inc.php文件里面的db绑定，
	      如'db' => array('bbs' => array(...))，同时建立模型时前缀bbs就是这里来的，如BbsThreadModel.class.php
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
	（1）fetchOne，fetchAll 除了支持数组形式输入之外，还支持sql操作，如 $this->bbs_thread->fetchAll("select * from thread")

	（2）$this->bbs_thread->query($sql) 执行查询操作
	  
	  
###五、缓存操作

	  
###五、视图操作

	 1、在Index控制器的Index的方法中 $this->tpl->show() ; 表示加载views下的default主题（在conf.inc.php中配置）下的Index目录的tpl.Index.php的文件
	 2、数据渲染：$this->tpl->assign('a',$a);表示把$a表示渲染到模板中，在模板中可以{$a}这样子调用
	 3、支持原生模式
		如 include $this->tpl->getFile();则直接加载文件到控制器，在文件中可以 <?php echo $a;?>这样子使用，不需要做$this->tpl->assign('a',$a);渲染
		
		
###六、配置文件调用

	在控制或者模型中可以使用$this->conf['autokey'] 这样子调用参数
	

###七、自定义类与系统帮助类

	...
		

		 
		
	   
