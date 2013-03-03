<?php
  include_once("$DOCUMENT_ROOT/include/utils.php");
?>

<nav>
  <ul>
    <li><a class="home" href="/">Home</a></li> 
    <li><a class="us-coins" href="/us-coins">U.S. Coins</a></li>
    <li><a class="foreign-coins" href="/foreign-coins">Foreign Coins</a></li>
    <li><a class="our-coins" href="/our-coins">Our Coins</a></li>
    <li><a class="tracker" href="/tracker">Trackers</a></li>    
<!--    <li><a class="add" href="/add">Add</a></li> -->
    <li><a class="search" href="/search">Search</a></li>
    <li><a class="admin" href="/admin">Admin</a></li>
  </ul>
  <div class="visualClear"></div>
</nav>


<div id="breadcrumb">
<p><?= breadcrumbs() ?></p>
</div>