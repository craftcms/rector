# Rector Rules for Craft CMS

This package provides rules for updating plugins and modules to Craft 4.

To apply them to your plugin or module, follow these steps:

1. Ensure you are running Craft 3.7.35 or later (but not 4.x).
2. In `composer.json`, set your `min-stability: "dev"` and `prefer-stable: true`:
   ```json
   {
     "minimum-stability": "dev",
     "prefer-stable": true,
     "...": "..."
   }
   ```
3. Run the following commands:
   ```sh
   > composer require craftcms/rector:dev-main
   > vendor/bin/rector process src --config vendor/craftcms/rector/sets/craftcms-40.php
   ``` 

You can add `--dry-run` to the `vendor/bin/rector` command if you’d like to see what will happen without actually
making any changes yet.

Once the commands are complete, you’re ready to update `craftcms/cms` to Craft 4.
