<?php


namespace Nichozuo\LaravelUtils\Traits;


trait ControllerTrait
{
    protected function perPage()
    {
        $params = request()->only('perPage');
        if (isset($params['perPage']) && is_numeric($params['perPage'])) {
            return $params['perPage'];
        }
        return 20;
    }

    protected function getMines(): string
    {
        $mime_image = 'gif,jpeg,png,ico,svg';
        $mine_docs = 'xls,xlsx,doc,docx,ppt,pptx,pdf';
        $mine_zip = '7z,zip,rar';
        return $mime_image . ',' . $mine_docs . ',' . $mine_zip;
    }
}
