cd ../exampledocs
java -Ddata=args  -Durl=http://localhost:8983/solr/update -jar post.jar "<delete><query>*:*</query></delete>"
cd ..
