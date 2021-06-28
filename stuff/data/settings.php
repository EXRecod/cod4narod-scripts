<?php

//database
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'recodmod');
DEFINE('DB_USER', 'root');
DEFINE('DB_PASSWORD', '');


DEFINE('DONATE_DB_HOST', 'localhost');
DEFINE('DONATE_DB_NAME', 'recodmod');
DEFINE('DONATE_DB_USER', 'root');
DEFINE('DONATE_DB_PASSWORD', '');
 

DEFINE('PROJECT_NAME', 'ZONA-ATO-GAME.RU');

DEFINE('YANDEX_MONEY_STATUS', 1);
DEFINE('YU_MONEY_ID', '41001485874410');
DEFINE('DONATE_URL', 'https://zona-ato-game.ru/donate/');
DEFINE('DONATE_URL_STATS', 'https://zona-ato-game.ru/stats/');


DEFINE('WEBMONEY_STATUS', 0);
DEFINE('WEBMONEY', 'https://funding.webmoney.ru/widgets/horizontal/33984670-2e50-41f2-977b-7cba95e2a9ce?bt=0&amp;hs=1&amp;sum=100&amp;hcc=1&amp;hym=1&amp;hmc=1');


DEFINE('PAYPAL_STATUS', 0);
DEFINE('PAYPAL_URL', 'https://www.paypal.me/volkv');
DEFINE('PAYPAL_USER', 'volkv'); 
DEFINE('PAYPAL_KEY', '-----BEGIN PKCS7-----MIIHTwYJKoZIhvcNAQcEoIIHQDCCBzwCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYB0kkePFhk5eVHTbOkdXgwFpCcL7ywlhkZOaOwWoNNXjUsFchU+shZGqRz9XVmSok7C9NM3K4YfbZBan9hl+DE771QYE3vXqOXrvionvoFsm+3NP9FSrwX9MestbJp4NvlI+rCIP5yE9mmdNPQFiWrfCb9sYKqdk9Wkus7HsoYgdjELMAkGBSsOAwIaBQAwgcwGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIJJVP5m3QG7GAgahZAkIw+6HzP88MBAHodE0naNqZDmQV05urIjbt0mXkInhWxQDV542V4BAI11Ny4fNXyZnjqEGgIu/tytSanV6lzJLjy+t1WB8WAOLYAluC7vxt0cokmNCYOaP470agh9h5tox/BOkSNf46+IJLeu2Gtme5ZPrfxzoyrcGLmN4lRltUss0xr8aWodYdKX16GJSgtGYRxtwCf6GtSbX5bzSSihMtfyzQX22gggOHMIIDgzCCAuygAwIBAgIBADANBgkqhkiG9w0BAQUFADCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wHhcNMDQwMjEzMTAxMzE1WhcNMzUwMjEzMTAxMzE1WjCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAMFHTt38RMxLXJyO2SmS+Ndl72T7oKJ4u4uw+6awntALWh03PewmIJuzbALScsTS4sZoS1fKciBGoh11gIfHzylvkdNe/hJl66/RGqrj5rFb08sAABNTzDTiqqNpJeBsYs/c2aiGozptX2RlnBktH+SUNpAajW724Nv2Wvhif6sFAgMBAAGjge4wgeswHQYDVR0OBBYEFJaffLvGbxe9WT9S1wob7BDWZJRrMIG7BgNVHSMEgbMwgbCAFJaffLvGbxe9WT9S1wob7BDWZJRroYGUpIGRMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbYIBADAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUAA4GBAIFfOlaagFrl71+jq6OKidbWFSE+Q4FqROvdgIONth+8kSK//Y/4ihuE4Ymvzn5ceE3S/iBSQQMjyvb+s2TWbQYDwcp129OPIbD9epdr4tJOUNiSojw7BHwYRiPh58S1xGlFgHFXwrEBb3dgNbMUa+u4qectsMAXpVHnD9wIyfmHMYIBmjCCAZYCAQEwgZQwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tAgEAMAkGBSsOAwIaBQCgXTAYBgkqhkiG9w0BCQMxCwYJKoZIhvcNAQcBMBwGCSqGSIb3DQEJBTEPFw0xNzAzMjQxNTI1NDJaMCMGCSqGSIb3DQEJBDEWBBRHQTTJPWYBPbcJYvrSQznkZoZCUjANBgkqhkiG9w0BAQEFAASBgJbuxeHodQtBt3qoR7CsZTm4a3M5uuKKGW2P/02yoVig9rlL2JHJIY3iJzzZodyZ2k0zXcC708IR1/pl8+gkEUsrRCmUmijErO9ZdGcDSslky00veSRg7ZxQnPrDf6B55rIk78lWMmAJOzBLUU2v3kfrZb6AkA5UEneuc5vucthE-----END PKCS7-----'); 


DEFINE('QIWI_STATUS', 0);
DEFINE('QIWI_URL', 'https://qiwi.com/payment/form.action?provider=1712');

?>