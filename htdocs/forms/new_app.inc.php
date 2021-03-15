<?php
debug ("Loading app inc file");

function validate_app_code () {
  global $dds;
  $retVal=false;
  $app_code=sanitizeGetVar('app_code');
  $sql="select count(*) from application where upper(app_code) = upper('" . $app_code . "')";
  $result=$dds->setSQL($sql);
  $row=$dds->getNextRow();
  if ($row[0]==0) $retVal=true;
  return $retVal;
}


function parse_new_data () {
  global $dds;
  debug ("Handling new app data");
        
        validate_app_code();
        $app_code=sanitizeGetVar('app_code');
        $app_desc=sanitizeGetVar('app_desc');
        $app_vend=sanitizeGetVar('app_vend');
        $app_name=sanitizeGetVar('app_name');
        $app_wiki=sanitizeGetVar('app_wiki',FILTER_SANITIZE_URL);
        $app_purl=sanitizeGetVar('app_purl',FILTER_SANITIZE_URL);
        
        $SQL="INSERT INTO application(INS_USER,APP_ID,APP_CODE,APP_NAME";
        if (isset($app_desc)) $SQL .= " ,DESCRIPTION";
        if (isset($app_vend)) $SQL .= " ,VENDOR_ID";
        if (isset($app_wiki)) $SQL .= " ,WIKI_PAGE";
        if (isset($app_purl)) $SQL .= " ,PRODUCT_URL";
        $SQL.=" ) SELECT lower('" . $_SESSION['username'] . "'), max(app_id) + 1, '" .$app_code ."','".$app_name. "' ";
        if (isset($app_desc)) $SQL .= " ,'" . $app_desc ."'";
        if (isset($app_vend)) $SQL .= " ,'" . $app_vend ."'";
        if (isset($app_wiki)) $SQL .= " ,'" . $app_wiki ."'";
        if (isset($app_purl)) $SQL .= " ,'" . $app_purl ."'";
        $SQL.=" FROM application";
        $dds->setSQL($SQL);
      
        echo 'App registered. <br> <a href="dataentry.php">Back</a>';
        echo "</body></html>";
}


function show_form () {
  global $dds;
  debug ("Showing new application form");

  echo '<H1>New application</H1>
  <form action="dataentry.php">
    <input type="hidden" id="newdata" name="newdata" value="app">
    <label for="code">App code (3 letters, uppercase):</label><br>
    <input type="text" id="code" name="app_code" required pattern="[A-Z]{3}" size=3 maxlength="3" value=""><br>
    <label for="name">Application name:</label><br>
    <input type="text" id="name" name="app_name" required size=60 value=""><br>
    <label for="desc">Description (optional):</label><br>
    <textarea id="descr" name="app_desc" rows=5 cols=60 value=""></textarea><br><br>
    <label for="app_wiki">Wiki page (optional):</label><br>
    <input type="text" id="app_wiki" name="app_wiki" size=100 massize="255" value=""><br> <br>
    <label for="app_purl">Vendor/product page (optional):</label><br>
    <input type="text" id="app_purl" name="app_purl" size=100 maxsize="255" value=""><br>
  <br>
    <label for="app_vend">Vendor (optional):</label>
    <select id="app_vend" name="app_vend">
      <option></option>';

  $sql="select '<option value=' ||chr(34) || vendor_id || chr(34) ||'>' || vendor_name || '</option>' from vendor order by vendor_name";
  $result=$dds->setSQL($sql);
  while ($row=$dds->getNextRow()) echo $row[0];

  echo '</select><br><br>
    <input type="submit" value="Submit">
  </form>';

}
?>
