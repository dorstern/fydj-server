<?php

class Company{

    public $job_page_url;
    public $job_dom_wrapper;
    public $job_dom_title;
    public $job_dom_location;
    public $job_dom_description;

    function __construct($job_page_url, $job_dom_wrapper, $job_dom_title, $job_dom_location, $job_dom_description)
    {
        $this->job_page_url = $job_page_url;
        $this->job_dom_wrapper = $job_dom_wrapper;
        $this->job_dom_title = $job_dom_title;
        $this->job_dom_location = $job_dom_location;
        $this->job_dom_description = $job_dom_description;
    }

}

?>