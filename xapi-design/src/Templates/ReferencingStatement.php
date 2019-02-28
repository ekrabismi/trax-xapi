<?php

namespace Trax\XapiDesign\Templates;

trait ReferencingStatement
{

    /**
     * Build the Statement.
     */
    public function statement()
    {
        return $this->builder
            ->agent()
                ->account($this->accountHomepage(), $this->accountName())
            ->verb()
                ->id($this->verbId())
            ->statementRef($this->statementRef())
            ->result()
                ->completion($this->completion())
                ->success($this->success())
                ->score($this->score())
                ->duration($this->duration())
                ->response($this->response())
                ->extensions($this->resultExtensions())
            ->context()
                ->contextActivities($this->contextActivities())
                ->extensions($this->contextExtensions())
                ->registration($this->registration())
                ->instructor($this->instructor())
                ->team($this->team())
                ->language($this->language())
                //->revision($this->revision())         // Not allowed with StatementRef
                //->platform($this->platformName())     // Not allowed with StatementRef
            ->timestamp($this->timestamp());
    }


}
