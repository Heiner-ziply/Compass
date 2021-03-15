<?php
debug ("loading vendor inc file");

function parse_new_data () {
        global $dds;
        $vendor=sanitizeGetVar('vendor_name');
        $SQL="Insert into Vendor (vendor_id, vendor_name) select max(vendor_id) +1, '". $vendor ."' from vendor";
        $dds->setSQL($SQL);
        echo 'Vendor registered. <br> <a href="dataentry.php">Back</a>';
        echo "</body></html>";

}

function show_form () {
        global $dds;
        echo '
        <H1>New vendor</H1>
        <form action="dataentry.php">
        <input type="hidden" id="newdata" name="newdata" value="vendor">
        <label for="vendor_name">Vendor name:</label><br>
        <input type="text" id="vendor_name" name="vendor_name" required size=60 value=""><br>
        <input type="submit" value="Submit">
        </form>';
}

?>