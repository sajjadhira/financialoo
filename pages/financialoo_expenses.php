<?php
function financialoo_expenses()
{
  $title = 'Expenses';
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

      <h2 class="text-xl font-bold mb-4">List of Expenses</h2>

      <!-- add a `add new prompt` button to right -->

      <div class="flex justify-end mb-4">
        <a href="<?php echo admin_url('admin.php?page=' . FINANCIALOO_PREFIX . 'expenses&add=new') ?>" class="bg-blue-500 hover:bg-white-700 text-white font-bold py-2 px-4 rounded">Add New</a>
      </div>

      <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3">
                Category
              </th>
              <th scope="col" class="px-6 py-3">
                Amount
              </th>
              <th scope="col" class="px-6 py-3">
                Actions
              </th>
            </tr>
          </thead>
          <tbody>

            <!-- get data from tables -->

            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . FINANCIALOO_PREFIX . 'expenses';
            $sql = "SELECT * FROM $table_name";
            $results = $wpdb->get_results($sql);
            foreach ($results as $result) {
            ?>
              <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  <?php
                  // count total expenses `amount`  of this category
                  $table_name = $wpdb->prefix . FINANCIALOO_PREFIX . 'expenses_categories';
                  $sql = "SELECT * FROM $table_name WHERE category_id = $result->id";
                  $category = $wpdb->get_results($sql);
                  echo $category->name;

                  ?>

                </th>
                <td class="px-6 py-4">
                  <?php
                  echo $result->amount;
                  ?>
                </td>

                <td class="px-6 py-4">


                  <a href="<?php echo admin_url('admin.php?page=' . FINANCIALOO_PREFIX . 'expenses&action=edit&id=' . $result->id) ?>" class="text-blue-600 hover:text-blue-900">Edit</a>
                  | <a href="<?php echo admin_url('admin.php?page=' . FINANCIALOO_PREFIX . 'expenses&action=delete&id=' . $result->id) ?>" class="text-red-600 hover:text-red-900">Delete</a>

                </td>
              </tr>
            <?php
            }
            ?>

          </tbody>
        </table>
      </div>


    </div>
  </div>

<?php
}
?>