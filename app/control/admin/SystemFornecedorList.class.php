<?php
/**
 * SystemFornecedorList Listing
 * @author  <your name here>
 */
class SystemFornecedorList extends TWindow
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
        parent::__construct();
        parent::setSize(0.7, null);
        parent::removePadding();
        //parent::removeTitleBar();
        parent::disableEscape();
        
        $this->setDatabase('sistema');            // defines the database
        $this->setActiveRecord('SystemFornecedor');   // defines the active record
        $this->setDefaultOrder('codigo', 'asc');         // defines the default order
        $this->setLimit(10);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('codigo', 'like', 'codigo'); // filterField, operator, formField
        $this->addFilterField('cnpj', 'like', 'cnpj'); // filterField, operator, formField
        $this->addFilterField('fornecedor', 'like', 'fornecedor'); // filterField, operator, formField
        $this->addFilterField('email', 'like', 'email'); // filterField, operator, formField
        $this->addFilterField('telefone', 'like', 'telefone'); // filterField, operator, formField
        $this->addFilterField('ativo', 'like', 'ativo'); // filterField, operator, formField
        $this->addFilterField('tipo', 'like', 'tipo'); // filterField, operator, formField
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_SystemFornecedor');
        $this->form->setFormTitle('SystemFornecedor');
        

        // create the form fields
        $codigo = new TEntry('codigo');
        $cnpj = new TEntry('cnpj');
        $fornecedor = new TEntry('fornecedor');
        $email = new TEntry('email');
        $telefone = new TEntry('telefone');
        $ativo = new TEntry('ativo');
        $tipo = new TEntry('tipo');


        // add the fields
        $this->form->addFields( [ new TLabel('Codigo') ], [ $codigo ] );
        $this->form->addFields( [ new TLabel('Cnpj') ], [ $cnpj ] );
        $this->form->addFields( [ new TLabel('Fornecedor') ], [ $fornecedor ] );
        $this->form->addFields( [ new TLabel('Email') ], [ $email ] );
        $this->form->addFields( [ new TLabel('Telefone') ], [ $telefone ] );
        $this->form->addFields( [ new TLabel('Ativo') ], [ $ativo ] );
        $this->form->addFields( [ new TLabel('Tipo') ], [ $tipo ] );


        // set sizes
        $codigo->setSize('100%');
        $cnpj->setSize('100%');
        $fornecedor->setSize('100%');
        $email->setSize('100%');
        $telefone->setSize('100%');
        $ativo->setSize('100%');
        $tipo->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['SystemFornecedorForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_codigo = new TDataGridColumn('codigo', 'Codigo', 'right');
        $column_cnpj = new TDataGridColumn('cnpj', 'Cnpj', 'left');
        $column_fornecedor = new TDataGridColumn('fornecedor', 'Fornecedor', 'left');
        $column_email = new TDataGridColumn('email', 'Email', 'left');
        $column_telefone = new TDataGridColumn('telefone', 'Telefone', 'left');
        $column_ativo = new TDataGridColumn('ativo', 'Ativo', 'left');
        $column_tipo = new TDataGridColumn('tipo', 'Tipo', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_codigo);
        $this->datagrid->addColumn($column_cnpj);
        $this->datagrid->addColumn($column_fornecedor);
        $this->datagrid->addColumn($column_email);
        $this->datagrid->addColumn($column_telefone);
        $this->datagrid->addColumn($column_ativo);
        $this->datagrid->addColumn($column_tipo);

        
        $action1 = new TDataGridAction(['SystemFornecedorForm', 'onEdit'], ['codigo'=>'{codigo}']);
        $action2 = new TDataGridAction([$this, 'onDelete'], ['codigo'=>'{codigo}']);
        
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
