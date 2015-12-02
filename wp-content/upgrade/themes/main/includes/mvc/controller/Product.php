<?php

/**
* Category Perfume Taxanomy
* 
*/

    class Product extends Ahlu_category{

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

			$_SESSION['cart_url'] = $_SERVER['REQUEST_URI'];

			
			$cls = strtolower(__CLASS__);
		   $this->template->assign("cls","{$cls} {$cls}-{$this->onView}");
			//assign data   for content 

		   $this->ecommercial_model = Ahlu::Call("Ecommercial_woo_model"); 

			//assign data   for header 
		   $this->template->assign("meta",$this->ecommercial_model->SEO());
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));


		   //print_r($this->ecommercial_model->getMe());

        

		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}",array("WP_enable"=>$this->enWP,"category"=>$this->ecommercial_model),true));

           

		   //assign data   for footer 
		   //$this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		

		//output html

           $this->template->render(FALSE);

        }

        

        //default post detail

        public function post($id){ 

           $this->post_model = Ahlu::Call("Ecommercial_woo_item_model")->load($this->post); 
		   $this->ecommercial_model = Ahlu::Call("Ecommercial_woo_model"); 

			$this->template->assign("cls","post product-post post-id-{$this->post->ID} {$this->post->post_name}");
           //assign data   
		  
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$this->post_model,"category"=>$this->ecommercial_model),true));
		
           //$this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);

        }

		public function cart($id){ 
			$cls = strtolower(__CLASS__);
			
		   $this->post_model = Ahlu::Call("Ecommercial_item_model")->load($this->post); 
		   $this->ecommercial_model = Ahlu::Call("Ecommercial_woo_model"); 

		   
		   print_r($this->ecommercial_model->get_cart_detail());
		   
		   die();
		   
		   $post = get_page_by_title( 'Cart' );

		   $this->template->assign("cls","{$cls} cart");
           //assign data   
		  
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"post"=>$post,"woo"=>$this->ecommercial_model),true));
		
           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

           //output html
           $this->template->render(FALSE);
	    }

        //default category detail

        public function category($id){

			$_SESSION['cart_url'] = $_SERVER['REQUEST_URI'];

			
			$cls = strtolower(__CLASS__);
		   $this->template->assign("cls","{$cls} {$cls}-{$this->onView} {$this->category->slug}");
			//assign data   for content 

		   $this->ecommercial_model = Ahlu::Call("Ecommercial_woo_model",$this->category); 

			//assign data   for header 
		   $this->template->assign("meta",$this->ecommercial_model->SEO());
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",array("SEO"=>null),true));


		   //print_r($this->ecommercial_model->getMe());

        

		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"category"=>$this->ecommercial_model),true));

           

		   //assign data   for footer 

		  // $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		

		//output html

           $this->template->render(FALSE);

        }

        //default category detail

        public function search($query=null){
			error_reporting(0);
			$query = isset($_REQUEST["q"]) ? $_REQUEST["q"] : null;
			//assign data   for header 
			
           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

          
			$this->template->assign("cls","search");
			
			
		   //assign data   for content 
		   $this->ecommercial_search_model = Ahlu::Call("Ecommercial_woo_search_model",$query); 
		  
		   //print_r($this->ecommercial_model->getMe());
			
			$meta= Ahlu::Library("Ahlu_SEO");
			$meta->setTitle($this->ecommercial_search_model->name);
			$meta->setKeyword($this->ecommercial_search_model->name);
			$meta->setDescription($this->ecommercial_search_model->name);
			$meta->setCanonical(site_url(__FUNCTION__));
			$meta = $meta->Meta();
			$this->template->assign("meta",$meta);
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array("WP_enable"=>$this->enWP,"search"=>$this->ecommercial_search_model),true));

           

		   //assign data   for footer 

		   $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		

		//output html

           $this->template->render(FALSE);

        }

        //////////////////example about product : product/gas 

        public function checkout(){

		   $data = array();
		   $this->template->assign("cls","checkout");
		   
		   $meta= Ahlu::Library("Ahlu_SEO");
			$meta->setTitle("Checkout");
			$meta->setKeyword("Checkout");
			$meta->setDescription("Checkout");
			$meta->setCanonical(site_url(__FUNCTION__));
			$meta = $meta->Meta();
			
		   $this->template->assign("meta",$meta);
           //assign data   for header 

           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

		   $this->ecommercial_model = Ahlu::Call("Ecommercial_woo_model"); 
        
		   $cart = $this->ecommercial_model->cart();
		   $view = $this->onView;
		   if(count($cart)==0){
			//$view = "checkout-non";
		   }
		   $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$view}",array("WP_enable"=>$this->enWP,"cart"=>$cart),true));

           

		   //assign data   for footer 

		   //$this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

		

		//output html

           $this->template->render(FALSE);

        }

		
		
		public function sitemap(){
			$this->template->assign("meta",$this->seo->setTitle("Site map")->setKeyword("Site map")->setDescription("Site map")->Meta());
			$model = Ahlu::Call("Ecommercial_model"); 

           //assign data   

           $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));

           $this->template->assign("content",$this->loader->view("ahlu-{$this->post_type}-{$this->onView}",array(),true));

           $this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));

        

           //output html
           $this->template->render(FALSE);
		}
		
		
		 /*public function store(){
			
		
		    $this->template->assign("header",$this->loader->view($this->pathTheme."/compoment/header.php",null,true));
		   
		   
			$this->template->assign("content",$this->loader->view(TEMPLATEPATH ."/woocommerce/shop.php",null,true));
			
			$this->template->assign("footer",$this->loader->view($this->pathTheme."/compoment/footer.php",null,true));
			
			$this->template->render(FALSE);

        }*/
		
	
		
		
		
		
    }

?>