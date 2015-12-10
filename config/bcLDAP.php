<?php

/**
 * Config file for LDAP connection
 *
 */
return [
	// bc-Club LDAP server, should be accessed via "localhost:60389" in production
    'server' => 'localhost',
    'port' => '60389',

    // This is the LDAP admin account for queries
    'admin-username' => 'exampleusername',
    'admin-password' => 'examplepassword',



    // Organisation unit needed for club members (with a comma at the end)
    'bc-club-ou' => 'ou=People,ou=bc-club,',

    // Group for bc-Clubleitung (with braces on both sides)
    'bc-club-management-group' => '(cn=bc-clubcl)',

    // Group for bc-Marketing (with braces on both sides)
    'bc-club-marketing-group' => '(cn=bcMarketing)',

    // Organisation unit for group search (with a comma at the end)
    'bc-club-group-ou' => 'ou=Groups,ou=bc-club,', 



    // Organisation unit needed for club members (with a comma at the end)
    'bc-cafe-ou' => 'ou=People,ou=cafe,',

    // Group for Café-Marketing (with braces on both sides)
    'bc-cafe-management-group' => '(cn=cafecl)',

    // Group for Café-Clubleitung (with braces on both sides)
    'bc-cafe-marketing-group' => '(cn=cafeKultur)',

    // Organisation unit for CAFE group search (with a comma at the end)
    'bc-cafe-group-ou' => 'ou=Groups,ou=cafe,', 




    // Base dn on the server (without a comma at the front)
    'base-dn' => 'o=ilsc',

    // Master password for LDAP downtime, hashed
    'ldap-override' => 'store-this-in-hashed-form-only!',
];