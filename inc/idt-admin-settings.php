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
* Testimonial settings class
* @since v0.1
*/
class idt_testimonial_settings
{

	public function __construct()
	{
		$this->idt_testimonial_display();
	}

	/**
	* Testimonial settings display function
	* @since v0.1
	*/

	private function idt_testimonial_display()
	{	
		if (esc_attr( $_POST['idt_settings'] == 'save' )) {
			$this->idt_arugment_update('idt_settings');
			wp_redirect( admin_url('admin.php?page=idt-options') );
			exit();
		}
		?>
		 	<div class="container-fluid idt_container">
			<div class="row idt_testiminial_container">

            <h1><?php _e( 'Testimonial Settings', 'idt' ); ?></h1>
            	<div>
            		<h3><?php _e( 'Display testimonials in your website', 'idt' ); ?></h3>
            		<p class="idt_shortcode"><?php _e( 'Using Shortoce', 'idt' ); ?> <strong>[idt_testimonial_display]</strong> <?php _e( 'in page to display testimonial in your page or post', 'idt' ); ?></p>
            	</div>
            	<br>
            	<div>
            		<form method="POST" action="#" enctype="multipart/form-data">

	               	<?php $this->idt_display_settings_fields(); ?>
	               	<div class="form-group">
	                   <button type="submit" name="idt_settings" class="idt_test_button_lg idt_button" value="save"><?php _e( 'Submit', 'idt' ); ?></button>
	                </div>
	                </p>
	            </form>
            	</div>

        	</div>
        </div>
        <?php
	}

	/**
	* Testimonial settings fields function
	* @since v0.1
	*/
	private function idt_display_settings_fields() {
		
		
		// get options values array
		$options = $this->idt_argument_val('idt_settings');
		?>
		<table class="form-table">
			<tbody>
				<tr><th style="font-size: 20px;"><?php _e( 'Testimonial Title Text', 'idt' ); ?></th></tr>
				<tr>
					<th><label for="testimonial-title"><?php _e( 'Testimonial Title', 'idt' ); ?></label></th>
					<td><input type="text" name="idt_testimonial_heading" value="<?php echo esc_attr($options['idt_testimonial_heading']); ?>" ></td>
				</tr>
				<tr>
					<th><label for="testimonial-desc"><?php _e( 'Testimonial Description', 'idt' ); ?></label></th>
					<td><textarea name="idt_testimonial_desc" ><?php echo esc_attr($options['idt_testimonial_desc']); ?></textarea></td>
				</tr>
				<hr>
				<tr><th style="font-size: 20px;"><?php _e( 'Testimonial settings', 'idt' ); ?></th></tr>

				<!-- <tr>
					<th><label for="emailsubject">Select Layout</label></th>
					<td><label for="emailsubject">Layout  </label><input type="radio" name="idt_design_layout" value="1" <?php //echo ($options['idt_design_layout'] == 1) ? 'checked="checked"' : '';  ?>>
						<label for="emailsubject">Layout 2 </label><input type="radio" name="idt_design_layout" value="2" <?php //echo ($options['idt_design_layout'] == 2) ? 'checked="checked"' : '';  ?>>
						<label for="emailsubject">Layout 3 </label><input type="radio" name="idt_design_layout" value="3" <?php //echo ($options['idt_design_layout'] == 3) ? 'checked="checked"' : '';  ?>>
					</td>
				</tr> -->
				<tr>
					<th><label for="emailsubject"><?php _e( 'Show Title text at front', 'idt' ); ?></label></th>
					<td><input type="checkbox" name="idt_show_title" <?php echo (esc_attr($options['idt_show_title']) == true) ? 'checked="checked"' : '';  ?>></td>
				</tr>
			</tbody>

		</table>


		<?php
	}

	/**
	* Testimonial settings fields list
	* @since v0.1
	*/
	private function idt_return_arr( $field ){

	  switch($field){
	        case 'idt_settings':
	            $variables = array(

	                                'idt_testimonial_heading' => 'Testimonial',
	                                'idt_testimonial_desc' => 'Testimonial description is here',
	                                'idt_display_image' => 1,
	                                'idt_design_layout' => 1,
	                                'idt_show_title' => on
	                              );
	        break;

	  }
	    return $variables;
	}

	/**
	* Testimonial settings values for page
	* @since v0.1
	*/

	private function idt_argument_val( $field ){
	    $variables = $this->idt_return_arr( $field );
	    foreach($variables as $key => $value){
	        if( get_option( $key ) === FALSE ) add_option($key, $value);
	        else $variables[$key] = esc_attr(get_option($key));
	    }
	    return $variables;
	}

	/**
	* Testimonial settings values saving to database
	* @since v0.1
	*/

	private function idt_arugment_update( $field ){
	    $variables = $this->idt_return_arr( $field );
	    foreach($variables as $key => $value){
	        if(get_option($key)===FALSE){
	            if(!isset($_REQUEST[$key])){
	                add_option($key, '');
	                //return;
	            }elseif(is_array(esc_attr($_REQUEST[$key]))){
	                add_option($key, serialize(wp_kses_post($_REQUEST[$key])));
	            }else { add_option($key, wp_kses_post($_REQUEST[$key]));}
	        }else{
	            if(!isset($_REQUEST[$key])){
	                update_option($key, '');
	                //return;
	            }elseif(is_array(esc_attr($_REQUEST[$key]))){
	                update_option($key, serialize(wp_kses_post($_REQUEST[$key])));
	            }else{
	                update_option($key, wp_kses_post($_REQUEST[$key]));
	            }
	        }
	    }

	}


}


/**
* Testimonials initialize settings Class
* @since v0.1
**/
new idt_testimonial_settings;
