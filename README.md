# Overdrive

## An application for viewing and navigating IT infrastructure data

Forked from buckaroo-labs/Compass

This application was developed for a production support team responsible for hundreds of services running on hundreds of hosts for several applications, each with several environments. It became impractical to maintain the server inventory as PuTTY connections in each team member's Windows registry and/or as entries in each Oracle TNSNAMES file. So I created a database to store the information and UI for viewing it, including links in the browser which could launch a PuTTY or sqlplus session. 

Developed on PHP version 7.3.16, MySQL 8.0.18, and Hydrogen v0.5


