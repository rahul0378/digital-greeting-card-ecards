<?php
 
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}


class _List_Table_manage_entries extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		 parent::__construct( array(
			'singular' => __( 'Entrie', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Entries', 'sp' ), //plural name of the listed records
			'ajax'   => false //We won't support Ajax for this table
                                          ) );
	  
	}
	
	/**
    * Prepare the table with different parameters, pagination, columns and table elements
    */
   function prepare_items() {
      global $wpdb, $_wp_column_headers;
      $screen = get_current_screen();

      global $wpdb;
      $table = $wpdb->prefix."ecards_entries";

      /* -- Preparing your query -- */
      $query = "SELECT * FROM `".$table."`";
      if(!isset($_GET["orderby"])){$orderby =$table.'.id';}else{$orderby =esc_html($_GET["orderby"]);}
      if(!isset($_GET["order"])){$order ='DESC';}else{$order =esc_html($_GET["order"]);}
      if(!isset($_GET["paged"])){$paged =1;}else{$paged =esc_html($_GET["paged"]);}
      /* -- Ordering parameters -- */
         //Parameters that are going to be used to order the result      
        $query.=' ORDER BY '.$orderby.' '.$order;
      /* -- Pagination parameters -- */
         //Number of elements in your table?
         
         $totalitems = $wpdb->query($query); //return the total number of affected rows
         //How many to display per page?
         $perpage = 10;
         
         $totalpages = ceil($totalitems/$perpage);
         //adjust the query to take pagination into account
         $offset=($paged-1)*$perpage;
            $query.=' LIMIT '.(int)$offset.','.(int)$perpage;

      /* -- Register the pagination -- */
      $this->set_pagination_args( array(
         "total_items" => $totalitems,
         "total_pages" => $totalpages,
         "per_page" => $perpage,
      ) );
      //The pagination links are automatically built according to those parameters

      /* -- Register the Columns -- */
      $columns = $this->get_columns();
      $hidden = array();
      $sortable = $this->get_sortable_columns();
      $this->_column_headers = array($columns, $hidden, $sortable);

      /* -- Fetch the items -- */
     return $this->items = $wpdb->get_results($query);  
}

      /**
       * Define the columns that are going to be used in the table
      * @return array $columns, the array of columns to use with the table
      */
      function get_columns() {
         return $columns= array(            
            'col_Id'=>__('ID'),
            'col_view'=>__('Image'),
            'col_from_email'=>__('From Email'),
            'col_to_email'=>__('To'),
            'col_email'=>__('Email'),
            'col_message'=>__('view'),                         
            'col_del'=>__('Delete'),   
         );
      }
      /**
       * Decide which columns to activate the sorting functionality on
      * @return array $sortable, the array of columns that can be sorted by the user
      */
      public function get_sortable_columns() { 
        global $wpdb;

         return  array(
            'col_Id'=> array( "id", true ), 
            'col_from_email'=> array( "from_email", true ),
            'col_to_email'=> array( "to_email", true ),
            'col_email'=>array( "email", true ) 
         );
      }



      /**
       * Display the rows of records in the table
      * @return string, echo the markup of the rows
      */
      function display_rows() {

         //Get the records registered in the prepare_items method
         $records = $this->items;
          
         //Get the columns registered in the get_columns and get_sortable_columns methods
         list( $columns, $hidden ) = $this->get_column_info();
         echo '<style>.fixed{table-layout: auto !important;}</style>';
         //Loop for each record
         if(!empty($records)){foreach($records as $rec){

            //Open the line
            echo '<tr id="record_'.$rec->id.'">';
            foreach ( $columns as $column_name => $column_display_name ) {
               //Style attributes for each col
               $class = "class='".$column_name." column-$column_name'";
               $style = "";
               if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
               $attributes = $class . $style;	     
               switch ( $column_name ) {	 
               case "col_Id":  echo '<td '.$attributes.'>'.$rec->id.'</td>';   break;
               case "col_view":  echo '<td '.$attributes.'><img src="'.$rec->image.'" width="100" /></a></td>';   break;
               case "col_from_email":  echo '<td '.$attributes.'>'.sanitize_email($rec->from_email).'</a></td>';   break;		
               case "col_to_email":  echo '<td '.$attributes.'>'.sanitize_email($rec->to_email).'</td>';   break; 
               case "col_email":  echo '<td '.$attributes.'>'.sanitize_email($rec->email).'</td>';   break;  
               case "col_message":  echo '<td '.$attributes.'><a class="button viewpriview" onclick="showpriv('.$rec->id.')" >'.__('View').'</a></td>';   
                echo '<div id="preview-model'.$rec->id.'" class="model fade">
                <div class="modal-content">
                  <div class="model-head">
                    <h3 class="model-title">'.__('PREVIEW ECARD AND MESSAGE').'</h3>
                    <span class="close">'.__('Close').' &times;</span>
                  </div>
                  <img class="priview-img" style="width:100%; margin-top:15px; padding:20px;" src="'.$rec->image.'" />
                  <div class="msg">'.nl2br(stripslashes( wp_kses_post($rec->message) )).'</div>
                </div>
              </div>';
                
               break;   
               case "col_edit":  echo '<td '.$attributes.'><a class="button" href="admin.php?page=Add+Card&ID='.$rec->id.'">'.__('Edit').'</a></td>';   break;                      
               case "col_del": echo '<td '.$attributes.'><a class="button" href="admin.php?page=Entries&delid='.$rec->id.'">'.__('Delete').'</a></td>'; break;
               }
            }
            //Close the line
            echo'</tr>';
         }}
      }
} // close Class
	
 


	


 

 function Entries(){
  
    echo '<h1>'.__('All Entries').'</h1>';
   ?>
   <style>
      .model{
         text-align: center;
      }
      img {
    max-width: 100%;
    box-sizing: border-box;
     }
      /*model*/
      .model{
      position: absolute;
      width: 50vw;
      height: 200px;
      display: block;
      margin-left: auto; 
      margin-right: auto; 
      top:6vh;
      left: 50%;
      transform: translate(-50%, 0);
      z-index: 9;
      
      }
      .bg-layer{
      content: "";
      position: fixed;
      background-color: #3433336b;
      width: 100%;
      top:0;
      left: 0;
      bottom: 0;
      z-index: 8;
      display: none;
      }
      .modal-content {
      position: relative;
      background-color: #fff;
      border: 1px solid #999;
      border: 1px solid rgba(0,0,0,0.2);
      border-radius: 6px;
      -webkit-box-shadow: 0 3px 9px rgb(0 0 0 / 50%);
      box-shadow: 0 3px 9px rgb(0 0 0 / 50%);
      background-clip: padding-box;
      outline: 0;
      min-width: 50%;
         
      }
      .fade {
      opacity: 0;
      display: none;
      -webkit-transition: opacity 0.15s linear;
      -o-transition: opacity 0.15s linear;
      transition: opacity 0.15s linear;
      }
      .model-head{
      padding: 15px;
      min-height: 13px;
      border-bottom: 1px solid #e5e5e5;
      }
      .model-title{
      text-align: left;
      font-family: "Helvetica Neue Regular",helvetica,arial,sans-serif;
      font-size: .75rem; 
      letter-spacing: 3px;   
      text-transform: uppercase;
      float: left;
      }
      .close{
      float: right;
      font-family: "Helvetica Neue Regular",helvetica,arial,sans-serif;
      font-size: .75rem; 
      letter-spacing: 3px;   
      text-transform: uppercase;
      cursor: pointer;
      }
   </style>
   <!-- Model preview-->
               
               
                <script>
                  function showpriv(id){
                     jQuery("#preview-model"+id+".fade").show().css('opacity', 1);
                     jQuery(".close").click(function() {
                   
                   jQuery(".bg-layer").hide();
                   jQuery(".model.fade").hide().css('opacity', 0)
                 })
                  }
                   

                  
                  
                </script>
                <?php
 

    if(isset($_GET['delid']))
    {
        global $wpdb;
        $table = $wpdb->prefix."ecards_entries";
        $where =array('id'=>esc_html($_GET['delid']));
        $wpdb->delete( $table,$where); 
        echo '<div id="message" class="updated notice is-dismissible"><p>'.__('Deleted').' !</p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">'.__('Dismiss this notice').'.</span>
        </button>
         </div>';
    }
    
    $wp_list_table = new _List_Table_manage_entries();
    $wp_list_table->prepare_items();
    $wp_list_table->display();
    
 } 
  
 