<?php
if(!defined('FRAMEWORK_PATH')) {
	header("HTTP/1.1 404 Not Found");
	die;
}
/**
 *	分页
 *	
 */
 
 class showPage {

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
    public function set_admin($pagesize=20, $total, $current=false, $target='_self', $anchors='') {
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
            		$url = core::getWebUrl($this->url_write[0].'-'.$this->pvar.'-'.'1').$anchors;
            		$output =' <a href="'.$url.'" title="首页" target="'.$target.'">首页</a>'."\n";
            	}else{
            		$output = ' <a href="'.$this->file.'?'.($this->varstr).$this->pvar.'=1'.$anchors.'" title="首页" target="'.$target.'">首页</a>'."\n";
            	}
            	
            	$this->output.=$output;
            }
            if ($current>1) {
            	
            	if($this->url_write){
            		$url = core::getWebUrl($this->url_write[0].'-'.$this->pvar.'-'.($current-1)).$anchors;
            		$output='<a href="'.$url.'" title="上一页" target="'.$target.'">上一页</a>'."\n";
            	}else{
            		$output='<a href="'.$this->file.'?'.($this->varstr).$this->pvar.'='.($current-1).$anchors.'" title="上一页" target="'.$target.'">&lt;</a>'."\n";
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
                    $this->output.='<span class="cur">'.$i.'</span>'."\n";    //输出当前页数
                } else {
                	
                	if($this->url_write){
                		 $url = core::getWebUrl($this->url_write[0].'-'.$this->pvar.'-'.$i).$anchors;
                		 $output='<a href="'.$url.'" target="'.$target.'">'.$i.'</a>'."\n";//输出页数
                	}else{
                		 $output='<a href="'.$this->file.'?'.$this->varstr.$this->pvar.'='.$i.$anchors.'" target="'.$target.'">'.$i.'</a>'."\n";//输出页数
                	}
                	
                	$this->output.=$output;
                	
                   
                }
            }

           
            if ($this->tpage>$show_num && ($this->tpage-$current)>=$show_num ) {
            	if($this->url_write){
            		$url1 = core::getWebUrl($this->url_write[0].'-'.$this->pvar.'-'.($current+1)).$anchors;
            		$url2 = core::getWebUrl($this->url_write[0].'-'.$this->pvar.'-'.($this->tpage)).$anchors;
            		$this->output.='<a href="'.$url1.'" title="下一页" target="'.$target.'">下一页</a>'."\n";
                	$this->output.=' <a href="'.$url2.'" title="最后一页" target="'.$target.'">尾页</a>'."\n";
            	}else{
            		$this->output.='<a href="'.$this->file.'?'.($this->varstr).$this->pvar.'='.($current+1).$anchors.'" title="下一页" target="'.$target.'">&gt;</a>'."\n";
                	$this->output.=' <a href="'.$this->file.'?'.($this->varstr).$this->pvar.'='.($this->tpage).$anchors.'" title="最后一页" target="'.$target.'">尾页</a>'."\n";
            	} 
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
    	if (!$omit){
    		$omit = array($this->pvar);
    	}
    	
    	$path_write = '';
    	$path_write_param = '';
    	
        foreach ($_GET as $k=>$v){
        	if (!in_array($k,$omit)){
        		if(empty($v) and strpos($k, '_htm')!==false){
        			$k = str_replace('_htm', '.htm', $k);
        			$this->varstr .= $k.'&amp;';
        			$path_write = $k;
        		}else{
        			$this->varstr .= $k.'='.urlencode($v).'&amp;';
        			$path_write_param .= $k.'-'.urlencode($v);
        		}
        	} 
        }
        	
      
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
					$colum[$url_rewirte_arr[$i]] = $url_rewirte_arr[$i+1];
					
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
    public function output($return = false, $showInput=false) {
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
    		$page .= '<a href="'.$url.$this->pvar.'='.($currentPage-1).'">上一页</a>';
    	}
    	if(!empty($dataNum) and $dataNum==$perPageNum){
    		$page .= '<a href="'.$url.$this->pvar.'='.($currentPage+1).'">下一页</a>';
    	}
    	
    	return $page;
    }
    
    
 }
 ?>