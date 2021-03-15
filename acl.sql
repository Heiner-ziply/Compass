BEGIN DBMS_NETWORK_ACL_ADMIN.CREATE_ACL (
acl => 'OVERDRIVE_APP-ziply-mail.xml',
description => 'access to Ziply mail server',
principal => 'OVERDRIVE_APP',
is_grant => TRUE,
privilege => 'connect');
END; 
/
BEGIN DBMS_NETWORK_ACL_ADMIN.ASSIGN_ACL (
acl=> 'OVERDRIVE_APP-ziply-mail.xml',
host=> 'mailrelay.nw1.nwestnetwork.com',
lower_port=> 25,
upper_port=> 25);
END;
/
