<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

    include_once dirname(__FILE__) . '/components/startup.php';
    include_once dirname(__FILE__) . '/components/application.php';
    include_once dirname(__FILE__) . '/' . 'authorization.php';


    include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page/page_includes.php';

    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthentication()->applyIdentityToConnectionOptions($result);
        return $result;
    }

    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class messPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->SetTitle('Mess');
            $this->SetMenuLabel('Mess');
            $this->SetHeader(GetPagesHeader());
            $this->SetFooter(GetPagesFooter());
    
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`mess`');
            $this->dataset->addFields(
                array(
                    new IntegerField('mess_ID_Number', true, true, true),
                    new IntegerField('fresher_id_Number'),
                    new IntegerField('non_fresher_id_Number'),
                    new StringField('student_name', true),
                    new StringField('Mess_name', true),
                    new IntegerField('Opening_Balance_Per_Sem', true),
                    new IntegerField('Closing_Balance_Per_Sem', true),
                    new IntegerField('JMC_Charges', true),
                    new IntegerField('Deposit'),
                    new IntegerField('Hostel_Id'),
                    new IntegerField('Meals'),
                    new IntegerField('Guests'),
                    new IntegerField('Fine'),
                    new IntegerField('Non_Veg'),
                    new IntegerField('Total')
                )
            );
            $this->dataset->AddLookupField('fresher_id_Number', 'fresher', new IntegerField('fresher_id_Number'), new StringField('First_Name', false, false, false, false, 'fresher_id_Number_First_Name', 'fresher_id_Number_First_Name_fresher'), 'fresher_id_Number_First_Name_fresher');
            $this->dataset->AddLookupField('non_fresher_id_Number', 'non_fresher', new IntegerField('non_fresher_id_Number'), new StringField('first_name', false, false, false, false, 'non_fresher_id_Number_first_name', 'non_fresher_id_Number_first_name_non_fresher'), 'non_fresher_id_Number_first_name_non_fresher');
            $this->dataset->AddLookupField('Hostel_Id', 'hostel', new IntegerField('Hostel_Id'), new StringField('Hostel_Name', false, false, false, false, 'Hostel_Id_Hostel_Name', 'Hostel_Id_Hostel_Name_hostel'), 'Hostel_Id_Hostel_Name_hostel');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function setupCharts()
        {
    
        }
    
        protected function getFiltersColumns()
        {
            return array(
                new FilterColumn($this->dataset, 'mess_ID_Number', 'mess_ID_Number', 'Mess ID Number'),
                new FilterColumn($this->dataset, 'fresher_id_Number', 'fresher_id_Number_First_Name', 'Fresher Id Number'),
                new FilterColumn($this->dataset, 'non_fresher_id_Number', 'non_fresher_id_Number_first_name', 'Non Fresher Id Number'),
                new FilterColumn($this->dataset, 'student_name', 'student_name', 'Student Name'),
                new FilterColumn($this->dataset, 'Mess_name', 'Mess_name', 'Mess Name'),
                new FilterColumn($this->dataset, 'Opening_Balance_Per_Sem', 'Opening_Balance_Per_Sem', 'Opening Balance Per Sem'),
                new FilterColumn($this->dataset, 'Closing_Balance_Per_Sem', 'Closing_Balance_Per_Sem', 'Closing Balance Per Sem'),
                new FilterColumn($this->dataset, 'JMC_Charges', 'JMC_Charges', 'JMC Charges'),
                new FilterColumn($this->dataset, 'Deposit', 'Deposit', 'Deposit'),
                new FilterColumn($this->dataset, 'Hostel_Id', 'Hostel_Id_Hostel_Name', 'Hostel Id'),
                new FilterColumn($this->dataset, 'Meals', 'Meals', 'Meals'),
                new FilterColumn($this->dataset, 'Guests', 'Guests', 'Guests'),
                new FilterColumn($this->dataset, 'Fine', 'Fine', 'Fine'),
                new FilterColumn($this->dataset, 'Non_Veg', 'Non_Veg', 'Non Veg'),
                new FilterColumn($this->dataset, 'Total', 'Total', 'Total')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['mess_ID_Number'])
                ->addColumn($columns['fresher_id_Number'])
                ->addColumn($columns['non_fresher_id_Number'])
                ->addColumn($columns['student_name'])
                ->addColumn($columns['Mess_name'])
                ->addColumn($columns['Opening_Balance_Per_Sem'])
                ->addColumn($columns['Closing_Balance_Per_Sem'])
                ->addColumn($columns['JMC_Charges'])
                ->addColumn($columns['Deposit'])
                ->addColumn($columns['Hostel_Id'])
                ->addColumn($columns['Meals'])
                ->addColumn($columns['Guests'])
                ->addColumn($columns['Fine'])
                ->addColumn($columns['Non_Veg'])
                ->addColumn($columns['Total']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
    
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
                $operation->SetAdditionalAttribute('data-modal-operation', 'delete');
                $operation->SetAdditionalAttribute('data-delete-handler-name', $this->GetModalGridDeleteHandler());
            }
            
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for mess_ID_Number field
            //
            $column = new NumberViewColumn('mess_ID_Number', 'mess_ID_Number', 'Mess ID Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for First_Name field
            //
            $column = new TextViewColumn('fresher_id_Number', 'fresher_id_Number_First_Name', 'Fresher Id Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for first_name field
            //
            $column = new TextViewColumn('non_fresher_id_Number', 'non_fresher_id_Number_first_name', 'Non Fresher Id Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for student_name field
            //
            $column = new TextViewColumn('student_name', 'student_name', 'Student Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Mess_name field
            //
            $column = new TextViewColumn('Mess_name', 'Mess_name', 'Mess Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Opening_Balance_Per_Sem field
            //
            $column = new NumberViewColumn('Opening_Balance_Per_Sem', 'Opening_Balance_Per_Sem', 'Opening Balance Per Sem', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Closing_Balance_Per_Sem field
            //
            $column = new NumberViewColumn('Closing_Balance_Per_Sem', 'Closing_Balance_Per_Sem', 'Closing Balance Per Sem', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for JMC_Charges field
            //
            $column = new NumberViewColumn('JMC_Charges', 'JMC_Charges', 'JMC Charges', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Deposit field
            //
            $column = new NumberViewColumn('Deposit', 'Deposit', 'Deposit', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Hostel_Name field
            //
            $column = new TextViewColumn('Hostel_Id', 'Hostel_Id_Hostel_Name', 'Hostel Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Meals field
            //
            $column = new NumberViewColumn('Meals', 'Meals', 'Meals', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Guests field
            //
            $column = new NumberViewColumn('Guests', 'Guests', 'Guests', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Fine field
            //
            $column = new NumberViewColumn('Fine', 'Fine', 'Fine', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Non_Veg field
            //
            $column = new NumberViewColumn('Non_Veg', 'Non_Veg', 'Non Veg', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Total field
            //
            $column = new NumberViewColumn('Total', 'Total', 'Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for mess_ID_Number field
            //
            $column = new NumberViewColumn('mess_ID_Number', 'mess_ID_Number', 'Mess ID Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for First_Name field
            //
            $column = new TextViewColumn('fresher_id_Number', 'fresher_id_Number_First_Name', 'Fresher Id Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for first_name field
            //
            $column = new TextViewColumn('non_fresher_id_Number', 'non_fresher_id_Number_first_name', 'Non Fresher Id Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for student_name field
            //
            $column = new TextViewColumn('student_name', 'student_name', 'Student Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Mess_name field
            //
            $column = new TextViewColumn('Mess_name', 'Mess_name', 'Mess Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Opening_Balance_Per_Sem field
            //
            $column = new NumberViewColumn('Opening_Balance_Per_Sem', 'Opening_Balance_Per_Sem', 'Opening Balance Per Sem', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Closing_Balance_Per_Sem field
            //
            $column = new NumberViewColumn('Closing_Balance_Per_Sem', 'Closing_Balance_Per_Sem', 'Closing Balance Per Sem', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for JMC_Charges field
            //
            $column = new NumberViewColumn('JMC_Charges', 'JMC_Charges', 'JMC Charges', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Deposit field
            //
            $column = new NumberViewColumn('Deposit', 'Deposit', 'Deposit', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Hostel_Name field
            //
            $column = new TextViewColumn('Hostel_Id', 'Hostel_Id_Hostel_Name', 'Hostel Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Meals field
            //
            $column = new NumberViewColumn('Meals', 'Meals', 'Meals', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Guests field
            //
            $column = new NumberViewColumn('Guests', 'Guests', 'Guests', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Fine field
            //
            $column = new NumberViewColumn('Fine', 'Fine', 'Fine', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Non_Veg field
            //
            $column = new NumberViewColumn('Non_Veg', 'Non_Veg', 'Non Veg', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Total field
            //
            $column = new NumberViewColumn('Total', 'Total', 'Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for fresher_id_Number field
            //
            $editor = new ComboBox('fresher_id_number_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`fresher`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('fresher_id_Number', true, true, true),
                    new StringField('First_Name', true),
                    new StringField('Course', true),
                    new StringField('Category'),
                    new StringField('Email'),
                    new IntegerField('Student_Mobile', true),
                    new IntegerField('Hostel_Id'),
                    new IntegerField('Room_No'),
                    new StringField('Native_Place'),
                    new StringField('Allotment_Remark')
                )
            );
            $lookupDataset->setOrderByField('First_Name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Fresher Id Number', 
                'fresher_id_Number', 
                $editor, 
                $this->dataset, 'fresher_id_Number', 'First_Name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for non_fresher_id_Number field
            //
            $editor = new ComboBox('non_fresher_id_number_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`non_fresher`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('non_fresher_id_Number', true, true, true),
                    new StringField('first_name', true),
                    new StringField('course', true),
                    new IntegerField('semester', true),
                    new IntegerField('Hostel_Id'),
                    new IntegerField('Room_No'),
                    new IntegerField('Pointer', true)
                )
            );
            $lookupDataset->setOrderByField('first_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Non Fresher Id Number', 
                'non_fresher_id_Number', 
                $editor, 
                $this->dataset, 'non_fresher_id_Number', 'first_name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for student_name field
            //
            $editor = new TextEdit('student_name_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Student Name', 'student_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Mess_name field
            //
            $editor = new TextEdit('mess_name_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Mess Name', 'Mess_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Opening_Balance_Per_Sem field
            //
            $editor = new TextEdit('opening_balance_per_sem_edit');
            $editColumn = new CustomEditColumn('Opening Balance Per Sem', 'Opening_Balance_Per_Sem', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Closing_Balance_Per_Sem field
            //
            $editor = new TextEdit('closing_balance_per_sem_edit');
            $editColumn = new CustomEditColumn('Closing Balance Per Sem', 'Closing_Balance_Per_Sem', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for JMC_Charges field
            //
            $editor = new TextEdit('jmc_charges_edit');
            $editColumn = new CustomEditColumn('JMC Charges', 'JMC_Charges', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Deposit field
            //
            $editor = new TextEdit('deposit_edit');
            $editColumn = new CustomEditColumn('Deposit', 'Deposit', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Hostel_Id field
            //
            $editor = new ComboBox('hostel_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`hostel`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('Hostel_Id', true, true),
                    new StringField('Hostel_Name'),
                    new IntegerField('No_Of_Rooms'),
                    new StringField('Hostel_Block')
                )
            );
            $lookupDataset->setOrderByField('Hostel_Name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Hostel Id', 
                'Hostel_Id', 
                $editor, 
                $this->dataset, 'Hostel_Id', 'Hostel_Name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Meals field
            //
            $editor = new TextEdit('meals_edit');
            $editColumn = new CustomEditColumn('Meals', 'Meals', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Guests field
            //
            $editor = new TextEdit('guests_edit');
            $editColumn = new CustomEditColumn('Guests', 'Guests', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Fine field
            //
            $editor = new TextEdit('fine_edit');
            $editColumn = new CustomEditColumn('Fine', 'Fine', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Non_Veg field
            //
            $editor = new TextEdit('non_veg_edit');
            $editColumn = new CustomEditColumn('Non Veg', 'Non_Veg', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Total field
            //
            $editor = new TextEdit('total_edit');
            $editColumn = new CustomEditColumn('Total', 'Total', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddMultiEditColumns(Grid $grid)
        {
            //
            // Edit column for fresher_id_Number field
            //
            $editor = new ComboBox('fresher_id_number_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`fresher`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('fresher_id_Number', true, true, true),
                    new StringField('First_Name', true),
                    new StringField('Course', true),
                    new StringField('Category'),
                    new StringField('Email'),
                    new IntegerField('Student_Mobile', true),
                    new IntegerField('Hostel_Id'),
                    new IntegerField('Room_No'),
                    new StringField('Native_Place'),
                    new StringField('Allotment_Remark')
                )
            );
            $lookupDataset->setOrderByField('First_Name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Fresher Id Number', 
                'fresher_id_Number', 
                $editor, 
                $this->dataset, 'fresher_id_Number', 'First_Name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for non_fresher_id_Number field
            //
            $editor = new ComboBox('non_fresher_id_number_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`non_fresher`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('non_fresher_id_Number', true, true, true),
                    new StringField('first_name', true),
                    new StringField('course', true),
                    new IntegerField('semester', true),
                    new IntegerField('Hostel_Id'),
                    new IntegerField('Room_No'),
                    new IntegerField('Pointer', true)
                )
            );
            $lookupDataset->setOrderByField('first_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Non Fresher Id Number', 
                'non_fresher_id_Number', 
                $editor, 
                $this->dataset, 'non_fresher_id_Number', 'first_name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for student_name field
            //
            $editor = new TextEdit('student_name_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Student Name', 'student_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Mess_name field
            //
            $editor = new TextEdit('mess_name_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Mess Name', 'Mess_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Opening_Balance_Per_Sem field
            //
            $editor = new TextEdit('opening_balance_per_sem_edit');
            $editColumn = new CustomEditColumn('Opening Balance Per Sem', 'Opening_Balance_Per_Sem', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Closing_Balance_Per_Sem field
            //
            $editor = new TextEdit('closing_balance_per_sem_edit');
            $editColumn = new CustomEditColumn('Closing Balance Per Sem', 'Closing_Balance_Per_Sem', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for JMC_Charges field
            //
            $editor = new TextEdit('jmc_charges_edit');
            $editColumn = new CustomEditColumn('JMC Charges', 'JMC_Charges', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Deposit field
            //
            $editor = new TextEdit('deposit_edit');
            $editColumn = new CustomEditColumn('Deposit', 'Deposit', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Hostel_Id field
            //
            $editor = new ComboBox('hostel_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`hostel`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('Hostel_Id', true, true),
                    new StringField('Hostel_Name'),
                    new IntegerField('No_Of_Rooms'),
                    new StringField('Hostel_Block')
                )
            );
            $lookupDataset->setOrderByField('Hostel_Name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Hostel Id', 
                'Hostel_Id', 
                $editor, 
                $this->dataset, 'Hostel_Id', 'Hostel_Name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Meals field
            //
            $editor = new TextEdit('meals_edit');
            $editColumn = new CustomEditColumn('Meals', 'Meals', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Guests field
            //
            $editor = new TextEdit('guests_edit');
            $editColumn = new CustomEditColumn('Guests', 'Guests', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Fine field
            //
            $editor = new TextEdit('fine_edit');
            $editColumn = new CustomEditColumn('Fine', 'Fine', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Non_Veg field
            //
            $editor = new TextEdit('non_veg_edit');
            $editColumn = new CustomEditColumn('Non Veg', 'Non_Veg', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Total field
            //
            $editor = new TextEdit('total_edit');
            $editColumn = new CustomEditColumn('Total', 'Total', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for fresher_id_Number field
            //
            $editor = new ComboBox('fresher_id_number_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`fresher`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('fresher_id_Number', true, true, true),
                    new StringField('First_Name', true),
                    new StringField('Course', true),
                    new StringField('Category'),
                    new StringField('Email'),
                    new IntegerField('Student_Mobile', true),
                    new IntegerField('Hostel_Id'),
                    new IntegerField('Room_No'),
                    new StringField('Native_Place'),
                    new StringField('Allotment_Remark')
                )
            );
            $lookupDataset->setOrderByField('First_Name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Fresher Id Number', 
                'fresher_id_Number', 
                $editor, 
                $this->dataset, 'fresher_id_Number', 'First_Name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for non_fresher_id_Number field
            //
            $editor = new ComboBox('non_fresher_id_number_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`non_fresher`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('non_fresher_id_Number', true, true, true),
                    new StringField('first_name', true),
                    new StringField('course', true),
                    new IntegerField('semester', true),
                    new IntegerField('Hostel_Id'),
                    new IntegerField('Room_No'),
                    new IntegerField('Pointer', true)
                )
            );
            $lookupDataset->setOrderByField('first_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Non Fresher Id Number', 
                'non_fresher_id_Number', 
                $editor, 
                $this->dataset, 'non_fresher_id_Number', 'first_name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for student_name field
            //
            $editor = new TextEdit('student_name_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Student Name', 'student_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Mess_name field
            //
            $editor = new TextEdit('mess_name_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Mess Name', 'Mess_name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Opening_Balance_Per_Sem field
            //
            $editor = new TextEdit('opening_balance_per_sem_edit');
            $editColumn = new CustomEditColumn('Opening Balance Per Sem', 'Opening_Balance_Per_Sem', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Closing_Balance_Per_Sem field
            //
            $editor = new TextEdit('closing_balance_per_sem_edit');
            $editColumn = new CustomEditColumn('Closing Balance Per Sem', 'Closing_Balance_Per_Sem', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for JMC_Charges field
            //
            $editor = new TextEdit('jmc_charges_edit');
            $editColumn = new CustomEditColumn('JMC Charges', 'JMC_Charges', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Deposit field
            //
            $editor = new TextEdit('deposit_edit');
            $editColumn = new CustomEditColumn('Deposit', 'Deposit', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Hostel_Id field
            //
            $editor = new ComboBox('hostel_id_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`hostel`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('Hostel_Id', true, true),
                    new StringField('Hostel_Name'),
                    new IntegerField('No_Of_Rooms'),
                    new StringField('Hostel_Block')
                )
            );
            $lookupDataset->setOrderByField('Hostel_Name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Hostel Id', 
                'Hostel_Id', 
                $editor, 
                $this->dataset, 'Hostel_Id', 'Hostel_Name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Meals field
            //
            $editor = new TextEdit('meals_edit');
            $editColumn = new CustomEditColumn('Meals', 'Meals', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Guests field
            //
            $editor = new TextEdit('guests_edit');
            $editColumn = new CustomEditColumn('Guests', 'Guests', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Fine field
            //
            $editor = new TextEdit('fine_edit');
            $editColumn = new CustomEditColumn('Fine', 'Fine', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Non_Veg field
            //
            $editor = new TextEdit('non_veg_edit');
            $editColumn = new CustomEditColumn('Non Veg', 'Non_Veg', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Total field
            //
            $editor = new TextEdit('total_edit');
            $editColumn = new CustomEditColumn('Total', 'Total', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        private function AddMultiUploadColumn(Grid $grid)
        {
    
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for mess_ID_Number field
            //
            $column = new NumberViewColumn('mess_ID_Number', 'mess_ID_Number', 'Mess ID Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for First_Name field
            //
            $column = new TextViewColumn('fresher_id_Number', 'fresher_id_Number_First_Name', 'Fresher Id Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for first_name field
            //
            $column = new TextViewColumn('non_fresher_id_Number', 'non_fresher_id_Number_first_name', 'Non Fresher Id Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for student_name field
            //
            $column = new TextViewColumn('student_name', 'student_name', 'Student Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for Mess_name field
            //
            $column = new TextViewColumn('Mess_name', 'Mess_name', 'Mess Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for Opening_Balance_Per_Sem field
            //
            $column = new NumberViewColumn('Opening_Balance_Per_Sem', 'Opening_Balance_Per_Sem', 'Opening Balance Per Sem', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Closing_Balance_Per_Sem field
            //
            $column = new NumberViewColumn('Closing_Balance_Per_Sem', 'Closing_Balance_Per_Sem', 'Closing Balance Per Sem', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for JMC_Charges field
            //
            $column = new NumberViewColumn('JMC_Charges', 'JMC_Charges', 'JMC Charges', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Deposit field
            //
            $column = new NumberViewColumn('Deposit', 'Deposit', 'Deposit', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Hostel_Name field
            //
            $column = new TextViewColumn('Hostel_Id', 'Hostel_Id_Hostel_Name', 'Hostel Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for Meals field
            //
            $column = new NumberViewColumn('Meals', 'Meals', 'Meals', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Guests field
            //
            $column = new NumberViewColumn('Guests', 'Guests', 'Guests', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Fine field
            //
            $column = new NumberViewColumn('Fine', 'Fine', 'Fine', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Non_Veg field
            //
            $column = new NumberViewColumn('Non_Veg', 'Non_Veg', 'Non Veg', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Total field
            //
            $column = new NumberViewColumn('Total', 'Total', 'Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for mess_ID_Number field
            //
            $column = new NumberViewColumn('mess_ID_Number', 'mess_ID_Number', 'Mess ID Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for First_Name field
            //
            $column = new TextViewColumn('fresher_id_Number', 'fresher_id_Number_First_Name', 'Fresher Id Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for first_name field
            //
            $column = new TextViewColumn('non_fresher_id_Number', 'non_fresher_id_Number_first_name', 'Non Fresher Id Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for student_name field
            //
            $column = new TextViewColumn('student_name', 'student_name', 'Student Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for Mess_name field
            //
            $column = new TextViewColumn('Mess_name', 'Mess_name', 'Mess Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for Opening_Balance_Per_Sem field
            //
            $column = new NumberViewColumn('Opening_Balance_Per_Sem', 'Opening_Balance_Per_Sem', 'Opening Balance Per Sem', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for Closing_Balance_Per_Sem field
            //
            $column = new NumberViewColumn('Closing_Balance_Per_Sem', 'Closing_Balance_Per_Sem', 'Closing Balance Per Sem', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for JMC_Charges field
            //
            $column = new NumberViewColumn('JMC_Charges', 'JMC_Charges', 'JMC Charges', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for Deposit field
            //
            $column = new NumberViewColumn('Deposit', 'Deposit', 'Deposit', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for Hostel_Name field
            //
            $column = new TextViewColumn('Hostel_Id', 'Hostel_Id_Hostel_Name', 'Hostel Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for Meals field
            //
            $column = new NumberViewColumn('Meals', 'Meals', 'Meals', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for Guests field
            //
            $column = new NumberViewColumn('Guests', 'Guests', 'Guests', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for Fine field
            //
            $column = new NumberViewColumn('Fine', 'Fine', 'Fine', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for Non_Veg field
            //
            $column = new NumberViewColumn('Non_Veg', 'Non_Veg', 'Non Veg', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for Total field
            //
            $column = new NumberViewColumn('Total', 'Total', 'Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for First_Name field
            //
            $column = new TextViewColumn('fresher_id_Number', 'fresher_id_Number_First_Name', 'Fresher Id Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for first_name field
            //
            $column = new TextViewColumn('non_fresher_id_Number', 'non_fresher_id_Number_first_name', 'Non Fresher Id Number', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for student_name field
            //
            $column = new TextViewColumn('student_name', 'student_name', 'Student Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for Mess_name field
            //
            $column = new TextViewColumn('Mess_name', 'Mess_name', 'Mess Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for Opening_Balance_Per_Sem field
            //
            $column = new NumberViewColumn('Opening_Balance_Per_Sem', 'Opening_Balance_Per_Sem', 'Opening Balance Per Sem', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Closing_Balance_Per_Sem field
            //
            $column = new NumberViewColumn('Closing_Balance_Per_Sem', 'Closing_Balance_Per_Sem', 'Closing Balance Per Sem', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for JMC_Charges field
            //
            $column = new NumberViewColumn('JMC_Charges', 'JMC_Charges', 'JMC Charges', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Deposit field
            //
            $column = new NumberViewColumn('Deposit', 'Deposit', 'Deposit', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Hostel_Name field
            //
            $column = new TextViewColumn('Hostel_Id', 'Hostel_Id_Hostel_Name', 'Hostel Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for Meals field
            //
            $column = new NumberViewColumn('Meals', 'Meals', 'Meals', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Guests field
            //
            $column = new NumberViewColumn('Guests', 'Guests', 'Guests', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Fine field
            //
            $column = new NumberViewColumn('Fine', 'Fine', 'Fine', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Non_Veg field
            //
            $column = new NumberViewColumn('Non_Veg', 'Non_Veg', 'Non Veg', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Total field
            //
            $column = new NumberViewColumn('Total', 'Total', 'Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
        }
    
        private function AddCompareHeaderColumns(Grid $grid)
        {
    
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        public function isFilterConditionRequired()
        {
            return false;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        protected function GetEnableModalGridDelete() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(false);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->setAllowSortingByDialog(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(false);
            $result->setAllowAddMultipleRecords(false);
            $result->setMultiEditAllowed($this->GetSecurityInfo()->HasEditGrant() && false);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddMultiEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
            $this->AddMultiUploadColumn($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(false);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(false);
            $this->setAllowPrintSelectedRecords(false);
            $this->setExportListAvailable(array());
            $this->setExportSelectedRecordsAvailable(array());
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array());
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            
            
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomDefaultValues(&$values, &$handled) 
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeUpdateRecord($page, $oldRowData, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $oldRowData, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doCustomHTMLHeader($page, &$customHtmlHeaderText)
        { 
    
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
        {
    
        }
    
        protected function doFileUpload($fieldName, $rowData, &$result, &$accept, $originalFileName, $originalFileExtension, $fileSize, $tempFileName)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
        protected function doPrepareColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function doPrepareFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
    
        }
    
        protected function doGetSelectionFilters(FixedKeysArray $columns, &$result)
        {
    
        }
    
        protected function doGetCustomFormLayout($mode, FixedKeysArray $columns, FormLayout $layout)
        {
    
        }
    
        protected function doGetCustomColumnGroup(FixedKeysArray $columns, ViewColumnGroup $columnGroup)
        {
    
        }
    
        protected function doPageLoaded()
        {
    
        }
    
        protected function doCalculateFields($rowData, $fieldName, &$value)
        {
    
        }
    
        protected function doGetCustomPagePermissions(Page $page, PermissionSet &$permissions, &$handled)
        {
    
        }
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
    }

    SetUpUserAuthorization();

    try
    {
        $Page = new messPage("mess", "mess.php", GetCurrentUserPermissionSetForDataSource("mess"), 'UTF-8');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("mess"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
