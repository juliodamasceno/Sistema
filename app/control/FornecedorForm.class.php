<?php
/**
 * SystemFornecedorForm Form
 * @author  <your name here>
 */
class FornecedorForm extends TPage
{
       protected $form; // form
        
        /**
         * Form constructor
         * @param $param Request
         */
        public function __construct( $param )
        {
            parent::__construct();
           
            
            $script = new TElement('script');
        	$script->type = 'text/javascript';
        
        	$javascript = "

        	    // personaliza os campos de acordo com o tipo de pessoa
        	    $('select[name=\"tipo\"]').change(function(event){
        	        var tipoPessoa;
        	        $('select[name=\"tipo\"] > option:selected').each(function(){
        	                   tipoPessoa = $(this).text();
        	        });
        	        
        	        //alert(tipoPessoa.toLowerCase());
        	        if (tipoPessoa.toLowerCase() == 'pessoa física') {
        	            //$('label:contains(CNPJ/CPF)').text('CPF');
        	            //$('label:contains(CNPJ)').text('CPF');
        	            $('input[name=\"cpf_cnpj\"]').attr({onkeypress:'return tentry_mask(this,event,\"999.999.999-99\")'}).val('');
        	        }
        	        if (tipoPessoa.toLowerCase() == 'pessoa jurídica') {
        	            //$('label:contains(CNPJ/CPF)').text('CNPJ');
        	            //$('label:contains(CPF)').text('CNPJ');
        	            $('input[name=\"cpf_cnpj\"]').attr({onkeypress:'return tentry_mask(this,event,\"99.999.999/9999-99\")'}).val('');
        	        }
        	    });
        	";
       
        $script->add($javascript);
        parent::add($script);
            
            
            
            
            // creates the form
            $this->form = new TQuickForm('form_FornecedorForm');
            $this->form->class = 'tform'; // change CSS class
            $this->form = new BootstrapFormWrapper($this->form);
            $this->form->style = 'display: table;width:100%'; // change style
            
            // define the form title
            $this->form->setFormTitle('Cadastro de Fornecedor :');
            
            // create the form fields
            $id = new TEntry('idfornecedor');
            $nome = new TEntry('fornecedor');
            //$cnpj = new TEntry('cnpj');
            $cpf_cnpj  = new TEntry('cnpj');
            $telefone = new TEntry('telefone');
            $email = new TEntry('email');
            $pessoa = new TCombo('tipo');
            $ativo = new TRadioGroup('ativo');
            $cadastro = new TDate('datacadastro');
               
           
            
            $pessoa_tipo = array();
            $pessoa_tipo['F'] = 'Física';
            $pessoa_tipo['J'] = 'Jurídica';
            $pessoa->addItems($pessoa_tipo);
            
            $ativo_tipo = array();
            $ativo_tipo['A'] = 'Ativo';
            $ativo_tipo['I'] = 'Inativo';
            $ativo->addItems($ativo_tipo);
            // add the fields
           


            /*Dados Principais*/
        //$this->form->addQuickField( ['<h4><b>Dados Principais</b></h4><hr>'] );
        $tipo = new TCombo('tipo');
        $tipo->setChangeAction(new TAction(array($this, 'onChangeSexo')));
        $combo_tipos = array();
        $combo_tipos['F'] = 'Pessoa Física';
        $combo_tipos['J'] = 'Pessoa Jurídica';
        $tipo->addItems($combo_tipos);

        //$tipo->setValue('J');
        
        //$cpf_cnpj = new TEntry('cpf_cnpj');
        //$buscaCnpj = new TAction(array($this, 'onCNPJ'));
        //$cpf_cnpj->setExitAction($buscaCnpj);

        $rg_ie = new TEntry('rg_ie');
        $im = new TEntry('im');
        
       // $this->form->addQuickField('Id:', $id ,  250 );
        $this->form->setFieldsByRow(2);
        $this->form->addQuickField('Código:', $id,  250 );
        $this->form->addQuickField('Fonercedor:', $nome,  250 );
        $this->form->addQuickField('Categoria:', $pessoa,  250 );
        //$this->form->addQuickField('CPF / CNPJ:', $cnpj,  250 );
        $this->form->addQuickField('CPF/CNPJ:', $cpf_cnpj ,  250 );
        $this->form->addQuickField('Telefone:', $telefone,  250 );
        $this->form->addQuickField('Email:', $email,  250 );
        $this->form->addQuickField('Cadastro:', $cadastro,  230 );
        $this->form->addQuickField('Situação:', $ativo,  250 );
       // $this->form->addQuickField('Id:', $tipo ,  250 );
        
        $this->form->addQuickField('Insc Estadual:', $rg_ie ,  250 );
         $this->form->addQuickField('IM:', $im  ,  250 );
        
        
        if (!empty($_GET['idfornecedor']))
        {
            TTransaction::open('sistema');
            $pegarID = new SystemFornecedor($_GET['idfornecedor']);
            if (!empty($pegarID->id)) {
                if($pegarID->tipo == 'J'){
                    $cpf_cnpj->setMask('99.999.999/9999-99');
                }else{
                    $cpf_cnpj->setMask('999.999.999-99');
                }
            }
            TTransaction::close();
        }

            
            $script = new TElement('script'); 
            $script->type = 'text/javascript'; 
            $javascript = " 
            // personaliza os campos de acordo com o tipo de pessoa 
            $('select[name=\"pessoa\"]').change(function(event){ 
            var tipoPessoa 
            $('select[name=\"pessoa\"] > option:selected').each(function(){ 
            pessoa = $(this).text(); 
            }); 
            if(pessoa == 'F') { 
            $('input[name=\"cnpj\"]').val(''); 
            $('input[name=\"cnpj\"]').attr({onkeypress:'return tentry_mask(this,event,\"999.999.999-99\")'}); 
            } 
            if(pessoa == 'J') { 
            $('input[name=\"cnpj\"]').val(''); 
            $('input[name=\"cnpj\"]').attr({onkeypress:'return tentry_mask(this,event,\"99.999.999/9999-99\")'}); 
            } 
            }); 
            "; 
            $script->add($javascript); 
            parent::add($script); 
            if (!empty($id))
            {
                $id->setEditable(FALSE);
            }
            
            /** samples
             $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
             $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
             $fieldX->setSize( 100, 40 ); // set size
             **/
             
            // create the form actions
            $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
            $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onClear')), 'bs:plus-sign green');
            $this->form->addQuickAction(_t('Back to the listing'), new TAction(array('SysFornecedorList','onReload')), 'fa:table blue');
            
            // vertical box container
            $container = new TVBox;
            $container->style = 'width: 90%';
            //$container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
            $container->add(TPanelGroup::pack('Cadastro de Fornecedor', $this->form));
            
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
                
                $object = new SystemFornecedor;  // create an empty object
                $data = $this->form->getData(); // get form data as array
                $object->fromArray( (array) $data); // load the object with data
                $object->store(); // save the object
                
                // get the generated id
                $data->id = $object->id;
                
                $this->form->setData($data); // fill form data
                TTransaction::close(); // close the transaction
                
                new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
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
            $this->form->clear();
        }
        
        public static function onChangeSexo($param)
    {
        if ($param['tipo'] == 'F')
        {
            TQuickForm::showField('form_Cliente', 'sexo');
        }
        else
        {
            TQuickForm::hideField('form_Cliente', 'sexo');
        }
    }

    
     public static function onCep($param)
    {
        try {
            $retorno = Utilidades::onCep($param['detail_cep']);
            $objeto  = json_decode($retorno);
            
            if (isset($objeto->logradouro)){
                $obj              = new stdClass();
                $obj->detail_logradouro = $objeto->logradouro;
                $obj->detail_bairro   = $objeto->bairro;
                $obj->detail_cidade   = $objeto->localidade;
                $obj->detail_uf       = $objeto->uf;
                $obj->detail_codMuni  = $objeto->ibge;

                TForm::sendData('form_Cliente',$obj);
                unset($obj);
            }else{
                //new TMessage('info', 'Erro ao buscar endereço por este CEP.');
            }
        }catch (Exception $e){
            new TMessage('error', '<b>Error:</b> ' . $e->getMessage());
        }
    }
                            
    public static function onCNPJ($param)
    {
        try {
        
          if(strlen(trim($param['cpf_cnpj'])) == 18){
          
                $retorno = Utilidades::onCNPJ($param['cpf_cnpj']);
                $objeto  = json_decode($retorno);
                //var_dump($objeto);
                if (isset($objeto->nome)){
                $obj              = new stdClass();
                $obj->razao_social = $objeto->nome;
                $obj->nome_fantasia = $objeto->fantasia;
                
                TForm::sendData('form_Cliente',$obj);
                unset($obj);
                }else{
                    new TMessage('info', 'Erro ao buscar endereço por este CNPJ.');
                }
           } 
        }catch (Exception $e){
            new TMessage('error', '<b>Error:</b> ' . $e->getMessage());
        }
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
                    $object = new SystemFornecedor($key); // instantiates the Active Record
                    $this->form->setData($object); // fill the form
                    TTransaction::close(); // close the transaction
                }
                else
                {
                    $this->form->clear();
                    $data = new StdClass;
                    $data->ativo = 'A';
                    $data->tipo = 'F';
                    $this->form->setData($data);
                }
            }
            catch (Exception $e) // in case of exception
            {
                new TMessage('error', $e->getMessage()); // shows the exception error message
                TTransaction::rollback(); // undo all pending operations
            }
        }
    }
    ?>


