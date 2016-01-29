#框架说明文档：<br/>
###一、启动
	1、访问 index.php
	
	2、url说明：
		（1）http://shop.tongpai.tv/?index-index-name-lijinhuan.html
		（2）http://shop.tongpai.tv/?action=index&do=index&name=lijinhuan
 		上面的请求是一样的，表示请求Index控制器下的Index方法，并且带上参数name
 		
	3、index.php DEBUG 生产环境请改为0，开发环境为1或者2
		
		
###二、控制器

       1、在controllers下，如 IndexController.class.php 表示Index控制器
       2、IndexController.class.php 定义方法 actionIndex() 表示Index方法
	   
	    <?php
			class IndexController extends BaseController {
				public function actionIndex(){
					echo '<h2>windphp framework hello world！</h2>';
				}
			}
		?>
	   
###三、模型

	   1、在models下，如 BbsThreadModel.class.php 表示bbs数据库服务器的thread模型
	   2、在控制器中调用模型，$this->bbs_thread->fetchOne(array('where'=>array('tid'=>1))); 表示获取tid为1的帖子
	   3、模型与confing/conf.inc.php 里面的db绑定，如'db' => array('bbs' => array(...))，建立模型时bbs就是这里来的
       
	   <?php
		   if(!defined('FRAMEWORK_PATH')) {exit('access error !');}
		   class WindTieBaBbsForumModel extends DbModel {
				public function __construct($conf){
					parent::__construct($conf);
					$this->table = 'thread'; //表
				}
		   }
	   ?>
	   
	   
###四、数据库操作

	  1、查询：在模型中 $this->fetchAll(array(
				'where' => array('fid'=>2),
				'select' => 'id',
				'order' => 'sort desc',
				'limit' => '1,30',
		 ));
		 $this->fetchOne(array('tid'=>2)); 查询方法如上，表示查询一条
		 
	  2、删除：
		$this->delete(array('where' => array('fid'=>2),'limit'=>1)); 
		
	  3、修改：$this->update(array(
				'where' => array('fid'=>2),
				'set' => array('name'=>'fourm2')
		 ));
		 
	  4、增加：$this->insert(array(
				'set' => array('name'=>'fourm2')
		 ));
		 
	  主要是array() 里面的方法，支持 where，set，limit，order 等，除此外还支持count update，lg，gt，in 等操作，后续会介绍

	  
###五、视图操作

	 1、在Index控制器的Index的方法中 $this->tpl->show() ; 表示加载views下的default主题（在conf.inc.php中配置）下的Index目录的tpl.Index.php的文件
	 2、数据渲染：$this->tpl->assign('a',$a);表示把$a表示渲染到模板中，在模板中可以{$a}这样子调用
	 3、支持原生模式
		如 include $this->tpl->getFile();则直接加载文件到控制器，在文件中可以 <?php echo $a;?>这样子使用，不需要做$this->tpl->assign('a',$a);渲染
		
		
###六、配置文件调用

	在控制或者模型中可以使用$this->conf['autokey'] 这样子调用参数
	

###七、自定义类与系统帮助类

	...
		

		 
		
	   
