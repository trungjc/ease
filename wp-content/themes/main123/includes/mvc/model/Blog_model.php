<?php

    /**

    *  Model Blog

    */

    class Blog_model extends Category_model
    {
         public function __construct(){
            parent::__construct();
			
			$this->post_type = "blog";
			$this->taxonomy = $this->post_type."_ahlu";
		 
            return $this; 
	
         }

	}

    ?>