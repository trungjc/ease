<?php
    class Page extends Ahlu_post{
        public function __construct(){
          parent::__construct();

			//enable theme worpress;

            //$this->enWP =true; 

		    //call ovveride

            //$this->custom = Ahlu::Call("Custom_template");

			if(!isset($_COOKIE['NHIEM']))

				setcookie('NHIEM',$first_name,time() + (86400 * 7)); // 86400 = 1 day
			
			//because of no creating Post type, we will use post type in specific plugin

			//so we must defined
			$this->class = strtolower(get_class($this));	
			$this->post_type = strtolower($this->class);
			$this->prefix_type="";

			////
			//track url root
            $_SESSION["track__url"]  = $this->post_type.".html";
			
			//use template 
			$this->enableTemplate();
		
        }
        
        public function index(){
			
            $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}",array("WP_enable"=>$this->enWP,"post"=>$this->post),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);
        }
        
        //default
        public function post($id){
		   $cls = strtolower(__CLASS__);
		   $this->template->assign("cls","{$cls} {$this->post->post_name} {$this->post->post_name}-{$cls} {$cls}-{$this->post->ID}");
           
		   
		   $this->loadPost($id);
			
		   $this->template->assign("meta",$this->post->SEO());
		   $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$this->post),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);
        }
		
								
		
		
		 public function search($q=null){
			
			if($q!=null){
				
				
				$q = isset($_REQUEST["s"]) ? $_REQUEST["s"] : null;
			
				
			$this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));
	
		   //assign data   for content 
		   $this->blog_model = Ahlu::Call("Blog_model")->load(); 
		 
		   
		   $this->template->assign("meta",$this->blog_model->SEO());
		
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"title"=>ucfirst($this->class)."s","query"=>$q,"category"=>$this->blog_model,"data"=>$this->blog_model->searchPostType($q,10,URI::getInstance()->page)),true));
		
		   //assign data for footer 
		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		//output html
           $this->template->render(FALSE);
		   }
        }
		
		public function contact_us($id){

		   $cls = strtolower(__CLASS__);
		   $this->onView = "contact-us";
		   $this->template->assign("cls","{$cls} {$this->post->post_name} {$cls}-{$this->post->ID} light-background");
		   
		   $this->loadPost($id);
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$this->post),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);
        }
		public function food($id){

		   $cls = strtolower(__CLASS__);

		   $this->template->assign("cls","{$cls} {$this->post->post_name}-{$cls} {$cls}-{$this->post->ID} dark-background");
		   
		   $this->loadPost($id);
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$this->post),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);
        }
		public function about_us($id){
			
		   $cls = strtolower(__CLASS__);
		   
		   $this->template->assign("cls","{$cls} {$this->post->post_name} {$this->post->post_name}-{$cls} {$cls}-{$this->post->ID} light-background");

		   $this->loadPost($id);
		   
		   $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$this->post),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);
        }
		
		public function faq($id){
		
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$this->post),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);
        }
		
		
		
    }
?>