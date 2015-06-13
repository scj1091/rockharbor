<?php header('Content-Type: application/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>'?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
	<ShortName>Search my site</ShortName>
	<Description>Search my site</Description>
	<url type="text/html" method="get" template="<?php echo 'http://' . $_SERVER['SERVER_NAME'] . '/?q={searchTerms}'; ?>"/>
</OpenSearchDescription>