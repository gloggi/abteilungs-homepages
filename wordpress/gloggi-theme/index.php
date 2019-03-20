<?php
/*
Template Name: Startseite
*/
global $post;
$index_trennbanner = wpptd_get_post_meta_value( $post->ID, 'index-separator-banner' );
if( $index_trennbanner ) {
    $index_trennbanner = '<div class="content__big_image_container">' . wp_get_attachment_image( $index_trennbanner, array(), false, array( 'class' => 'content__big_image parallax__layer' ) ) . '</div>';
} else $index_trennbanner = '';
$index_trennbanner2 = wpptd_get_post_meta_value( $post->ID, 'index-separator-banner2' );
if( $index_trennbanner2 ) {
    $index_trennbanner2 = '<div class="content__big_image_container">' . wp_get_attachment_image( $index_trennbanner2, array(), false, array( 'class' => 'content__big_image parallax__layer' ) ) . '</div>';
} else $index_trennbanner2 = '';
$index_content1 = wpptd_get_post_meta_value( $post->ID, 'index-content1' );
$index_content2 = wpptd_get_post_meta_value( $post->ID, 'index-content2' );
$index_content3 = wpptd_get_post_meta_value( $post->ID, 'index-content3' );

$formfields = wpptd_get_post_meta_value( $post->ID, 'index-contact-form-fields' );
$emailSent = false;
$hasError = false;
$prefill = array();

array_walk( $formfields, function(&$item) { $item['class'] = 'form-control'; } );

if(isset($_POST['submit'])) {
  $index = 0;
  $fields = array();
  foreach( $formfields as $key => $field ) {
    $value = $_POST['field' . $index];
    if( trim($value) === '') {
      if( $field['required'] ) {
        $formfields[$key]['class'] .= ' field-error ';
        $hasError = true;
      } else {
        $fields[$key] = '';
      }
    } else {
      switch( $field['type'] ) {
        case 'text':
          $fields[$key] = trim($value);
          break;
        case 'textarea':
          if(function_exists('stripslashes')) {
            $fields[$key] = stripslashes(trim($value));
          } else {
            $fields[$key] = trim($value);
          }
          break;
        case 'number':
          if (!preg_match("/^[0-9]+([,.][0-9]+)?$/i", trim($value))) {
            $hasError = true;
            $formfields[$key]['class'] .= ' field-error ';
            $fields[$key] = '';
          } else {
            $fields[$key] = trim($value);
          }
          break;
        case 'email':
          if (!preg_match("/^[[:alnum:]][a-z0-9_.+-]*@[a-z0-9.-]+\.[a-z]{2,6}$/i", trim($value))) {
            $hasError = true;
            $formfields[$key]['class'] .= ' field-error ';
            $fields[$key] = '';
          } else {
            $fields[$key] = trim($value);
          }
          break;
        case 'tel':
          if (!preg_match("/^[0-9+ ]{\$/i", trim($value))) {
            $hasError = true;
            $formfields[$key]['class'] .= ' field-error ';
            $fields[$key] = '';
          } else {
            $fields[$key] = trim($value);
          }
          break;
        case 'gender':
          if ($value != 'm' && $value != 'w' && $value != 'x') {
            $hasError = true;
            $formfields[$key]['class'] .= ' field-error ';
            $fields[$key] = '';
          } else {
            $fields[$key] = trim($value);
          }
          break;
      }
      $prefill[$index] = $fields[$key];
    }
    $index++;
  }
  if(!$hasError) {
    $emailTo = wpod_get_option('gloggi_einstellungen', 'mitmachen-email');
    $subject = 'Neue Nachricht auf ' . get_the_permalink();
    $body = '';
    $email = '';
    foreach( $fields as $key => $field ) {
      $body .= $formfields[$key]['name'] . ":\n" . $field . "\n\n";
      if( $formfields[$key]['type'] == 'email' && $field != '' ) {
        $email = $field;
      }
    }
    $headers = array( 'From: Homepage '. wpod_get_option( 'gloggi_einstellungen', 'abteilung' ) .' <'.$emailTo.'>', 'Reply-To: ' . $email );
    wp_mail($emailTo, $subject, $body, $headers);
    $emailSent = true;
    $prefill = array();
  }
}
?>
<?php get_template_part('header-large'); ?>

<div class="content__block">
  <div class="circle-large color-primary not-small">
    <img src="<?php echo get_bloginfo('template_directory'); ?>/files/img/scout-logos.svg">
  </div>
  <div>
    <p class="wysiwyg"><?php echo $index_content1; ?></p>
  </div>
</div>
<?php if( $formfields && count( $formfields ) > 0 ) : ?>
<div class="content__block" id="mitmachen">
  <h2 style="margin-top: 0px;">Mitmachen</h2>
<?php if( $hasError ) : ?>
  <h3>Bitte Fehler in den markierten Feldern korrigieren.</h3>
<?php elseif( $emailSent ) : ?>
  <h3>Vielen Dank, die Nachricht wurde verschickt.</h3>
<?php endif; ?>
  <form action="<?php echo get_the_permalink(); ?>#mitmachen" method="POST">
    <ul>
<?php $index = 0; foreach( $formfields as $field ) : ?>
      <li><label for="field<?php echo $index; ?>"><?php echo $field['name'] . ( $field['required'] ? '*' : '' ); ?></label>
<?php if( $field['type'] == 'textarea' ) :?>
        <textarea rows="3" name="field<?php echo $index; ?>" id="field<?php echo $index; ?>" <?php if( $field['required'] ) : ?>required="required" <?php endif; ?>class="<?php echo $field['class']; ?>"><?php echo $prefill[$index]; ?></textarea></li>
<?php elseif( $field['type'] == 'gender' ) : ?>
        <select name="field<?php echo $index; ?>" id="field<?php echo $index; ?>" <?php if( $field['required'] ) : ?>required="required" <?php endif; ?>class="<?php echo $field['class']; ?>">
          <option value="">Bitte w&auml;hlen</option>
          <option value="m"<?php if( $prefill[$index] == 'm' ) echo ' selected="selected"'; ?>>m</option>
          <option value="w"<?php if( $prefill[$index] == 'w' ) echo ' selected="selected"'; ?>>w</option>
          <option value="x"<?php if( $prefill[$index] == 'x' ) echo ' selected="selected"'; ?>>x</option>
        </select>
<?php else : ?>
        <input type="<?php echo $field['type']; ?>" name="field<?php echo $index; ?>" id="field<?php echo $index; ?>" value="<?php echo $prefill[$index]; ?>" <?php if( $field['required'] ) : ?>required="required" <?php endif; ?>class="<?php echo $field['class']; ?>"/>
<?php endif; ?>
      </li>
<?php $index++; endforeach; ?>
    </ul>
    <button type="submit" class="button" name="submit" value="1">Absenden</button>
  </form>
</div>
<?php endif; ?>

<?php echo $index_trennbanner; ?>

<?php
$instagram = wpod_get_option( 'gloggi_einstellungen', 'instagram' );
$facebook = wpod_get_option( 'gloggi_einstellungen', 'facebook' );
$twitter = wpod_get_option( 'gloggi_einstellungen', 'twitter' );
$anysocialmedia = $instagram || $facebook || $twitter;
if ($anysocialmedia) :
?>
<div class="content__block content__two-columns content__columns--1-2">
  <div class="content__column circle-stack">
    <?php if ($instagram) : ?>
    <a href="<?php echo $instagram; ?>">
      <div class="circle-small instagram-icon">
        <img src="<?php echo get_bloginfo('template_directory'); ?>/files/img/instagram-icon.svg">
      </div>
    </a>
    <?php endif; ?>
    <?php if ($facebook) : ?>
    <a href="<?php echo $facebook; ?>">
      <div class="circle-small facebook-icon">
        <img src="<?php echo get_bloginfo('template_directory'); ?>/files/img/facebook-icon.svg">
      </div>
    </a>
    <?php endif; ?>
    <?php if ($twitter) : ?>
    <a href="<?php echo $twitter; ?>">
      <div class="circle-small twitter-icon">
        <img src="<?php echo get_bloginfo('template_directory'); ?>/files/img/twitter-icon.svg">
      </div>
    </a>
    <?php endif; ?>
  </div>
  <div class="content__column">
<?php else: ?>
  <div class="content__block">
<?php endif; ?>
    <p class="wysiwyg"><?php echo $index_content2; ?></p>
<?php if ($anysocialmedia) : ?>
  </div>
<?php endif; ?>
</div>

<?php echo $index_trennbanner2; ?>

<?php if( $index_content3 ) : ?>
<div class="content__block">
  <p class="wysiwyg"><?php echo $index_content3; ?></p>
</div>
<?php endif; ?>

<?php get_template_part( 'footer' ); ?>
