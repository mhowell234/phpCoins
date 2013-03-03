<fieldset id="add">
  <legend>Add a Coin</legend>

  <form action="." method="post">
    <h3 style="float:right; cursor:pointer"><a onclick="clearOurCoin()">Clear</a></h3>

    <br />
<?php
 if ($error != '') {
   echo "<p class='error'>$error</p>";
 }
?>
      
    <br />
        
    <table id="coin-search">

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

     <tr id="tr-coin-pricepaid">
      <td><label>Price Paid</label></td>
      <td>
        <input type="text" name="pricePaid" value="<?= $PricePaid ?>"/>
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
     
     <tr id="tr-origin">
       <td><label>Received/Bought From</label></td>
       <td>
         <select name="origin" id="tr-origin">
           <option value="">-- Received From --</option>
           
<?php
$origins = getCoinOrigins();
foreach($origins as $orig) {
  print '<option value="' . get($orig, 'id') . '"';
  if (get($orig, 'id') == $origin) {
    print " selected='selected'";
  }
  print '>' . get($orig, 'name') . '</option>';
}
?>           
         </select>
       </td>
     </tr>
     
     <tr id="tr-originDate">
      <td><label>Date Received/Bought</label></td>
      <td>
        <input type="text" name="originDate" value="<?= $originDate ?>"/>
      </td>
     </tr>


     <tr id="tr-isWrapped">
      <td><label>Is Wrapped?</label></td>
      <td>
        <input name="isWrapped" id="tr-isWrapped" type="checkbox"
        <?php if ($isWrapped==true) { echo " checked='checked'"; } ?>
        />
      </td>
     </tr>

     <tr id="tr-isProof">
      <td><label>Is Proof?</label></td>
      <td>
        <input name="isProof" id="tr-isProof" type="checkbox"
        <?php if ($isProof==true) { echo " checked='checked'"; } ?>
        />
      </td>
     </tr>

     <tr id="tr-notes">
      <td><label>Notes</label></td>
      <td>
        <textarea name="notes" rows="4" style="width:80%"><?= $notes ?></textarea>
      </td>
     </tr>


    </table>    
    
    <br /><br />
    <input type="hidden" name="formSubmitted" value="1" />
    <input type="submit" value="Add Our Coin" />
  </form>
</fieldset>

