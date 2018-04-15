My first attempt at using guzzle to ingest an API - httpbin.org in this case.

Quickstart Guide
---
`composer install`

`docker build -t guzzle .`

`cd Guzzle && docker run -p 80:80 -v <path_to_repo>/SimpleGuzzleClient/:/var/www/html/ guzzle`

`localhost:80/src`
