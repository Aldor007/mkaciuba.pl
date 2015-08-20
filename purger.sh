
curl -X PURGE -H "x-forwarded-proto: http" -H "Accept-encoding: gzip" -H "Host: mkaciuba.pl" http://localhost/purge/$1
echo "------------------------------"
curl -X PURGE  -H "Host: mkaciuba.pl" http://localhost/purge/$1
curl -X PURGE -H "x-forwarded-proto: https" -H "Accept-encoding: gzip" -H "Host: mkaciuba.pl" http://localhost/purge/$1
echo "------------------------------"
curl -X PURGE  -H "x-forwarded-proto: https" -H "Host: mkaciuba.pl" http://localhost/purge/$1
echo "------------------------------"
curl -X PURGE  -H "x-forwarded-proto: https" -H "Accept-encoding: gzip" -H "Host: m.mkaciuba.pl" -H "User-agent: Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)" http://localhost/purge/$1
echo "------------------------------"
curl -X PURGE  -H "Host: m.mkaciuba.pl" -H "User-agent: Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)" http://localhost/purge/$1
echo "------------------------------"
curl -X PURGE  -H "Host: m.mkaciuba.pl" -H "User-agent: Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)" http://localhost/$1
curl -X DELETE  -H "Host: mkaciuba.pl" http://localhost/$1
curl -X DELETE -H "Accept-encoding: gzip" -H "Host: mkaciuba.pl" http://localhost/$1
