<?php

$queried_object = get_queried_object();

$the_title = 'Latest From Our Blog';

$context = array(
	'the_title' => apply_filters( 'the_title', $the_title ),
);
Sprig::out( 'home.twig', $context );
