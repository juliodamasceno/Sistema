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
        //parent::removeTitleBar();
        //parent::disableEscape();
        
        // creates the form
       
        $this->form = new BootstrapFormBuilder('form_Sale');
        $this->form->setFormTitle('<h4>Cadastro de Pagamentos xyz</h4>');
        $this->form->setProperty('style', 'margin:0;border:0');
        $this->form->setClientValidation(true); 
        
        // master fields
        $id          = new TEntry('id');
        $date        = new TDate('date');
        $numprocesso = new TEntry('numprocesso');
        $customer_id = new TDBUniqueSearch('customer_id', 'sistema', 'SystemFornecedor', 'idfornecedor', 'fornecedor');
        $cpf_cnpj = new TEntry('cnpj');
        $obs         = new TText('obs');
        
        // detail fields
        $empenho_id = new THidden('idempenho');
        $empenho_detail_id         = new THidden('empenho_id');
        $product_detail_product_id = new TDBUniqueSearch('product_detail_product_id', 'sistema', 'Sysempenho', 'idempenho', 'numempenho');
        $product_detail_product_id->setMinLength(1);
        $product_detail_product_id->setMask('({idempenho}) - {numempenho} / {objeto}');   
        
        $empenho_detalhe_id = new TDBUniqueSearch('empenho_detalhe_id', 'sistema', 'Sysempenho', 'idempenho', 'numempenho');
        $empenho_detalhe_id ->setMinLength(1);
        $empenho_detalhe_id ->setMask('({idempenho}) - {numempenho} / {objeto}');        
        
        $product_detail_price      = new TEntry('product_detail_price');
        $product_detail_amount     = new TEntry('product_detail_amount');
        $product_detail_discount   = new TEntry('product_detail_discount');
        $product_detail_discount2 = new TEntry('product_detail_discount2');
        $product_detail_total      = new TEntry('product_detail_total');
        
        // adjust field properties
        $id->setEditable(false);
        $customer_id->setSize('100%');
        $customer_id->setMinLength(1);
        $date->setSize('100%');
        $obs->setSize('100%', 80);
        $product_detail_product_id->style = 'width:160%;';
        $product_detail_product_id->setSize('100%');
        $product_detail_product_id->setMinLength(1);
        $product_detail_price->setSize('100%');
        $product_detail_amount->setSize('100%');
        $product_detail_discount->setSize('100%');
        $product_detail_discount2->setSize('100%');
   
       
       
             
       // $button = new TActionLink('Open dialog', new TAction([$this, 'onInputDialog']));
       // $button->class='btn btn-default';
        
        // add validations
        $date->addValidation('Date', new TRequiredValidator);
        $customer_id->addValidation('Customer', new TRequiredValidator);
        
        // change action
        $product_detail_product_id->setChangeAction(new TAction([$this,'onProductChange']));
        
        
       
        $this->form->addFields( [new TLabel('Código')], [$id], 
                                [new TLabel('Número do Processo', '#FF0000')], [$numprocesso],
                                [new TLabel('Data de Cadastro (*)', '#FF0000')], [$date] );
        $this->form->addFields( [new TLabel('Fornecedor (*)', '#FF0000')], [$customer_id ] );
        $this->form->addFields( [new TLabel('CPF/CNPJ (*)', '#FF0000')], [$cpf_cnpj ] );
        $this->form->addFields( [new TLabel('Objeto do Pagamento')], [$obs] );
        $add_link = '<a class="btn btn-default" generator="adianti" href="index.php?class=SysempenhoForm"> <b>Cadastrar Empenho</b></a>';

        $this->form->addContent( ['<h4>Empenho (s) :</h4><hr>'] );
        $this->form->addFields( [ $empenho_id], [$empenho_detail_id]);
        
      
        
     
        $this->form->addFields( [ new TLabel('N° do Empenho (*)', '#FF0000') ], [$product_detail_product_id], [], [$add_link]); 
        $this->form->addFields( [ new TLabel('Valor total R$', '#FF0000') ],   [$product_detail_amount] );
        $this->form->addFields( [ new TLabel('Fonte', '#FF0000') ],   [$product_detail_price],
                                [ new TLabel('NR')],                [$product_detail_discount],
                                [ new TLabel('Area')],                [$product_detail_discount2] );
        
        $this->form->addContent( ['<h4>Empenho Extra:</h4><hr>'] );
        
        $this->form->addContent( ['<h4>Nota Liquidação :</h4><hr>'] );
        
        
        
        $add_product = TButton::create('add_product', [$this, 'onProductAdd'], 'Register', 'fa:plus-circle green');
        $add_product->getAction()->setParameter('static','1');
        $this->form->addFields( [], [$add_product] );
        
        $this->product_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->product_list->setHeight(150);
        $this->product_list->makeScrollable();
        $this->product_list->setId('products_list');
        $this->product_list->generateHiddenFields();
        $this->product_list->style = "min-width: 700px; width:100%;margin-bottom: 10px";
        
        $col_uniq   = new TDataGridColumn( 'uniqid', 'Uniqid', 'center', '10%');
        $col_id     = new TDataGridColumn( 'id', 'ID', 'center', '10%');
        $col_pid    = new TDataGridColumn( 'product_id', 'ProdID', 'center', '10%');
        $col_descr  = new TDataGridColumn( 'product_id', 'Product', 'left', '30%');
        $col_amount = new TDataGridColumn( 'amount', 'Amount', 'left', '10%');
        $col_price  = new TDataGridColumn( 'sale_price', 'Price', 'right', '15%');
        $col_disc   = new TDataGridColumn( 'discount', 'Discount', 'right', '15%');
        $col_subt   = new TDataGridColumn( '={amount} * ( {sale_price} - {discount} )', 'Subtotal', 'right', '20%');
        
        $this->product_list->addColumn( $col_uniq );
        $this->product_list->addColumn( $col_id );
        $this->product_list->addColumn( $col_pid );
        $this->product_list->addColumn( $col_descr );
        $this->product_list->addColumn( $col_amount );
        $this->product_list->addColumn( $col_price );
        $this->product_list->addColumn( $col_disc );
        $this->product_list->addColumn( $col_subt );
        
        $col_descr->setTransformer(function($value) {
            return Product::findInTransaction('samples', $value)->description;
        });
        
        $col_id->setVisibility(false);
        $col_uniq->setVisibility(false);
        

        // creates two datagrid actions
        $action1 = new TDataGridAction([$this, 'onEditItemProduto'] );
        $action1->setFields( ['uniqid', '*'] );
        
        $action2 = new TDataGridAction([$this, 'onDeleteItem']);
        $action2->setField('uniqid');
        
        // add the actions to the datagrid
        $this->product_list->addAction($action1, _t('Edit'), 'far:edit blue');
        $this->product_list->addAction($action2, _t('Delete'), 'far:trash-alt red');
        
        $this->product_list->createModel();
        
        $panel = new TPanelGroup;
        $panel->add($this->product_list);
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
        $data->customer_id   = $param['customer_id'];
        $this->form->setData($data);
    }
    
    
    /**
     * On product change
     */
    public static function onProductChange( $params )
    {
        if( !empty($params['product_detail_product_id']) )
        {
            try
            {
                TTransaction::open('sistema');
                $empenho   = new SysEmpenho($params['product_detail_product_id']);
                TForm::sendData('form_Sale', (object) ['product_detail_price' => $empenho->valor ]);
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
    public function onProductAdd( $param )
    {
        try
        {
            $this->form->validate();
            $data = $this->form->getData();
            
            if( (! $data->product_detail_product_id) || (! $data->product_detail_amount) || (! $data->product_detail_price) )
            {
                throw new Exception('The fields Product, Amount and Price are required');
            }
            
            $uniqid = !empty($data->product_detail_uniqid) ? $data->product_detail_uniqid : uniqid();
            
            $grid_data = ['uniqid'      => $uniqid,
                          'id'          => $data->product_detail_id,
                          'product_id'  => $data->product_detail_product_id,
                          'amount'      => $data->product_detail_amount,
                          'sale_price'  => $data->product_detail_price,
                          'discount'    => $data->product_detail_discount];
            
            // insert row dynamically
            $row = $this->product_list->addItem( (object) $grid_data );
            $row->id = $uniqid;
            
            TDataGrid::replaceRowById('products_list', $uniqid, $row);
            
            // clear product form fields after add
            $data->product_detail_uniqid     = '';
            $data->product_detail_id         = '';
            $data->product_detail_product_id = '';
            $data->product_detail_name       = '';
            $data->product_detail_amount     = '';
            $data->product_detail_price      = '';
            $data->product_detail_discount   = '';
            
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
        TDataGrid::removeRowById('products_list', $param['uniqid']);
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
                    $row = $this->product_list->addItem( $item );
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
            TTransaction::open('samples');
            
            $data = $this->form->getData();
            $this->form->validate();
            
            $sale = new Sale;
            $sale->fromArray((array) $data);
            $sale->store();
            
            SaleItem::where('sale_id', '=', $sale->id)->delete();
            
            $total = 0;
            if( !empty($param['products_list_product_id'] ))
            {
                foreach( $param['products_list_product_id'] as $key => $item_id )
                {
                    $item = new SaleItem;
                    $item->product_id  = $item_id;
                    $item->sale_price  = (float) $param['products_list_sale_price'][$key];
                    $item->amount      = (float) $param['products_list_amount'][$key];
                    $item->discount    = (float) $param['products_list_discount'][$key];
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
    
    /**
     * Closes window
     */
    public static function onClose()
    {
        parent::closeWindow();
    }
}
