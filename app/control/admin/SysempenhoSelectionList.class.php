<?php
/**
 * SysempenhoSelectionList Record selection
 * @author  <your name here>
 */
class SysempenhoSelectionList extends TPage
{
    protected $form;     // search form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    use Adianti\base\AdiantiStandardListTrait;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('sistema');            // defines the database
        $this->setActiveRecord('Sysempenho');   // defines the active record
        $this->setDefaultOrder('idempenho', 'asc');         // defines the default order
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
        $this->form->setFormTitle('Sysempenho');
        

        // create the form fields
        $idempenho = new TEntry('idempenho');
        $numempenho = new TEntry('numempenho');
        $objeto = new TEntry('objeto');
        $valor = new TEntry('valor');
        $dataemp = new TEntry('dataemp');
        $procorigem = new TEntry('procorigem');
         $equipamento = new TDBUniqueSearch('equipamento', 'sistema', 'SysEquipamento', 'idequipamentoq', 'nomeequipamento');
        $equipamento->setMinLength(1);
        $equipamento->setMask('({idequipamentoq}) {nomeequipamento}');
        //$equipamento = new TCombo('equipamento', 'sistema', 'SysEquipamento', 'idequipamentoq', 'nomeequipamento');


        // add the fields
        $this->form->addFields( [ new TLabel('Idempenho') ], [ $idempenho ] );
        $this->form->addFields( [ new TLabel('Numempenho') ], [ $numempenho ] );
        $this->form->addFields( [ new TLabel('Objeto') ], [ $objeto ] );
        $this->form->addFields( [ new TLabel('Valor') ], [ $valor ] );
        $this->form->addFields( [ new TLabel('Dataemp') ], [ $dataemp ] );
        $this->form->addFields( [ new TLabel('Procorigem') ], [ $procorigem ] );
        $this->form->addFields( [ new TLabel('Equipamento') ], [ $equipamento ] );


        // set sizes
        $idempenho->setSize('100%');
        $numempenho->setSize('100%');
        $objeto->setSize('100%');
        $valor->setSize('100%');
        $dataemp->setSize('100%');
        $procorigem->setSize('100%');
        $equipamento->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__ . '_filter_data') );
        
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_idempenho = new TDataGridColumn('idempenho', 'Idempenho', 'right');
        $column_numempenho = new TDataGridColumn('numempenho', 'Numempenho', 'left');
        $column_objeto = new TDataGridColumn('objeto', 'Objeto', 'left');
        $column_valor = new TDataGridColumn('valor', 'Valor', 'right');
        $column_dataemp = new TDataGridColumn('dataemp', 'Dataemp', 'left');
        $column_procorigem = new TDataGridColumn('procorigem', 'Procorigem', 'left');
        $column_equipamento = new TDataGridColumn('equipamento', 'Equipamento', 'right');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_idempenho);
        $this->datagrid->addColumn($column_numempenho);
        $this->datagrid->addColumn($column_objeto);
        $this->datagrid->addColumn($column_valor);
        $this->datagrid->addColumn($column_dataemp);
        $this->datagrid->addColumn($column_procorigem);
        $this->datagrid->addColumn($column_equipamento);

        $column_idempenho->setTransformer([$this, 'formatRow'] );
        
        // creates the datagrid actions
        $action1 = new TDataGridAction([$this, 'onSelect'], ['idempenho' => '{idempenho}', 'register_state' => 'false']);
        //$action1->setUseButton(TRUE);
        $action1->setButtonClass('btn btn-default');
                
        // add the actions to the datagrid
        $this->datagrid->addAction($action1, 'Select', 'far:square fa-fw black');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        $panel = new TPanelGroup;
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);
        $panel->addHeaderActionLink( 'Show results', new TAction([$this, 'showResults']), 'far:check-circle' );
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }
    
    /**
     * Save the object reference in session
     */
    public function onSelect($param)
    {
        // get the selected objects from session 
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        
        TTransaction::open('sistema');
        $object = new Sysempenho($param['key']); // load the object
        if (isset($selected_objects[$object->idempenho]))
        {
            unset($selected_objects[$object->idempenho]);
        }
        else
        {
            $selected_objects[$object->idempenho] = $object->toArray(); // add the object inside the array
        }
        TSession::setValue(__CLASS__.'_selected_objects', $selected_objects); // put the array back to the session
        TTransaction::close();
        
        // reload datagrids
        $this->onReload( func_get_arg(0) );
    }
    
    /**
     * Highlight the selected rows
     */
    public function formatRow($value, $object, $row)
    {
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        
        if ($selected_objects)
        {
            if (in_array( (int) $value, array_keys( $selected_objects ) ) )
            {
                $row->style = "background: #abdef9";
                
                $button = $row->find('i', ['class'=>'far fa-square fa-fw black'])[0];
                if ($button)
                {
                    $button->class = 'far fa-check-square fa-fw black';
                }
            }
        }
        
        return $value;
    }
    
    /**
     * Show selected records
     */
    public function showResults()
    {
        $datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $datagrid->width = '100%';
        $datagrid->addColumn( new TDataGridColumn('idempenho',  'Idempenho',  'right') );
        $datagrid->addColumn( new TDataGridColumn('numempenho',  'Numempenho',  'left') );
        $datagrid->addColumn( new TDataGridColumn('objeto',  'Objeto',  'left') );
        $datagrid->addColumn( new TDataGridColumn('valor',  'Valor',  'right') );
        $datagrid->addColumn( new TDataGridColumn('dataemp',  'Dataemp',  'left') );
        $datagrid->addColumn( new TDataGridColumn('procorigem',  'Procorigem',  'left') );
        $datagrid->addColumn( new TDataGridColumn('equipamento',  'Equipamento',  'right') );
        
        // create the datagrid model
        $datagrid->createModel();
        
        $selected_objects = TSession::getValue(__CLASS__.'_selected_objects');
        ksort($selected_objects);
        if ($selected_objects)
        {
            $datagrid->clear();
            foreach ($selected_objects as $selected_object)
            {
                $datagrid->addItem( (object) $selected_object );
            }
        }
        
        $win = TWindow::create('Results', 0.6, 0.6);
        $win->add($datagrid);
        $win->show();
    }
}
