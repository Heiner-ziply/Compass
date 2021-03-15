<?php
debug ("Loading service inc file");


function parse_new_data () {
  global $dds;
        debug ("Handling new service data");

  #First, make sure that the specified environment exists for the service
  
  $ENV_CL=sanitizeGetVar('env_class');
  $APP_ID=sanitizeGetVar('app_id',FILTER_SANITIZE_NUMBER_INT);

  $SQL="INSERT INTO environment(ins_user,env_id,env_name,app_id,env_class) ";
  $SQL .=" SELECT '" . $_SESSION['username'] . "',x.nextID,a.app_code ||'-'||'".$ENV_CL."','".$APP_ID."','".$ENV_CL."' ";
  $SQL .=" from (select max(env_id)+1 as nextID FROM environment) x, ";
  $SQL .=" (select app_code from application where app_id='".$APP_ID."') a ";
  $SQL .=" where not exists (select 8 from environment e ";
  $SQL .=" where app_id='".$APP_ID."' and env_class='".$ENV_CL."')";
	$dds->setSQL($SQL);

  $IPADDR=sanitizeGetVar2('ip');
  $PRTNUM=sanitizeGetVar2('port',FILTER_SANITIZE_NUMBER_INT);
  $HOSTID=sanitizeGetVar2('host_id',FILTER_SANITIZE_NUMBER_INT);
  $USERNM=sanitizeGetVar2('host_username');
  $SBRAND=sanitizeGetVar2('server_brand');
  $S_TYPE=sanitizeGetVar2('service_type');
  $TEAMID=sanitizeGetVar2('team_id',FILTER_SANITIZE_NUMBER_INT);
  $SVCNAM=sanitizeGetVar2('servicename');

  $SQL="INSERT INTO SERVICE(INS_USER,SERVICE_ID,STATUS";
  if ($IPADDR) $SQL.=" ,IP";
  if ($PRTNUM) $SQL.=" ,PORT";
  if ($HOSTID) $SQL.=" ,HOST_ID";
  if ($USERNM) $SQL.=" ,HOST_USERNAME";
  if ($SBRAND) $SQL.=" ,SERVER_BRAND";
  if ($S_TYPE) $SQL.=" ,SERVICE_TYPE";
  if ($SVCNAM) $SQL.=" ,SERVICENAME";
  if ($TEAMID) $SQL.=" ,SUPPORT_TEAM_ID";

  $SQL.=") select lower('".$_SESSION['username']."'), max(service_id) + 1, 'Active'";
  if ($IPADDR) $SQL.=" ,'".$IPADDR."'";
  if ($PRTNUM) $SQL.=" ,'".$PRTNUM."'";
  if ($HOSTID) $SQL.=" ,'".$HOSTID."'";
  if ($USERNM) $SQL.=" ,'".$USERNM."'";
  if ($SBRAND) $SQL.=" ,'".$SBRAND."'";
  if ($S_TYPE) $SQL.=" ,'".$S_TYPE."'";
  if ($SVCNAM) $SQL.=" ,'".$SVCNAM."'";
  if ($TEAMID) $SQL.=" ,'".$TEAMID."'";

  $SQL.=" FROM service";

  #echo "<P> SQL: $SQL</P>"
  $dds->setSQL($SQL);

	#Now assign the service to its environment
  $SQL="INSERT INTO m_env_service (ins_user,env_id,service_id) ";
  $SQL.=" SELECT '".$_SESSION['username'] ."',max(e.env_id),max(s.service_id) ";
  $SQL.=" FROM environment e,service s where e.app_id='".$APP_ID."' ";
  $SQL.=" and e.env_class='".$ENV_CL."' and s.servicename='".$SVCNAM."'";
  $dds->setSQL($SQL);

  echo 'Service registered. <br> <a href="dataentry.php">Back</a>';
  echo "</body></html>";

}

function show_form () {
  global $dds;
debug ("Presenting new service form");
echo '
<H1>New Service</H1>
<form action="dataentry.php">
  <input type="hidden" id="newdata" name="newdata" value="service">

  <label for="host_id">Runs on host:</label>
  <select id="host_id"  required name="host_id">
  <option></option>';

$sql="select '<option value='|| chr(34)  || host_id ||  chr(34) ||'>' || hostname || nvl2(domain,'.' || domain,'') || '</option>' from host order by hostname";
$result=$dds->setSQL($sql);
while ($row=$dds->getNextRow()) echo $row[0];


echo '
  </select><br> 

  <label for="host_username">Runs as (lowercase username, optional):</label><br>
  <input type="text" id="host_username" name="host_username" pattern="[a-z0-9]*" size=12 maxlength="30" value=""><br><br>
  
  
    <label for="service_type">Service type:</label>
  <select id="service_type"  required name="service_type">
  <option></option>
  <option value="App">Application</option>
  <option value="Database">Database</option>
  <option value="Web">HTTP</option>
   </select><br> 
  
  
    <label for="server_brand">Server brand (optional):</label>
  <select id="server_brand"  name="server_brand">
  <option></option>
    <option value="Cassandra">Cassandra DB</option>
      <option value="DB2">DB2</option>
        <option value="Informix">Informix</option>
  <option value="MSSQL">MS SQL</option>
  <option value="MongoDB">MongoDB</option>
  <option value="MySQL">MySQL/MariaDB</option>
  <option value="Oracle">Oracle</option>
    <option value="PostgreSQL">PostgreSQL</option>
   </select><br>

   
   <label for="servicename">Service name (e.g. Oracle SID; use hostname if not sure):</label><br>
  <input type="text" id="servicename" name="servicename" required size=30 value=""><br><br>


  <label for="app_id">Application:</label>
  <select id="app_id"  required name="app_id">
  <option></option>';

$sql="select '<option value='|| chr(34) || app_id ||  chr(34) ||'>' || app_code || nvl2(app_name, '- '|| app_name ,'') || '</option>' from application order by app_code";
$result=$dds->setSQL($sql);
while ($row=$dds->getNextRow()) echo $row[0];

echo '
  </select><br>

   <label for="env_class">Environment:</label>
  <select id="env_class" required name="env_class">
  <option></option>
  <option value="Production">Production</option>
  <option value="Testing">Test</option>
  <option value="Development">Development</option>
  <option value="DR">Disaster recovery</option>
   </select><br><br>

  <label for="team_id">Support team:</label>
  <select id="team_id"  name="team_id">
  <option></option>';


$sql="select '<option value='|| chr(34) || id || chr(34) ||'>' || display_name || '</option>' from support_team order by decode(id,0,0,1), display_name";
$result=$dds->setSQL($sql);
while ($row=$dds->getNextRow()) echo $row[0];


echo '</select><br><br>
  <label for="IP">IP address (if not reachable by host name):</label><br>
  <input type="text" id="IP" name="IP" size=16 maxsize="16" value=""><br>  
  <label for="port">Port (optional):</label><br>
  <input type="port" id="port" name="port" size=5 maxsize="5" value=""><br> <br>

  <input type="submit" value="Submit">';

}

?>