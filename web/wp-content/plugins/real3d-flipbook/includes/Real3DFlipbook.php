<?php

/*plugin class*/
class Real3DFlipbook {

	public $PLUGIN_VERSION;
	public $PLUGIN_DIR_URL;
	public $PLUGIN_DIR_PATH;

	// Singleton
	private static $instance = null;

	protected $custom_post_type = true;
	
	public static function get_instance() {
		if (null == self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	protected function __construct() {

		$this->PLUGIN_VERSION = REAL3D_FLIPBOOK_VERSION;
		$this->PLUGIN_DIR_URL = plugin_dir_url( REAL3D_FLIPBOOK_FILE );
		$this->PLUGIN_DIR_PATH = plugin_dir_path( REAL3D_FLIPBOOK_FILE );
		$this->add_actions();
		register_activation_hook(REAL3D_FLIPBOOK_FILE, array( $this, 'activation_hook' ) );

	}

	public function activation_hook($network_wide) {

		update_option( 'r3d_activated', true );

	}
	
	public function enqueue_scripts() {

		wp_register_script("real3d-flipbook", $this->PLUGIN_DIR_URL."js/flipbook.min.js", array('jquery', 'real3d-flipbook-iscroll'),$this->PLUGIN_VERSION);

     	wp_register_script("real3d-flipbook-book3", $this->PLUGIN_DIR_URL."js/flipbook.book3.min.js", array('real3d-flipbook'),$this->PLUGIN_VERSION);

     	wp_register_script("real3d-flipbook-bookswipe", $this->PLUGIN_DIR_URL."js/flipbook.swipe.min.js", array('real3d-flipbook'),$this->PLUGIN_VERSION);

     	wp_register_script("real3d-flipbook-iscroll", $this->PLUGIN_DIR_URL."js/iscroll.min.js", array(),$this->PLUGIN_VERSION);

     	wp_register_script("real3d-flipbook-threejs", $this->PLUGIN_DIR_URL."js/three.min.js", array(),$this->PLUGIN_VERSION);

     	wp_register_script("real3d-flipbook-webgl", $this->PLUGIN_DIR_URL."js/flipbook.webgl.min.js", array('real3d-flipbook', 'real3d-flipbook-threejs' ),$this->PLUGIN_VERSION);

     	wp_register_script("real3d-flipbook-pdfjs", $this->PLUGIN_DIR_URL."js/pdf.min.js", array(),$this->PLUGIN_VERSION);
     	wp_register_script("real3d-flipbook-pdfworkerjs", $this->PLUGIN_DIR_URL."js/pdf.worker.min.js", array(),$this->PLUGIN_VERSION);

     	wp_register_script("real3d-flipbook-pdfservice", $this->PLUGIN_DIR_URL."js/flipbook.pdfservice.min.js", array(),$this->PLUGIN_VERSION);

     	wp_register_script("real3d-flipbook-embed", $this->PLUGIN_DIR_URL."js/embed.js", array('real3d-flipbook'),$this->PLUGIN_VERSION);

     	wp_register_style( 'real3d-flipbook-style', $this->PLUGIN_DIR_URL."css/flipbook.style.css" , array(),$this->PLUGIN_VERSION);
     	wp_register_style( 'real3d-flipbook-font-awesome', $this->PLUGIN_DIR_URL."css/font-awesome.css" , array(),$this->PLUGIN_VERSION);
	}

	public function admin_enqueue_scripts($hook_suffix) {

     	wp_register_script( 'alpha-color-picker', $this->PLUGIN_DIR_URL. 'js/alpha-color-picker.js', array( 'jquery', 'wp-color-picker' ),$this->PLUGIN_VERSION, true );
		wp_register_style( 'alpha-color-picker', $this->PLUGIN_DIR_URL. 'css/alpha-color-picker.css', array( 'wp-color-picker' ), $this->PLUGIN_VERSION );

     	wp_register_script( "real3d-flipbook-admin", $this->PLUGIN_DIR_URL. "js/edit_flipbook.js", array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-resizable', 'jquery-ui-selectable', 'real3d-flipbook-pdfjs', 'alpha-color-picker', 'common', 'wp-lists', 'postbox' ),$this->PLUGIN_VERSION); 

     	wp_register_script( "real3d-flipbook-edit-post", $this->PLUGIN_DIR_URL. "js/edit_flipbook_post.js", array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-resizable', 'jquery-ui-selectable', 'real3d-flipbook-pdfjs', 'alpha-color-picker', 'common', 'wp-lists', 'postbox' ),$this->PLUGIN_VERSION); 

     	wp_register_script( "real3d-flipbook-settings", $this->PLUGIN_DIR_URL. "js/general.js", array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-resizable', 'jquery-ui-selectable', 'alpha-color-picker', 'common', 'wp-lists', 'postbox' ),$this->PLUGIN_VERSION); 

     	wp_register_script( "real3d-flipbook-flipbooks", $this->PLUGIN_DIR_URL. "js/flipbooks.js", array( 'jquery', 'common', 'wp-lists', 'postbox' ),$this->PLUGIN_VERSION); 

     	wp_register_script( "real3d-flipbook-import", $this->PLUGIN_DIR_URL. "js/import.js", array( 'jquery' ),$this->PLUGIN_VERSION); 

     	wp_register_script( "real3d-flipbook-activation", $this->PLUGIN_DIR_URL. "js/activation.js", array( 'jquery' ),$this->PLUGIN_VERSION); 

		wp_register_style( 'real3d-flipbook-admin', $this->PLUGIN_DIR_URL. "css/flipbook-admin.css",array(), $this->PLUGIN_VERSION ); 

		if( in_array($hook_suffix, array('edit.php') ) ){
	        $screen = get_current_screen();

	        if( is_object( $screen ) && 'r3d' == $screen->post_type ){

	          wp_register_style("real3d-flipbook-posts", $this->PLUGIN_DIR_URL."css/posts.css", array(),$this->PLUGIN_VERSION);
	          wp_enqueue_style('real3d-flipbook-posts');

	          wp_register_script("real3d-flipbook-posts", $this->PLUGIN_DIR_URL."js/posts.js", array(),$this->PLUGIN_VERSION);
	          wp_enqueue_script('real3d-flipbook-posts');

	        }
	    }

	    if( in_array($hook_suffix, array('edit-tags.php') ) ){
	        $screen = get_current_screen();

	        if( is_object( $screen ) && 'r3d' == $screen->post_type ){

	          wp_register_script("real3d-flipbook-categories", $this->PLUGIN_DIR_URL."js/categories.js", array(),$this->PLUGIN_VERSION);
	          wp_enqueue_script('real3d-flipbook-categories');

	        }
	    }

		
	}
	
	protected function get_translation_array() {
		return Array(
            'objectL10n' => array(
                'loading' => esc_html__('Loading...', 'r3dfb')
               
            ));
	}

	public function admin_link($links) {
		array_unshift($links, '<a href="' . get_admin_url() . 'options-general.php?page=flipbooks">Admin</a>');
		return $links;
	}

	public function init() {

		$capability = get_option("real3dflipbook_capability");
		if(!$capability) $capability = "publish_posts";

		if ( current_user_can( $capability ) ) {
			add_action('media_buttons', array($this, 'insert_flipbook_button'));
		}

		$this->enqueue_scripts();

		add_filter('widget_text', 'do_shortcode');
		add_shortcode( 'real3dflipbook', array($this, 'on_shortcode') );

		if($this->custom_post_type ){

			include_once plugin_dir_path(__FILE__) . 'post-type.php';

			if(get_option("r3d_activated")){

				delete_option("r3d_activated");

				wp_redirect( admin_url( 'admin.php?page=real3d_flipbook_activation') );
    			exit;

			}

		}
	}

	public function plugins_loaded() {
		// load_plugin_textdomain( 'r3dfb', false, dirname($this->my_plugin_basename()).'/lang/' );
	}

	protected function add_actions() {

		// add_action('plugins_loaded', array($this, 'plugins_loaded') );

		add_action('init', array($this, 'init') );
		
		// add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

		if (is_admin()) {
			include_once( plugin_dir_path(__FILE__).'plugin-admin.php' );
			add_filter("plugin_action_links_" . plugin_basename(__FILE__), array($this,"admin_link"));
			// add_action('media_buttons', array($this, 'insert_flipbook_button'));


			add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts') );
			add_action('admin_menu', array($this, "admin_menu"));

			add_action('wp_ajax_r3d_duplicate', array( $this, 'ajax_duplicate_flipbook' ));
			add_action('wp_ajax_nopriv_r3d_duplicate', array( $this, 'ajax_duplicate_flipbook' ));

			add_action('wp_ajax_r3d_delete', array( $this, 'ajax_delete_flipbook' ));
			add_action('wp_ajax_nopriv_r3d_delete', array( $this, 'ajax_delete_flipbook' ));

			add_action('wp_ajax_r3d_import', array( $this,  'ajax_import_flipbooks' ));
			add_action('wp_ajax_nopriv_r3d_import', array( $this,  'ajax_import_flipbooks' ));

			add_action('wp_ajax_r3d_get_json', array( $this,  'ajax_get_json' ));
			add_action('wp_ajax_nopriv_r3d_get_json', array( $this,  'ajax_get_json' ));

			add_action('wp_ajax_r3d_get_posts', array( $this,  'ajax_get_posts' ));
			add_action('wp_ajax_nopriv_r3d_get_posts', array( $this,  'ajax_get_posts' ));

			add_action('wp_ajax_r3d_generate_post', array( $this,  'ajax_generate_post_for_flipbook' ));
			add_action('wp_ajax_nopriv_r3d_generate_post', array( $this,  'ajax_generate_post_for_flipbook' ));

			add_action('admin_footer', array($this, 'admin_footer'), 11);

			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 100 );
			add_action( 'edit_form_after_title', [ $this, 'print_content' ] );
			add_action( 'save_post_r3d', [ $this, 'save_post_r3d' ], 10, 3 );


		}

		add_filter( 'single_template', array( $this, 'load_r3d_template' ));


	}


	public function load_r3d_template( $template ) {

	    global $post;

	    if ( 'r3d' === $post->post_type) {

	        /*
	         * This is a 'r3d' post
	         * AND a 'single r3d template' is not found on
	         * theme or child theme directories, so load it
	         * from our plugin directory.
	         */
	        return plugin_dir_path( __FILE__ ) . 'single-r3d.php';
	    }

	    return $template;
	}

	

	public function insert_flipbook_button(){     	

		global $pagenow;
		if (!in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) return;

        printf('<a href="#TB_inline?&inlineId=choose_flipbook" class="thickbox button r3d-insert-flipbook-button" title="%s"><span class="wp-media-buttons-icon" style="background:url(%simages/th.png); background-repeat: no-repeat; background-position: left bottom;"></span>%s</a>',
       		 	__( "Select flipbook to insert into post", "r3dfb" ),
				$this->PLUGIN_DIR_URL,
				__( "Real3D Flipbook", "r3dfb" )
			);

	}

	public function ajax_import_flipbooks() {

		check_ajax_referer( 'r3d_nonce', 'security' );

		$json = stripslashes($_POST['flipbooks']);

		$newFlipbooks = json_decode($json, true);

		if((string)$json != "" && is_array($newFlipbooks)){

			$real3dflipbooks_ids = get_option('real3dflipbooks_ids'); 

			foreach ($real3dflipbooks_ids as $id) {

				delete_option('real3dflipbook_'.(string)$id);
			}

			$allposts= get_posts( array('post_type'=>'r3d','numberposts'=>-1) );
			foreach ($allposts as $eachpost) {
			  wp_delete_post( $eachpost->ID, true );
			}

			$real3dflipbooks_ids = array();

			foreach ($newFlipbooks as $b) {
				$id = $b['id'];

				if($id == 'global'){
					update_option('real3dflipbook_global', $b);

				}else{
					add_option('real3dflipbook_'.(string)$id, $b);
					array_push($real3dflipbooks_ids,(string)$id);
				}

				
			}

			update_option('real3dflipbooks_ids', $real3dflipbooks_ids);
	
		}

		wp_die(); // this is required to terminate immediately and return a proper response

	}

	public function ajax_generate_post_for_flipbook() {

		check_ajax_referer( 'r3d_nonce', 'security' );

		$id = $_POST['id'];

		$book = get_option('real3dflipbook_'.$id);

		if($book){
			
			if(!isset($book['post_id']) || !get_post_status ( $book['post_id'] )){

				$args = array(
	            'post_title'=> $book["name"], 
	            'post_type'=>'r3d', 
	            
	            // 'post_content'=>'demo text',
	            'post_status'   => 'publish',
	            'meta_input' => array(
	            	'flipbook_id' => $id
	            )
	          );

			if(isset($book['date']))
				$args['post_date'] = $book['date'];


	          $postId = wp_insert_post($args);

	          $book["post_id"] = $postId;
	          //save post id to book
	          update_option('real3dflipbook_'.$id, $book);

	        }

		}

		wp_die(); // this is required to terminate immediately and return a proper response

	}

	public function ajax_get_json() {

		check_ajax_referer( 'r3d_nonce', 'security' );

		$real3dflipbooks_ids = get_option('real3dflipbooks_ids');

	    if(!$real3dflipbooks_ids){
	      $real3dflipbooks_ids = array();
	    }
	    $flipbooks = array();
	    foreach ($real3dflipbooks_ids as $id) {
	      $book = get_option('real3dflipbook_'.$id);
	      if($book) $flipbooks[$id] = $book;
	    }

		echo json_encode($flipbooks);

		wp_die(); 

	}

	public function ajax_get_posts() {

		$args = array(
		    'post_type' => 'r3d',
		    'post_status' => 'publish'
		);

		// filter by category
		if(isset($_POST['cat']) && $_POST['cat']){

			$args['tax_query'] = array(
	            array(
	                'taxonomy' => 'r3d_category',
	                'field' => 'slug',
	                'terms' => $_POST['cat'],
	            )
	        );

		}

		//search by title
		if(isset($_POST['s']) && $_POST['s']){
			$args['s'] = $_POST['s'];
		}

		if(isset($_POST['paged']) && $_POST['paged']){
			$args['paged'] = $_POST['paged'];
		}

		if(isset($_POST['posts_per_page']) && $_POST['posts_per_page']){
			$args['posts_per_page'] = $_POST['posts_per_page'];
		}

		$query = new WP_Query($args);

		$numPages = $query->max_num_pages;

		$books = array();

		while ($query->have_posts()) {
		    $query->the_post();
		    $post_id = get_the_ID();
		    $post_title = get_post_meta($post_id, 'book_title', true);
		    if(!$post_title) 
		    	$post_title = get_the_title();
		    $permalink = get_permalink($post_id);
		    // $fb = get_post_meta($post_id, 'flipbook_options', true);
		    $thumb = get_the_post_thumbnail($post_id);
		    // if(isset($fb['lightboxThumbnailUrl']))
		    // 	$thumb = $fb['lightboxThumbnailUrl'];
		    // else
		    // 	$thumb = "data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=";
		  
		  	$author = get_post_meta($post_id, 'book_author', true);

		    array_push($books, array(
		    	'thumb' => $thumb,
		    	'title' => $post_title,
		    	'link' => $permalink,
		    	'author' => $author
		    ));
		}

		$response = array(
			'books' => $books,
			'numPages' => $numPages
		);

		echo json_encode($response);

		wp_reset_postdata();

		wp_die(); // this is required to terminate immediately and return a proper response

	}


	function ajax_delete_flipbook() {

	  check_ajax_referer( 'r3d_nonce', 'security' );

	  $real3dflipbooks_ids = get_option('real3dflipbooks_ids'); 

	  $current_id = sanitize_text_field($_POST['currentId']);

	  if($current_id){
	    
	    $ids = explode(',', $current_id);
	    
	    foreach ($ids as $id) {
	      delete_option('real3dflipbook_'.(string)$id);
	    }
	    $real3dflipbooks_ids = array_diff($real3dflipbooks_ids, $ids);
	    update_option('real3dflipbooks_ids', $real3dflipbooks_ids);

	  }else{

	    foreach ($real3dflipbooks_ids as $id) {
	      delete_option('real3dflipbook_'.(string)$id);
	    }

	    delete_option('real3dflipbook_1');
	    delete_option('real3dflipbook_2');
	    delete_option('real3dflipbook_3');
	    delete_option('real3dflipbook_4');
	    delete_option('real3dflipbook_5');

	    delete_option('real3dflipbooks_ids');

	  }

	  wp_die(); // this is required to terminate immediately and return a proper response

	}

	public function ajax_duplicate_flipbook() {

		check_ajax_referer( 'r3d_nonce', 'security' );

		$real3dflipbooks_ids = get_option('real3dflipbooks_ids'); 

		$current_id = sanitize_text_field($_POST['currentId']);

		if($current_id){

			$new_id = 0;
			$highest_id = 0;

			foreach ($real3dflipbooks_ids as $id) {
				if((int)$id > $highest_id) {
					$highest_id = (int)$id;
				}
			}
			$current = get_option('real3dflipbook_'.$current_id);
			$new_id = $highest_id + 1;
			$new = $current;
			$new["id"] = $new_id;
			$new["name"] = $current["name"]." (copy)";

			$new["date"] = current_time( 'mysql' );
			if(isset($new['post_id'])) unset($new['post_id']);

			delete_option('real3dflipbook_'.(string)$new_id);
			add_option('real3dflipbook_'.(string)$new_id,$new);

			array_push($real3dflipbooks_ids,$new_id);
			update_option('real3dflipbooks_ids',$real3dflipbooks_ids);

		}

		wp_die();
	}

	public function admin_menu(){

		$capability = get_option("real3dflipbook_capability");
		if(!$capability) $capability = "publish_posts";

		add_menu_page(
			'Real3D Flipbook', 
			'Real3D Flipbook', 
			$capability, 
			'real3d_flipbook_admin',
			array($this,"admin"),
			'dashicons-book' 
		);


		if(! $this->custom_post_type){

			add_submenu_page( 
				'real3d_flipbook_admin', 
				'Flipbooks', 
				'Flipbooks',
			    $capability, 
			    'real3d_flipbook_admin',
			    array($this,"admin")
			);

			add_submenu_page( 
				'real3d_flipbook_admin', 
				'Add new', 
				'Add new',
			    $capability, 
			    'real3d_flipbook_add_new',
			    array($this,"add_new")
			);

			
		}else{

			add_submenu_page( 
				'real3d_flipbook_admin', 
				'Flipbooks', 
				'Flipbooks',
			    $capability, 
			    'edit.php?post_type=r3d'
			);

			add_submenu_page( 
				'real3d_flipbook_admin', 
				'Add new', 
				'Add new',
			    $capability, 
			    'post-new.php?post_type=r3d'
			);

			add_submenu_page( 
				'real3d_flipbook_admin', 
				'Categories', 
				'Categories',
			    $capability, 
			    'edit-tags.php?taxonomy=r3d_category&post_type=r3d'
			);

			add_submenu_page( 
				'real3d_flipbook_admin', 
				'Import / Export', 
				'Import / Export',
			    $capability, 
			    'real3d_flipbook_import',
			    array($this,"import")
			);

			add_submenu_page( 
				'', 
				'Activation', 
				'Activation',
			    $capability, 
			    'real3d_flipbook_activation',
			    array($this,"activation")
			);

			remove_submenu_page('real3d_flipbook_admin', 'real3d_flipbook_admin');

		}

		add_submenu_page(
			'real3d_flipbook_admin',
			__( 'Settings', 'r3dfb' ),
			__( 'Settings', 'r3dfb' ),
			$capability,
			'real3d_flipbook_settings',
			array($this,"settings")
		);

		

		if (function_exists('register_block_type')) {

			// // Register block, and explicitly define the attributes we accept.
			register_block_type( 'r3dfb/embed', array(
				// 'attributes' => array(
				// 	'id' => array(
				// 		'type' => 'string',
				// 	)
				// ),
				// 'render_callback' => 'slidertx_render_callback',
			) );

			add_action( 'enqueue_block_assets', array($this,'enqueue_block_assets'));
			add_action( 'enqueue_block_editor_assets', array($this,'enqueue_block_editor_assets'));

		}

		if(current_user_can($capability))

			do_action('real3d_flipbook_menu');
		
	}

    public function admin_footer() {

        global $pagenow;
        global $current_screen;

		if ( $current_screen->post_type == 'r3d' ) 
			return;

        if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) ) {

        	$real3dflipbooks_ids = get_option('real3dflipbooks_ids');
			if(!$real3dflipbooks_ids){
				$real3dflipbooks_ids = array();
			}
			$flipbooks = array();
			foreach ($real3dflipbooks_ids as $id) {

				$b = get_option('real3dflipbook_'.$id);
				if($b){
					$book = array(	
						"id" => $b['id'], 
						"name" => $b['name']
					);
					array_push($flipbooks,$book);
				}
			}

			wp_enqueue_script( 'r3dfb-insert-js', $this->PLUGIN_DIR_URL."js/insert-flipbook.js", array( 'jquery' ), $this->PLUGIN_VERSION );

			wp_enqueue_style( 'r3dfb-insert-css', $this->PLUGIN_DIR_URL."css/insert-flipbook.css",  array(), $this->PLUGIN_VERSION );
  
            ?>

            <div id="choose_flipbook" style="display: none;">
                <div id="r3d-tb-wrapper">
                	<div class="r3d-tb-inner">
                    <?php
                        if ( count( $flipbooks ) ) {
                        	?>
                         <h3 style='margin-bottom: 20px;'><?php _e("Insert Flipbook", "r3dfb"); ?></h3>
                         <select id='r3d-select-flipbook'>
                          	<option value='' selected=selected><?php _e( "Default Flipbook (Global Settings)", "r3dfb" );?></option>
                          	<?php						
                            foreach ( $flipbooks as $book ) {
                            	$id = $book['id'];
                            	$name = $book['name'];
                            ?>
                            <option value="<?php echo($id);?>"><?php echo($name);?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <?php
                        } else {
                            _e( "No flipbooks found. Create new flipbook or set flipbook source", "r3dfb" );
                        }
                        ?> 
         						
						<h3 style="margin-top: 40px;"><?php _e("Flipbook source", "r3dfb") ?></h3>
						<p><?php _e("Select PDF or images from media library, or enter PDF URL. PDF needs to be on the same domain or CORS needs to be enabled.", "r3dfb") ?></p>

						<div class="r3d-row r3d-row-pdf">
							
                        	<input type='text' class='regular-text' id='r3d-pdf-url' placeholder="PDF URL">
							<button class='button-secondary' id='r3d-select-pdf'><?php _e( "Select PDF", "r3dfb" ); ?></button>
							<button class='button-secondary' id='r3d-select-images'><?php _e( "Select images", "r3dfb" ); ?></button>
							<div class="r3d-pages"></div>
							
						</div>

						<h3 style="margin-top: 40px;"><?php _e("Flipbook settings", "r3dfb") ?></h3>

						<div class="r3d-row r3d-row-mode">
							<span class="r3d-label-wrapper"><label for="r3d-mode"><?php _E("Mode", "r3dfb") ?></label></span>
							<select id='r3d-mode' class="r3d-setting">
								<option selected="selected" value=""><?php _e("Default", "r3dfb"); ?></option>
								<option value="normal">Normal (inside div)</option>
								<option value="lightbox">Lightbox (popup)</option>
								<option value="fullscreen">Fullscreen</option>
							</select>
						</div>

						<div class="r3d-row r3d-row-thumb r3d-row-lightbox" style="display: none;">
							<span class="r3d-label-wrapper"><label for="r3d-thumb"><?php _e("Show thumbnail", "r3dfb"); ?></label></span>
							<select id='r3d-thumb' class="r3d-setting">
								<option selected="selected" value=""><?php _e("Default", "r3dfb"); ?></option>
								<option value="1">yes</option>
								<option value="">no</option>
							</select>
						</div>

						<div class="r3d-row r3d-row-class r3d-row-lightbox" style="display: none;">
							<span class="r3d-label-wrapper"><label for="r3d-class"><?php _e("CSS class", "r3dfb") ?></label></span>
							<input id="r3d-class" type="text" class="r3d-setting">
						</div>

						<div class="r3d-row r3d-row-insert">
							<button class="button button-primary button-large" disabled="disabled" id="r3d-insert-btn"><?php _e( "Insert flipbook", "r3dfb" );?></button>
						</div>

                	</div>
                </div>
            </div>

            <?php
        }
    }

	public function enqueue_block_assets(){

	}

	public function enqueue_block_editor_assets(){

		wp_enqueue_script(
			'r3dfb-block-js', // Unique handle.
			$this->PLUGIN_DIR_URL."js/blocks.js", 
			array( 'wp-editor', 'wp-blocks', 'wp-i18n', 'wp-element' ), // Dependencies, defined above.
			$this->PLUGIN_VERSION
		);

		$r3dfb_ids = get_option('real3dflipbooks_ids');

		$books = array();

		foreach ($r3dfb_ids as $id) {
	      	
	      	$fb = get_option('real3dflipbook_'.$id);
	      	$book = array();
	      	$book["id"] = $fb["id"];
	      	$book["name"] = $fb["name"];
	      	if(isset($fb["mode"]))
	      		$book["mode"] = $fb["mode"];
	      	if(isset($fb["pdfUrl"]))
	      		$book["pdfUrl"] = $fb["pdfUrl"];
	      	array_push($books, $book);

	     }

		wp_localize_script( 'r3dfb-block-js','r3dfb', array(json_encode($books) ));
		
	}

	public function admin(){

		include_once( plugin_dir_path(__FILE__).'admin-actions.php' );

    }

    public function settings(){

		include_once( plugin_dir_path(__FILE__).'general.php' );

    }

    public function import(){

		include_once( plugin_dir_path(__FILE__).'import.php' );

    }

    public function activation(){

		include_once( plugin_dir_path(__FILE__).'activation.php' );

    }


    public function add_new(){

		$_GET['action'] = "add_new";
		$this->admin();
		
	}

	public function print_content(){

		global $current_screen;
		if ( $current_screen->post_type == 'r3d' ) {
			include_once( plugin_dir_path(__FILE__).'edit-flipbook-post.php' );
		}

	}


	public function save_post_r3d($post_ID, $post, $update){

		$status = $post->post_status;

		$title = $post->post_title;

		if("auto-draft" == $status && $title){

			//clear default draft title
			wp_update_post( [
		        'ID'         => $post_ID,
		        'post_title' => ''
		    ] );
		}else if('draft' == $status || 'publish' == $status){

			$flipbook_defaults = r3dfb_getDefaults();

			$flipbook = array();

			if(isset($_POST['id'])){

				$flipbook_id = $_POST['id'];

				$flipbook['name'] = $title;
				$flipbook['post_id'] = $post_ID;

				foreach ($flipbook_defaults as $key => $val) {
					if(isset($_POST[$key])){
						$flipbook[$key] = $_POST[$key];
					}
				}

				update_post_meta($post_ID, 'flipbook_id', $flipbook_id);

				update_option('real3dflipbook_' . $flipbook_id, $flipbook);

				$real3dflipbooks_ids = get_option('real3dflipbooks_ids');
				if(!$real3dflipbooks_ids)
					$real3dflipbooks_ids = array();

				//update array of flipbooks id-s
				if(!in_array($flipbook_id, $real3dflipbooks_ids)){

					array_push($real3dflipbooks_ids, $flipbook_id);
					update_option('real3dflipbooks_ids', $real3dflipbooks_ids);

				}

			}

		}
		
	}


	public function add_meta_boxes(){

		add_meta_box( 'r3d_post_meta_box_shortcode', __( 'Shortcode', 'r3dfb' ), array( $this, 'create_meta_box_shortcode' ), 'r3d', 'side', 'high' );

	}

	public function create_meta_box_shortcode( $post ){

		global $current_screen;

		$post_id = $post->ID;

    	$id = get_post_meta($post_id, 'flipbook_id', true);
    
		$tabs   = array(
		    'normal' => __( 'Normal', 'r3dfb' ),
		    'thumb'  => __( 'Thumbnail', 'r3dfb' ),
		    'button' => __( 'Button', 'r3dfb' )
		);

		if ( $current_screen->post_type == 'r3d' ) {
		  if ( $current_screen->action == 'add' ) {
		    echo "Save Post to generate shortcode.";
		  } else {

			?>
			<code>[real3dflipbook id="<?php echo $id;?>"]</code>
			<?php
		   
		  }
		}

	}


	public function on_shortcode($atts, $content=null) {

		$args = shortcode_atts( 
			array(
				'id'   => '-1',
				'name' => '-1',
				'pdf' => '-1',
				'mode' => '-1',
				'viewmode' => '-1',
				'lightboxopened' => '-1',
				'lightboxfullscreen' => '-1',
				'lightboxtext' => '-1',
				'lightboxcssclass' => '-1',
				'class' => '-1',
				'lightboxthumbnail' => '-1',
				'lightboxthumbnailurl' => '-1',
				'hidemenu' => '-1',
				'autoplayonstart' => '-1',
				'autoplayinterval' => '-1',
				'zoom' => '-1',
				'zoomdisabled' => '-1',
				'btndownloadpdfurl' => '-1',
				'aspect' => '-1',

				'thumb' => '-1',
				'thumbcss' => '-1',
				'containercss' => '-1',

				'tilt' => '-1',
				'tiltmin' => '-1',
				'tiltmax' => '-1',
				'lights' => '-1',
				'shadows' => '-1',
				'pageroughness' => '-1',
				'pagemetalness' => '-1',
				'pagehardness' => '-1',
				'coverhardness' => '-1',

				'singlepage' => '-1',
				'startpage' => '-1',

				'deeplinkingprefix' => '-1',

				'search' => '-1',

				'loadpagesf' => '-1',
				'loadpagesb' => '-1',

				'pages' => '-1',
				'thumbs' => '-1',

				'thumbalt' => '-1',

				'category' => '-1'

			), 
			$atts
		);

		if($args['id'] == "all"){

			$output = '<div></div>';

			$real3dflipbooks_ids = get_option('real3dflipbooks_ids');

			foreach ($real3dflipbooks_ids as $id) {

				$shortcode = '[real3dflipbook id="'.$id.'" mode="lightbox"';

				if($args['thumbcss'] != -1)
					$shortcode .= ' thumbcss="'.$args['thumbcss'].'"';

				if($args['containercss'] != -1)
					$shortcode .= ' containercss="'.$args['containercss'].'"';

				$shortcode .= ']';

				$output .= do_shortcode($shortcode);

			}

			return $output;

		}

		if($args['category'] != -1){

			$output = '';

			$query_args = array(
			    'post_type' => 'r3d',
			    'post_status' => 'publish',
			    'tax_query' => array(
		            array(
		                'taxonomy' => 'r3d_category',
		                'field' => 'slug',
		                'terms' => $args['category'],
		            )
		        )
			);

			$query = new WP_Query($query_args);

			while ($query->have_posts()) {
			    $query->the_post();
			    $post_id = get_the_ID();

			    $flipbook_id = get_post_meta($post_id, 'flipbook_id', true);

			    $shortcode = '[real3dflipbook id="'.$flipbook_id.'" mode="lightbox"]';
			  	
			  	$output .= do_shortcode($shortcode);

			}

			return $output;

		}
		
		$id = (int) $args['id'];
		$name = $args['name'];

		if($name != -1){
			$real3dflipbooks_ids = get_option('real3dflipbooks_ids');
			foreach ($real3dflipbooks_ids as $id) {
				$book = get_option('real3dflipbook_'.$id);
				if($book && $book['name'] == $name){
					$flipbook = $book;
					$id = $flipbook['id'];
					break;
				}
			}
		}else if($id != -1){
			$flipbook = get_option('real3dflipbook_'.$id);
		}else{
			$flipbook = array();
			$id = '0';
		}

		$bookId = $id .'_'.uniqid();

		foreach ($args as $key => $val) {
			if($val != -1){

				if($key == 'mode') $key = 'mode';
				if($key == 'viewmode') $key = 'viewMode';
				
				if($key == 'pdf' && $val != "") $key = 'pdfUrl';
				if($key == 'btndownloadpdfurl') $key = 'btnDownloadPdfUrl';
				
				if($key == 'hidemenu') $key = 'hideMenu';
				if($key == 'autoplayonstart') $key = 'autoplayOnStart';
				if($key == 'autoplayinterval') $key = 'autoplayInterval';
				if($key == 'zoom') $key = 'zoomLevels';
				if($key == 'zoomisabled') $key = 'zoomDisabled';

				if($key == 'lightboxtext') $key = 'lightboxText';
				if($key == 'lightboxcssclass') $key = 'lightboxCssClass';
				if($key == 'class') $key = 'lightboxCssClass';
				if($key == 'lightboxthumbnailurl') $key = 'lightboxThumbnailUrl';
				if($key == 'thumbcss') $key = 'lightboxThumbnailUrlCSS';
				if($key == 'thumb') $key = 'lightboxThumbnailUrl';
				if($key == 'containercss') $key = 'lightboxContainerCSS';
				if($key == 'lightboxopened') $key = 'lightBoxOpened';
				if($key == 'lightboxfullscreen') $key = 'lightBoxFullscreen';

				if($key == 'tiltmin') $key = 'tiltMin';
				if($key == 'tiltmax') $key = 'tiltMax';
				if($key == 'pageroughness') $key = 'pageRoughness';
				if($key == 'pagemetalness') $key = 'pageMetalness';
				if($key == 'pagehardness') $key = 'pageHardness';
				if($key == 'coverhardness') $key = 'coverHardness';

				if($key == 'aspect') {
					$key = 'aspectRatio';
					$flipbook['responsiveHeight'] = 'true';
				}

				if($key == 'singlepage') $key = 'singlePageMode';

				if($key == 'startpage') $key = 'startPage';

				if($key == 'deeplinkingprefix') {
					$flipbook['deeplinkingEnabled'] = 'true';
					$flipbook['deeplinkingPrefix'] = $val;
					unset($flipbook['deeplinking']);
				}

				if($key == 'search') $key = 'searchOnStart';

				if($key == 'loadpagesf') $key = 'loadPagesF';
				if($key == 'loadpagesb') $key = 'loadPagesB';

				if($key == 'thumbalt') $key = 'thumbAlt';

		    	$flipbook[$key] = $val;
			}
		}

		if($args['pages'] != -1){
			$pages = explode(',', $args['pages']);

			if($args['thumbs'] != -1)
				$thumbs = explode(',', $args['thumbs']);

			$flipbook['pages'] = array();
			foreach ($pages as $key => $src) {
				$flipbook['pages'][$key] = array();
				$flipbook['pages'][$key]['src'] = $src;
				if($thumbs && $thumbs[$key])
					$flipbook['pages'][$key]['thumb'] = $thumbs[$key];
			}
		}

		$flipbook['rootFolder'] = $this->PLUGIN_DIR_URL;
		$flipbook['uniqueId'] = $bookId;

		$flipbook['date'] = get_the_date( 'Y-m-d', get_post($flipbook['post_id']));

		$flipbook_global_options = get_option("real3dflipbook_global", array());

		$flipbook = array_merge($flipbook_global_options, $flipbook);

		$output = '<div class="real3dflipbook" id="'.$bookId.'" style="position:absolute;" data-flipbook-options="'.htmlspecialchars(json_encode($flipbook)).'"></div>';


		if (!wp_script_is( 'real3d-flipbook', 'enqueued' )) {
	     	wp_enqueue_script("real3d-flipbook");
	     }

	     if (!wp_script_is( 'real3d-flipbook-book3', 'enqueued' )) {
	     	wp_enqueue_script("real3d-flipbook-book3");
	     }

	     if (!wp_script_is( 'real3d-flipbook-bookswipe', 'enqueued' )) {
	     	wp_enqueue_script("real3d-flipbook-bookswipe");
	     }

	     if (!wp_script_is( 'real3d-flipbook-iscroll', 'enqueued' )) {
	     	wp_enqueue_script("real3d-flipbook-iscroll");
	     }

	     if($flipbook['viewMode'] == 'webgl'){
		     if (!wp_script_is( 'real3d-flipbook-threejs', 'enqueued' )) {
		     	wp_enqueue_script("real3d-flipbook-threejs");
		     }
		     if (!wp_script_is( 'real3d-flipbook-webgl', 'enqueued' )) {
		     	wp_enqueue_script("real3d-flipbook-webgl");
		     }
	     }

	     if(isset($flipbook['pdfUrl']) && $flipbook['pdfUrl'] != -1 || isset($flipbook['type']) && $flipbook['type'] == 'pdf'){

		     if (!wp_script_is( 'real3d-flipbook-pdfjs', 'enqueued' )) {
		     	wp_enqueue_script("real3d-flipbook-pdfjs");
		     }

		     if (!wp_script_is( 'real3d-flipbook-pdfservice', 'enqueued' )) {
		     	wp_enqueue_script("real3d-flipbook-pdfservice");
		     }

	     }

	     if (!wp_script_is( 'real3d-flipbook-embed', 'enqueued' )) {
	     	wp_enqueue_script("real3d-flipbook-embed");
	     }

	     // wp_localize_script('real3d-flipbook-embed', 'real3dflipbook_'.$bookId, json_encode($flipbook));

	     if (!wp_style_is( 'real3d-flipbook-style', 'enqueued' )) {
	     	wp_enqueue_style("real3d-flipbook-style");
	     }

	     $useFontAwesome5 = !isset($flipbook_global_options['useFontAwesome5']) || $flipbook_global_options['useFontAwesome5'] == 'true';

	     if (!wp_style_is( 'real3d-flipbook-font-awesome', 'enqueued' ) && $useFontAwesome5) {
	     	wp_enqueue_style("real3d-flipbook-font-awesome");
	     }



		return $output;
	}
}

if(!function_exists("trace")){
  function trace($var){
    echo('<script type="text/javascript">console.log(' .json_encode($var). ')</script>');
  }
}

Real3DFlipbook::get_instance();