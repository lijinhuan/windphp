<?php
/**
 * @todo 翻页类
 * @author jinhuan.li
 * @link https://github.com/lijinhuan/windphp
 * @version 2.0
 * @copyright	(c) 2016 Windphp Framework
 * @date 2016-09-02
 */

namespace Windphp\Misc;


 
 use Windphp\Core\Config;
use Windphp\Web\Request;
		class ShowPage {
	public $show_go = true;
 	
 	private $url_write = array();
 	
    /**
     * 页面输出结果
     *
     * @private string
     */
    private $output;

    /**
     * 使用该类的文件,默认为 PHP_SELF
     *
     * @private string
     */
    private $file;

    /**
     * 页数传递变量，默认为 'page'
     *
     * @private string
     */
    private $pvar = "page";

    /**
     * 页面大小
     *
     * @private integer
     */
    private $psize;

    /**
     * 当前页面
     *
     * @private ingeger
     */
    private $curr;

    /**
     * 要传递的变量数组
     *
     * @private array
     */
    private $varstr = '';

    /**
     * 总页数
     *
     * @private integer
     */
    private $tpage;

	private $inputPageNum;
	
	public $showNum = 8;
	
	/**
	   * @name 构造函数
	   * @param 
	   * @param 
	   * @access 
	   * @todo 设置地址栏传递参数
	   * @return 
	   */
	public function __construct(){
		
	}
        
        /**
	   * @name setShowNum
	   * @param 
	   * @param 
	   * @access 
	   * @todo 设置展示页数
	   * @return 
	   */
	public function setShowNum($num=8){
		$this->showNum = $num;
	}
	
    
    
    /**
     * 分页设置
     *
     * @access public
     * @param int $pagesize 页面大小
     * @param int $total    总记录数
     * @param int $current  当前页数，默认会自动读取
     * @param string $anchors 锚点
     * @return void
     */
    public function setAdmin($pagesize=20, $total, $current=false, $target='_self', $anchors='') {
        $show_num = $this->showNum;	//显示几个翻页按钮
        $this->tpage = ceil($total/$pagesize);
        if (!$current) {$current = $_GET[$this->pvar];}
        if ($current>$this->tpage) {$current = $this->tpage;}
        if ($current<1) {$current = 1;}
        $this->curr  = $current;
        $this->psize = $pagesize;
	    $this->output = '';
        if ($this->tpage >= 1) {
            if ($current>$show_num) {
            	if($this->url_write){
            		$url = Core::getWebUrl($this->url_write[0].'-'.$this->pvar.'-'.'1').$anchors;
            		$output =' <li><a href="'.$url.'" title="首页" target="'.$target.'">首页</a></li>'."\n";
            	}else{
            		$output = ' <li><a href="'.$this->file.'?'.($this->varstr).$this->pvar.'=1'.$anchors.'" title="首页" target="'.$target.'">首页</a></li>'."\n";
            	}
            	
            	$this->output.=$output;
            }
            if ($current>1) {
            	
            	if($this->url_write){
            		$url = Core::getWebUrl($this->url_write[0].'-'.$this->pvar.'-'.($current-1)).$anchors;
            		$output='<li><a href="'.$url.'" title="上一页" target="'.$target.'">上一页</a></li>'."\n";
            	}else{
            		$output='<li><a href="'.$this->file.'?'.($this->varstr).$this->pvar.'='.($current-1).$anchors.'" title="上一页" target="'.$target.'">&lt;</a></li>'."\n";
            	}
            	 
            	$this->output.=$output;
            	
                
            }

	   		 $start  = $current-floor($show_num/2);
            if($start<1){ 
                $end  = $show_num; 
            }else{
                $end    = $current+ceil($show_num/2);
            }

            if ($start<1)            {$start=1;}
            if ($end>$this->tpage)    {$end=$this->tpage;}

            for ($i=$start; $i<=$end; $i++) {
                if ($current==$i) {
                    $this->output.='<li><span class="thisclass">'.$i.'</span></li>'."\n";    //输出当前页数
                } else {
                	
                	if($this->url_write){
                		 $url = Core::getWebUrl($this->url_write[0].'-'.$this->pvar.'-'.$i).$anchors;
                		 $output='<li><a href="'.$url.'" target="'.$target.'">'.$i.'</a></li>'."\n";//输出页数
                	}else{
                		 $output='<li><a href="'.$this->file.'?'.$this->varstr.$this->pvar.'='.$i.$anchors.'" target="'.$target.'">'.$i.'</a></li>'."\n";//输出页数
                	}
                	
                	$this->output.=$output;
                	
                   
                }
            }

           
            if ($this->tpage>$show_num && ($this->tpage-$current)>=$show_num ) {
            	if($this->url_write){
            		$url1 = Core::getWebUrl($this->url_write[0].'-'.$this->pvar.'-'.($current+1)).$anchors;
            		$url2 = Core::getWebUrl($this->url_write[0].'-'.$this->pvar.'-'.($this->tpage)).$anchors;
            		$this->output.='<li><a href="'.$url1.'" title="下一页" target="'.$target.'">下一页</a></li>'."\n";
                	$this->output.=' <li><a href="'.$url2.'" title="最后一页" target="'.$target.'">尾页</a></li>'."\n";
            	}else{
            		$this->output.='<li><a href="'.$this->file.'?'.($this->varstr).$this->pvar.'='.($current+1).$anchors.'" title="下一页" target="'.$target.'">&gt;</a></li>'."\n";
                	$this->output.=' <li><a href="'.$this->file.'?'.($this->varstr).$this->pvar.'='.($this->tpage).$anchors.'" title="最后一页" target="'.$target.'">尾页</a></li>'."\n";
            	} 
            }
            
            if($this->show_go){
            	$rand = rand();
            	$this->inputPageNum = '<li><input id="pageNumInput'.$rand.'" 
            			                  onKeypress="var osO;try{osO=window.event.keyCode}catch(e){osO=event.which;}if(osO==13){if(this.value<='.($this->tpage).'){document'.($target!='_self'?".$target":'').'.location = \''.$this->file.'?'.($this->varstr).''.$this->pvar.'='.'\'+this.value+\''.$anchors.'\';}else{ alert(\'数值太大!\');}};if (osO < 45 || osO > 57) try{event.returnValue = false;}catch(e){}" 
            			                  		type="text" value="" size="1" maxlength="5" type="text" />'."\n".
            	                        '<a href="javascript://" onclick="var objectPageNumIpt = document.getElementById(\'pageNumInput'.$rand.'\');if(objectPageNumIpt.value<='.($this->tpage).'){document'.($target!='_self'?".$target":'').'.location = \''.$this->file.'?'.($this->varstr).''.$this->pvar.'='.'\'+objectPageNumIpt.value+\''.$anchors.'\';}else{ alert(\'数值太大!\');}">跳</a>		
            	                        </li>';
            	$this->output = $this->output.$this->inputPageNum;
            }
            
        }
		return $this->output;
		
    }

    
    
    /**
       * @name 
       * @param $omit:$omit=array('page')   需要忽略的变量
       * @access 
       * @todo 设置地址栏参数传递
       * @return string eg:action=gloomy&do=find&orderby=dateline&
       * @author modify by melon @ 2008
       */
    public function setvar($omit = array()) {
    	if (!$omit){$omit = array($this->pvar);}
    	$path_write = '';
    	$path_write_param = '';
    	//print_r($_GET);
        foreach ($_GET as $k=>$v){
        	if (!in_array($k,$omit)){
        		if(empty($v) and (strpos($k, '_htm')!==false or strpos($k, '_html')!==false)){
        			if(strpos($k, '_html')!==false)$k = str_replace('_html', '.html', $k);
        			if(strpos($k, '_htm')!==false)$k = str_replace('_htm', '.htm', $k);
        			$this->varstr .= $k.'&amp;';
        			$path_write = $k;
        		}else{
        			$this->varstr .= $k.'='.urlencode($v).'&amp;';
        			if(!in_array($k, array('controller','action')))$path_write_param .= $k.'-'.urlencode($v).'-';
        		}
        	} 
        }
        $path_write_param = trim($path_write_param,'-');
      
        if($path_write){
        	$path_write = explode('.', $path_write);
        	
        	if($path_write_param){
        		$path_write[0] = $path_write[0].'-'.$path_write_param;
        	}
        	
        	$url_rewirte_arr = explode('-', $path_write[0]);
        	
        	
        	$colum = array();
        	$num = count($url_rewirte_arr);
			if($num > 2) {
				for($i=2; $i<$num; $i+=2) {
					if(isset($colum[$url_rewirte_arr[$i]])){
						unset($url_rewirte_arr[$i],$url_rewirte_arr[$i+1]);
						continue;
					}
					$colum[$url_rewirte_arr[$i]] = isset($url_rewirte_arr[$i+1])?$url_rewirte_arr[$i+1]:'';
					
					if(in_array($url_rewirte_arr[$i], $omit)){
						unset($url_rewirte_arr[$i],$url_rewirte_arr[$i+1]);
					}
				}
			}
			$path_write[0] = implode('-', $url_rewirte_arr);
        	$this->url_write = $path_write; 
        }
       
		return $this->varstr;	
		
    }
    
    
    
    /**
     * 分页结果输出
     *
     * @access public
     * @param bool $return 为真时返回一个字符串，否则直接输出，默认直接输出
     * @return string
     */
    public function outPut($return = false, $showInput=false) {
        $output = $showInput?($this->totalPage.$this->output.$this->inputPageNum):$this->output;
        if ($return) {
            return $output;
        } else {
            echo $output;
        }
    }

    
    public function getTotalPage(){
    	return $this->tpage;
    }
    
    
    public function getSmallPage($currentPage,$dataNum,$perPageNum){
    	$url = $this->file.'?'.$this->varstr;
    	$page = '';
    	if($currentPage>1){
    		$page .= '<li><a href="'.$url.$this->pvar.'='.($currentPage-1).'">上一页</a></li>';
    	}
    	if(!empty($dataNum) and $dataNum==$perPageNum){
    		$page .= '<li><a href="'.$url.$this->pvar.'='.($currentPage+1).'">下一页</a></li>';
    	}
    	
    	return $page;
    }
    
    
    /**
     * 获取翻页html
     * @param int $totalCount 总数
     * @param int $currentPage 当前第几页
     * @param number $pageRows 每页多少条数据
     * @param number $showPageNum 分页列表显示多少页
     * @param array $pagevars 
     * @return string|Ambigous <string, unknown>
     */
 	public static function getPageStr($totalCount,$currentPage,$showGo=true,$pageRows=20,$showPageNum=6,$pagevars=array('page')){
		$total_page = ceil($totalCount/$pageRows);
		if($total_page==1){return '';}
		$sp = new self();
		$sp->show_go = $showGo;
		$sp -> setShowNum($showPageNum); // 分页列表显示多少页
		$sp -> setVar($pagevars);
		$sp -> setAdmin($pageRows, $totalCount,$currentPage);
		return $sp -> outPut(true);
	}
	
	
	
	public static function getCurPage() {
		$page_var = isset(Config::$systemConfig['page_var'])?Config::getSystem('page_var') :'page';
		$page = abs(Request::getInput($page_var,'string'));
		$page =  min($page,Config::getSystem('maxpage'));
		if($page<1){$page = 1;}
		return $page;
	}
	
	
	public static  function getPageQueryLimit() {
		$limit_start = (self::getCurPage()-1)*Config::getSystem('page_rows');
		return $limit_start.','.Config::getSystem('page_rows');
	}
    
    
 }
 ?>