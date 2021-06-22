<?php

namespace App\Http\Controllers\Kintone;

class NonPublicApiController extends KintoneApiController
{
    public function updateSystemSetting(array $setting)
    {
        $updateSystemSetting = $this->kintoneNonpublicApi->updateSystemSetting($setting);

        return $updateSystemSetting;
    }

    public function postSpaceTemplate($item)
    {
        $postSpaceTemplate = $this->kintoneNonpublicApi->postSpaceTemplate($item);

        return $postSpaceTemplate;
    }

    public function addFromTemplate($item)
    {
        $postSpaceTemplate = $this->kintoneNonpublicApi->postSpaceTemplate($item);

        return $postSpaceTemplate;
    }

    public function updateCustomizeSetting($jsScope, $jsFiles)
    {
        $updateCustomizeSetting = $this->kintoneNonpublicApi->updateCustomizeSetting($jsScope, $jsFiles);

        return $updateCustomizeSetting;
    }

    public function getAppList($excludeUnrelatedApps, $includeCreatorInfo, $size, $offset, $includeGuestInfo)
    {
        $getAppList = $this->kintoneNonpublicApi->getAppList($excludeUnrelatedApps, $includeCreatorInfo, $size, $offset, $includeGuestInfo);

        return $getAppList;
    }

    public function installFromTemplateFile($thread, $fileKey)
    {
        $installFromTemplateFile = $this->kintoneNonpublicApi->installFromTemplateFile($thread, $fileKey);

        return $installFromTemplateFile;
    }

    public function exportSpaceTemplate($id)
    {
        $exportSpaceTemplate = $this->kintoneNonpublicApi->exportSpaceTemplate($id);
        return $exportSpaceTemplate;
    }
    
    public function exportSpaceTemplates($ids)
    {
        $exportSpaceTemplates = $this->kintoneNonpublicApi->exportSpaceTemplates($ids);
        return $exportSpaceTemplates;
    }

    public function exportAppTemplates($items)
    {
        $exportAppTemplates = $this->kintoneNonpublicApi->exportAppTemplates($items);
        return $exportAppTemplates;
    }
    
    public function getSpaceTemplateList()
    {
        $getSpaceTemplateList = $this->kintoneNonpublicApi->getSpaceTemplateList();
        return $getSpaceTemplateList;
    }
    
    public function getAppTemplateList()
    {
        $getAppTemplateList = $this->kintoneNonpublicApi->getAppTemplateList();
        return $getAppTemplateList;
    }
    
}
