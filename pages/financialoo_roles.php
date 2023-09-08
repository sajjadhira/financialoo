<?php
function financialoo_roles()
{
  $title = 'Roles';


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
        include FINANCIALOO_PLUGIN_DIR . '/pages/partials/roles/form.php';
      } else if ($action == "delete") {
        include FINANCIALOO_PLUGIN_DIR . '/pages/partials/roles/delete.php';
      } else {
        include FINANCIALOO_PLUGIN_DIR . '/pages/partials/roles/list.php';
      }
      ?>



    </div>
  </div>

<?php
}
?>