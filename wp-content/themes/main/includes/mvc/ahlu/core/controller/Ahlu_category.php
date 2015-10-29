<?php
   class Ahlu_category extends Ahlu_MVC{
       
       public function __construct(){
            parent::__construct();
             
        } 
		
		//////////////////////////////////////// Default
        public function post($id){
			trigger_error("Please overrived this method 'post' in your controller.");
        }
		
		public function category($id){
           trigger_error("Please overrived this method 'category' in your controller.");
        } 
        ////////////////////////////////////////// End Default
		
		protected function loadPost($id){
			$this->post = new Post_model();
			$this->post->load($id);
		}
		
		protected function loadCategory($id){
			$this->category = new Category_model();
			$this->category->load($id);
		}
    }
?>