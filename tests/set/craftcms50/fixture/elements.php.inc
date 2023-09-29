<?php

class Foo extends \craft\base\Element
{
    public function foo() {
        $hasCheckeredThumb = $this->getHasCheckeredThumb();
        $hasRoundedThumb = $this->getHasRoundedThumb();
        $thumbAlt = $this->getThumbAlt();
        $thumbUrl = $this->getThumbUrl(212);
    }

    public function getHasCheckeredThumb(): bool {
        return true;
    }

    public function getHasRoundedThumb(): bool {
        return true;
    }

    public function getThumbAlt(): ?string {
        return true;
    }

    public function getThumbUrl(): ?string {
        return true;
    }

    public function getTableAttributeHtml(): string {
        return true;
    }

    protected function tableAttributeHtml(): string {
        return true;
    }

    public function getAncestors(?int $dist = null): \craft\elements\db\ElementQueryInterface|\Illuminate\Support\Collection {
        return parent::getAncestors($dist);
    }

    public function getDescendants(?int $dist = null): \craft\elements\db\ElementQueryInterface|\Illuminate\Support\Collection {
        return parent::getDescendants($dist);
    }

    public function getChildren(): \craft\elements\db\ElementQueryInterface|\Illuminate\Support\Collection {
        return parent::getChildren();
    }

    public function getSiblings(): \craft\elements\db\ElementQueryInterface|\Illuminate\Support\Collection {
        return parent::getSiblings();
    }

    public function getEagerLoadedElements(string $handle): ?\Illuminate\Support\Collection
    {
        return parent::getEagerLoadedElements($handle);
    }

    public static function indexHtml(
        \craft\elements\db\ElementQueryInterface $elementQuery,
        ?array $disabledElementIds,
        array $viewState,
        ?string $sourceKey,
        ?string $context,
        bool $includeContainer,
        bool $showCheckboxes,
    ): string {
        return parent::indexHtml(
            $elementQuery,
            $disabledElementIds,
            $viewState,
            $sourceKey,
            $context,
            $includeContainer,
            $showCheckboxes
        );
    }
}

?>
-----
<?php

class Foo extends \craft\base\Element
{
    public function foo() {
        $hasCheckeredThumb = $this->hasCheckeredThumb();
        $hasRoundedThumb = $this->hasRoundedThumb();
        $thumbAlt = $this->thumbAlt();
        $thumbUrl = $this->thumbUrl(212);
    }

    protected function hasCheckeredThumb(): bool {
        return true;
    }

    protected function hasRoundedThumb(): bool {
        return true;
    }

    protected function thumbAlt(): ?string {
        return true;
    }

    protected function thumbUrl(): ?string {
        return true;
    }

    public function getAttributeHtml(): string {
        return true;
    }

    protected function attributeHtml(): string {
        return true;
    }

    public function getAncestors(?int $dist = null): \craft\elements\db\ElementQueryInterface|\craft\elements\ElementCollection {
        return parent::getAncestors($dist);
    }

    public function getDescendants(?int $dist = null): \craft\elements\db\ElementQueryInterface|\craft\elements\ElementCollection {
        return parent::getDescendants($dist);
    }

    public function getChildren(): \craft\elements\db\ElementQueryInterface|\craft\elements\ElementCollection {
        return parent::getChildren();
    }

    public function getSiblings(): \craft\elements\db\ElementQueryInterface|\craft\elements\ElementCollection {
        return parent::getSiblings();
    }

    public function getEagerLoadedElements(string $handle): ?\craft\elements\ElementCollection
    {
        return parent::getEagerLoadedElements($handle);
    }

    public static function indexHtml(
        \craft\elements\db\ElementQueryInterface $elementQuery,
        ?array $disabledElementIds,
        array $viewState,
        ?string $sourceKey,
        ?string $context,
        bool $includeContainer,
        bool $showCheckboxes,
        bool $sortable = false,
    ): string {
        return parent::indexHtml(
            $elementQuery,
            $disabledElementIds,
            $viewState,
            $sourceKey,
            $context,
            $includeContainer,
            $showCheckboxes
        );
    }
}

?>
