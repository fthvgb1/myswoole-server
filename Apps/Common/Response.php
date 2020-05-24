<?php


namespace Apps\Common;


class Response extends \Symfony\Component\HttpFoundation\Response implements interfaces\Response
{

    public function response($response = null)
    {
        return $this->setContent($response)->send();
    }
}