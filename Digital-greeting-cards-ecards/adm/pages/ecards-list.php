<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}


class _List_Table_manage_users extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		 parent::__construct( array(
			'singular' => __( 'Ecard', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Ecards', 'sp' ), //plural name of the listed records
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
      $table = $wpdb->prefix."ecards";

      /* -- Preparing your query -- */
      $query = "SELECT $table.*, ".$wpdb->prefix."ecards_categories.title as category_title FROM `".$table."`";
      if(!isset($_GET["orderby"])){$orderby =$table.'.id';}else{$orderby =esc_html($_GET["orderby"]);}
      if(!isset($_GET["order"])){$order ='DESC';}else{$order =esc_html($_GET["order"]);}
      if(!isset($_GET["paged"])){$paged =1;}else{$paged =esc_html($_GET["paged"]);}
      /* -- Ordering parameters -- */
         //Parameters that are going to be used to order the result      
      $query .= 'JOIN '.$wpdb->prefix.'ecards_categories on '.$wpdb->prefix.'ecards_categories.id = '.$wpdb->prefix.'ecards.category';

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
            'col_title'=>__('Title'),
            'col_category'=>__('Category'),
            'col_view'=>__('Image'),   
            'col_edit'=>__('Edit'),          
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
            'col_Id'=> array( $wpdb->prefix."ecards.id", true ), 
            'col_title'=> array( $wpdb->prefix."ecards.title", true ),
            'col_category'=> array( $wpdb->prefix."ecards_categories.title", true )
            
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
               case "col_title":  echo '<td '.$attributes.'>'.$rec->title.'</a></td>';   break;
               case "col_category":  echo '<td '.$attributes.'>'.$rec->category_title.'</a></td>';   break;		
               case "col_view":  echo '<td '.$attributes.'><img src="'.$rec->image.'" width="100" /></td>';   break;  
               case "col_edit":  echo '<td '.$attributes.'><a class="button" href="admin.php?page=Add+Card&ID='.$rec->id.'">'.__('Edit').'</a></td>';   break;                      
               case "col_del": echo '<td '.$attributes.'><a class="button" href="admin.php?page=all-ecards&delid='.$rec->id.'">'.__('Delete').'</a></td>'; break;
               }
            }
            //Close the line
            echo'</tr>';
         }}
      }
} // close Class
	
 


	


 

 function all_ecards(){
    if(isset($_GET['tip'])){
      echo '<div id="message" class="updated notice is-dismissible"><h3>
      '.__('E-card Home page Created now create some ecards and there Categorys!').' 
       </h3>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Dismiss this notice</span>
        </button>
         </div>';
    }
    echo '<h1>'.__('All Ecards').'</h1>';
 
    echo '<a class="button" href="admin.php?page=Add+Card">'.__('Create Ecard').'</a></td>';

    if(isset($_GET['delid']))
    {
        global $wpdb;
        $table = $wpdb->prefix."ecards";
        $where =array('id'=>esc_html($_GET['delid']));
        $wpdb->delete( $table,$where); 
        echo '<div id="message" class="updated notice is-dismissible"><p>'.__('Deleted').' !</p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Dismiss this notice</span>
        </button>
         </div>';
    }
    
    $wp_list_table = new _List_Table_manage_users();
    $wp_list_table->prepare_items();
    $wp_list_table->display();
    
 } 
  
 