<?php
  $navsection = 'add';

  include_once('../include/init.php');
  include("$DOCUMENT_ROOT/include/html/header.php");

?>

  <div id="main-wrapper">
    <?php include("$DOCUMENT_ROOT/include/html/searchSection.php"); ?>

    <h1>Coins</h1>
    <?php include("$DOCUMENT_ROOT/include/html/menu.php"); ?>

    <h2>Add a New Coin</h2>
   
    <p>Enter the coin information below to add a new coin.</p>

    <fieldset id="add">
      <legend>Add a Coin</legend>
      
      <form action="." method="post">
        
        <div>
          <label>Coin Kind:</label>
          <select name="coinType">
            <option value="">-- What kind of Coin? --</option>
            <option value="UsCoin">Coin from the US</option> 
            <option value="ForeignCoin">Coin from Another Country</option>
            <option value="OurCoin">One of Our Coins</option>
          </select>
        </div>

        <br />        
        <input type="submit" value="Add Coin" />
      </form>
    </fieldset>

   
    <div class="visualClear"></div>
  </div>
  
<?php include("$DOCUMENT_ROOT/include/html/footer.php"); ?>
