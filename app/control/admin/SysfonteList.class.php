<?php
/**
 * SysfonteList Listing
 * @author  <your name here>
 */
class SysfonteList extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    
    use Adianti\base\AdiantiStandardListTrait;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('sistema');            // defines the database
        $this->setActiveRecord('Sysfonte');   // defines the active record
        $this->setDefaultOrder('idfonte', 'asc');         // defines the default order
        $this->setLimit(10);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('idfonte', 'like', 'idfonte'); // filterField, operator, formField
        $this->addFilterField('fonte', 'like', 'fonte'); // filterField, operator, formField
        $this->addFilterField('fontedec', 'like', 'fontedec'); // filterField, operator, formField
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_Sysfonte');
        $this->form->setFormTitle('Listagem de Fonte');
        

        // create the form fields
        $idfonte = new TEntry('idfonte');
        $fonte = new TEntry('fonte');
        $fontedec = new TEntry('fontedec');


        // add the fields
        $this->form->addFields( [ new TLabel('Código') ], [ $idfonte ] );
        $this->form->addFields( [ new TLabel('Fonte') ], [ $fonte ] );
        $this->form->addFields( [ new TLabel('Descrição da Fonte') ], [ $fontedec ] );


        // set sizes
        $idfonte->setSize('100%');
        $fonte->setSize('100%');
        $fontedec->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['SysfonteForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_idfonte = new TDataGridColumn('idfonte', 'Código', 'right');
        $column_fonte = new TDataGridColumn('fonte', 'Fonte', 'left');
        $column_fontedec = new TDataGridColumn('fontedec', 'Descrição da Fonte', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_idfonte);
        $this->datagrid->addColumn($column_fonte);
        $this->datagrid->addColumn($column_fontedec);

        
        $action1 = new TDataGridAction(['SysfonteForm', 'onEdit'], ['idfonte'=>'{idfonte}']);
        $action2 = new TDataGridAction([$this, 'onDelete'], ['idfonte'=>'{idfonte}']);
        
        $this->datagrid->addAction($action1, _t('Edit'),   'far:edit blue');
        $this->datagrid->addAction($action2 ,_t('Delete'), 'far:trash-alt red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        $panel = new TPanelGroup('', 'white');
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);
        
        // header actions
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf red' );
        $panel->addHeaderWidget( $dropdown );
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }
}
