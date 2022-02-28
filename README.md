# Rector Rules for Craft CMS

This package provides rules for updating plugins and modules to Craft 4.

First, ensure you are running Craft 3.7.35 or later. (Prior versions of Craft weren’t compatible with Rector.)

Then run the following commands:
```sh
composer config minimum-stability dev
```

```sh
composer config prefer-stable true
```

```sh
composer require craftcms/rector:dev-main --dev
```

```sh
vendor/bin/rector process src --config vendor/craftcms/rector/sets/craftcms-40.php
```

You can add `--dry-run` to the `vendor/bin/rector` command if you’d like to see what will happen without actually
making any changes yet.

Once the commands are complete, you’re ready to update `craftcms/cms` to Craft 4.
