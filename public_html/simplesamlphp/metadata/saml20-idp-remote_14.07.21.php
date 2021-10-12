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
MIIGLzCCBRegAwIBAgIQAr37BsCBpg8Fi9qkgyja1DANBgkqhkiG9w0BAQsFADBe
MQswCQYDVQQGEwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3
d3cuZGlnaWNlcnQuY29tMR0wGwYDVQQDExRSYXBpZFNTTCBSU0EgQ0EgMjAxODAe
Fw0xODA4MTQwMDAwMDBaFw0yMDAxMzAxMjAwMDBaMBkxFzAVBgNVBAMMDiouY29s
bWFuLmFjLmlsMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqZFWGfm+
evlfTKEPir3oRjbnyKuFupXQruCYzZQOWH3Ev5zK5jPET/2UjQMPHZlTBD4jKVK0
3IEH5dMqLZfLt9VPQmV5Nrjhi1xoj8kznOMpZrJjGf8Wi7LVmniswdNEEVixrnrh
uEOkvqraHT1GqadrigxYFmabfv5fh6DPyJMc3X1o8suGhfQW+AMxwF0+xGEDYhlR
DNk6Jm1pYRhI+0P0gsZLiOzcNrmjTcx+H9cNr77SCToIB7qGvYYOrBeacdzy06h9
HPUg2qWLGFPjRQDw0BDobixtt+yP5JRO3NsUkYqeNCYOniLtUGF5G7D/j4mDQn2e
MwONUuJMrMXvxwIDAQABo4IDLDCCAygwHwYDVR0jBBgwFoAUU8oXWfxrwAMhLxqu
5KqoHIJW2nUwHQYDVR0OBBYEFEFffjr/2lXb0T8QI6RdwMRk2N5VMCcGA1UdEQQg
MB6CDiouY29sbWFuLmFjLmlsggxjb2xtYW4uYWMuaWwwDgYDVR0PAQH/BAQDAgWg
MB0GA1UdJQQWMBQGCCsGAQUFBwMBBggrBgEFBQcDAjA+BgNVHR8ENzA1MDOgMaAv
hi1odHRwOi8vY2RwLnJhcGlkc3NsLmNvbS9SYXBpZFNTTFJTQUNBMjAxOC5jcmww
TAYDVR0gBEUwQzA3BglghkgBhv1sAQIwKjAoBggrBgEFBQcCARYcaHR0cHM6Ly93
d3cuZGlnaWNlcnQuY29tL0NQUzAIBgZngQwBAgEwdQYIKwYBBQUHAQEEaTBnMCYG
CCsGAQUFBzABhhpodHRwOi8vc3RhdHVzLnJhcGlkc3NsLmNvbTA9BggrBgEFBQcw
AoYxaHR0cDovL2NhY2VydHMucmFwaWRzc2wuY29tL1JhcGlkU1NMUlNBQ0EyMDE4
LmNydDAJBgNVHRMEAjAAMIIBfAYKKwYBBAHWeQIEAgSCAWwEggFoAWYAdQCkuQmQ
tBhYFIe7E6LMZ3AKPDWYBPkb37jjd80OyA3cEAAAAWU4Ab0HAAAEAwBGMEQCIEo0
DhzDpIJsr/DLTPc5PqQtsTolee4tf50HozJ5FyHuAiBCA+T8M9o+Dqv29IdX6aRf
LfkL6Yhm4LuBVFN0MJi4MgB1AId1v+dZfPiMQ5lfvfNu/1aNR1Y2/0q1YMG06v9e
oIMPAAABZTgBvm4AAAQDAEYwRAIgERQTziCUuyvDz1ZCwgttm7acCUorxqew0VEL
E+MP8GUCIE0y0k7p5F6PWIZ1dbiT0Ly1pIh2OZk4WWgR2PaY0hpFAHYAu9nfvB+K
cbWTlCOXqpJ7RzhXlQqrUugakJZkNo4e0YUAAAFlOAG+AAAABAMARzBFAiEA89Vq
HAfSdMC70OFTcvWA6Cajvd72Fgyd23MPZe5SfP8CICuX7OGhNI9yjCaCC/yYslmY
jLaDRxhzbADOh79hZf+eMA0GCSqGSIb3DQEBCwUAA4IBAQBvTxsKzlIOwgXfUSo7
HgcjX4IyisqEVVnvKYJh3ix6shmdQP+aqPvn5qoezHtqsGjfpBy5Y3J5jULgLDo7
x8rEADmXO9gwtu5Vc6DH2jlPXA0+7+iRGizaMJhd2Y3kAm4m6B1cVrV6ZbiSVPTF
WYZOXE43Cia8/ZVQ3ugLAV8uSDFTZw70+yOv/BDMQ+AulfdmGY7YY9zi84TUX4l1
A5bo3R5dxVP1XRE4t+e248rs94iifIZ9X/BTbHeFDsgbBtNCTJOmgVGvOeH1CuuW
FUB42WaDwODyg7+PFVobVy/NocdWp3jFj9pQxAAt80djaBD4l6v4vGEtqWGLyKBY
lJmQ
',
    ),
    1 => 
    array (
      'encryption' => true,
      'signing' => false,
      'type' => 'X509Certificate',
      'X509Certificate' => '
MIIGLzCCBRegAwIBAgIQAr37BsCBpg8Fi9qkgyja1DANBgkqhkiG9w0BAQsFADBe
MQswCQYDVQQGEwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3
d3cuZGlnaWNlcnQuY29tMR0wGwYDVQQDExRSYXBpZFNTTCBSU0EgQ0EgMjAxODAe
Fw0xODA4MTQwMDAwMDBaFw0yMDAxMzAxMjAwMDBaMBkxFzAVBgNVBAMMDiouY29s
bWFuLmFjLmlsMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqZFWGfm+
evlfTKEPir3oRjbnyKuFupXQruCYzZQOWH3Ev5zK5jPET/2UjQMPHZlTBD4jKVK0
3IEH5dMqLZfLt9VPQmV5Nrjhi1xoj8kznOMpZrJjGf8Wi7LVmniswdNEEVixrnrh
uEOkvqraHT1GqadrigxYFmabfv5fh6DPyJMc3X1o8suGhfQW+AMxwF0+xGEDYhlR
DNk6Jm1pYRhI+0P0gsZLiOzcNrmjTcx+H9cNr77SCToIB7qGvYYOrBeacdzy06h9
HPUg2qWLGFPjRQDw0BDobixtt+yP5JRO3NsUkYqeNCYOniLtUGF5G7D/j4mDQn2e
MwONUuJMrMXvxwIDAQABo4IDLDCCAygwHwYDVR0jBBgwFoAUU8oXWfxrwAMhLxqu
5KqoHIJW2nUwHQYDVR0OBBYEFEFffjr/2lXb0T8QI6RdwMRk2N5VMCcGA1UdEQQg
MB6CDiouY29sbWFuLmFjLmlsggxjb2xtYW4uYWMuaWwwDgYDVR0PAQH/BAQDAgWg
MB0GA1UdJQQWMBQGCCsGAQUFBwMBBggrBgEFBQcDAjA+BgNVHR8ENzA1MDOgMaAv
hi1odHRwOi8vY2RwLnJhcGlkc3NsLmNvbS9SYXBpZFNTTFJTQUNBMjAxOC5jcmww
TAYDVR0gBEUwQzA3BglghkgBhv1sAQIwKjAoBggrBgEFBQcCARYcaHR0cHM6Ly93
d3cuZGlnaWNlcnQuY29tL0NQUzAIBgZngQwBAgEwdQYIKwYBBQUHAQEEaTBnMCYG
CCsGAQUFBzABhhpodHRwOi8vc3RhdHVzLnJhcGlkc3NsLmNvbTA9BggrBgEFBQcw
AoYxaHR0cDovL2NhY2VydHMucmFwaWRzc2wuY29tL1JhcGlkU1NMUlNBQ0EyMDE4
LmNydDAJBgNVHRMEAjAAMIIBfAYKKwYBBAHWeQIEAgSCAWwEggFoAWYAdQCkuQmQ
tBhYFIe7E6LMZ3AKPDWYBPkb37jjd80OyA3cEAAAAWU4Ab0HAAAEAwBGMEQCIEo0
DhzDpIJsr/DLTPc5PqQtsTolee4tf50HozJ5FyHuAiBCA+T8M9o+Dqv29IdX6aRf
LfkL6Yhm4LuBVFN0MJi4MgB1AId1v+dZfPiMQ5lfvfNu/1aNR1Y2/0q1YMG06v9e
oIMPAAABZTgBvm4AAAQDAEYwRAIgERQTziCUuyvDz1ZCwgttm7acCUorxqew0VEL
E+MP8GUCIE0y0k7p5F6PWIZ1dbiT0Ly1pIh2OZk4WWgR2PaY0hpFAHYAu9nfvB+K
cbWTlCOXqpJ7RzhXlQqrUugakJZkNo4e0YUAAAFlOAG+AAAABAMARzBFAiEA89Vq
HAfSdMC70OFTcvWA6Cajvd72Fgyd23MPZe5SfP8CICuX7OGhNI9yjCaCC/yYslmY
jLaDRxhzbADOh79hZf+eMA0GCSqGSIb3DQEBCwUAA4IBAQBvTxsKzlIOwgXfUSo7
HgcjX4IyisqEVVnvKYJh3ix6shmdQP+aqPvn5qoezHtqsGjfpBy5Y3J5jULgLDo7
x8rEADmXO9gwtu5Vc6DH2jlPXA0+7+iRGizaMJhd2Y3kAm4m6B1cVrV6ZbiSVPTF
WYZOXE43Cia8/ZVQ3ugLAV8uSDFTZw70+yOv/BDMQ+AulfdmGY7YY9zi84TUX4l1
A5bo3R5dxVP1XRE4t+e248rs94iifIZ9X/BTbHeFDsgbBtNCTJOmgVGvOeH1CuuW
FUB42WaDwODyg7+PFVobVy/NocdWp3jFj9pQxAAt80djaBD4l6v4vGEtqWGLyKBY
lJmQ
',
    ),
  ),
);
