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
* Front end Display class
* @since v0.1
*/
class idt_testimonial_front
{

	public function __construct()
	{

		add_shortcode( 'idt_testimonial_display', array( $this, 'idt_testimonial_front_display' ) );

	}

    /**
    * Shortcode function for displaying testimonial at frontend
    * @since v0.1
    */
  
	public function idt_testimonial_front_display()
	{
        
		?>
		<div class="container text-center">
            <?php 
            if (esc_attr(get_option( 'idt_show_title')) == true) {
                   $heading = esc_attr(get_option( 'idt_testimonial_heading'));
                 if ($heading != '') {
                        echo '<h1 style="text-align: center;">'.$heading.'</h1>';
                    } 
                    $desc = esc_attr(get_option( 'idt_testimonial_desc'));
                    if ($desc != '') {
                        echo '<p class="section-description">'.$desc.'</p>';
                    }
            }
           
            ?>
			<div class="row">

            <?php
            /**
            * Layout function calling
            * @since v0.1
            **/
             $this->idt_testimonial_layout_2();
             // $layout = get_option( 'idt_design_layout');
             // if ($layout == 1) {
             //      $this->idt_testimonial_layout_1();
             // } elseif ($layout == 2) {
                
             // }
              ?>

			</div>
		</div>
		<?php
        
	}

    /**
    * Get all Testimonial List
    * @since v0.1
    * $val = int , Number of testimonials to display, default null
    **/

    public function idt_testimonial_list($val = null)
    {
        if (empty($val)) {
            $val = -1;
        } 
        if ((int) $val) {
            $args = array(
            'posts_per_page'   => $val,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'testimonial',
            'post_status'      => 'publish',
            'suppress_filters' => true
            );
            $testimonials = get_posts( $args );
            return $testimonials;
        }
       
    }

    /**
    * Testimonial layout 1
    * Next launch
    **/

    public function idt_testimonial_layout_1()
    {

        $i = 1;
        $testimonials = $this->idt_testimonial_list();
        if (is_array($testimonials) && !empty($testimonials)) {

        foreach ($testimonials as $testimonial) {


        ?>

        <div class="col-lg-4 col-md-4 mb-r">
            <div class="card testimonial-card">
                <div class="card-up info-color">
                </div>
                <div class="avatar">
                    <?php
                        $im_id = get_post_meta( $testimonial->ID, 'idt_testimonial_logo', true );
                        $image = wp_get_attachment_url( $im_id );

                    ?>
                    <img src="<?php echo esc_attr($image); ?>" class="rounded-circle img-responsive">
                </div>
                <div class="card-body">
                    <!--Name-->
                    <h4 class="mt-1">
                        <strong><?php echo esc_attr(get_post_meta( $testimonial->ID, 'idt_t_author', true )); ?></strong>
                    </h4>
                    <hr>
                    <!--Quotation-->
                    <p class="dark-grey-text"><?php echo esc_attr($testimonial->post_content); ?></p>
                </div>
            </div>

        </div>
        <?php
        if ($i == 3) {
           break;
        }

         $i++;  }
     }

    }

    /**
    * Testimonial layout 2
    * @since v0.1
    **/
    public function idt_testimonial_layout_2()
    {
        ?>
        <div class="idt_testimonials_wrap">
            <ul class="idt_testimonials">
            <?php
            // get list of testimonials
            $testimonials = $this->idt_testimonial_list();
            foreach ($testimonials as $testimonial) {
                ?>
                <li>
                    <p>
                       <?php echo esc_attr($testimonial->post_content); ?>
                        <strong><?php echo esc_attr(get_post_meta( $testimonial->ID, 'idt_t_author', true )); ?></strong>
                    </p>
                </li>
                <?php  } ?>
            </ul>
        </div>
        <?php
    }


}

/**
* Testimonials initialize front end Class
* @since v0.1
**/
new idt_testimonial_front;

