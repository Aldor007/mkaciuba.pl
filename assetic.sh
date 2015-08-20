php app/console assetic:dump -e prod
cp   web/ckeditor/config.js  vendor/egeloen/ckeditor-bundle/Resources/public/
php app/console assets:install -e prod
cp   web/ckeditor/config.js  web/bundles/ivoryckeditor/config.js
cp   web/ckeditor/config.js  vendor/egeloen/ckeditor-bundle/Resources/public/
