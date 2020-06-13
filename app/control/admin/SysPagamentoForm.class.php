<?php
/**
 * POSFormView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */

require 'kint.phar';



class SysPagamentoForm extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        //parent::setSize(0.8, null);
        //parent::removePadding();
        // parent::removeTitleBar();
        //parent::disableEscape();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Sale');
        $this->form->setFormTitle('<h4>Cadastro de Pagamentos</h4>');
        $this->form->setProperty('style', 'margin:0;border:0');
        $this->form->setClientValidation(true);
        
        
        // master fields
        $id          = new TEntry('id');
        $date        = new TDate('dateemp');
        $numprocesso = new TEntry('numprocesso');
        $empenho_id = new THidden('idempenho');
        $cpf_cnpj = new TEntry('cnpj');
        $obs         = new TText('obs');
        
        // detail fields
        
        $fornecedor_id = new TDBUniqueSearch('fornecedor_id', 'sistema', 'SystemFornecedor', 'idfornecedor', 'fornecedor');
        $fornecedor_id  ->setMinLength(1);
        $fornecedor_id  ->setMask('{fornecedor} ');   
              
        $empenho_detalhe_id = new TDBUniqueSearch('empenho_detalhe_id', 'sistema', 'Sysempenho', 'idempenho', 'numempenho');
        $empenho_detalhe_id ->setMinLength(1);
        $empenho_detalhe_id ->setMask('({idempenho}) - {numempenho} / {objeto}');        
        
        $empenho_valor      = new TEntry('valor');
        $empenho_equipamento     = new TEntry('equipamento');
        $empenho_objeto  = new TText('objeto');
        $empenho_data = new TDate('dataemp');
        $empenho_fonte = new TEntry('fonte');
         
        // adjust field properties
        $id->setEditable(false);
        $fornecedor_id->setSize('100%');
        $fornecedor_id->setMinLength(1);
        $date->setSize('100%');
        $obs->setSize('100%', 80);
        $empenho_detalhe_id->style = 'width:160%;';
        $empenho_detalhe_id->setSize('100%');
        $empenho_detalhe_id->setMinLength(1);
        $empenho_valor->setSize('50%');
        $empenho_equipamento->setSize('100%');
        $empenho_fonte->setSize('100%');
        $empenho_objeto->setSize('100%');
        $empenho_fonte->setEditable(false);
                
        // add validations
        $date->addValidation('Date', new TRequiredValidator);
        $fornecedor_id->addValidation('Customer', new TRequiredValidator);
        
        // change action
        $empenho_detalhe_id->setChangeAction(new TAction([$this,'onEmpenhoChange']));
        $fornecedor_id->setChangeAction(new TAction([$this,'onFornecedorChange']));
        $empenho_valor->setEditable(false);
        $empenho_equipamento->setEditable(false);
        $empenho_objeto->setEditable(false);
        $add_link = '<a class="btn btn-default" generator="adianti" href="index.php?class=FornecedorForm">Cadastrar Fornecedor</a>';

        $this->form->addFields( [new TLabel('Código')], [$id], 
                                [new TLabel('Número do Processo')], [$numprocesso],
                                [new TLabel('Data de Cadastro (*)')], [$date] );
        $this->form->addFields( [new TLabel('Fornecedor (*)')], [$fornecedor_id ], [], [$add_link] );
        
        
        $this->form->addFields( [new TLabel('CPF/CNPJ (*)', '#FF0000')], [$cpf_cnpj ] );
        $this->form->addFields( [new TLabel('Objeto do Pagamento')], [$obs] );
        $add_link2 = '<a class="btn btn-default" generator="adianti" href="index.php?class=SysempenhoForm"> <b>Cadastrar Empenho</b></a>';

        $this->form->addContent( ['<h4>Empenho (s) :</h4><hr>'] );
  
     
        $this->form->addFields( [ new TLabel('N° do Empenho (*)', '#FF0000') ], [$empenho_detalhe_id], [], [$add_link2]); 
        $this->form->addFields( [ new TLabel('Valor total R$', '#FF0000') ],   [$empenho_valor], [ new TLabel('Equipamento', '#FF0000') ],   [$empenho_equipamento], [ new TLabel('Fonte', '#FF0000') ],   [$empenho_fonte] );
        $this->form->addFields( [ new TLabel('Descrição do Empenho', '#FF0000') ],   [$empenho_objeto] );
        $this->form->addFields( [new TLabel('Data do Empenho', '#FF0000') ],   [$empenho_data]  );
        
       
        $this->form->addContent( ['<h4>Nota Liquidação :</h4><hr>'] );
        $nl_id          = new TEntry('idNL');
        $numnl        = new TEntry('numnl');
        $valornl =  new TEntry('valornl');
        $datanl = new TDate('datanl');
        $objetoanl = new TText('objetonl');
     
        $this->form->addFields( [ new TLabel('Código da NL (*)', '#FF0000') ], [$nl_id]); 
        $this->form->addFields( [ new TLabel('Numéro da Liquidacao', '#FF0000') ],   [$numnl], [ new TLabel('Valor', '#FF0000') ],   [$valornl], [ new TLabel('Data', '#FF0000') ],   [$datanl] );
        $this->form->addFields( [ new TLabel('Objeto', '#FF0000') ], [$objetoanl]); 
        
        $add_product = TButton::create('add_nota', [$this, 'onNotaAdd'], 'Adicionar Nota de Liquidação', 'fa:plus-circle green');
        $add_product->getAction()->setParameter('static','1');
        $this->form->addFields( [], [$add_product] );
        
        $this->nota_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->nota_list->setHeight(150);
        $this->nota_list->makeScrollable();
        $this->nota_list->setId('notas_list');
        $this->nota_list->generateHiddenFields();
        $this->nota_list->style = "min-width: 700px; width:100%;margin-bottom: 10px";
        
        $col_uniq   = new TDataGridColumn( 'uniqid', 'Uniqid', 'center', '10%');
        $col_idempenho = new TDataGridColumn('idempenho', 'Código do Emp', 'center', '10%');
        $col_numempenho = new TDataGridColumn('numempenho', 'Numero do Emp', 'center', '10%');
        $col_valorempenho = new TDataGridColumn('valorempenho', 'Valor do Emp', 'center', '10%');
        $col_fonteempenho = new TDataGridColumn('fonteempenho', 'Valor do Emp', 'center', '10%');
        $col_id     = new TDataGridColumn( 'idNL', 'ID', 'center', '10%');
        $col_pid    = new TDataGridColumn( 'numnl', 'ProdID', 'center', '10%');
        $col_descr  = new TDataGridColumn( 'valor', 'Product', 'left', '30%');
        $col_amount = new TDataGridColumn( 'data', 'Amount', 'left', '10%');
        $col_price  = new TDataGridColumn( 'sale_price', 'Price', 'right', '15%');
        $col_disc   = new TDataGridColumn( 'discount', 'Discount', 'right', '15%');
        $col_subt   = new TDataGridColumn( '={valorempenho} - {valor} )', 'Subtotal', 'right', '20%');
        
        $this->nota_list->addColumn( $col_uniq );
        $this->nota_list->addColumn( $col_idempenho );
        $this->nota_list->addColumn( $col_numempenho );
        $this->nota_list->addColumn( $col_valorempenho );
        $this->nota_list->addColumn( $col_fonteempenho );
        $this->nota_list->addColumn( $col_id );
        $this->nota_list->addColumn( $col_pid );
        $this->nota_list->addColumn( $col_descr );
        $this->nota_list->addColumn( $col_amount );
        $this->nota_list->addColumn( $col_price );
        $this->nota_list->addColumn( $col_disc );
        $this->nota_list->addColumn( $col_subt );
        
        $col_descr->setTransformer(function($value) {
            return Product::findInTransaction('sistema', $value)->description;
        });
        
        $col_id->setVisibility(false);
        $col_uniq->setVisibility(false);
        
        // creates two datagrid actions
        $action1 = new TDataGridAction([$this, 'onEditItemProduto'] );
        $action1->setFields( ['uniqid', '*'] );
        
        $action2 = new TDataGridAction([$this, 'onDeleteItem']);
        $action2->setField('uniqid');
        
        // add the actions to the datagrid
        $this->nota_list->addAction($action1, _t('Edit'), 'far:edit blue');
        $this->nota_list->addAction($action2, _t('Delete'), 'far:trash-alt red');
        
        $this->nota_list->createModel();
        
        $panel = new TPanelGroup;
        $panel->add($this->nota_list);
        $panel->getBody()->style = 'overflow-x:auto';
        $this->form->addContent( [$panel] );
        
        $format_value = function($value) {
            if (is_numeric($value)) {
                return 'R$ '.number_format($value, 2, ',', '.');
            }
            return $value;
        };
        
        $col_price->setTransformer( $format_value );
        $col_disc->setTransformer( $format_value );
        $col_subt->setTransformer( $format_value );
        
        $this->form->addHeaderActionLink( _t('Fornecedor'),  new TAction(['FornecedorForm', 'onEdit'], ['register_state1' => 'true']), 'fa:plus blue' );
        //$this->form->addHeaderActionLink( _t('Empenho'),  new TAction(['SysempenhoForm', 'onEdit'], ['register_state2' => 'false']), 'fa:plus green' );
        $this->form->addHeaderActionLink( _t('Close'),  new TAction([__CLASS__, 'onClose'], ['static'=>'1']), 'fa:times red');
        $this->form->addAction( 'Save',  new TAction([$this, 'onSave'], ['static'=>'1']), 'fa:save green');
        $this->form->addAction( 'Clear', new TAction([$this, 'onClear']), 'fa:eraser red');
       
      
        
        // create the page container
        $container = new TVBox;
        $container->style = 'width: 100%';
        //$container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        parent::add($container);
    }
    
    /**
     * Pre load some data
     */
    public function onLoad($param)
    {
        $data = new stdClass;
        $data->fornecedor_nome   = $param['fornecedor_nome'];
        $this->form->setData($data);
    }
    
     
    
    
    
     /**
     * On Fornecedor change
     */
    public static function onFornecedorChange( $params )
    {
        if( !empty($params['fornecedor_id']) )
        {
            try
            {
                TTransaction::open('sistema');
                $fornecedor   = new SystemFornecedor($params['fornecedor_id']);
                TForm::sendData('form_Sale', (object) ['cnpj' => $fornecedor->cnpj ]);
               
                TTransaction::close();
            }
            catch (Exception $e)
            {
                new TMessage('error', $e->getMessage());
                TTransaction::rollback();
            }
        }
    }
    
    
    
    /**
     * On Empenho change
     */
    public static function onEmpenhoChange( $params )
    {
        if( !empty($params['empenho_detalhe_id']) )
        {
            try
            {
                TTransaction::open('sistema');
                $empenho   = new SysEmpenho($params['empenho_detalhe_id']);
                TForm::sendData('form_Sale', (object) ['valor' => $empenho->valor ]);
                TForm::sendData('form_Sale', (object) ['objeto' => $empenho->objeto ]);
                TForm::sendData('form_Sale', (object) ['fonte' => $empenho->fonte ]);
                TForm::sendData('form_Sale', (object) ['equipamento' => $empenho->equipamento ]);
                TForm::sendData('form_Sale', (object) ['dataemp' => $empenho->data ]);
                TTransaction::close();
            }
            catch (Exception $e)
            {
                new TMessage('error', $e->getMessage());
                TTransaction::rollback();
            }
        }
    }
    
    
    /**
     * Clear form
     * @param $param URL parameters
     */
    function onClear($param)
    {
        $this->form->clear();
    }
    
    /**
     * Add a product into item list
     * @param $param URL parameters
     */
    public function onNotaAdd( $param )
    {
        try
        {
            $this->form->validate();
            $data = $this->form->getData();
            
            if( (! $data->nl_id) || (! $data->empenho_detalhe_id))
            {
                var_dump($data->nl_id);

                Kint::dump($data->nl_id); // pass any number of parameters
                d($data->nl_id); // or simply use d() as a shorthand

                Kint::trace(); // Debug backtrace
                d(1); // Debug backtrace shorthand

                s($data->nl_id); // Basic output mode

                ~d($data->nl_id); // Text only output mode

                Kint::$enabled_mode = false; // Disable kint
                d('Get off my lawn!'); // Debugs no longer have any effect




                throw new Exception('Os Campos da Liquidações devem serem inseridos, Amount and Price are required');
            }
            
            $uniqid = !empty($data->nl_id) ? $data->nl_id : nl_id();
            
            $grid_data = ['uniqid'      => $nl_id,
                          'idempenho'          => $data->empenho_detalhe_id,
                          'numempenho'  => $data->product_detail_product_id,
                          'valorempenho'      => $data->empenho_valor,
                          'numnl'  => $data->numnl ,
                          'valor'  => $data->valornl,
                          'data'  => $data->datanl
                          //'numnl'  => $data->product_detail_price,
                          //'numnl'  => $data->product_detail_price,
                          //'discount'    => $data->product_detail_discount
                          ];
            
            // insert row dynamically
            $row = $this->nota_list->addItem( (object) $grid_data );
            $row->id = $uniqid;
            
            TDataGrid::replaceRowById('notas_list', $uniqid, $row);
            
            // clear product form fields after add
            $data->product_detail_uniqid     = '';
            $data->product_detail_id         = '';
            $data->product_detail_product_id = '';
            $data->product_detail_name       = '';
            //$data->product_detail_amount     = '';
            //$data->product_detail_price      = '';
            //$data->product_detail_discount   = '';
            
            // send data, do not fire change/exit events
            TForm::sendData( 'form_Sale', $data, false, false );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Edit a product from item list
     * @param $param URL parameters
     */
    public static function onEditItemProduto( $param )
    {
        $data = new stdClass;
        $data->product_detail_uniqid     = $param['uniqid'];
        $data->product_detail_id         = $param['id'];
        $data->product_detail_product_id = $param['product_id'];
        $data->product_detail_amount     = $param['amount'];
        $data->product_detail_price      = $param['sale_price'];
        $data->product_detail_discount   = $param['discount'];
        
        // send data, do not fire change/exit events
        TForm::sendData( 'form_Sale', $data, false, false );
    }
    
    /**
     * Delete a product from item list
     * @param $param URL parameters
     */
    public static function onDeleteItem( $param )
    {
        $data = new stdClass;
        $data->product_detail_uniqid     = '';
        $data->product_detail_id         = '';
        $data->product_detail_product_id = '';
        $data->product_detail_amount     = '';
        $data->product_detail_price      = '';
        $data->product_detail_discount   = '';
        
        // send data, do not fire change/exit events
        TForm::sendData( 'form_Sale', $data, false, false );
        
        // remove row
        TDataGrid::removeRowById('notas_list', $param['uniqid']);
    }
    
    /**
     * Edit Sale
     */
    public function onEdit($param)
    {
        try
        {
            TTransaction::open('samples');
            
            if (isset($param['key']))
            {
                $key = $param['key'];
                
                $object = new Sale($key);
                $sale_items = SaleItem::where('sale_id', '=', $object->id)->load();
                
                foreach( $sale_items as $item )
                {
                    $item->uniqid = uniqid();
                    $row = $this->nota_list->addItem( $item );
                    $row->id = $item->uniqid;
                }
                $this->form->setData($object);
                TTransaction::close();
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Save the sale and the sale items
     */
    public function onSave($param)
    {
        try
        {
            TTransaction::open('sistema');
            
            $data = $this->form->getData();
            $this->form->validate();
            
            $sale = new Sale;
            $sale->fromArray((array) $data);
            $sale->store();
            
            SaleItem::where('sale_id', '=', $sale->id)->delete();
            
            $total = 0;
            if( !empty($param['empenho_detalhe_id'] ))
            {
                foreach( $param['empenho_detalhe_id'] as $key => $item_id )
                {
                    $item = new SaleItem;
                    $item->product_id  = $item_id;
                    $item->sale_price  = (float) $param['notas_list_sale_price'][$key];
                    $item->amount      = (float) $param['notas_list_amount'][$key];
                    $item->discount    = (float) $param['notas_list_discount'][$key];
                    $item->total       = ( $item->sale_price * $item->amount ) - $item->discount;
                    
                    $item->sale_id = $sale->id;
                    $item->store();
                    $total += $item->total;
                }
            }
            $sale->total = $total;
            $sale->store(); // stores the object
            
            TForm::sendData('form_Sale', (object) ['id' => $sale->id]);
            
            TTransaction::close(); // close the transaction
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback();
        }
    }
    
     public function formatDate($date, $object)
    {
        $dt = new DateTime($date);
        return $dt->format('d/m/Y');
    }
    /**
     * Closes window
     */
    public static function onClose()
    {
        parent::closeWindow();
    }
}
