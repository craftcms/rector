<?php

class Foo extends \craft\base\Field implements \craft\base\PreviewableFieldInterface
{
    public static function valueType(): string {
        return 'string';
    }

    public function getTableAttributeHtml(mixed $value, \craft\base\ElementInterface $element): string {
        return 'hi';
    }

    protected function inputHtml(
        mixed $value,
        ?\craft\base\ElementInterface $element,
    ): string {
        return parent::inputHtml($value, $element);
    }
}

class RelationField extends \craft\fields\BaseRelationField
{
    protected function tableAttributeHtml(\Illuminate\Support\Collection $elements): string {
        return 'hi';
    }
}

/** @var \craft\fields\Matrix $matrixField */
$minBlocks = $matrixField->minBlocks;
$maxBlocks = $matrixField->maxBlocks;

?>
-----
<?php

class Foo extends \craft\base\Field implements \craft\base\PreviewableFieldInterface
{
    public static function phpType(): string {
        return 'string';
    }

    public function getPreviewHtml(mixed $value, \craft\base\ElementInterface $element): string {
        return 'hi';
    }

    protected function inputHtml(
        mixed $value,
        ?\craft\base\ElementInterface $element,
        bool $inline = false,
    ): string {
        return parent::inputHtml($value, $element, $inline);
    }
}

class RelationField extends \craft\fields\BaseRelationField
{
    protected function previewHtml(\craft\elements\ElementCollection $elements): string {
        return 'hi';
    }
}

/** @var \craft\fields\Matrix $matrixField */
$minBlocks = $matrixField->minEntries;
$maxBlocks = $matrixField->maxEntries;

?>
