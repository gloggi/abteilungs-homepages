<?php
/*
Template Name: Startseite
*/
global $post;
$index_trennbanner = wpptd_get_post_meta_value( $post->ID, 'index-separator-banner' );
$index_trennbanner2 = wpptd_get_post_meta_value( $post->ID, 'index-separator-banner2' );
$index_content3 = wpptd_get_post_meta_value( $post->ID, 'index-content3' );
$formfields = wpptd_get_post_meta_value( $post->ID, 'index-contact-form-fields' );
$emailSent = false;
$hasError = false;
$prefill = array();

if(isset($_POST['submit'])) {
  $index = 0;
  $fields = array();
  foreach( $formfields as $key => $field ) {
    $value = $_POST['field' . $index];
    if( trim($value) === '') {
      if( $field['required'] ) {
        $formfields[$key]['class'] .= 'field-error ';
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
            $formfields[$key]['class'] .= 'field-error ';
            $fields[$key] = '';
          } else {
            $fields[$key] = trim($value);
          }
          break;
        case 'email':
          if (!preg_match("/^[[:alnum:]][a-z0-9_.+-]*@[a-z0-9.-]+\.[a-z]{2,6}$/i", trim($value))) {
            $hasError = true;
            $formfields[$key]['class'] .= 'field-error ';
            $fields[$key] = '';
          } else {
            $fields[$key] = trim($value);
          }
          break;
        case 'tel':
          if (!preg_match("/^[0-9+ ]{\$/i", trim($value))) {
            $hasError = true;
            $formfields[$key]['class'] .= 'field-error ';
            $fields[$key] = '';
          } else {
            $fields[$key] = trim($value);
          }
          break;
        case 'gender':
          if ($value != 'm' && $value != 'w') {
            $hasError = true;
            $formfields[$key]['class'] .= 'field-error ';
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
    foreach( $fields as $key => $field ) {
      $body .= $formfields[$key]['name'] . ":\n" . $field . "\n\n";
    }
		$headers = 'From: Homepage '. wpod_get_option( 'gloggi_einstellungen', 'abteilung' ) .' <'.$emailTo.'>' . "\r\n";
		wp_mail($emailTo, $subject, $body, $headers);
		$emailSent = true;
    $prefill = array();
	}

}
?>
<?php get_template_part('header-large'); ?>

<div class="content__block  content__columns--2-1">
  <div class="content__column">
    <p><?php echo wpptd_get_post_meta_value( $post->ID, 'index-content1' ); ?></p>
  </div>
  <div class="content__column">
    <div class="circle color-primary not-small">
      <img src="<?php echo get_bloginfo('template_directory'); ?>/files/scout-logos.svg">
    </div>
  </div>
</div>
<?php if( $formfields && count( $formfields ) > 0 ) : ?>
<div class="content__block" id="mitmachen">
  <h2 style="margin-top: 0px;">Mitmachen</h2>
<?php if( $hasError ) : ?>
  <h3>Bitte Fehler in den untenstehenden Feldern korrigieren.</h3>
<?php elseif( $emailSent ) : ?>
  <h3>Vielen Dank, die Nachricht wurde verschickt.</h3>
<?php endif; ?>
  <form action="<?php echo get_the_permalink(); ?>#mitmachen" method="POST">
    <ul>
<?php $index = 0; foreach( $formfields as $field ) : ?>
      <li><label for="field<?php echo $index; ?>"><?php echo $field['name'] . ( $field['required'] ? '*' : '' ); ?></label>
<?php if( $field['type'] == 'textarea' ) :?>
        <textarea rows="3" name="field<?php echo $index; ?>" id="field<?php echo $index; ?>" <?php if( $field['required'] ) : ?>required="required" <?php endif; ?><?php if( $field['class'] ) : ?>class="<?php echo $field['class']; ?>" <?php endif; ?>><?php echo $prefill[$index]; ?></textarea></li>
<?php elseif( $field['type'] == 'gender' ) : ?>
        <select name="field<?php echo $index; ?>" id="field<?php echo $index; ?>" <?php if( $field['required'] ) : ?>required="required" <?php endif; ?><?php if( $field['class'] ) : ?>class="<?php echo $field['class']; ?>" <?php endif; ?>>
          <option value="">Bitte w&auml;hlen</option>
          <option value="m"<?php if( $prefill[$index] == 'm' ) echo ' selected="selected"'; ?>>m&auml;nnlich</option>
          <option value="w"<?php if( $prefill[$index] == 'w' ) echo ' selected="selected"'; ?>>weiblich</option>
        </select>
<?php else : ?>
        <input type="<?php echo $field['type']; ?>" name="field<?php echo $index; ?>" id="field<?php echo $index; ?>" value="<?php echo $prefill[$index]; ?>" <?php if( $field['required'] ) : ?>required="required" <?php endif; ?><?php if( $field['class'] ) : ?>class="<?php echo $field['class']; ?>" <?php endif; ?>/>
<?php endif; ?>
      </li>
<?php $index++; endforeach; ?>
    </ul>
    <button type="submit" class="button" name="submit" value="1">Absenden</button>
  </form>
</div>
<?php endif; ?>

<?php if( $index_trennbanner ) : ?>
<div class="content__big_image_container">
    <img class="content__big_image parallax__layer" src="<?php echo wp_get_attachment_url( $index_trennbanner ); ?>" alt="">
</div>
<?php endif; ?>

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
        <img src="<?php echo get_bloginfo('template_directory'); ?>/files/instagram-icon.svg">
      </div>
    </a>
    <?php endif; ?>
    <?php if ($facebook) : ?>
    <a href="<?php echo $facebook; ?>">
      <div class="circle-small facebook-icon">
        <img src="<?php echo get_bloginfo('template_directory'); ?>/files/facebook-icon.svg">
      </div>
    </a>
    <?php endif; ?>
    <?php if ($twitter) : ?>
    <a href="<?php echo $twitter; ?>">
      <div class="circle-small twitter-icon">
        <img src="<?php echo get_bloginfo('template_directory'); ?>/files/twitter-icon.svg">
      </div>
    </a>
    <?php endif; ?>
  </div>
  <div class="content__column">
<?php else: ?>
  <div class="content__block">
<?php endif; ?>
    <p><?php echo wpptd_get_post_meta_value( $post->ID, 'index-content2' ); ?></p>
<?php if ($anysocialmedia) : ?>
  </div>
<?php endif; ?>
</div>

<?php if( $index_trennbanner2 ) : ?>
<div class="content__big_image_container">
  <img class="content__big_image" src="<?php echo wp_get_attachment_url( $index_trennbanner2 ); ?>">
</div>
<?php endif; ?>

<?php if( $index_content3 ) : ?>
<div class="content__block">
  <p><?php echo $index_content3; ?></p>
</div>
<?php endif; ?>

<?php get_template_part( 'footer' ); ?>
