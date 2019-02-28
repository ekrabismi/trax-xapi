<?php

namespace Trax\XapiDesign\Templates;

trait ClassicStatement
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
            ->activity()
                ->id($this->activityId())
                ->name($this->activityName())
                ->description($this->activityDescription())
                ->moreInfo($this->activityMoreInfo())
                ->type($this->activityType())
                ->extensions($this->activityExtensions())
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
                ->revision($this->revision())
                ->language($this->language())
                ->platform($this->platformName())
            ->timestamp($this->timestamp());
    }


}
