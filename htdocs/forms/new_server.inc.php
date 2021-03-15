<?php
debug ("Loading server inc file");

function parse_new_data () {
  global $dds;
  debug ("Handling new server data");
    $hostnam=sanitizeGetVar('hostname');   
    $domainx=sanitizeGetVar('domain');
    $ip_addr=sanitizeGetVar('ip');
    $ilom_ip=sanitizeGetVar('ilom');
    $virtual=sanitizeGetVar('virtual');
    $os_type=sanitizeGetVar('ostype');
    $make_xx=sanitizeGetVar('make');
    $model_x=sanitizeGetVar('model');
    $cputype=sanitizeGetVar('cputype');
    $cpu_cnt=sanitizeGetVar('cpuct',FILTER_SANITIZE_NUMBER_INT);
    $cpu_spd=sanitizeGetVar('cpuspeed',FILTER_SANITIZE_NUMBER_INT);
    $memory_=sanitizeGetVar('memory',FILTER_SANITIZE_NUMBER_INT);
    $comment=sanitizeGetVar('comments');
    $loction=sanitizeGetVar('location',FILTER_SANITIZE_NUMBER_INT);
    $rack_xx=sanitizeGetVar('rack');
    $serial_=sanitizeGetVar('serial');

        //[[ $VIRTUAL == "" ]] && unset VIRTUAL

        $SQL="INSERT INTO HOST(INS_USER,DEVICE_TYPE,HOST_ID,HOSTNAME,DOMAIN";
        if(isset($ip_addr)) $SQL .= ", IP" ;
        if(isset($ilom_ip)) $SQL .= ", ILOM_IP" ;
        if(isset($virtual)) $SQL .= ", VIRTUALIZATION" ;
        if(isset($os_type)) $SQL .= ", OS_TYPE" ;
        if(isset($make_xx)) $SQL .= ", MAKE" ;
        if(isset($model_x)) $SQL .= ", MODEL" ;
        if(isset($cputype)) $SQL .= ", CPU_TYPE" ;
        if(isset($cpu_cnt)) $SQL .= ", CPU_COUNT" ;
        if(isset($cpu_spd)) $SQL .= ", CPU_SPEED_MHZ" ;
        if(isset($memory_)) $SQL .= ", MEMORY_MB" ;
        if(isset($comment)) $SQL .= ", COMMENTS" ;
        if(isset($loction)) $SQL .= ", LOCATION_ID" ;
        if(isset($rack_xx)) $SQL .= ", SUBLOCATION" ;
        if(isset($serial_)) $SQL .= ", SERIALNUMBER" ;

        $SQL.=") select lower('".$_SESSION['username']."'),'Server', max(host_id) + 1, '".$hostnam."','".$domainx."'";

        if(isset($ip_addr)) $SQL .= ", '" . $ip_addr. "'";
        if(isset($ilom_ip)) $SQL .= ", '" . $ilom_ip. "'";
        if(isset($virtual)) $SQL .= ", '" . $virtual. "'";
        if(isset($os_type)) $SQL .= ", '" . $os_type. "'";
        if(isset($make_xx)) $SQL .= ", '" . $make_xx. "'";
        if(isset($model_x)) $SQL .= ", '" . $model_x. "'";
        if(isset($cputype)) $SQL .= ", '" . $cputype. "'";
        if(isset($cpu_cnt)) $SQL .= ", '" . $cpu_cnt. "'";
        if(isset($cpu_spd)) $SQL .= ", '" . $cpu_spd. "'";
        if(isset($memory_)) $SQL .= ", '" . $memory_. "'";
        if(isset($comment)) $SQL .= ", '" . $comment. "'";
        if(isset($loction)) $SQL .= ", '" . $loction. "'";
        if(isset($rack_xx)) $SQL .= ", '" . $rack_xx. "'";
        if(isset($serial_)) $SQL .= ", '" . $serial. "'";

        $SQL.=" FROM host";

        #echo "<P> SQL: $SQL</P>"
        $dds->setSQL($SQL);

        echo 'Server registered. <br> <a href="dataentry.php">Back</a>';
        echo "</body></html>";


}

function show_form () {
  global $dds;
  
echo '<H1>New server</H1>
<form action="dataentry.php">
<input type="hidden" id="newdata" name="newdata" value="server">

  <label for="hostname">Hostname (lowercase):</label><br>
    <input type="text" id="hostname" name="hostname" required pattern="[a-z0-9]*[-]*[a-z0-9]*" size=20 maxlength="30" value=""><br>

  <label for="domain">Domain:</label><br>
    <input type="text" id="domain" name="domain" required size=20 maxlength="30" value="nw1.nwestnetwork.com"><br><br>

    <label for="ostype">OS Type:</label>
  <select id="ostype" name="ostype">
    <option></option>
    <option value="UNIX">UNIX</option>
    <option value="Windows">Windows</option>
  </select><br><br>

  <label for="virtual">Virtualization:</label>
  <select id="virtual" name="virtual">
    <option></option>
    <option value="V">Virtual</option>
    <option value="P">Physical</option>
  </select><br><br>

    <label for="make">Make:</label>
    <input type="text" id="make" name="make" size=20 maxlength="20" value=""><br>
    <label for="make">Model:</label>
    <input type="text" id="model" name="model" size=20 maxlength="20" value=""><br>
    <label for="serial">Serial number:</label>
    <input type="text" id="serial" name="serial" size=20 maxlength="20" value=""><br><br>

    <label for="cputype">CPU Type:</label>
    <input type="text" id="cputype" name="cputype" size=20 maxlength="20" value=""><br>
    <label for="cpuct">CPU Count:</label>
    <input type="text" id="cpuct" name="cpuct" size=2 pattern="[0-9]*"  maxlength="3" value=""><br>
    <label for="cpuspeed">CPU Speed (MHz):</label>
    <input type="text" id="cpuspeed" name="cpuspeed" pattern="[0-9]*" size=4 maxlength="9" value=""><br><br>
    <label for="memory">Memory (MB):</label>
    <input type="text" id="memory" name="memory" pattern="[0-9]*" size=4 maxlength="9" value=""><br><br>

    <label for="ip">IP address:</label>
    <input type="text" id="ip" name="ip" size=20 maxlength="20" pattern="[0-9]*.[0-9]*.[0-9]*.[0-9]*" value=""><br>
    <label for="ilom">ILOM address:</label>
    <input type="text" id="ilom" name="ilom" size=20 maxlength="20" pattern="[0-9]*.[0-9]*.[0-9]*.[0-9]*" value=""><br><br>

  <label for="location">Data center:</label>
  <select id="location" name="location">
    <option></option>
    <option value="1">Fort Wayne, Oakbrook Pkwy.</option>
    <option value="2">Everett, Casino Rd.</option>
  </select><br>

    <label for="rack">Rack location:</label>
    <input type="text" id="rack" name="rack" size=20 maxlength="20" value=""><br><br>

  <label for="comments">Comments (optional):</label><br>
  <textarea id="comments" name="comments" rows=5 cols=60 value=""></textarea><br><br>


  <input type="submit" value="Submit"> 
</form>';

}

?>