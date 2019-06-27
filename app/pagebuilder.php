<?php

add_action( 'acf/init', 'init_pagebuilder_field' );

if ( ! function_exists( 'init_pagebuilder_field' ) ) {
	function init_pagebuilder_field() {
		acf_add_local_field_group( [
			                           'key'                   => 'pagebuilder',
			                           'title'                 => 'Page Builder',
			                           'fields'                => [
				                           [
					                           'key'               => 'modules',
					                           'label'             => 'Modules',
					                           'name'              => 'modules',
					                           'type'              => 'flexible_content',
					                           'layouts'           => apply_filters( 'flexible_content_field_layouts',
					                                                                 [] ),
					                           'instructions'      => '',
					                           'required'          => 0,
					                           'conditional_logic' => 0,
					                           'wrapper'           => [
						                           'width' => '',
						                           'class' => '',
						                           'id'    => '',
					                           ],
					                           'default_value'     => '',
					                           'placeholder'       => '',
					                           'prepend'           => '',
					                           'append'            => '',
					                           'maxlength'         => '',
				                           ],
			                           ],
			                           'location'              => [
				                           [
					                           [
						                           'param'    => 'page_template',
						                           'operator' => '==',
						                           'value'    => 'pagebuilder.php',
					                           ],
				                           ],
			                           ],
			                           'menu_order'            => 0,
			                           'position'              => 'normal',
			                           'style'                 => 'default',
			                           'label_placement'       => 'top',
			                           'instruction_placement' => 'label',
			                           'hide_on_screen'        => [
				                           0  => 'the_content',
			                           ],
			                           'active'                => 1,
			                           'description'           => '',
		                           ] );
	}
}

$pattern = [
	get_template_directory(),
	DIRECTORY_SEPARATOR,
	'modules',
	DIRECTORY_SEPARATOR,
	'*',
	DIRECTORY_SEPARATOR,
	'register.php'
];

foreach ( glob( implode('', $pattern) ) as $filename ) {
	require_once $filename;
}

add_filter( 'theme_templates', 'pagebuilder_template', 10, 4 );

if ( ! function_exists( 'pagebuilder_template' ) ) {
	function pagebuilder_template( $post_templates, $theme, $post, $post_type ) {
		return array_merge( $post_templates, [ 'pagebuilder.php' => 'Page Builder' ] );
	}
}

add_filter( 'the_content', 'pagebuilder_content' );

if ( ! function_exists( 'pagebuilder_content' ) ) {
	function pagebuilder_content( $content ) {
		/** @var WP_Post $post */
		$post = get_post();
		if ( $post->page_template == 'pagebuilder.php' ) {
            ob_start();
            get_template_part( 'partials/pagebuilder' );
            $content = ob_get_clean();
		}

		return $content;
	}
}
