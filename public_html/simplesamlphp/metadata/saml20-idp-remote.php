<?php
/**
 * SAML 2.0 remote IdP metadata for simpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-remote 
 */

/*
 * Guest IdP. allows users to sign up and register. Great for testing!
 */
$metadata['https://openidp.feide.no'] = array(
	'name' => array(
		'en' => 'Feide OpenIdP - guest users',
		'no' => 'Feide Gjestebrukere',
	),
	'description'          => 'Here you can login with your account on Feide RnD OpenID. If you do not already have an account on this identity provider, you can create a new one by following the create new account link and follow the instructions.',

	'SingleSignOnService'  => 'https://openidp.feide.no/simplesaml/saml2/idp/SSOService.php',
	'SingleLogoutService'  => 'https://openidp.feide.no/simplesaml/saml2/idp/SingleLogoutService.php',
	'certFingerprint'      => 'b32309bb454921de695618ab8cc68033abc8797c'
	
);
$metadata['https://is.colman.ac.il/nidp/saml2/metadata'] = array (
  'entityid' => 'https://is.colman.ac.il/nidp/saml2/metadata',
  'name' => 
  array (
    'en' => 'Colman',
  ),
  'description' => 
  array (
    'en' => 'Colman',
  ),
  'OrganizationName' => 
  array (
    'en' => 'Colman',
  ),
  'OrganizationDisplayName' => 
  array (
    'en' => 'Colman',
  ),
  'url' => 
  array (
    'en' => 'http://www.colman.ac.il',
  ),
  'OrganizationURL' => 
  array (
    'en' => 'http://www.colman.ac.il',
  ),
  'contacts' => 
  array (
    0 => 
    array (
      'contactType' => 'other',
    ),
  ),
  'metadata-set' => 'saml20-idp-remote',
  'SingleSignOnService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
      'Location' => 'https://is.colman.ac.il/nidp/saml2/sso',
    ),
    1 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://is.colman.ac.il/nidp/saml2/sso',
    ),
  ),
  'SingleLogoutService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
      'Location' => 'https://is.colman.ac.il/nidp/saml2/slo',
      'ResponseLocation' => 'https://is.colman.ac.il/nidp/saml2/slo_return',
    ),
    1 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP',
      'Location' => 'https://is.colman.ac.il/nidp/saml2/soap',
    ),
    2 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://is.colman.ac.il/nidp/saml2/slo',
      'ResponseLocation' => 'https://is.colman.ac.il/nidp/saml2/slo_return',
    ),
  ),
  'ArtifactResolutionService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP',
      'Location' => 'https://is.colman.ac.il/nidp/saml2/soap',
      'index' => 0,
      'isDefault' => true,
    ),
  ),
  'keys' => 
  array (
    0 => 
    array (
      'encryption' => false,
      'signing' => true,
      'type' => 'X509Certificate',
      'X509Certificate' => '
MIIGMDCCBRigAwIBAgIQB0tC8FeTH8o475hrCA/oMjANBgkqhkiG9w0BAQsFADBeMQswCQYDVQQG
EwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3d3cuZGlnaWNlcnQuY29tMR0w
GwYDVQQDExRSYXBpZFNTTCBSU0EgQ0EgMjAxODAeFw0yMDAxMjYwMDAwMDBaFw0yMjAyMTcxMjAw
MDBaMBkxFzAVBgNVBAMMDiouY29sbWFuLmFjLmlsMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIB
CgKCAQEA4uMln+PDHXNMIVL4WUNt33PvWwQjntD9MenIlj+WZHdcj4UQVKr6/c0+aMfODBGTItM2
B8UxMZmI+dFFSFzJ+JGKzlpoZv4j8w2rtMPn1cYwUvYgrgbx+o4WWdKCJGhVAc6AzWmvaONsU0vX
Fo0vOkZSJIe0zRS+K22bXshkCuTslc5cMfNww0pjQXYBgS/siAnrI5fmKVTF1T+ItLwg6z3f2Dvi
znQJnfnDZF8U30LWf98tZcEg6mRn9iA3udYHEx5l51/QRTtKGllrVa3vzCHRxTxHXXAIoWlftS+R
P+XYKIWvVO4VHts/FoXdgGB6gUgI99SZw3nmI9FYy6KI/QIDAQABo4IDLTCCAykwHwYDVR0jBBgw
FoAUU8oXWfxrwAMhLxqu5KqoHIJW2nUwHQYDVR0OBBYEFLb8+Y3gvBrrjKQz/RpeauCOBcorMCcG
A1UdEQQgMB6CDiouY29sbWFuLmFjLmlsggxjb2xtYW4uYWMuaWwwDgYDVR0PAQH/BAQDAgWgMB0G
A1UdJQQWMBQGCCsGAQUFBwMBBggrBgEFBQcDAjA+BgNVHR8ENzA1MDOgMaAvhi1odHRwOi8vY2Rw
LnJhcGlkc3NsLmNvbS9SYXBpZFNTTFJTQUNBMjAxOC5jcmwwTAYDVR0gBEUwQzA3BglghkgBhv1s
AQIwKjAoBggrBgEFBQcCARYcaHR0cHM6Ly93d3cuZGlnaWNlcnQuY29tL0NQUzAIBgZngQwBAgEw
dQYIKwYBBQUHAQEEaTBnMCYGCCsGAQUFBzABhhpodHRwOi8vc3RhdHVzLnJhcGlkc3NsLmNvbTA9
BggrBgEFBQcwAoYxaHR0cDovL2NhY2VydHMucmFwaWRzc2wuY29tL1JhcGlkU1NMUlNBQ0EyMDE4
LmNydDAJBgNVHRMEAjAAMIIBfQYKKwYBBAHWeQIEAgSCAW0EggFpAWcAdgC72d+8H4pxtZOUI5eq
kntHOFeVCqtS6BqQlmQ2jh7RhQAAAW/j38dpAAAEAwBHMEUCIByaer9IGvFOE18Fenacv+JbIMLP
k/qxQ0VS6Ad0dZkyAiEAyNecVxnbZBO9RaacdM6Zpw1z5EhcN3kxFoCBZhaJ0B4AdgAiRUUHWVUk
VpY/oS/x922G4CMmY63AS39dxoNcbuIPAgAAAW/j38eQAAAEAwBHMEUCIQCGdTVUpKeqw/EQvkEQ
8mySAPqQtAKwbsKZxzjJ3fcLSwIgGMjlwG/XQwLMiL4QGdRjN3vHC+MHonOP+ziU19Duu48AdQBR
o7D1/QF5nFZtuDd4jwykeswbJ8v3nohCmg3+1IsF5QAAAW/j38fVAAAEAwBGMEQCIFSwZycbeHIG
MN74Q2jQw3YxnEtsegIXLErp9P+/bD3CAiAaqNPfwoouzxUg40ip8CJFWQyDsKamnttTDsBXyzx9
1zANBgkqhkiG9w0BAQsFAAOCAQEAsX/LS3S4ifaN98DP6afbo7qK4rSK9n2gsqKRiyShNDKBLDrl
HYKC35Zo+l+Npk7eCiOK24vAaSm3WapYSa1DwZxkL6WEf4qJZzw/yH9PP0I4xiDUno29CXxo0s/0
6Qn1CS5O4IQHaLro5MmU22AKP0f956ZOAYSSNLGBrohKnfWfdckmG313aTRYaJWC2LSImh9dcGmS
ahHzs1YIw7i1re7q8pB0jzHdvZPbnSMAfsbu7QmbkaOL6bLTHh4hiU43rSkWyULuK43OaNLAgbYl
htrmu12nCoAN/mxZl/gZH4J34Yni4+fzDtHySUBcC/k6YlNSU1FKRQZySz1rdzTlBA==
',
    ),
    1 => 
    array (
      'encryption' => true,
      'signing' => false,
      'type' => 'X509Certificate',
      'X509Certificate' => '
MIIGMDCCBRigAwIBAgIQB0tC8FeTH8o475hrCA/oMjANBgkqhkiG9w0BAQsFADBeMQswCQYDVQQG
EwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3d3cuZGlnaWNlcnQuY29tMR0w
GwYDVQQDExRSYXBpZFNTTCBSU0EgQ0EgMjAxODAeFw0yMDAxMjYwMDAwMDBaFw0yMjAyMTcxMjAw
MDBaMBkxFzAVBgNVBAMMDiouY29sbWFuLmFjLmlsMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIB
CgKCAQEA4uMln+PDHXNMIVL4WUNt33PvWwQjntD9MenIlj+WZHdcj4UQVKr6/c0+aMfODBGTItM2
B8UxMZmI+dFFSFzJ+JGKzlpoZv4j8w2rtMPn1cYwUvYgrgbx+o4WWdKCJGhVAc6AzWmvaONsU0vX
Fo0vOkZSJIe0zRS+K22bXshkCuTslc5cMfNww0pjQXYBgS/siAnrI5fmKVTF1T+ItLwg6z3f2Dvi
znQJnfnDZF8U30LWf98tZcEg6mRn9iA3udYHEx5l51/QRTtKGllrVa3vzCHRxTxHXXAIoWlftS+R
P+XYKIWvVO4VHts/FoXdgGB6gUgI99SZw3nmI9FYy6KI/QIDAQABo4IDLTCCAykwHwYDVR0jBBgw
FoAUU8oXWfxrwAMhLxqu5KqoHIJW2nUwHQYDVR0OBBYEFLb8+Y3gvBrrjKQz/RpeauCOBcorMCcG
A1UdEQQgMB6CDiouY29sbWFuLmFjLmlsggxjb2xtYW4uYWMuaWwwDgYDVR0PAQH/BAQDAgWgMB0G
A1UdJQQWMBQGCCsGAQUFBwMBBggrBgEFBQcDAjA+BgNVHR8ENzA1MDOgMaAvhi1odHRwOi8vY2Rw
LnJhcGlkc3NsLmNvbS9SYXBpZFNTTFJTQUNBMjAxOC5jcmwwTAYDVR0gBEUwQzA3BglghkgBhv1s
AQIwKjAoBggrBgEFBQcCARYcaHR0cHM6Ly93d3cuZGlnaWNlcnQuY29tL0NQUzAIBgZngQwBAgEw
dQYIKwYBBQUHAQEEaTBnMCYGCCsGAQUFBzABhhpodHRwOi8vc3RhdHVzLnJhcGlkc3NsLmNvbTA9
BggrBgEFBQcwAoYxaHR0cDovL2NhY2VydHMucmFwaWRzc2wuY29tL1JhcGlkU1NMUlNBQ0EyMDE4
LmNydDAJBgNVHRMEAjAAMIIBfQYKKwYBBAHWeQIEAgSCAW0EggFpAWcAdgC72d+8H4pxtZOUI5eq
kntHOFeVCqtS6BqQlmQ2jh7RhQAAAW/j38dpAAAEAwBHMEUCIByaer9IGvFOE18Fenacv+JbIMLP
k/qxQ0VS6Ad0dZkyAiEAyNecVxnbZBO9RaacdM6Zpw1z5EhcN3kxFoCBZhaJ0B4AdgAiRUUHWVUk
VpY/oS/x922G4CMmY63AS39dxoNcbuIPAgAAAW/j38eQAAAEAwBHMEUCIQCGdTVUpKeqw/EQvkEQ
8mySAPqQtAKwbsKZxzjJ3fcLSwIgGMjlwG/XQwLMiL4QGdRjN3vHC+MHonOP+ziU19Duu48AdQBR
o7D1/QF5nFZtuDd4jwykeswbJ8v3nohCmg3+1IsF5QAAAW/j38fVAAAEAwBGMEQCIFSwZycbeHIG
MN74Q2jQw3YxnEtsegIXLErp9P+/bD3CAiAaqNPfwoouzxUg40ip8CJFWQyDsKamnttTDsBXyzx9
1zANBgkqhkiG9w0BAQsFAAOCAQEAsX/LS3S4ifaN98DP6afbo7qK4rSK9n2gsqKRiyShNDKBLDrl
HYKC35Zo+l+Npk7eCiOK24vAaSm3WapYSa1DwZxkL6WEf4qJZzw/yH9PP0I4xiDUno29CXxo0s/0
6Qn1CS5O4IQHaLro5MmU22AKP0f956ZOAYSSNLGBrohKnfWfdckmG313aTRYaJWC2LSImh9dcGmS
ahHzs1YIw7i1re7q8pB0jzHdvZPbnSMAfsbu7QmbkaOL6bLTHh4hiU43rSkWyULuK43OaNLAgbYl
htrmu12nCoAN/mxZl/gZH4J34Yni4+fzDtHySUBcC/k6YlNSU1FKRQZySz1rdzTlBA==
',
    ),
  ),
);
