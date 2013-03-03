<?php


//
// Get rating categories
//
function getRatingCategories() {
  global $RATING_CATEGORIES_SQL;
  
  $SQL = $RATING_CATEGORIES_SQL;  
  $result = mysql_query($SQL);
  
  $categories = array();
  if (mysql_num_rows($result) == 0) {
    return $categories;
  }

  while($row = mysql_fetch_array($result)) {
  
    $info = array();
    
    $id = $row['srcid'];
    $title = $row['title'];
    $description = $row['description'];
    
    $info['id'] = $id;
    $info['title'] = $title;
    $info['description'] = $description;
    
    array_push($categories, $info);
  }
  
  return $categories;
}



//
// Get rating scales
//
function getRatingScales() {
  global $RATING_SCALES_SQL;
  
  $SQL = $RATING_SCALES_SQL;  
  $result = mysql_query($SQL);
  
  $scales = array();
  if (mysql_num_rows($result) == 0) {
    return $scales;
  }

  while($row = mysql_fetch_array($result)) {
  
    $info = array();
    
    $id = $row['srsid'];
    $title = $row['title'];
    $value = $row['value'];
    $description = $row['description'];
    $category = $row['category'];
    
    $info['id'] = $id;
    $info['title'] = $title;
    $info['value'] = $value;
    $info['description'] = $description;
    $info['category'] = $category;
    
    array_push($scales, $info);
  }
  
  return $scales;
}


//
// Get rating values for a category
//
function getRatingsForCategory($categoryId) {
  global $RATING_VALUES_BY_CATEGORY_SQL;

  $SQL = sprintf($RATING_VALUES_BY_CATEGORY_SQL, $categoryId);  
  $result = mysql_query($SQL);

  $ratings = array();
  if (mysql_num_rows($result) == 0) {
    return $ratings;
  }
  
  while($row = mysql_fetch_array($result)) {
    $info = array();
    
    $id = $row['srsid'];
    $title = $row['title'];
    
    $info['id'] = $id;
    $info['title'] = $title;
    
    array_push($ratings, $info);
  }
  
  return $ratings;
}


//
// Get rating category by id
//
function getRatingCategoryById($categoryId) {
  global $RATING_CATEGORY_BY_ID_SQL;
  
  $SQL = sprintf($RATING_CATEGORY_BY_ID_SQL, $categoryId);  
  $result = mysql_query($SQL);

  $category = array();
  if (mysql_num_rows($result) == 0) {
    return $category;
  }
  
  $row = mysql_fetch_array($result);
  
  $category['id'] = $row['srcid'];
  $category['title'] = $row['title'];
  $category['description'] = $row['description'];
  $category['start'] = $row['start'];
  $category['end'] = $row['end'];
  $category['specialOrder'] = $row['specialOrder'];
  
  return $category;
}


//
// Get rating value by id
//
function getRatingById($ratingId) {
  global $RATING_BY_ID_SQL;

  $SQL = sprintf($RATING_BY_ID_SQL, $ratingId);  
  $result = mysql_query($SQL);

  $rating = array();
  if (mysql_num_rows($result) == 0) {
    return $rating;
  }
  
  $row = mysql_fetch_array($result);
  
  $rating['id'] = $row['srsid'];
  $rating['title'] = $row['title'];
  $rating['value'] = $row['value'];
  $rating['description'] = $row['description'];
  $rating['category'] = $row['category'];
  
  return $rating;
  
}


//
// Get all coin origins
//
function getCoinOrigins() {
  global $COIN_ORIGINS_SQL;
  
  $SQL = $COIN_ORIGINS_SQL;
  $result = mysql_query($SQL);
  
  $origins = array();
  if (mysql_num_rows($result) == 0) {
    return $origins;
  }

  while($row = mysql_fetch_array($result)) {
  
    $info = array();
    
    $id = $row['coid'];
    $name = $row['name'];
    
    $info['id'] = $id;
    $info['name'] = $name;
    
    array_push($origins, $info);
  }
  
  return $origins;
}


//
// Get coin origins by id
//
function getCoinOriginById($id) {
  global $COIN_ORIGIN_BY_ID_SQL;
  
  $SQL = sprintf($COIN_ORIGIN_BY_ID_SQL, $id);
  $result = mysql_query($SQL);

  $origin = array();
  if (mysql_num_rows($result) == 0) {
    return $origin;
  }
  
  $row = mysql_fetch_array($result);
  
  $origin['id'] = $row['coid'];
  $origin['name'] = $row['name'];
  
  return $origin;
}


//
// Get all rating agencies
//
function getRatingAgencies() {
  global $RATING_AGENCIES_SQL;
  
  $SQL = $RATING_AGENCIES_SQL;
  $result = mysql_query($SQL);
  
  $agencies = array();
  if (mysql_num_rows($result) == 0) {
    return $agencies;
  }

  while($row = mysql_fetch_array($result)) {
  
    $info = array();
    
    $id = $row['raid'];
    $name = $row['name'];
    $fullName = $row['fullName'];
    
    $info['id'] = $id;
    $info['name'] = $name;
    $info['fullName'] = $fullName;
    
    array_push($agencies, $info);
  }
  
  return $agencies;
}


//
// Get coin origins by id
//
function getRatingAgencyById($id) {
  global $RATING_AGENCY_BY_ID_SQL;
  
  $SQL = sprintf($RATING_AGENCY_BY_ID_SQL, $id);
  $result = mysql_query($SQL);

  $agency = array();
  if (mysql_num_rows($result) == 0) {
    return $agency;
  }
  
  $row = mysql_fetch_array($result);
  
  $agency['id'] = $row['raid'];
  $agency['name'] = $row['name'];
  $agency['fullName'] = $row['fullName'];
  
  return $agency;
}


//
// Get all precious metals
//
function getPreciousMetals() {
  global $PRECIOUS_METALS_SQL;
  
  $SQL = $PRECIOUS_METALS_SQL;
  $result = mysql_query($SQL);
  
  $metals = array();
  if (mysql_num_rows($result) == 0) {
    return $metals;
  }

  while($row = mysql_fetch_array($result)) {
  
    $info = array();
    
    $id = $row['pmid'];
    $name = $row['name'];
    $symbol = $row['symbol'];
    $unit = $row['unit'];
    $conversionFactor = $row['conversionFactor'];

    $info['id'] = $id;
    $info['name'] = $name;
    $info['symbol'] = $symbol;
    $info['unit'] = $unit;
    $info['conversionFactor'] = $conversionFactor;
    
    array_push($metals, $info);
  }
  
  return $metals;
}


//
// Get precious metal info by id
//
function getPreciousMetalById($id) {
  global $PRECIOUS_METAL_BY_ID_SQL;
  
  $SQL = sprintf($PRECIOUS_METAL_BY_ID_SQL, $id);
  $result = mysql_query($SQL);

  $metal = array();
  if (mysql_num_rows($result) == 0) {
    return $metal;
  }
  
  $row = mysql_fetch_array($result);
  
  $metal['id'] = $row['pmid'];
  $metal['name'] = $row['name'];
  $metal['symbol'] = $row['symbol'];
  $metal['unit'] = $row['unit'];
  $metal['conversionFactor'] = $row['conversionFactor'];
  
  return $metal;
}

?>