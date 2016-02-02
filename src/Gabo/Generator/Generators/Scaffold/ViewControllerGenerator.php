<?php

namespace Gabo\Generator\Generators\Scaffold;

use Config;
use Gabo\Generator\CommandData;
use Illuminate\Support\Str;
use Gabo\Generator\Generators\GeneratorProvider;
use Gabo\Generator\Utils\GeneratorUtils;

class ViewControllerGenerator implements GeneratorProvider
{
    /** @var  CommandData */
    private $commandData;

    /** @var string */
    private $path;

    public function __construct($commandData)
    {
        $this->commandData = $commandData;
        $this->path = Config::get('generator.path_controller', app_path('Http/Controllers/'));
    }

    public function generate()
    {
        $templateData = $this->commandData->templatesHelper->getTemplate('Controller', 'scaffold');

        $templateData = GeneratorUtils::fillTemplate($this->commandData->dynamicVars, $templateData);

        if ($this->commandData->paginate) {
            $templateData = str_replace('$RENDER_TYPE$', 'paginate('.$this->commandData->paginate.')', $templateData);
        } else {
            $templateData = str_replace('$RENDER_TYPE$', 'all()', $templateData);
        }

        //$SelectOptions="";
        //$SelectSource="";
        $DataFields="";
        //
        foreach ($this->commandData->inputFields as $field) {
        //    if (!is_null($field['typeOptions']))
        //    {
        //        $SelectSource .= "$" . Str::title(str_replace('_', ' ', $field['typeOptions'])) . " = ".Config::get('generator.path_model', app_path('Models/')) . Str::title(str_replace('_', ' ', $field['typeOptions'])) . "::lists('description','id');\n\t";
        //        $SelectOptions .= "->with('" . Str::title(str_replace('_', ' ', $field['typeOptions'])) . "', $" . Str::title(str_replace('_', ' ', $field['typeOptions'])) . ")\n\t";
        //    }
            $DataFields .= "'". $this->commandData->modelNameCamel .".".Str::title(str_replace('_', ' ', $field['fieldName']))."',";
        }
        //
        //$SelectOptions = trim($SelectOptions);
        //$SelectSource = trim($SelectSource);
        $DataFields = trim($DataFields);
        //
        $templateData = str_replace('$DATA_FIELDS$', $DataFields, $templateData);
        //
        $templateData = str_replace('$SELECTS_INPUTS$', "", $templateData);
        //
        $templateData = str_replace('$SELECTS_SOURCE$', "", $templateData);
        //


        $fileName = $this->commandData->modelName.'Controller.php';

        $path = $this->path.$fileName;

        $this->commandData->fileHelper->writeFile($path, $templateData);
        $this->commandData->commandObj->comment("\nController created: ");
        $this->commandData->commandObj->info($fileName);
    }
}
