<?php
global $post;
$post = (object) ['post_title' => ''];
$pages = get_pages(array(
    'meta_key' => '_wp_page_template',
    'meta_value' => 'index.php'
));
if( count( $pages ) ) {
	$post = $pages[0];
}
$post->post_title = 'Nicht gefunden';
get_template_part('header'); ?>
<div class="content__block wysiwyg">
<p><strong>Oops!</strong>
Leider k&ouml;nnen wir unter der angegebenen Adresse nichts finden. Wie w&auml;re es mit einer der Seiten aus der Navigation?</p>
</div>

<?php get_template_part( 'footer' ); ?>
