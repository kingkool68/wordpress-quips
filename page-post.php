<?php
$context = array(
	'the_title'       => apply_filters( 'the_title', get_the_title() ),
	'the_content'     => apply_filters( 'the_content', get_the_content() ),
	'the_nonce_field' => wp_nonce_field( 'wp_rest', '_wpnonce', true, false ),
);
Sprig::out( 'page-post.twig', $context );
