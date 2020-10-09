<?php
/**
 * SysempenhoList Listing
 * @author  <your name here>
 */
class SysempenhoList extends TPage
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
       // parent::setSize(0.7, null);
        //parent::removePadding();
        //parent::removeTitleBar();
        //parent::disableEscape();
        
        $this->setDatabase('sistema');            // defines the database
        $this->setActiveRecord('Sysempenho');   // defines the active record
        $this->setDefaultOrder('idempenho', 'asc');         // defines the default order
        $this->setLimit(10);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('idempenho', 'like', 'idempenho'); // filterField, operator, formField
        $this->addFilterField('numempenho', 'like', 'numempenho'); // filterField, operator, formField
        $this->addFilterField('objeto', 'like', 'objeto'); // filterField, operator, formField
        $this->addFilterField('valor', 'like', 'valor'); // filterField, operator, formField
        $this->addFilterField('dataemp', 'like', 'dataemp'); // filterField, operator, formField
        $this->addFilterField('procorigem', 'like', 'procorigem'); // filterField, operator, formField
        $this->addFilterField('equipamento', 'like', 'equipamento'); // filterField, operator, formField
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_Sysempenho');
        $this->form->setFormTitle('Controle de Empenhos:');
        

        // create the form fields
        $idempenho = new TEntry('idempenho');
        $numempenho = new TEntry('numempenho');
        $objeto = new TEntry('objeto');
        $valor = new TEntry('valor');
        $dataemp = new TEntry('dataemp');
        $ProcOrigem = new TEntry('procorigem');
        $Equipamento = new TEntry('equipamento');


        // add the fields
        $this->form->addFields( [ new TLabel('Idempenho') ], [ $idempenho ] );
        $this->form->addFields( [ new TLabel('Numempenho') ], [ $numempenho ] );
        $this->form->addFields( [ new TLabel('Objeto') ], [ $objeto ] );
        $this->form->addFields( [ new TLabel('Valor') ], [ $valor ] );
        $this->form->addFields( [ new TLabel('Dataemp') ], [ $dataemp ] );
        $this->form->addFields( [ new TLabel('Procorigem') ], [ $ProcOrigem ] );
        $this->form->addFields( [ new TLabel('Equipamento') ], [ $Equipamento ] );

        // set sizes
        $idempenho->setSize('100%');
        $numempenho->setSize('100%');
        $objeto->setSize('100%');
        $valor->setSize('100%');
        $dataemp->setSize('100%');
        $ProcOrigem->setSize('100%');
        $Equipamento->setSize('100%');
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['SysempenhoForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_idempenho = new TDataGridColumn('idempenho', 'Idempenho', 'right');
        $column_numempenho = new TDataGridColumn('numempenho', 'Num empenho', 'left');
        $column_objeto = new TDataGridColumn('objeto', 'Objeto', 'left');
        $column_valor = new TDataGridColumn('valor', 'Valor', 'left');
        $column_dataemp = new TDataGridColumn('dataemp', 'Data do empenho', 'left');
        $column_ProcOrigem = new TDataGridColumn('procorigem', 'Processo', 'left');
        $column_Equipamento = new TDataGridColumn('sysequipamento->sigla', 'Equipamento', 'left');
        $column_Fonte = new TDataGridColumn('fonte', 'Fonte de recurso', 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_idempenho);
        $this->datagrid->addColumn($column_numempenho);
        $this->datagrid->addColumn($column_objeto);
        $this->datagrid->addColumn($column_valor);
        $this->datagrid->addColumn($column_dataemp);
        $this->datagrid->addColumn($column_ProcOrigem);
        $this->datagrid->addColumn($column_Equipamento);
        $this->datagrid->addColumn($column_Fonte);
        
        $action1 = new TDataGridAction(['SysempenhoForm', 'onEdit'], ['idempenho'=>'{idempenho}']);
        $action2 = new TDataGridAction([$this, 'onDelete'], ['idempenho'=>'{idempenho}']);
        
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
