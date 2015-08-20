sudo npm install -g uglify-js
sudo npm install -g less
sudo  npm install -g uglifycss
patch -p1 < patches/FormatterType.patch
cp  patches/UnserializeObjectConstructor.php vendor/jms/serializer/src/JMS/Serializer/Construction/UnserializeObjectConstructor.php 
cp patches/SeoExtension.php vendor/sonata-project/seo-bundle/Twig/Extension/SeoExtension.php
