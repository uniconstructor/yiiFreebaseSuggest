<?php

/**
 * Yii wrapper for freebase suggest widget
 * 
 * @see https://developers.google.com/freebase/v1/search-widget
 * @see https://developers.google.com/freebase/v1/search-cookbook
 */
class FreebaseSuggest extends CWidget
{
    /**
     * @var array - original widget options
     * @see https://developers.google.com/freebase/v1/suggest#configuration-options
     */
    public $options = array();
    /**
     * @var array
     */
    public $htmlOptions = array();
    /**
     * @var string - API key equal to $this->options['key'] 
     * @see https://developers.google.com/freebase/v1/getting-started#api-keys
     */
    public $key;
    
    /**
     * @var string
     */
    protected $initJs;
    /**
     * @var string
     */
    protected $assetUrl;
    
    /**
     * @see CWidget::init()
     */
    public function init()
    {
        if ( ! isset($this->options['lang']) )
        {
            $this->options['lang'] = Yii::app()->language;
        }
        if ( ! isset($this->htmlOptions['id']) )
        {
            $this->htmlOptions['id'] = 'fb-suggest-'.$this->id;
        }
        if ( ! isset($this->htmlOptions['type']) )
        {
            $this->htmlOptions['type'] = 'text';
        }
        // publish assets
        $this->assetUrl = Yii::app()->assetManager->publish('ext.FreebaseSuggest.assets');
        /* @var $cs CClientScript */
        $cs = Yii::app()->getClientScript();
        // register core js
        $cs->registerCoreScript('jquery');
        // register widget js/css
        $cs->registerCssFile($this->assetUrl.'/suggest.min.css');
        $cs->registerScriptFile($this->assetUrl.'/suggest.min.js');
        // create init JS script
        $options = CJSON::encode($this->options);
        $this->initJs = "$('#{$this->htmlOptions['id']}').suggest({$options});";
    }
    
    /**
     * @see CWidget::run()
     */
    public function run()
    {
        echo CHtml::tag('input', $this->htmlOptions);
        // AJAX-safe script output
        if ( Yii::app()->request->isAjaxRequest )
        {
            echo CHtml::script($this->initJs);
        }else
        {
            Yii::app()->clientScript->registerScript('_init-suggest#'.$this->id, $this->initJs, CClientScript::POS_END);
        }
    }
}