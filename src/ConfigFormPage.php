<?php
namespace PrestaShop\Module\Ec_Xmlimport;

use AdminController;

use Configuration;
use HelperForm;
use Tools;
use Validate;


class ConfigFormPage{
private $module;
private $fieldsForm;
private $fields_value;

public function __construct($module)
{
    $this->module=$module;


    // Init Fields form array
    $this->fieldsForm[0]['form'] = [
        'legend' => [
            'title' => $this->module->l('Settings'),
        ],
        'input' => [
            [
                'type' => 'text',
                'label' => $this->module->l('Action API key'),
                'name' => 'actionApiKey',
                'size' => 50,
                'required' => true
            ],
            [
                'type' => 'text',
                'label' => $this->module->l('Action user name'),
                'name' => 'actionUserName',
                'size' => 20,
                'required' => true
            ],
            [
                'type' => 'text',
                'label' => $this->module->l('Action Password'),
                'name' => 'actionPassword',
                'size' => 20,
                'required' => true
            ],
            [
                'type' => 'text',
                'label' => $this->module->l('Action Customer id'),
                'name' => 'actionCustomerId',
                'size' => 20,
                'required' => true
            ],
            [
            'type' => 'text',
            'label' => $this->module->l('Action image secret key'),
            'name' => 'actionImageKey',
            'size' => 50,
            'required' => true
        ]
        ],
        'submit' => [
            'title' => $this->module->l('Save'),
            'class' => 'btn btn-default pull-right'
        ]
    ];
}

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit'.$this->module->name)) {

            $moduleConfigValues = $this->getDefinedValues();


            foreach ($moduleConfigValues as $value) {
                $myModuleName = strval(Tools::getValue($value['name']  ));
                if (!$myModuleName || empty($myModuleName)
                ) {
                    $output .= $this->module->displayError($this->module->l('Invalid Configuration value:'. $value['label']));
                    $this->fields_value[$value['name']]=$myModuleName;
                } else {
                    Configuration::updateValue($value['name'], $myModuleName);
                    $this->fields_value[$value['name']]=$myModuleName;
                    $output .= $this->module->displayConfirmation($this->module->l('Settings updated'));
                }
                                                         }
        }
        $moduleConfigValues = $this->getDefinedValues();
        foreach ($moduleConfigValues as $value) {

            $this->fields_value[$value['name']]= Configuration::get($value['name']);
        }

        return $output.$this->displayForm();
    }


    public function displayForm()
    {
        // Get default language
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');



        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this->module;
        $helper->name_controller = $this->module->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->module->name;

        // Language
        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;

        // Title and toolbar
        $helper->title = $this->module->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->module->name;
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->module->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->module->name.'&save'.$this->module->name.
                    '&token='.Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->module->l('Back to list')
            ]
        ];

        // Load current value
        $helper->fields_value=$this->fields_value;

        return $helper->generateForm($this->fieldsForm);
    }


    private function getDefinedValues(){

return $this->fieldsForm[0]['form']['input'];

}

}