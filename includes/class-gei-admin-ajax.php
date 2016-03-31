<?php

/**
 * UOU Bulk Uploader
 *
 * Admin AJAX
 *
 * @class       Globo_ExIM_Admin_Ajax
 * @version     1.0.0
 * @since     1.0.0
 * @package     UOU Bulk Uploader/includes
 * @category    Class
 * @author      UOUApps
 */

class Globo_ExIm_Admin_Ajax {
	public $post_heading = array (
	    	"post_name",
	    	"post_title",
	    	"post_status",
	    	"post_content",
	    	"comment_status",
	    	"industry",
			"company_slogan",
			"company_logo",
			"company_cover",
			"employs",
			"company_address",
			"company_city",
			"company_country",
			"company_founded",
			"company_lattitude",
			"company_longitude",
			"legal_entity",
			"turnover",
	    );
	public $industry_heading = array(
		"name",
		"slug",
		"description"
	);
    public function __construct() {
        add_action( 'wp_ajax_globo_exim_export_companies', array( $this, 'globo_exim_export_companies' ) );
        // add_action( 'wp_ajax_globo_exim_export_companies_download', array( $this, 'globo_exim_export_companies_download' ) );
        
        add_action( 'wp_ajax_globo_exim_export_template', array( $this, 'globo_exim_export_template' ) );
        add_action( 'wp_ajax_globo_exim_export_template_download', array( $this, 'globo_exim_export_template_download' ) );

        add_action( 'wp_ajax_globo_exim_upload_csv', array( $this, 'globo_exim_upload_csv' ) );
        add_action( 'wp_ajax_globo_exim_csv_load', array( $this, 'globo_exim_csv_load' ) );
        add_action( 'wp_ajax_globo_exim_industry_import_csv', array( $this, 'globo_exim_industry_import_csv' ) );
    }


    public function globo_exim_export_template() {

    	// create new CSV instance
		$csv = new CSV();
		
		// set headings
		if( $_GET['download_type'] == 'company' ) {
			$csv->setHeading( $this->post_heading );	
		} else {
			$csv->setHeading( $this->industry_heading );
		}
		
		
		
		 
		// output the csv
		$csv->output();
		
		// clear the buffer
		$csv->clear();


		wp_die();

    }

    public function globo_exim_export_template_download() {
    	// Used for mock times
	    $date = new DateTime();
	    $ts = $date->format( 'Y-m-d H:i:s' );

	    // A name with a time stamp, to avoid duplicate filenames
	    $filename = $_POST['globo_type'] ."-$ts.csv";

	    // Tells the browser to expect a CSV file and bring up the
	    // save dialog in the browser
	    header( 'Content-Type: text/csv' );
	    header( 'Content-Disposition: attachment;filename='.$filename);
		
		

		if($_POST['data']){
		    print stripslashes_deep( $_POST['data'] );
		}

		wp_die();
    }

    public function globo_exim_industry_import_csv() {
    	//reset time to 30
		set_time_limit(0);
		
		//no more cache
		wp_suspend_cache_invalidation ( true );

		//disable term counting
		wp_defer_term_counting( true ) ;
		$post_data = $_POST;
		    	
    	$filename = $post_data['filename'];

    	require_once( GEI_DIR . '/includes/parsecsv.lib.php' );
    	$csv = new parseCSV($filename);

    	$count = 0;
    	foreach ($csv->data as $industry) {
    		
    		$pieces = explode(">", $industry['name']);

    		if( count($pieces) == 1 ) {
    			$parent_term = term_exists( $pieces[0], 'industry' );  // find parent term
    			
    			if( empty( $parent_term['term_id']) ) {
    				
					wp_insert_term(
					  $pieces[0], // the term 
					  'industry', // the taxonomy
					  array(
					    'description'=> $industry['description'],
					    'slug' => $industry['slug'],                   // what to use in the url for term archive
					    'parent'=> 0
					  )
					);
    			}

    		} elseif ( count($pieces) == 2 ) {
    			$parent_term = term_exists( $pieces[0], 'industry' );  // find parent term

    			if( empty( $parent_term['term_id'] ) ) {
    				
    				$parent_term_id = wp_insert_term($pieces[0], 'industry');
    				
					wp_insert_term(
					  $pieces[1], // the term 
					  'industry', // the taxonomy
					  array(
					    'description'=> $industry['description'],
					    'slug' => $industry['slug'],                   // what to use in the url for term archive
					    'parent'=> $parent_term_id['term_id']
					  )
					);
					
    			} else {
    				$parent_term_id = $parent_term['term_id']; // get numeric term id	

    				wp_insert_term(
					  $pieces[1], // the term 
					  'industry', // the taxonomy
					  array(
					    'description'=> $industry['description'],
					    'slug' => $industry['slug'],                   // what to use in the url for term archive
					    'parent'=> $parent_term_id
					  )
					);
    			}

    		}

    		$count++;
    	}

    	echo $count . " Rows imported";

    	wp_die();
    }

    public function globo_exim_csv_load() {
    	//reset time to 30
		set_time_limit(0);
		
		//no more cache
		wp_suspend_cache_invalidation ( true );

		//disable term counting
		wp_defer_term_counting( true ) ;
		$post_data = $_POST;
		    	
    	$filename = $post_data['filename'];

    	require_once( GEI_DIR . '/includes/parsecsv.lib.php' );
    	$csv = new parseCSV($filename);
    	

    	$company_items = array(
    		array(
				'title' => 'Profile',
				'type'  => 'profile'
	    	),
	    	array(
				'title' => 'Contact',
				'type'  => 'contact'
	    	),
    	);
		
    	$count = 0;
		foreach ($csv->data as $company) {
			
			$industry_id = '';
			if( isset( $company['industry'] ) && !empty( $company['industry'] ) ){
				$parent_term = term_exists( $company['industry'], 'industry' );  // find parent term

				if( !empty($parent_term)) {
					$industry_id = $parent_term['term_id'];
				} else {
					$industry_term = wp_insert_term( $company['industry'], 'industry' );
					$industry_id = $industry_term['term_id'];
				}
			}

			$company_address = '';

			if( isset( $company['company_address'] ) && !empty( $company['company_address'] ) ){
				$company_address = $company['company_address'];
			}

			$post_title = '';
			
			if( isset( $company['post_title'] ) && !empty( $company['post_title'] ) ){
				$post_title = $company['post_title'];
			}

			$company_lattitude = '';

			if( isset( $company['company_lattitude'] ) && !empty( $company['company_lattitude'] ) ){
				$company_lattitude = $company['company_lattitude'];
			}

			$company_longitude = '';

			if( isset( $company['company_longitude'] ) && !empty( $company['company_longitude'] ) ){
				$company_longitude = $company['company_longitude'];
			}

			$company_city = '';

			if( isset( $company['company_city'] ) && !empty( $company['company_city'] ) ){
				$company_city = $company['company_city'];
			}

			$company_country = '';

			if( isset( $company['company_country'] ) && !empty( $company['company_city'] ) ){
				$company_country = $company['company_country'];
			}

			$post_content = '';

			if( isset( $company['post_content'] ) && !empty( $company['post_content'] ) ){
				$post_content = $company['post_content'];
			}

			$company_slogan = '';

			if( isset( $comapny['company_slogan'] ) && !empty( $comapny['company_slogan'] ) ){
				$company_slogan = $comapny['company_slogan'];
			}

			$company_founded = '';

			if( isset( $company['company_founded'] ) && !empty( $company['company_founded'] ) ){
				$company_founded = $company['company_founded'];
			}

			$legal_entity = '';

			if( isset( $company['legal_entity'] ) && !empty( $company['legal_entity'] ) ){
				$legal_entity = $company['legal_entity'];
			}

			$turnover = '';

			if( isset( $company['turnover'] ) && !empty( $company['turnover'] ) ){
				$turnover = $company['turnover'];
			}

			$employs = '';

			if( isset( $company['employs'] ) && !empty( $company['employs'] ) ){
				$employs = $company['employs'];
			}

			$post_status = 'draft';

			if( isset( $company['post_status'] ) && !empty( $company['post_status'] ) ){
				$post_status = $company['post_status'];
			}

			$comment_status = 'open';

			if( isset( $company['comment_status'] ) && !empty( $company['comment_status'] ) ){
				$comment_status = $company['comment_status'];
			}

			// Create new post
			$new_post = array(
				'post_title'     => $post_title,
				'post_content'   => $post_content,
				'post_status'    => $post_status,
				'post_date'      => date('Y-m-d H:i:s'),
				// 'post_author' => $company['post_author'],
				'post_type'      => 'company',
				'comment_status' => $comment_status,
	        );

	        $post_id = wp_insert_post($new_post);

			$company_logo = '';
			
			if( isset( $company['company_logo'] ) && !empty( $company['company_logo'] ) ){

				$company_logo = $this->gei_image_ulpoad( $post_id, $company['company_logo'] );
			}


			$company_cover = '';

			if( isset( $company['company_cover'] ) && !empty( $company['company_cover'] ) ){
				
				$company_cover = $this->gei_image_ulpoad( $post_id, $company['company_cover'] );

			}

			$company_for_edit = array(
				'main' => array(

					'company_name'        => $post_title,
					'industry'            => $industry_id,
					'company_address'     => $company_address,
					'company_lattitude'   => $company_lattitude,
					'company_longitude'   => $company_longitude,
					'company_city'        => $company_city,
					'company_country'     => $company_country,
					'company_description' => $post_content,
				
				
				),
				'items' => $company_items,
				'company' => array(
					'company_slogan'  => $company_slogan,
					'company_founded' => $company_founded,
					'legal_entity'    => $legal_entity,
					'turnover'        => $turnover,
					'employs'         => $employs,
					'company_logo'    => $company_logo,
					'company_cover'   => $company_cover,
				)
			);

	        if( !empty( $post_title ) ) {
	        	update_post_meta($post_id, 'company_name', $post_title);	
	        }
        	
        	if( !empty( $company_slogan ) ) {
        		update_post_meta($post_id, 'company_slogan', $company_slogan);	
        	}

        	if( !empty( $post_content ) ) {
        		update_post_meta($post_id, 'company_description', $post_content);	
        	}

        	if( !empty( $industry_id ) ){
        		update_post_meta($post_id, 'industry', $industry_id);
        		wp_set_post_terms( $post_id, $industry_id, 'industry', true );
        	}
        	
        	
        	if( !empty( $company_logo ) ) {
        		
        	
        		update_post_meta($post_id, 'company_logo', $company_logo);
        	}
        	
        	if( !empty( $company_cover ) ) {
        	
        		update_post_meta($post_id, 'company_cover', $company_cover);
        	}
        	

        	if( !empty( $employs ) ) {
        		update_post_meta($post_id, 'employs', $employs);
        	}
        	if( !empty( $company_address ) ) {
        		update_post_meta($post_id, 'company_address', $company_address);
        	}
        	
        	if( !empty( $company_city ) ) {
        		update_post_meta($post_id, 'company_city', $company_city);
        	}
        	
        	if( !empty( $company_country ) ) {
        		update_post_meta($post_id, 'company_country', $company_country);
        	}
        	
        	if( !empty( $company_founded ) ) {
        		update_post_meta($post_id, 'company_founded', $company_founded);
        	}
        	
        	if( !empty( $company_lattitude ) ) {
        		update_post_meta($post_id, 'company_lattitude', $company_lattitude);
        	}
        	
        	if( !empty( $company_longitude ) ) {
        		update_post_meta($post_id, 'company_longitude', $company_longitude);
        	}
        	
        	if( !empty( $legal_entity ) ) {
        		update_post_meta($post_id, 'legal_entity', $legal_entity);
        	}
        	
        	if( !empty( $turnover ) ) {
        		update_post_meta($post_id, 'turnover', $turnover);	
        	}
        	
        	update_post_meta($post_id, 'company_items', $company_items);
        	update_post_meta($post_id, 'company_for_edit', $company_for_edit);

			$count++;
		}



		echo $count . " Rows imported";

    	wp_die();
    }

    
    public function globo_exim_upload_csv() {


    	$upload_dir = wp_upload_dir();
    	$generate_file_name = md5(date('Y-m-d H:i:s:u'));
		$to_location = $upload_dir['basedir'] . '/globoexim/' . $generate_file_name. '.csv';

		if (@move_uploaded_file($_FILES["csvfile"]["tmp_name"], $to_location)) {
			
			echo $to_location;
		} else {
			return false;	
		}

    	die();
    }
    public function globo_exim_export_companies() {

    	// Used for mock times
	    $date = new DateTime();
	    $ts = $date->format( 'Y-m-d H:i:s' );

    	// create new CSV instance
		$csv = new CSV();
		 
		// set headings
		$csv->setHeading( $this->post_heading );
		
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'company'
		);

		$query = get_posts( $args );

		$rows = array();



		foreach ($query as $q) {

			$industry = ''; $prefix = '';
			$terms = get_the_terms( $q->ID, 'industry' );
			
			if( !empty( $terms ) ) {

				foreach( $terms as $term ) {
					
					$industry .= $prefix . $term->name;

					$prefix = '|';
					
				}	
			}
			

			$rows[] = array(
				
				$q->post_name,
				$q->post_title,
				$q->post_status,
				$q->post_content,
				$q->comment_status,
				$industry,
				get_post_meta($q->ID, "company_slogan", true),
				get_post_meta($q->ID, "company_logo", true),
				get_post_meta($q->ID, "company_cover", true),
				get_post_meta($q->ID, "employs", true),
				get_post_meta($q->ID, "company_address", true),
				get_post_meta($q->ID, "company_city", true),
				get_post_meta($q->ID, "company_country", true),
				get_post_meta($q->ID, "company_founded", true),
				get_post_meta($q->ID, "company_lattitude", true),
				get_post_meta($q->ID, "company_longitude", true),
				get_post_meta($q->ID, "legal_entity", true),
				get_post_meta($q->ID, "turnover", true),
				
			);
		}


		// add lots of data
		$csv->addData( $rows );
		 
		// output the csv
		$csv->output();
		
		// clear the buffer
		$csv->clear();


		wp_die();
    }

  //   public function globo_exim_export_companies_download() {
		// // Used for mock times
	 //    $date = new DateTime();
	 //    $ts = $date->format( 'Y-m-d H:i:s' );

	 //    // A name with a time stamp, to avoid duplicate filenames
	 //    $filename = "report-$ts.csv";

	 //    // Tells the browser to expect a CSV file and bring up the
	 //    // save dialog in the browser
	 //    header( 'Content-Type: text/csv' );
	 //    header( 'Content-Disposition: attachment;filename='.$filename);
		
		

		// if($_POST['data']){
		//     print stripslashes_deep( $_POST['data'] );
		// }

		// wp_die();
  //   }


    public function gei_image_ulpoad( $post_id, $url ) {

		$tmp = download_url( $url );
		$file_array = array();

		// Set variables for storage
		// fix file filename for query strings
		preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches);
		$file_array['name'] = basename($matches[0]);
		$file_array['tmp_name'] = $tmp;

		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';
		}

		// do the validation and storage stuff
		$id = media_handle_sideload( $file_array, $post_id );

		// If error storing permanently, unlink
		if ( is_wp_error($id) ) {
			@unlink($file_array['tmp_name']);
			return $id;
		}

		$src = wp_get_attachment_url( $id );

		return $src;
    }
    
}

new Globo_ExIm_Admin_Ajax();