<?php

namespace app\modules\dashboard;

/**
 * dashboard module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\dashboard\controllers';
    public $layout = 'main';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->layoutPath = '@app/modules/dashboard/views/layouts';
    }
}
