<?php
function financialoo_home_page()
{
  $title = 'Home';
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


      <!-- add some cards for stats -->
      <!-- card for dashboard staticties -->

      <div class="flex flex-wrap -mx-4">
        <div class="w-full md:w-1/2 xl:w-1/3 p-4">
          <div class="bg-white border-transparent rounded-lg shadow-xl">
            <div class="bg-gray-400 uppercase text-gray-800 border-b-2 border-gray-500 rounded-tl-lg rounded-tr-lg p-2">
              <h5 class="font-bold uppercase text-gray-600">Total Income</h5>
            </div>
            <div class="p-5">
              <h1 class="text-3xl text-gray-800 font-bold">&#2547; 0.00</h1>
              <div class="text-sm text-gray-500">Jan 1 - Apr 1</div>
            </div>
          </div>
        </div>
        <div class="w-full md:w-1/2 xl:w-1/3 p-4">
          <div class="bg-white border-transparent rounded-lg shadow-xl">
            <div class="bg-gray-400 uppercase text-gray-800 border-b-2 border-gray-500 rounded-tl-lg rounded-tr-lg p-2">
              <h5 class="font-bold uppercase text-gray-600">Total Expenses</h5>
            </div>
            <div class="p-5">
              <h1 class="text-3xl text-gray-800 font-bold">&#2547; 0.00</h1>
              <div class="text-sm text-gray-500">Jan 1 - Apr 1</div>
            </div>
          </div>
        </div>
        <div class="w-full md:w-1/2 xl:w-1/3 p-4">
          <div class="bg-white border-transparent rounded-lg shadow-xl">
            <div class="bg-gray-400 uppercase text-gray-800 border-b-2 border-gray-500 rounded-tl-lg rounded-tr-lg p-2">
              <h5 class="font-bold uppercase text-gray-600">Total Balance</h5>
            </div>
            <div class="p-5">
              <h1 class="text-3xl text-gray-800 font-bold">&#2547; 0.00</h1>
              <div class="text-sm text-gray-500">Jan 1 - Apr 1</div>
            </div>
          </div>

        </div>
      </div>

    <?php
  }
    ?>