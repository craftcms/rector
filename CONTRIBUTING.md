# Contributing

The bulk of the rules are dynamically defined, whose purpose is to add type declarations to properties and methods.

These are driven by generated signature files.

To generate a new signature file, run the following commands;

```sh
> php scripts/signature-builder.php ../craft4/craftcms/cms signatures/craftcms-40.php -n craft -e craft\\test -a lib/yii2/Yii.php,src/Craft.php
> php scripts/signature-cleanup.php signatures/craftcms-40.php
```

(Replace `../craft4/craftcms/cms` with the path to the source folder at the target version, and
`signatures/craftcms-40.php` with the destination file path.)
