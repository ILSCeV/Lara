<?php

/**
 * Config file for LDAP connection
 *
 */
return array(
	// bc-Club LDAP server, should be accessed via "localhost:60389" in production
    'server' => 'localhost:60389',

    // This is the LDAP admin account for queries
    'adminUsername' => 'exampleusername',
    'adminPassword' => 'examplepassword',

    // Organisation unit needed for club members (with a comma at the end)
    'bcou' => 'ou=People,ou=bc-club,',

    // Organisation unit needed for club members (with a comma at the end)
    'cafeou' => 'ou=People,ou=cafe,',

    // Group for bc-Clubleitung (with braces on both sides)
    'clgroup' => '(cn=bc-clubcl)',

    // Group for bc-Marketing (with braces on both sides)
    'marketinggroup' => '(cn=bcMarketing)',

    // Organisation unit for group search (with a comma at the end)
    'groupou' => 'ou=Groups,ou=bc-club,', 

    // Base dn on the server (without a comma at the front)
    'basedn' => 'o=ilsc',

    // Master password for LDAP downtime, hashed
    'ldapOverride' => 'store-this-in-hashed-form-only!',
);