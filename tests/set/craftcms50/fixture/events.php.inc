<?php

\yii\base\Event::on(
    \craft\base\Element::class,
    \craft\base\Element::EVENT_SET_TABLE_ATTRIBUTE_HTML,
    function(\craft\events\SetElementTableAttributeHtmlEvent $event) {
        $event->html = 'hi';
    }
);

?>
-----
<?php

\yii\base\Event::on(
    \craft\base\Element::class,
    \craft\base\Element::EVENT_DEFINE_ATTRIBUTE_HTML,
    function(\craft\events\DefineAttributeHtmlEvent $event) {
        $event->html = 'hi';
    }
);

?>
