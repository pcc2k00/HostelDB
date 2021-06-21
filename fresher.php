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
    
    
    
    class fresherPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->SetTitle('Fresher');
            $this->SetMenuLabel('Fresher');
            $this->SetHeader(GetPagesHeader());
            $this->SetFooter(GetPagesFooter());
    
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`fresher`');
            $this->dataset->addFields(
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
            $this->dataset->AddLookupField('Hostel_Id', 'hostel', new IntegerField('Hostel_Id'), new StringField('Hostel_Name', false, false, false, false, 'Hostel_Id_Hostel_Name', 'Hostel_Id_Hostel_Name_hostel'), 'Hostel_Id_Hostel_Name_hostel');
            $this->dataset->AddLookupField('Room_No', 'room', new IntegerField('Room_No'), new IntegerField('Hostel_Id', false, false, false, false, 'Room_No_Hostel_Id', 'Room_No_Hostel_Id_room'), 'Room_No_Hostel_Id_room');
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
                new FilterColumn($this->dataset, 'fresher_id_Number', 'fresher_id_Number', 'Fresher Id Number'),
                new FilterColumn($this->dataset, 'First_Name', 'First_Name', 'First Name'),
                new FilterColumn($this->dataset, 'Course', 'Course', 'Course'),
                new FilterColumn($this->dataset, 'Category', 'Category', 'Category'),
                new FilterColumn($this->dataset, 'Email', 'Email', 'Email'),
                new FilterColumn($this->dataset, 'Student_Mobile', 'Student_Mobile', 'Student Mobile'),
                new FilterColumn($this->dataset, 'Hostel_Id', 'Hostel_Id_Hostel_Name', 'Hostel Id'),
                new FilterColumn($this->dataset, 'Room_No', 'Room_No_Hostel_Id', 'Room No'),
                new FilterColumn($this->dataset, 'Native_Place', 'Native_Place', 'Native Place'),
                new FilterColumn($this->dataset, 'Allotment_Remark', 'Allotment_Remark', 'Allotment Remark')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['fresher_id_Number'])
                ->addColumn($columns['First_Name'])
                ->addColumn($columns['Course'])
                ->addColumn($columns['Category'])
                ->addColumn($columns['Email'])
                ->addColumn($columns['Student_Mobile'])
                ->addColumn($columns['Hostel_Id'])
                ->addColumn($columns['Room_No'])
                ->addColumn($columns['Native_Place'])
                ->addColumn($columns['Allotment_Remark']);
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
            // View column for fresher_id_Number field
            //
            $column = new NumberViewColumn('fresher_id_Number', 'fresher_id_Number', 'Fresher Id Number', $this->dataset);
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
            $column = new TextViewColumn('First_Name', 'First_Name', 'First Name', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Course field
            //
            $column = new TextViewColumn('Course', 'Course', 'Course', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Category field
            //
            $column = new TextViewColumn('Category', 'Category', 'Category', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Email field
            //
            $column = new TextViewColumn('Email', 'Email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Student_Mobile field
            //
            $column = new NumberViewColumn('Student_Mobile', 'Student_Mobile', 'Student Mobile', $this->dataset);
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
            // View column for Hostel_Id field
            //
            $column = new NumberViewColumn('Room_No', 'Room_No_Hostel_Id', 'Room No', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Native_Place field
            //
            $column = new TextViewColumn('Native_Place', 'Native_Place', 'Native Place', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('fresher_Native_Place_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for Allotment_Remark field
            //
            $column = new TextViewColumn('Allotment_Remark', 'Allotment_Remark', 'Allotment Remark', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for fresher_id_Number field
            //
            $column = new NumberViewColumn('fresher_id_Number', 'fresher_id_Number', 'Fresher Id Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for First_Name field
            //
            $column = new TextViewColumn('First_Name', 'First_Name', 'First Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Course field
            //
            $column = new TextViewColumn('Course', 'Course', 'Course', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Category field
            //
            $column = new TextViewColumn('Category', 'Category', 'Category', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Email field
            //
            $column = new TextViewColumn('Email', 'Email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Student_Mobile field
            //
            $column = new NumberViewColumn('Student_Mobile', 'Student_Mobile', 'Student Mobile', $this->dataset);
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
            // View column for Hostel_Id field
            //
            $column = new NumberViewColumn('Room_No', 'Room_No_Hostel_Id', 'Room No', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Native_Place field
            //
            $column = new TextViewColumn('Native_Place', 'Native_Place', 'Native Place', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('fresher_Native_Place_handler_view');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for Allotment_Remark field
            //
            $column = new TextViewColumn('Allotment_Remark', 'Allotment_Remark', 'Allotment Remark', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for First_Name field
            //
            $editor = new TextEdit('first_name_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('First Name', 'First_Name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Course field
            //
            $editor = new TextEdit('course_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Course', 'Course', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Category field
            //
            $editor = new TextEdit('category_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Category', 'Category', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Email field
            //
            $editor = new TextEdit('email_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Email', 'Email', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Student_Mobile field
            //
            $editor = new TextEdit('student_mobile_edit');
            $editColumn = new CustomEditColumn('Student Mobile', 'Student_Mobile', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
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
            // Edit column for Room_No field
            //
            $editor = new ComboBox('room_no_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`room`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('Room_No', true, true),
                    new IntegerField('Hostel_Id'),
                    new StringField('students_name'),
                    new StringField('hostel_Block', true),
                    new IntegerField('capacity', true),
                    new StringField('Allotment_Status')
                )
            );
            $lookupDataset->setOrderByField('Hostel_Id', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Room No', 
                'Room_No', 
                $editor, 
                $this->dataset, 'Room_No', 'Hostel_Id', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Native_Place field
            //
            $editor = new TextAreaEdit('native_place_edit', 50, 8);
            $editColumn = new CustomEditColumn('Native Place', 'Native_Place', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for Allotment_Remark field
            //
            $editor = new TextEdit('allotment_remark_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Allotment Remark', 'Allotment_Remark', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddMultiEditColumns(Grid $grid)
        {
            //
            // Edit column for First_Name field
            //
            $editor = new TextEdit('first_name_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('First Name', 'First_Name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Course field
            //
            $editor = new TextEdit('course_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Course', 'Course', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Category field
            //
            $editor = new TextEdit('category_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Category', 'Category', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Email field
            //
            $editor = new TextEdit('email_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Email', 'Email', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Student_Mobile field
            //
            $editor = new TextEdit('student_mobile_edit');
            $editColumn = new CustomEditColumn('Student Mobile', 'Student_Mobile', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
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
            // Edit column for Room_No field
            //
            $editor = new ComboBox('room_no_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`room`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('Room_No', true, true),
                    new IntegerField('Hostel_Id'),
                    new StringField('students_name'),
                    new StringField('hostel_Block', true),
                    new IntegerField('capacity', true),
                    new StringField('Allotment_Status')
                )
            );
            $lookupDataset->setOrderByField('Hostel_Id', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Room No', 
                'Room_No', 
                $editor, 
                $this->dataset, 'Room_No', 'Hostel_Id', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Native_Place field
            //
            $editor = new TextAreaEdit('native_place_edit', 50, 8);
            $editColumn = new CustomEditColumn('Native Place', 'Native_Place', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for Allotment_Remark field
            //
            $editor = new TextEdit('allotment_remark_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Allotment Remark', 'Allotment_Remark', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for First_Name field
            //
            $editor = new TextEdit('first_name_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('First Name', 'First_Name', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Course field
            //
            $editor = new TextEdit('course_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Course', 'Course', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Category field
            //
            $editor = new TextEdit('category_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Category', 'Category', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Email field
            //
            $editor = new TextEdit('email_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Email', 'Email', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Student_Mobile field
            //
            $editor = new TextEdit('student_mobile_edit');
            $editColumn = new CustomEditColumn('Student Mobile', 'Student_Mobile', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
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
            // Edit column for Room_No field
            //
            $editor = new ComboBox('room_no_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`room`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('Room_No', true, true),
                    new IntegerField('Hostel_Id'),
                    new StringField('students_name'),
                    new StringField('hostel_Block', true),
                    new IntegerField('capacity', true),
                    new StringField('Allotment_Status')
                )
            );
            $lookupDataset->setOrderByField('Hostel_Id', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Room No', 
                'Room_No', 
                $editor, 
                $this->dataset, 'Room_No', 'Hostel_Id', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Native_Place field
            //
            $editor = new TextAreaEdit('native_place_edit', 50, 8);
            $editColumn = new CustomEditColumn('Native Place', 'Native_Place', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for Allotment_Remark field
            //
            $editor = new TextEdit('allotment_remark_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Allotment Remark', 'Allotment_Remark', $editor, $this->dataset);
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
            // View column for fresher_id_Number field
            //
            $column = new NumberViewColumn('fresher_id_Number', 'fresher_id_Number', 'Fresher Id Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for First_Name field
            //
            $column = new TextViewColumn('First_Name', 'First_Name', 'First Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for Course field
            //
            $column = new TextViewColumn('Course', 'Course', 'Course', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for Category field
            //
            $column = new TextViewColumn('Category', 'Category', 'Category', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for Email field
            //
            $column = new TextViewColumn('Email', 'Email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for Student_Mobile field
            //
            $column = new NumberViewColumn('Student_Mobile', 'Student_Mobile', 'Student Mobile', $this->dataset);
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
            // View column for Hostel_Id field
            //
            $column = new NumberViewColumn('Room_No', 'Room_No_Hostel_Id', 'Room No', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Native_Place field
            //
            $column = new TextViewColumn('Native_Place', 'Native_Place', 'Native Place', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('fresher_Native_Place_handler_print');
            $grid->AddPrintColumn($column);
            
            //
            // View column for Allotment_Remark field
            //
            $column = new TextViewColumn('Allotment_Remark', 'Allotment_Remark', 'Allotment Remark', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for fresher_id_Number field
            //
            $column = new NumberViewColumn('fresher_id_Number', 'fresher_id_Number', 'Fresher Id Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for First_Name field
            //
            $column = new TextViewColumn('First_Name', 'First_Name', 'First Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for Course field
            //
            $column = new TextViewColumn('Course', 'Course', 'Course', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for Category field
            //
            $column = new TextViewColumn('Category', 'Category', 'Category', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for Email field
            //
            $column = new TextViewColumn('Email', 'Email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for Student_Mobile field
            //
            $column = new NumberViewColumn('Student_Mobile', 'Student_Mobile', 'Student Mobile', $this->dataset);
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
            // View column for Hostel_Id field
            //
            $column = new NumberViewColumn('Room_No', 'Room_No_Hostel_Id', 'Room No', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for Native_Place field
            //
            $column = new TextViewColumn('Native_Place', 'Native_Place', 'Native Place', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('fresher_Native_Place_handler_export');
            $grid->AddExportColumn($column);
            
            //
            // View column for Allotment_Remark field
            //
            $column = new TextViewColumn('Allotment_Remark', 'Allotment_Remark', 'Allotment Remark', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for First_Name field
            //
            $column = new TextViewColumn('First_Name', 'First_Name', 'First Name', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for Course field
            //
            $column = new TextViewColumn('Course', 'Course', 'Course', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for Category field
            //
            $column = new TextViewColumn('Category', 'Category', 'Category', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for Email field
            //
            $column = new TextViewColumn('Email', 'Email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for Student_Mobile field
            //
            $column = new NumberViewColumn('Student_Mobile', 'Student_Mobile', 'Student Mobile', $this->dataset);
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
            // View column for Hostel_Id field
            //
            $column = new NumberViewColumn('Room_No', 'Room_No_Hostel_Id', 'Room No', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Native_Place field
            //
            $column = new TextViewColumn('Native_Place', 'Native_Place', 'Native Place', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('fresher_Native_Place_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for Allotment_Remark field
            //
            $column = new TextViewColumn('Allotment_Remark', 'Allotment_Remark', 'Allotment Remark', $this->dataset);
            $column->SetOrderable(true);
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
            //
            // View column for Native_Place field
            //
            $column = new TextViewColumn('Native_Place', 'Native_Place', 'Native Place', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'fresher_Native_Place_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for Native_Place field
            //
            $column = new TextViewColumn('Native_Place', 'Native_Place', 'Native Place', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'fresher_Native_Place_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for Native_Place field
            //
            $column = new TextViewColumn('Native_Place', 'Native_Place', 'Native Place', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'fresher_Native_Place_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for Native_Place field
            //
            $column = new TextViewColumn('Native_Place', 'Native_Place', 'Native Place', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'fresher_Native_Place_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
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
        $Page = new fresherPage("fresher", "fresher.php", GetCurrentUserPermissionSetForDataSource("fresher"), 'UTF-8');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("fresher"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
