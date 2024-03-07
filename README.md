# Rector Rules for Craft CMS

This package provides [Rector](https://github.com/rectorphp/rector) rules for updating Craft CMS plugins and modules for:

- [Craft CMS 3 → 4](#craft-cms-3--4)
- [Craft CMS 4 → 5](#craft-cms-4--5)

## Craft CMS 3 → 4

First, ensure Craft 3.7.35 or later is Composer-installed. (Prior versions of Craft weren’t compatible with Rector.)

```sh
composer update craftcms/cms 
```

Then run the following commands:

```sh
composer require php:^8.0.2 --ignore-platform-reqs
```

```sh
composer config minimum-stability dev
```

```sh
composer config prefer-stable true
```

```sh
composer require craftcms/rector:dev-main --dev --ignore-platform-reqs
```

```sh
vendor/bin/rector process src --config vendor/craftcms/rector/sets/craft-cms-40.php
```

If you have code that extends Craft Commerce classes, you can run the following command as well:

```sh
vendor/bin/rector process src --config vendor/craftcms/rector/sets/craft-commerce-40.php
```

Once Rector is complete, you’re ready to update `craftcms/cms`.

```sh
composer require craftcms/cms:^4.0.0-alpha -W --ignore-platform-reqs
```

## Craft CMS 4 → 5

Run the following commands:

```sh
composer require php:^8.2 --ignore-platform-reqs
```

```sh
composer config minimum-stability dev
```

```sh
composer config prefer-stable true
```

```sh
composer require craftcms/rector:dev-main --dev --ignore-platform-reqs
```

```sh
vendor/bin/rector process src --config vendor/craftcms/rector/sets/craft-cms-50.php
```

Once Rector is complete, you’re ready to update `craftcms/cms`:

```sh
composer require craftcms/cms:^5.0.0-beta.1 -W --ignore-platform-reqs
```

## Notes

## Advanced Configuration

If you’d like to include additional Rector rules, or customize which files/directories should be processed,
you’ll need to give your project a `rector.php` file.

Here’s an example which runs the Craft 4 rule set, but skips over a `src/integrations/` folder:

```php
<?php
declare(strict_types = 1);

use craft\rector\SetList as CraftSetList;
use Rector\Core\Configuration\Option;
use Rector\Config\RectorConfig;

return static function(RectorConfig $rectorConfig): void {
    // Skip the integrations folder
    $rectorConfig->skip([
        __DIR__ . '/src/integrations',
    ]);

    // Import the Craft 4 upgrade rule set
    $rectorConfig->sets([
        CraftSetList::CRAFT_CMS_40
    ]);
};
```
