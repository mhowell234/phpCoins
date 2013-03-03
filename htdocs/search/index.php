<?php
  $navsection = 'search';

  include_once('../include/init.php');
  include_once("$DOCUMENT_ROOT/include/utils.php");
  
  $searchText = request('searchText');
  $ourCoinSearch = request('ourCoin');

  headerTitleMenu("Coins");

?>

    <h2>Search</h2>
   
    <p>Use the search box below to search for coins.</p>

    <fieldset id="search">
      <legend>Search</legend>
      
      <form action="." method="get">
        <input type="text" name="searchText" value='<?= $searchText ?>' />
        <input type="submit" value="Search" />
        <div style="padding-top:5px; padding-left:5px">
          <strong>Only Our Coins? : </strong> 
          <input type="checkbox" name="ourCoin" 
<?php 
if ($ourCoinSearch) {
  echo " checked='checked'";
}
echo '/>';
?>

        </div>   
      </form>
    </fieldset>

<?php
  if ($searchText) {
    echo "<h3>Search Results</h3>";
    
    include("$DOCUMENT_ROOT/include/sections/search.php");
  }
  
  endHeaderTitleMenu();
  
  include("$DOCUMENT_ROOT/include/html/footer.php"); 
?>
