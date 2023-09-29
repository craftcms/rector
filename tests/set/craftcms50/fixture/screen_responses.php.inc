<?php

class Foo extends \craft\web\Controller
{
    public function actionFoo(): \yii\web\Response {
        $response = $this->asCpScreen()
            ->additionalButtons('')
            ->content('')
            ->contextMenu('')
            ->notice('')
            ->pageSidebar('')
            ->sidebar('');

        $additionalButtons = $response->additionalButtons;
        $content = $response->content;
        $contextMenu = $response->contextMenu;
        $notice = $response->notice;
        $pageSidebar = $response->pageSidebar;
        $sidebar = $response->sidebar;
    }
}

?>
-----
<?php

class Foo extends \craft\web\Controller
{
    public function actionFoo(): \yii\web\Response {
        $response = $this->asCpScreen()
            ->additionalButtonsHtml('')
            ->contentHtml('')
            ->contextMenuHtml('')
            ->noticeHtml('')
            ->pageSidebarHtml('')
            ->sidebarHtml('');

        $additionalButtons = $response->additionalButtonsHtml;
        $content = $response->contentHtml;
        $contextMenu = $response->contextMenuHtml;
        $notice = $response->noticeHtml;
        $pageSidebar = $response->pageSidebarHtml;
        $sidebar = $response->sidebarHtml;
    }
}

?>
