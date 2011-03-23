#!/bin/bash
# Licensed to the Apache Software Foundation (ASF) under one or more
# contributor license agreements. (etc.)

echo "Sending txt files to default Solr install\n"
infiles=*.txt
URL="localhost:8983/solr/update/extract?uprefix=attr_&fmap.content=body&literal.id=exid"

declare -i count=(1)		# shell script initialize int

	# shell script loop for all text files in the directory
for f in $infiles 
do
	# use echo for debugging
  echo "Posting $f as http://$URL$count -F \"sc=@$f\" "
	# send each to solr for indexing  
  curl "http://$URL$count" -F "sc=@$f" 
  echo 
  count=$(($count + 1))
done

	# send the command to commit these changes to solr (ignore the "xml"
curl "http://localhost:8983/solr/update/" -H "Content-Type: text/xml" --data-binary '<commit waitFlush="false" waitSearcher="false"/>'
echo
'<commit/>'
echo

