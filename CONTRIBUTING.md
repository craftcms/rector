# Contributing

The bulk of the rules are dynamically defined, whose purpose is to add type declarations to properties and methods.

These are driven by generated signature files.

To generate a new signature file, run the following commands;

```sh
php scripts/signature-builder.php ../craft4/craftcms/cms craft-cms-40 -n craft -e craft\\test -a lib/yii2/Yii.php,src/Craft.php
```

```sh
php scripts/signature-diff.php ../craft3/craftcms/cms craft-cms-40 -a lib/yii2/Yii.php,src/Craft.php
```

```sh
php scripts/signature-cleanup.php craft-cms-40
```

(Replace `../craft4/craftcms/cms` with the path to the source folder at the target version, and
`craft-cms-40.php` with the signature file name.)

The source folder must have Composer dependencies installed, and its Composer-generated autoloader must be optimized:

```sh
> composer dump-autoload -o 
```
