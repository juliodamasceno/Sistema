<?php
/**
 * SysempenhoCad Form
 * @author  <your name here>
 */
class SysempenhoCad extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Sysempenho');
        $this->form->setFormTitle('Cadastro de Empenho');
        

        // create the form fields
        $idempenho = new TEntry('idempenho');
        $numempenho = new TEntry('numempenho');
        $objeto = new TText('objeto');
        $valor = new TEntry('valor');
        $dataemp = new TDate('dataemp');
        $procorigem = new TEntry('procorigem');
        
        $equipamento = new TDBUniqueSearch('idequipamento', 'sistema', 'SysEquipamento', 'idequipamento', 'nomeequipamento');
        $equipamento->setMinLength(1);
        $equipamento->setMask('{idequipamento}, {sigla}');
        
        $fonte = new TDBUniqueSearch('idfonte', 'sistema', 'SysFonte', 'idfonte', 'fonte');
        $fonte->setMinLength(1);
        $fonte->setMask('{idfonte}, {fontedec} - {fonte}');
        
        
        // add the fields
        $this->form->addFields( [ new TLabel('Código') ], [ $idempenho ] );
        $this->form->addFields( [ new TLabel('Número do empenho') ], [ $numempenho ] );
        $this->form->addFields( [ new TLabel('Objeto') ], [ $objeto ] );
        $this->form->addFields( [ new TLabel('Valor em R$') ], [ $valor ] );
        $this->form->addFields( [ new TLabel('Data do empenho') ], [ $dataemp ] );
        $this->form->addFields( [ new TLabel('Processo de origem') ], [ $procorigem ] );
        $this->form->addFields( [ new TLabel('Equipamento atendido') ], [ $equipamento ] );
        $this->form->addFields( [new TLabel('Fonte de recurso') ], [$fonte]);




        // set sizes
        $idempenho->setSize('100%');
        $numempenho->setSize('100%');
        $objeto->setSize('100%');
        $valor->setSize('100%');
        $dataemp->setSize('100%');
        $procorigem->setSize('100%');
        $equipamento->setSize('100%');



        if (!empty($idempenho))
        {
            $idempenho->setEditable(FALSE);
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('sistema'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new Sysempenho;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated idempenho
            $data->idempenho = $object->idempenho;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(TRUE);
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('sistema'); // open a transaction
                $object = new Sysempenho($key); // instantiates the Active Record
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear(TRUE);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
}
