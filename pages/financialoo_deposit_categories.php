<?php
function financialoo_deposit_categories()
{
  $title = 'Deposit Categories';


  if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $action = sanitize_text_field($action);
  } else {
    $action = '';
  }


?>
  <div class="wrap">
    <div class="bg-gray-100 items-center pt-8">
      <?php
      include FINANCIALOO_PLUGIN_DIR . '/pages/header.php';
      ?>
      <div class="bg-white p-8 rounded shadow-md">
        <h2 class="text-xl font-bold mb-4"><?php echo $title; ?></h2>
        <div class="relative overflow-x-auto"></div>
      </div>
    </div>


    <div class="bg-white p-8 rounded shadow-md">

      <?php
      if ($action == "form") {
        include FINANCIALOO_PLUGIN_DIR . '/pages/partials/depositcategories/form.php';
      } else if ($action == "delete") {
        include FINANCIALOO_PLUGIN_DIR . '/pages/partials/depositcategories/delete.php';
      } else {
        include FINANCIALOO_PLUGIN_DIR . '/pages/partials/depositcategories/list.php';
      }
      ?>



    </div>
  </div>

<?php
}
?>