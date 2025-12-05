<?php

$GLOBALS['TL_DCA']['tl_module']['palettes']['logo'] = '
    {title_legend},name,type;
	{config_legend},singleSRC,imgSize,ravenUrl;
    {template_legend:hide},customTpl;
    {expert_legend:hide},cssID
';

$GLOBALS['TL_DCA']['tl_module']['palettes']['filtered_root_page_dependend_modules'] = '
    {title_legend},name,type;
	{config_legend},rootPageDependentModules,pages,ravenInherit,ravenInvert;
	{protected_legend:hide},protected
';


		
$GLOBALS['TL_DCA']['tl_module']['fields']['ravenInherit'] = array
(
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w25'),
	'sql'                     => array('type' => 'boolean', 'default' => false),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['ravenInvert'] = array
(
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w25'),
	'sql'                     => array('type' => 'boolean', 'default' => false),
);
		
$GLOBALS['TL_DCA']['tl_module']['fields']['ravenUrl'] = array
(
	'inputType' => 'text',
	'eval' => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048, 'dcaPicker'=>true, 'tl_class'=>'w50'),
	'sql' => "text NULL"
); 
