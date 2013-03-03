<?php
  $navsection = 'admin';

  include("../include/init.php");
  include_once("$DOCUMENT_ROOT/include/utils.php");  

  headerTitleMenu("Our Coin Awesomeness");

?>
   <h2>Admin Actions</h2>
   <p>Select an administrative action below</p>
   
   <section id='general-admin'>
     <div class='section'>
       <h3 class='heading down-arrow'>General Administration</h3>
       <div class='content'>
         <br />
         <dl>
           <dt class='first'><a href="/admin/createThumbnails.php" target='_blank'>Create Thumbnails</a></dt>
           <dd class='first'><p>Creates lower quality thumbnails for all the photos that we have for coins</p></dd>

           <dt><a href="/admin/exportCoins_V2.php" target='_blank'>Export Coins</a></dt>
           <dd><p>Export coins to .coin files</p></dd>

           <dt><a href="/batch/addSearchableData.php" target='_blank'>Reindex Search</a></dt>
           <dd><p>Reindex searchable data for coins</p></dd>

           <dt><a href="/batch/updateMetalValues.php" target='_blank'>Update Precious Metals</a></dt>
           <dd><p>Update precious metal values</p></dd>

           <dt><a href="/batch/batchTrackerSearch.php" target='_blank'>Run Tracker Searches</a></dt>
           <dd><p>Run all trackers</p></dd>

         </dl>

         <?php echo clear(); ?>
       </div>
     </div>
   </section>

   <?php echo clear(); ?>

   <section id='reference-data'>
     <div class='section'>
       <h3 class='heading down-arrow'>DB Export Data</h3>
       <div class='content'>
         <br />
         <dl>
           <dt><a href="/admin/export/all.php" target='_blank'>Export All DB Data</a></dt>
           <dd><p>Export all data to DB format</p></dd>

           <dt><a href="/admin/export/CommonDB.php" target='_blank'>Export Common DB Data</a></dt>
           <dd>
             <ul>
               <li><a href="/admin/export/preciousMetals.php" target='_blank'>Precious Metals</a></li>
               <li><a href="/admin/export/ratingCategories.php" target='_blank'>Rating Categories</a></li>
               <li><a href="/admin/export/ratingScales.php" target='_blank'>Rating Scales</a></li>
               <li><a href="/admin/export/ratingAgencies.php" target='_blank'>Rating Agencies</a></li>
               <li><a href="/admin/export/coinOrigins.php" target='_blank'>Coin Origins</a></li>
             </ul>
           </dd>
           
           <dt><a href="/admin/export/UsCoinDB.php" target='_blank'>Export U.S. Coin DB Data</a></dt>
           <dd>
             <ul>
               <li><a href="/admin/export/mints.php" target='_blank'>Mints</a></li>
               <li><a href="/admin/export/mintDates.php" target='_blank'>Mint Dates</a></li>
               <li><a href="/admin/export/coins.php" target='_blank'>Coins</a></li>
               <li><a href="/admin/export/coinAttribs.php" target='_blank'>Coin Attributes</a></li>
               <li><a href="/admin/export/coinValues.php" target='_blank'>Coin Values</a></li>
               <li><a href="/admin/export/coinValueAttribs.php" target='_blank'>Coin Values Attributes</a></li>
               <li><a href="/admin/export/coinYears.php" target='_blank'>Coin Years</a></li>
               <li><a href="/admin/export/coinMetalCompositions.php" target='_blank'>Coin Metal Compositions</a></li>
               <li><a href="/admin/export/mintCoins.php" target='_blank'>Mint Coins</a></li>
             </ul>
           </dd>

           <dt><a href="/admin/export/OurCoinDB.php" target='_blank'>Export Our Coin DB Data</a></dt>
           <dd>
             <ul>
               <li><a href="/admin/export/ourCoins.php" target='_blank'>Our Coins</a></li>
             </ul>
           </dd>

           <dt><a href="/admin/export/ForeignCoinDB.php" target='_blank'>Export Foreign Coin DB Data</a></dt>
           <dd>
             <ul>
               <li><a href="/admin/export/foreignCountries.php" target='_blank'>Countries</a></li>
               <li><a href="/admin/export/foreignCoinValues.php" target='_blank'>Coin Values</a></li>
               <li><a href="/admin/export/foreignCoins.php" target='_blank'>Coins</a></li>
               <li><a href="/admin/export/foreignCoinYears.php" target='_blank'>Coin Years</a></li>
               <li><a href="/admin/export/foreignMints.php" target='_blank'>Mints</a></li>
               <li><a href="/admin/export/foreignMintDates.php" target='_blank'>Mint Dates</a></li>
               <li><a href="/admin/export/foreignCoinMetalCompositions.php" target='_blank'>Coin Metal Compositions</a></li>
               <li><a href="/admin/export/foreignMintCoins.php" target='_blank'>Mint Coins</a></li>
             </ul>
           </dd>

           <dt><a href="/admin/export/TrackerDB.php" target='_blank'>Export Tracker DB Data</a></dt>
           <dd>
             <ul>
               <li><a href="/admin/export/trackers.php" target='_blank'>Trackers</a></li>
             </ul>
           </dd>
         </dl>

         <?php echo clear(); ?>
       </div>
     </div>
   </section>
         

   
   <?php echo clear(); ?>

   <section id='reference-data'>
     <div class='section'>
       <h3 class='heading down-arrow'>JSON Data</h3>
       <div class='content'>
         <br />
         <dl>
           <dt class='first'><a href="/ajax/coinValues.json" target='_blank'>Coin Denominations</a></dt>
           <dd class='first'><p>JSON - All Denominations for U.S. Coins</p></dd>

           <dt>Coins For Denomination</a></dt>
           <dd>
<?php
  $denoms = getCoinDenominations();
  if (sizeof($denoms) > 0 ) {
?>           
             <ul>
<?php
  foreach ($denoms as $denom) {
    $cvid = $denom['id'];
    $name = $denom['name'];
    
    echo "<li><a href='/ajax/coinsForValue.json?valueId=$cvid' target='_blank'>$name</a></li>";
  }
?>             
             </ul>
<?php } ?>             
           </dd>

      
         </dl>

         <?php echo clear(); ?>
       </div>
     </div>
   </section>

   <?php echo clear(); ?>
  
   <section id='reference-data'>
     <div class='section'>
       <h3 class='heading down-arrow'>Reference Data for DB File Conversion</h3>
       <div class='content'>
         <h4>Common</h4>
         <dl>
           <dt><a href="/output/coinGradeCategoryReference.php" target='_blank'>Coin Grade Categories</a></dt>
           <dd><p>All Sheldon Rating Category Data</p></dd>

           <dt><a href="/output/coinGradeReference.php" target='_blank'>Coin Grades</a></dt>
           <dd><p>All Sheldon Rating Grade Data</p></dd>

           <dt><a href="/output/preciousMetalReference.php" target='_blank'>Precious Metals</a></dt>
           <dd><p>All Precious Metal Data</p></dd>

           <dt><a href="/output/ratingAgencyReference.php" target='_blank'>Rating Agencies</a></dt>
           <dd><p>All Rating Agency Data</p></dd>
         </dl>

         <?php echo clear(); ?>

         <h4>U.S. Coins</h4>
         <dl>
           <dt><a href="/output/coinReference.php" target='_blank'>Coins</a></dt>
           <dd><p>All U.S. Coins</p></dd>

           <dt><a href="/output/coinValueReference.php" target='_blank'>Coin Denominations</a></dt>
           <dd><p>All Denominations for U.S. Coins</p></dd>

           <dt><a href="/output/coinYearReference.php" target='_blank'>Coin Years</a></dt>
           <dd><p>All Years for each U.S. Coin</p></dd>

           <dt><a href="/output/mintReference.php" target='_blank'>Mints</a></dt>
           <dd><p>All U.S. Mints</p></dd>

           <dt><a href="/output/mintCoinReference.php" target='_blank'>Mint Coins</a></dt>
           <dd><p>All Years/Mints for each U.S. Coin</p></dd>

           <dt><a href="/output/mintCoinValueReference.php" target='_blank'>Mint Coin Values</a></dt>
           <dd><p>All Values for Years/Mints for each U.S. Coin</p></dd>
         </dl>

         <?php echo clear(); ?>

         <h4>Foreign Coins</h4>
         <dl>
           <dt><a href="/output/foreignCountryReference.php" target='_blank'>Countries</a></dt>
           <dd><p>Foreign Countries</p></dd>

           <dt><a href="/output/foreignCoinReference.php" target='_blank'>Coins</a></dt>
           <dd><p>All Foreign Coins</p></dd>

         </dl>

         <?php echo clear(); ?>

         <h4>Our Coins</h4>
         <dl>
           <dt><a href="/output/ourCoinReference.php" target='_blank'>Our Coins</a></dt>
           <dd><p>All of Our Coins</p></dd>

         </dl>
 
         <?php echo clear(); ?>
          
       </div>
     </div>
   </section>
       
<?php 
  echo clear(); 
  
  endHeaderTitleMenu();
  
  include_once("$DOCUMENT_ROOT/include/html/footer.php");
?>
