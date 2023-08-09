<?php
/**
 * Plugin Name: My Order Form
 * Description: Add a custom order form to your website
 * Version: 1.0
 * Author: Artem Stepanov
 */

function my_order_form_shortcode() {
  ob_start();
  
  // HTML order form
  ?>
  <form method="post" enctype="multipart/form-data">
    <div>
      <label for="name">Name:</label>
      <input type="text" name="name" id="name" required>
    </div>
    <div>
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required>
    </div>
    <div>
      <label for="phone">Phone:</label>
      <input type="tel" name="phone" id="phone" required>
    </div>
    <div>
      <label for="details">Details:</label>
      <textarea name="details" id="details" required></textarea>
    </div>
    <div>
      <label for="attachment">Attachment:</label>
      <input type="file" name="attachment" id="attachment">
    </div>
    <div>
      <input type="submit" name="submit" value="Submit">
    </div>
  </form>
  <?php
  
  // Getting data from the form and sending it to the specified email
  if (isset($_POST['submit'])) {
    $to = 'stepanovartem313@gmail.com';
    $subject = 'New Order';
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $details = $_POST['details'];
    $attachment = '';
    
    if (!empty($_FILES['attachment']['name'])) {
      $upload = wp_upload_bits($_FILES['attachment']['name'], null, file_get_contents($_FILES['attachment']['tmp_name']));
      if (isset($upload['error']) && $upload['error'] != 0) {
        wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
      } else {
        $attachment = $upload['file'];
      }
    }
    
    $headers[] = "From: $name <$email>";
    $headers[] = "Reply-To: $name <$email>";
    $headers[] = "Content-Type: text/html; charset=UTF-8";
    
    $message = "Name: $name<br>";
    $message .= "Email: $email<br>";
    $message .= "Phone: $phone<br>";
    $message .= "Details: $details<br>";
    
    if (!empty($attachment)) {
      $attachment_id = media_handle_upload('attachment', 0);
      if (!is_wp_error($attachment_id)) {
        $attachments = array($attachment_id);
        wp_mail($to, $subject, $message, $headers, $attachments);
      } else {
        wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
      }
    }
  }
  return ob_get_clean();
}
add_shortcode('my_order_form', 'my_order_form_shortcode');
