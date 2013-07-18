Usage
============================================
--------------------------------------------
*  How to Zip a URL
--------------------------------------------
Call the /zipurl endpoint with a POST parameter of long_url.  The URL is then zipped and a short string is returned to the user.  For example:

{
	Request Url: http://localhost:8080/zip
	Request Method: POST
	Status Code: 200
	Params: 
	{
		"long_url": "http%3A%2F%2Flearnyousomeerlang.com%2Fcommon-test-for-uncommon-tests"
	}
}

Responds with:

19AgLDm3

--------------------------------------------
*  How to UnZip a URL
--------------------------------------------
Call the /unzip/xxx enpoint where xxx is the returned zipped string from the zipurl endpoint.  For example:
{
	Request Url: http://localhost:8080/unzip/19AgLDm3
	Request Method: GET
	Status Code: 200
}
This will automatically redirect the user's browser to the expanded URL.