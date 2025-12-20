<?php
$context = array(
	'site_url' => get_site_url(),
	'logo'     => get_bloginfo( 'name' ),
);
Sprig::out( 'header.twig', $context );
