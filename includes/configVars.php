<?php
/**
 * Configuration file for Tiger Laundry (2011 Recode Version)
 * 
 * This file contains all the variables for making the website work
 * 
 * @author Ben Russell (benrr101@csh.rit.edu) 
 */
 
 /* The array */
 $_TLCONFIG = array();
 
 // Database Configuration ----------------------
 
 /* Database Host -
  * This is the address of the database server. In most cases, this is localhost
  */
 $_TLCONFIG['db_host'] = 'localhost';
 
 /* Database Username -
  * This is the username that has permission to write/read from the TigerLaundry
  * database. This should /not/ be your database root user
  */
 $_TLCONFIG['db_user'] = 'user';
 
 /* Database Password -
  * Password for the username provided. 
  */
 $_TLCONFIG['db_pass'] = 'pass';
 
 /* Database Name -
  * The name of the database that contains the TigerLaundry information
  */
 $_TLCONFIG['db_name'] = 'db';
?>
