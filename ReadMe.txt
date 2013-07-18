Usage
============================================

--------------------------------------------
*  How to Zip a URL
--------------------------------------------
Call the /zip endpoint with a POST parameter of long_url.  The URL is then zipped and a short string is returned to the user.  For example:

{
	Request Url: http://localhost:8080/zipapi/zip
	Request Method: POST
	Status Code: 200
	Params: {
		"long_url": "http://www.google.com"
	}
}

Responds with:

19AgLDm3

--------------------------------------------
*  How to UnZip a URL
--------------------------------------------
Call the /unzip/xxx enpoint where xxx is the returned zipped string from the zipurl endpoint.  For example:
{
	Request Url: http://localhost:8080/zipapi/unzip/19AgLDm3
	Request Method: GET
	Status Code: 200
	Params: {}
}
This will automatically redirect the user's browser to the expanded URL.

--------------------------------------------
*  Future Plans
--------------------------------------------
1. /zip should optionally accept a length parameter so clients may choose how long of a hash they get
2. a chron job that removes hashes that have not been used in the last X days
3. a deploy script
4. a metrics page
5. tests to determine the optimal length of the unq_urls DB index, 128 was guesstimated