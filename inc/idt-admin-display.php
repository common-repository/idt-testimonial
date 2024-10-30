<?php
/*
Copyright (c) 2011, innovativedigitalteam.
 
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


/**
* Testimonial Admin Display class
* @since v0.1
*/

class idt_testimonial_admin
{

	public function __construct()
	{
		$this->idt_admin_main_testimonial_page();


	}

	/**
	* Testimonial Admin Display function
	* @since v0.1
	*/
	public function idt_admin_main_testimonial_page()
	{ 

  		
		$id = esc_attr($_GET['idt_id']);
		// dekete testimonial by checking url
		if ((int)$id && esc_attr($_GET['action']) == 'delete') {
			$nounce = esc_attr($_REQUEST['_wpnonce']);

			if (wp_verify_nonce( $nounce, "delete_testimonial-{$id}" )) {
				$this->idt_delete_testimonial( (int) esc_attr($_GET['idt_id']));
			} else {
				wp_redirect( 'admin.php?page=idt-testimonial');
			}
				
		} 
		// add or save testimonial 
		if (esc_attr($_POST['save_testimonial']) == 'Save Testimonial') {
			
			$nounce =  esc_attr($_POST['_wpnonce']);
			if (wp_verify_nonce( $nounce, "save_testimonial" )) {
				$this->idt_save_testimonial($_POST,(int) esc_attr($_GET['idt_id']));
			}
			
		}
		// Get display page by cheking the url of admin page
		if ( esc_attr($_GET['page']) == 'add_testimonial') {
			$this->idt_add_testimonial();
		} elseif( esc_attr($_GET['page']) == 'idt-testimonial') {
			$this->idt_testimonial_list();
		}
		
	}


	/**
	* Add/edit testmenital display function
	* @since v0.1
	*/
	public function idt_add_testimonial()
	{
		// fields list 
		$fields = $this->idt_fields_array();
		 $t_id = esc_attr($_GET['idt_id']);
		if (empty( $t_id) ) {
			?><div class="container-fluid idt_container">
			<div class="row idt_testiminial_container">

					<h1><?php _e( 'Enter testimonial Details', 'idt' ); ?></h1><label><?php _e( 'Testimonial', 'idt' ); ?></label><div></div>
					<form action="#" method="POST">

						<textarea name="idt_t_main"></textarea>
						<?php $this->idt_testimonial_input_fields($fields); ?>
						<!-- <div class="form-group">
							<img id="idt_img_url" src="" width="200px" height="auto" >
							<div>
								<input type="submit"  name="button" id="upload_image_button" class="idt_button idt_button_lg" value="Add Logo"/>
							</div>
							
							<input type="hidden" name="idt_testimonial_logo" id="idt_logo" value="">
						</div> -->
						<div>
							<?php $nounce = wp_create_nonce( 'save_testimonial'); ?>
							<input type="hidden" name="_wpnonce" value="<?php echo $nounce; ?>">
							<input type="submit" name="save_testimonial" class="idt_button idt_button_lg" value="Save Testimonial">
						</div>
						
					</form>
				</div>
			</div>
			<?php
		} elseif (isset($t_id ) &&  (int) $t_id) {

			if (!is_numeric($t_id) ) {
				wp_redirect( 'admin.php?page=idt-testimonial');
			}
			$post_id = (int)$t_id;
			
			$post = get_post($post_id);
			$post_meta = $this->idt_testimonial_meta($post_id,$fields);

			?>
				<div class="container-fluid idt_container">
			<div class="row idt_testiminial_container">

					<h1><?php _e( 'Edit testimonial Details', 'idt' ); ?></h1><div></div>
					<label><?php _e( 'Testimonial', 'idt' ); ?></label>
					<form action="#" method="POST">

					<textarea name="idt_t_main"><?php echo esc_html($post->post_content); ?></textarea>
					<?php $this->idt_testimonial_input_fields($fields,$post_meta); ?>
					<div class="form-group">
					<?php $im_id = get_post_meta( $post_id, 'idt_testimonial_logo', true );
						// if ($im_id) {
						//  	$image = wp_get_attachment_url( $im_id );
						//  	echo '<img id="idt_img_url" src="'.$image.'" width="200px" height="auto" >';
						//  	echo '<input type="hidden" name="idt_testimonial_logo" id="idt_logo" value="'.$im_id.'">';
						//  }  else {
						//  	echo '<img id="idt_img_url" src="" width="200px" height="auto" >';
						//  	echo '<input type="hidden" name="idt_testimonial_logo" id="idt_logo" value="">';
						//  }

						?>
						<!-- <div><input type="submit"  name="button" id="upload_image_button" class="button idt_button" value="Add Logo"/></div> -->
						<!-- <p>Try to use square image</p> -->
					</div>
					<br>
					<br>
					<?php $nounce = wp_create_nonce( 'save_testimonial'); ?>
					<input type="hidden" name="_wpnonce" value="<?php echo $nounce; ?>">
					<p><input type="submit" name="save_testimonial" class="idt_button idt_button_lg" value="Save Testimonial"> </p>

					</form>
				</div>
			</div>


		<?php
		} else {
			die('stop kidding');
		}

	}

	/**
	* Fields lists array gerenating function
	* @since v0.1
	*/
	private function idt_fields_array()
	{
		$fields = array(array('Author','idt_t_author','text') ,
						array('Company','idt_t_company','text'),

		 );

		return $fields ;
	}

	/**
	* Get testimonial meta
	* @since v0.1
	*/
	private function idt_testimonial_meta($post_id,$feilds)
	{

		if ($post_id && is_numeric($post_id)) {
			foreach ($feilds as $key) {

				$post_meta [$key[1]] = get_post_meta( $post_id, wp_kses_post($key[1]));
			}

			return $post_meta;
		}

	}

	/**
	* Saving testimonial to database
	* @since v0.1
	*/

	private function idt_save_testimonial($data,$id=null)
	{ ini_set("display_errors", 1);
		$fields = $this->idt_fields_array();
		if ($id != null && is_numeric($id)) {
			
			$post_update = array(
			      'ID'           => $id,
			      'post_content' =>  wp_kses_post( $data['idt_t_main'] ),
			  );
			$post_id = $id;
			// Update the post into the database
			wp_update_post( $post_update );
			update_post_meta( $post_id,'testimonial_editor', get_current_user_id());
			//update_post_meta( $post_id,'idt_testimonial_logo',wp_kses_post( $data['idt_testimonial_logo']) );
			foreach ($fields as $key) {

				update_post_meta(  $post_id, $key[1], wp_kses_post( $data[$key[1]] ));
			}


			wp_safe_redirect( admin_url( 'admin.php?page=add_testimonial&idt_id='.$id ) ); exit;
		} else {
			if (is_array($data) && is_numeric($id)) {
				$pieces = explode(" ", wp_kses_post($data['idt_t_main']));
				$string = implode(" ", array_splice($pieces, 0, 5));
				$insert_array = array(	'post_title' => $string,
										'post_type' => 'testimonial',
										'post_content' => wp_kses_post($data['idt_t_main']),
										'post_status' => 'publish');
				$post_id = wp_insert_post($insert_array);

				add_post_meta( $post_id,'testimonial_editor', get_current_user_id(), true );
				//add_post_meta( $post_id,'idt_testimonial_logo', wp_kses_post($data['idt_testimonial_logo']), true );

				foreach ($fields as $key) {
					add_post_meta( $post_id, $key[1], wp_kses_post($data[$key[1]]), true );
					}

				wp_safe_redirect( admin_url( 'admin.php?page=add_testimonial&idt_id='.$post_id ) ); exit;
			}
		}

	}

	/**
	* Redirecting page after adding or saving testimonial
	* @since v0.1
	*/

	private function idt_edit_testimonial_url($post_id=null,$delete=false)
	{
		if ($post_id==null && is_numeric($post_id)) {
			return admin_url( 'admin.php?page=add_testimonial' );
		} elseif ($post_id != null  && $delete ==true && is_numeric($post_id)) {
			return admin_url( 'admin.php?page=idt-testimonial&idt_id='.$post_id .'&action=delete' );
		}

		else {
			return admin_url( 'admin.php?page=add_testimonial&idt_id='.$post_id  );
		}

	}

	/**
	* displaying testimonial fields 
	* @since v0.1
	*/
	private function idt_testimonial_input_fields($fields,$fields_value=null) {

		if (is_array($fields)) {
			foreach ($fields as $key) {
				?>
				<div class="form-group">
				    <label for="<?php echo esc_html($key[2]) ?>"><?php echo esc_html($key[0]); ?></label>
				    <input type="<?php echo esc_html($key[2]) ?>" class="form-control"  name="<?php echo esc_html($key[1]); ?>" value="<?php echo esc_html($this->idt_testimonial_input_fields_value( $key[1],$fields_value)); ?>" >

				 </div>
				<?php
			}
		} else {
			return;
		}

	}

	/**
	* get value of input fields
	* @since v0.1
	*/
	private function idt_testimonial_input_fields_value($field_name,$fields_value)
	{

		if (is_array($fields_value)) {


			return $fields_value[$field_name][0];
		} else {
			return;
		}


	}
	/**
	* Displaying testimonial list
	* @since v0.1
	*/
	private function idt_testimonial_list()
	{
		$testimonials = $this->idt_testimonial_list_data();
		?>
		<div class="container-fluid">
			<div class="row idt_testiminial_container">
				<a href="<?php echo esc_html($this->idt_edit_testimonial_url()); ?>" class="idt_button idt_button_lg"><?php _e( 'Add Testimonial', 'idt' ); ?></a>

				<div class="idt_tm_table">
			        <div class="idt_tm_table-row idt_tm_table_head_row">
			          <div class="idt_tm_table-cell"><?php _e( '#', 'idt' ); ?></div>
			          <div class="idt_tm_table-cell"><?php _e( 'Testimonial', 'idt' ); ?></div>
			          <div class="idt_tm_table-cell"><?php _e( 'Added by', 'idt' ); ?></div>
			          <div class="idt_tm_table-cell"><?php _e( 'Date', 'idt' ); ?></div>
			        </div>
			        <?php
				  	$i = 1; 
				  	if (is_array($testimonials ) && !empty($testimonials)) {
				  		foreach ($testimonials as $testimonial) {
				  			$nounce = wp_create_nonce( 'delete_testimonial-' . $testimonial->ID);
				  		?>
				        <div class="idt_tm_table-row">
				          <div class="idt_tm_table-cell"><?php echo esc_html($i); ?></div>
				          <div class="idt_tm_table-cell"><?php echo esc_html(get_post_meta($testimonial->ID,'idt_t_author',true)); ?><div><a class="idt_button idt_button_sm" href="<?php echo esc_html($this->idt_edit_testimonial_url($testimonial->ID)); ?>"><?php _e( 'edit', 'idt' ); ?></a><a class="idt_button idt_button_sm" href="<?php echo esc_html($this->idt_edit_testimonial_url($testimonial->ID,true)); ?>&_wpnonce=<?php echo $nounce; ?> "><?php _e( 'delete', 'idt' ); ?></a></div></div>
				          <div class="idt_tm_table-cell"><?php $user_id= get_post_meta( $testimonial->ID, $key = 'testimonial_editor', true );
									      	$data = $this->idt_user_data($user_id);
									      	echo esc_html($data->display_name);
									      ?></div>
				          <div class="idt_tm_table-cell"><?php echo date('F j, Y', strtotime($testimonial->post_date)); ?></div>
				       	</div>
				  		<?php
				  		 $i++;
					  	}
				  	} else {
				  		?>
				  		 <div class="idt_tm_table-row">
				  		 	<p><?php _e( 'Please', 'idt' ); ?> <a href="<?php echo admin_url( 'admin.php?page=add_testimonial' ) ?>"><?php _e( 'click here', 'idt' ); ?></a> <?php _e( 'to add testimonials', 'idt' ); ?></p>
				  		 </div>
				  		<?php
				  	}
				  	
				  	?>
      			 </div>
			</div>
		</div>
		<?php
	}



	/**
	* Getting testimonial list
	* @since v0.1
	*/
	public function idt_testimonial_list_data()
	{
		$args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'testimonial',
			'post_status'      => 'publish',
			'suppress_filters' => true
		);
		$testimonials = get_posts( $args );
		return $testimonials;
	}

	/**
	* Get userinfo from userid
	* @since v0.1
	*/
	private function idt_user_data($user_id)
	{
	 	if (isset($user_id) && (int)$user_id) {
	      	$user_data = get_userdata($user_id);
	      	return $user_data;
      	}
	}

	/**
	* Delete testimonial using post ID
	* @since v0.1
	*/

	private function idt_delete_testimonial($post_id)
	{
		if (intval($post_id) && (int)$post_id) {
			wp_delete_post($post_id,true);

			$post_meta = $this->idt_fields_array();
			delete_post_meta( $post_id,'testimonial_editor' );
			foreach ($post_meta as $key) {
				delete_post_meta( $post_id, $key[1] );
				}
			wp_reset_postdata ();

			wp_safe_redirect( admin_url( 'admin.php?page=idt-testimonial' ) ); exit;
		} else {
			return false;
		}
	}
}

/**
* Testimonials admin display funstions Class
* @since v0.1
**/

new idt_testimonial_admin;
