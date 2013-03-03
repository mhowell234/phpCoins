<fieldset id="add">
  <legend>Add a Tracker</legend>

  <form action="." method="post">
    <h3 style="float:right; cursor:pointer"><a onclick="clearTracker()">Clear</a></h3>

    <br />
<?php
 if ($error != '') {
   echo "<p class='error'>$error</p>";
 }
?>
      
    <br />
        
    <table id="tracker-search">

     <tr id="tr-name">
      <td class=' <?php if ($errorRow == 'name') { echo "errorRow"; } ?>'>
        <label>Tracker Name</label>
      </td>
      <td class=' <?php if ($errorRow == 'name') { echo "errorRow"; } ?>'>
        <input type="text" name="name" value="<?= $name ?>"/>
      </td>
     </tr>

     <tr id="tr-name">
      <td><label>Description</label></td>
      <td>
        <input type="text" name="description" value="<?= $description ?>" style="width:80%" />
      </td>
     </tr>

     <tr id="tr-coin-value">
      <td class=' <?php if ($errorRow == 'coinValue') { echo "errorRow"; } ?>'>
        <label>Denomination</label>
      </td>
      <td class=' <?php if ($errorRow == 'coinValue') { echo "errorRow"; } ?>'>
        <select name="coinValue" id="tr-coinValue">
         <option value="">-- Type --</option>
        </select>
      </td>
     </tr>

     <tr id="tr-coin-type" style="display:none">
      <td><label><span class="subdiv showdiv">&rarr;</span> Coin</label></td>
      <td>
        <select name="coinType" id="tr-coinType">
          <option value="">-- Coin --</option>
        </select>
      </td>
     </tr>

     <tr id="tr-coin-year-range1" style="display:none">
      <td><label><span class="subdiv">&rarr;</span><span class="subdiv showdiv">&rarr;</span> Year Range</label></td>
      <td>
        <select name="coinYearStart" id="tr-coinStartYear">
          <option value="">-- Start --</option>
        </select>
        <strong>-</strong>
        <select name="coinYearEnd" id="tr-coinEndYear">
          <option value="">-- End --</option>
        </select>
      </td>
     </tr>

     <tr id="tr-coin-year" style="display:none">
      <td><label><span class="subdiv">&rarr;</span><span class="subdiv showdiv">&rarr;</span> Specific Year</label></td>
      <td>
        <select name="coinYear" id="tr-coinYear">
          <option value="">-- Year --</option>
        </select>
      </td>
     </tr>

     <tr id="tr-coin-mintyear" style="display:none">
      <td><label><span class="subdiv">&rarr;</span><span class="subdiv">&rarr;</span><span class="subdiv showdiv">&rarr;</span> Mint</label></td>
      <td>
        <select name="coinMintYear" id="tr-coinMintYear">
          <option value="">-- Mint --</option>
        </select>
      </td>
     </tr>

     <tr id="tr-coin-mint" style="display:none">
      <td><label><span class="subdiv">&rarr;</span><span class="subdiv showdiv">&rarr;</span> Mint</label></td>
      <td>
        <select name="coinMint" id="tr-coinMint">
          <option value="">-- Mint --</option>
        </select>
      </td>
     </tr>

     <tr id="tr-coin-minprice">
      <td><label>Min Price</label></td>
      <td>
        <input type="text" name="minPrice" value="<?= $minPrice ?>"/>
      </td>
     </tr>

     <tr id="tr-coin-maxprice">
      <td><label>Max Price</label></td>
      <td>
        <input type="text" name="maxPrice" value="<?= $maxPrice ?>"/>
      </td>
     </tr>

     <tr id="tr-discount">
      <td><label>Discount Percentage</label></td>
      <td>
        <select name="discountPercentage" id="tr-discountPercentage">
          <option value="">-- % --</option>

      <?php
        foreach (range(1, 100) as $num) {
          print "<option value='$num'";
          if ($num == $discountPercentage) {
            print " selected='selected'";
          }
          print ">$num%</option>";
        }
      ?>          
        </select>
      </td>
     </tr>

     <tr id="tr-premium">
      <td><label>Premium Percentage</label></td>
      <td>
        <select name="premiumPercentage" id="tr-premiumPercentage">
          <option value="">-- % --</option>

      <?php
        foreach (range(1, 500) as $num) {
          print "<option value='$num'";
          if ($num == $premiumPercentage) {
            print " selected='selected'";
          }
          print ">$num%</option>";
        }
      ?>          
        </select>
      </td>
     </tr>

     <tr id="tr-auction-end">
      <td><label>Auction End Time</label></td>
      <td>
        <select name="auctionEndDay" id="tr-auctionEndDay">
          <option value="">-- Days --</option>

      <?php
        foreach (range(0, 30) as $num) {
          print "<option value='$num'";
          if ($num == $auctionEndDay) {
            print " selected='selected'";
          }
          print ">$num</option>";
        }
      ?>          
        </select>
        <strong>:</strong>
        <select name="auctionEndHour" id="tr-auctionEndHour">
          <option value="">-- Hours --</option>

      <?php
        foreach (range(0, 23) as $num) {
          print "<option value='$num'";
          if ($num == $auctionEndHour) {
            print " selected='selected'";
          }
          print ">$num</option>";
        }
      ?>          
        </select>
        <strong>:</strong>
        <select name="auctionEndMinute" id="tr-auctionEndMinute">
          <option value="">-- Minutes --</option>

      <?php
        foreach (range(0, 59) as $num) {
          print "<option value='$num'";
          if ($num == $auctionEndMinute) {
            print " selected='selected'";
          }
          print ">$num</option>";
        }
      ?>          
        </select>
      </td>
     </tr>

     <tr id="tr-gradeCategory">
       <td><label>Grade Category</label></td>
       <td>
         <select name="gradeCategory" id="tr-grade-category">
           <option value="">-- Category --</option>
           
<?php
$categories = getRatingCategories();
foreach($categories as $category) {
  print '<option value="' . get($category, 'id') . '"';
  if (get($category, 'id') == $gradeCategory) {
    print " selected='selected'";
  }
  print '>' . get($category, 'description') . ' (' . get($category, 'title') . ')</option>';
}
?>           
         </select>
       </td>
     </tr>

     <tr id="tr-gradeRating" style="display:none">
       <td><label><span class="subdiv showdiv">&rarr;</span> Specific Grade</label></td>
       <td>
         <select name="grade" id="tr-grade">
          <option value="">-- Specific Grade --</option>
         </select>
       </td>
     </tr>
     
     <tr id="tr-sellerRating">
      <td><label>Seller Min Rating</label></td>
      <td>
        <input type="text" name="sellerMinRating" value="<?= $sellerMinRating ?>"/>
      </td>
     </tr>

     <tr id="tr-ratingAgency">
      <td><label>Rating Agency</label></td>
      <td>
        <select name="ratingAgency">
          <option value="">-- Rating Agency --</option>
          <option value="ANACS" <?php if ($ratingAgency == 'ANACS') { echo "selected='selected'"; } ?>>ANACS</option>
          <option value="ICG" <?php if ($ratingAgency == 'ICG') { echo "selected='selected'"; } ?>>ICG</option>
          <option value="NGC" <?php if ($ratingAgency == 'NGC') { echo "selected='selected'"; } ?>>NGC</option>
          <option value="PCGS" <?php if ($ratingAgency == 'PCGS') { echo "selected='selected'"; } ?>>PCGS</option>
        </select>
      </td>
     </tr>

     <tr id="tr-isBuyItNow">
      <td><label>Is Buy It Now?</label></td>
      <td>
        <input name="isBuyItNow" id="tr-isBuyItNow" type="checkbox"
        <?php if ($isBuyItNow==true) { echo " checked='checked'"; } ?>
        />
      </td>
     </tr>

     <tr id="tr-phraseToAdd">
      <td><label>Phrases To Add</label></td>
      <td>
        <textarea name="phrasesToAdd" rows="4" style="width:80%"><?= $phrasesToAdd ?></textarea>
      </td>
     </tr>

     <tr id="tr-phraseToRemove">
      <td><label>Phrases To Remove</label></td>
      <td>
        <textarea name="phrasesToRemove" rows="4" style="width:80%"><?= $phrasesToRemove ?></textarea>
      </td>
     </tr>

     <tr id="tr-email">
      <td><label>Emails</label></td>
      <td>
        <textarea name="emails" rows="4" style="width:80%"><?= $emails ?></textarea>
      </td>
     </tr>

    </table>    
    
    <br /><br />
    <input type="hidden" name="formSubmitted" value="1" />
    <input type="submit" value="Add Tracker" />
  </form>
</fieldset>

