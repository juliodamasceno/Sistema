<?php
/**
 * SystemAccessLogForm Form
 * @author  <your name here>
 */
class SystemAccessLogForm2 extends TPage
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
        $this->form = new BootstrapFormBuilder('form_SystemAccessLog');
        $this->form->setFormTitle('SystemAccessLog');
        

        // create the form fields
        $id = new TEntry('id');
        $sessionid = new TText('sessionid');
        $login = new TText('login');
        $login_time = new TEntry('login_time');
        $login_year = new TEntry('login_year');
        $login_month = new TEntry('login_month');
        $login_day = new TEntry('login_day');
        $logout_time = new TEntry('logout_time');
        $impersonated = new TEntry('impersonated');
        $access_ip = new TEntry('access_ip');


        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Sessionid') ], [ $sessionid ] );
        $this->form->addFields( [ new TLabel('Login') ], [ $login ] );
        $this->form->addFields( [ new TLabel('Login Time') ], [ $login_time ] );
        $this->form->addFields( [ new TLabel('Login Year') ], [ $login_year ] );
        $this->form->addFields( [ new TLabel('Login Month') ], [ $login_month ] );
        $this->form->addFields( [ new TLabel('Login Day') ], [ $login_day ] );
        $this->form->addFields( [ new TLabel('Logout Time') ], [ $logout_time ] );
        $this->form->addFields( [ new TLabel('Impersonated') ], [ $impersonated ] );
        $this->form->addFields( [ new TLabel('Access Ip') ], [ $access_ip ] );



        // set sizes
        $id->setSize('100%');
        $sessionid->setSize('100%');
        $login->setSize('100%');
        $login_time->setSize('100%');
        $login_year->setSize('100%');
        $login_month->setSize('100%');
        $login_day->setSize('100%');
        $logout_time->setSize('100%');
        $impersonated->setSize('100%');
        $access_ip->setSize('100%');



        if (!empty($id))
        {
            $id->setEditable(FALSE);
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
            TTransaction::open('log'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new SystemAccessLog;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
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
                TTransaction::open('log'); // open a transaction
                $object = new SystemAccessLog($key); // instantiates the Active Record
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
