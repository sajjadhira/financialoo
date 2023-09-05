<?php
function financialoo_withdrawals()
{
    $title = 'Withdrawals';
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
</div>

<?php
}
?>
