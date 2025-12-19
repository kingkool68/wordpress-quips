<?php
$context = array(
	'the_title'       => apply_filters( 'the_title', get_the_title() ),
	'the_nonce_field' => wp_nonce_field( 'wp_rest', '_wpnonce', true, false ),
);
wp_enqueue_style( 'rh-page-post' );
wp_enqueue_script( 'rh-page-post' );
Sprig::out( 'page-post.twig', $context );
