<?php

class Instagram_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'instagram-widget';
	}

	public function get_title() {
		return __( 'Instagram Widget', 'plugin-name' );
	}

	public function get_icon() {
		return 'fab fa-instagram';
	}

	public function get_categories() {
		return [ 'farajou' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'instagram_contents',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
        );
        
        $this->add_control(
			'ig_widget_access_token',
			[
				'label' => __( 'Access Token', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'Your Token'
			]
		);
        
        $this->add_control(
			'ig_widget_user_id',
			[
				'label' => __( 'User Id', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'Your User Id'
			]
		);
        
        $this->add_control(
			'ig_widget_image_count',
			[
				'label' => __( 'Image Count', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => '12'
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

?>

		<?php
			//instagram access token
			$ig_access_token = "".$settings['ig_widget_access_token']."";

			// instagram user id
			$ig_user_id = "".$settings['ig_widget_user_id']."";

			// instagram image count
			$image_count = "".$settings['ig_widget_image_count']."";

			// instagram api url
			$remote_wp = wp_remote_get('https://api.instagram.com/v1/users/'.$ig_user_id.'/media/recent/?access_token='.$ig_access_token.'&count='.$image_count.'');
			
			// json
			$ig_response = json_decode($remote_wp['body']);
		
		?>

		<?php if($remote_wp['response']['code']) : ?>
			<?php foreach($ig_response->data as $ig) : ?>
				<a href="<?php echo $ig->link ?>">
					<div class="instagram-image" style="background-image:url(<?php echo $ig->images->standard_resolution->url ?>);">
						<div class="ig-hover">
							<div class="ig-hover-inner">
								<div class="ig-likes-comments">
									<span class="ig-likes"><i class="far fa-heart"></i> <?php echo $ig->likes->count ?></span>
									<span class="ig-comments"><i class="far fa-comments"></i> <?php echo $ig->comments->count ?></span>
								</div>
							</div>
						</div>
					</div>
				</a>
			<?php endforeach; ?>
		<?php endif; ?>


<?php

	}

}
