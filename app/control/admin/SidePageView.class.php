<?php
class SidePageView extends TPage
{
    public function __construct()
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');
        
        // create the HTML Renderer
        $this->html = new THtmlRenderer('app/resources/page_sample.html');
        
        $replaces = [];
        $replaces['title']  = 'Sisttema Web';
        $replaces['footer'] = 'Panel footer';
        $replaces['name']   = 'Someone famous';
        
        // replace the main section variables
        $this->html->enableSection('main', $replaces);
        
        parent::add($this->html);          
    }
    
     /**
     * on close
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
