<?php
    class Home extends Ahlu_post{
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
			
			$this->template->assign("cls","index");
			
			
            $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}",array("WP_enable"=>$this->enWP,"post"=>$this->post),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);
        }
        
        //default
        public function post($id){
			
		   $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$this->post),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);
        }

        public function category($id){
           
        }
		public function contact_us($id){
		
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$this->post),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);
        }
		
		public function about_us($id){
		
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
		
		
		public function cart(){
					
		 if(substr($_SERVER['REQUEST_URI'],5,21)=='cart.html?remove_item')
		   {
			
		   
		   }
		 else if(substr($_SERVER['REQUEST_URI'],5,19)=='cart.html?undo_item')
		   {
			   
		   } else{
			  
			//$this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));
			
		    //$this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$this->post),true));         
		   
		   //$this->template->assign("content",$this->loader->view(TEMPLATEPATH ."/woocommerce/cart/cart.php",null,true));         

		   //$this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));				

           //$this->template->render(FALSE);			

		   }
        }
		
		
		
		public function newsletter()
		{	
			if(isset($_POST["ahlu"])){
				$ahlu = json_decode(stripslashes(urldecode($_POST["ahlu"])));
				global $wpdb;
				$results = $wpdb->get_results("select * from ".WP_eemail_TABLE_SUB." where eemail_email_sub='" . trim($ahlu->email). "'", OBJECT );
				
				if(is_array($results) && count($results) ){
					echo json_encode(array("d"=>array("data"=>null,"code"=>0,"error"=>'Email(s) are already in our database')));
				}else{
					//insert
					$wpdb->insert( 
						WP_eemail_TABLE_SUB, 
						array('eemail_name_sub'=>'No Name','eemail_email_sub'=>$ahlu->email, 'eemail_status_sub'=>'CON', 'eemail_date_sub'=>date('Y-m-d G:i:s'))
					);
					echo json_encode(array("d"=>array("data"=>null,"error"=>'',"code"=>1)));
				}
			
			}
		}
		
		
		
		
		
    }
?>