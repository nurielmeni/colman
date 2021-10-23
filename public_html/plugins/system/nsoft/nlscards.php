<?php

defined('_JEXEC') or die;

require_once 'nlssecurity.php';

class nlsCards {

    private $url;
    private $client;
    private $siteId;
    private $soap_headers;
    public $user_id;
    private $session;

    public function __construct(/* string */$username_token, /* string */ $password_token, $connectToService = true) {
        $this->session = &JFactory::getSession();

        if ($connectToService) {
            $this->url = nsoftConfig::$nlsCardsWsdlUrl; #		Prod: "https://xcardsservice.hunterhrms.com/huntercards.svc"
            $this->siteId = nsoftConfig::$nsoftSiteId; #								Prod: "2e066b0b-8f9a-44c9-b985-7195afd9ab53"

            $this->client = new SoapClient($this->url, array(
                'trace' => nsoftConfig::$trace,
                'exceptions' => nsoftConfig::$exceptions,
                'cache_wsdl' => nsoftConfig::$cache_wsdl
            ));
            $this->soap_headers = array(new SoapHeader('_', 'NiloosoftCred1', $username_token),
                new SoapHeader('_', 'NiloosoftCred2', $password_token));

            $this->client->__setSoapHeaders($this->soap_headers);

            $session = JFactory::getSession();
            $this->user_id = $session->get("applicant_user_id"); //"451D2E28-BB7F-4076-A56E-0E6E2445E458";  // 

            $this->cache = JFactory::getCache('nlscards');
            $this->cache->setCaching(1);
        }
    }

    public function Test() {
        try {
            return $this->client->isServiceReachable()->isServiceReachableResult;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getApplyOptionsAction() {

//  $employment_types = $this->getEmploymenTypes('a6b6cb37-071f-48f4-81ca-c3c3f44323ea');
// var_dump($employment_types);
        /* $listData['jobArea'] = [["name" => $employment_types->Value,
          "id" => $employment_types->Value]];
         */


        $file_list = $this->getFileList($this->user_id, 0, 1000);
        if (property_exists($file_list->FilesListGetResult, "FileInfo")) {
            if (is_array($file_list->FilesListGetResult->FileInfo)) {
                $listData['fileList'] = $file_list->FilesListGetResult->FileInfo;
            } else {
                $listData['fileList'] = [$file_list->FilesListGetResult->FileInfo];
            }
        } else {
            $listData['fileList'] = [];
        }


        $listData['cvList'] = $this->getCVList($this->user_id)['files'];

        echo json_encode($listData);
        die;
    }

# Supported lists: "YearsOfExperience" "Regions" "JobEnploymentType" "JobSeniority" "ProfessionalExpertises"

    public function jobSiteGetRelevantLists(/* string */
    $listName, $suplier_id) {
        $transactionCode = guid::newGuid();
        try {
            $params = array('supplierId' => $this->siteId,
                'transactionCode' => $transactionCode,
                "SupplierId" => $suplier_id);
            $res = $this->client->JobSiteGetRelevantLists($params);
            $jobsLists = $res->JobSiteGetRelevantListsResult->PublishedJobsListsInfo;

            for ($i = 0; $i < count($jobsLists); $i++) {
                if ($jobsLists[$i]->JobListName === $listName) {
                    return $jobsLists[$i]->JobList;
                }
            }
            return null;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function hunterFiterBuilder(
    /* int */$catObj,
    /* string */ $keyword,
    /* int */ $expertise,
    /* int */ $employment_type,
    /* Guid */ $customerId,
    /* int */ $lastId,
    /* int */ $countPerPage, $suplier_id, $jobscop = "", $jobLocation = "", $date_period = "", $sendToAgent = false) {
//

        $filter = new stdClass;
        $filter->GeoSortDescriptor = null;
        $filter->FromView = "Jobs";
        $filter->LanguageId = nsoftConfig::$language_code;
        $filter->SelectFilterFields = array(
            "JobId",
            "JobScope",
            "Description"
        );
        $numericValue = "NumericValues";
        $textualSearch = "TextualSearch";
        $TermsNonAnalyzed = "TermsNonAnalyzed";
        $datetimSearch = "DateTimeRange";
        $filter->WhereFilters = array();


        $jobfilterFileds = array();

# Supplier
//   $jobfilterFileds

        $filter->WhereFilters[] = $this->jobFilterWhere(array(
            $this->hunterJobFilterField("PublishedJobSupplier_SupplierId", "All", $suplier_id, false, $TermsNonAnalyzed, "PublishedJobSupplier")
                ), "OR");




# Category
        $subcategory = isset($catObj->sub) ? $catObj->sub : NULL;
        $categoryId = isset($catObj->cat) ? $catObj->cat : NULL;

        $isSub = $catObj->isSub;
        if (!empty($categoryId)) {

            $entity = $categoryId;
            $filterKeyword = "JobProfessionalFieldInfo_CategoryId";

            if (is_array($entity)) {

                $jobFilterArray = array();
                foreach ($entity as $value) {
                    $jobFilterArray[] = $this->hunterJobFilterField($filterKeyword, "Exact", $value, false, $numericValue, "JobProfessionalFields");
                }

                $filter->WhereFilters[] = $this->jobFilterWhere($jobFilterArray
                        , "OR");
            } else {
                $filter->WhereFilters[] = $this->jobFilterWhere(array(
                    $this->hunterJobFilterField($filterKeyword, "Exact", $entity, false, $numericValue, "JobProfessionalFields")
                        ), "OR");
            }

            if ($isSub) {
                $filterKeyword = "JobProfessionalFieldInfo_ProfessionalFieldId";
                $entity = $subcategory;

                if (is_array($entity)) {

                    $jobFilterArray = array();
                    foreach ($entity as $value) {
                        $jobFilterArray[] = $this->hunterJobFilterField($filterKeyword, "Exact", $value, false, $numericValue, "JobProfessionalFields");
                    }

                    $filter->WhereFilters[] = $this->jobFilterWhere($jobFilterArray
                            , "OR");
                } else {
                    $filter->WhereFilters[] = $this->jobFilterWhere(array(
                        $this->hunterJobFilterField($filterKeyword, "Exact", $entity, false, $numericValue, "JobProfessionalFields")
                            ), "OR");
                }
            }
        }

//# Expertise
//        if (!empty($expertise)) {
//            $entity = $expertise;
//            $filterKeyword = "ExpertiseId";
//
//            if (is_array($entity)) {
//                foreach ($entity as $value) {
//                    $filter->WhereFilters[] = $this->jobFilterWhere(array(
//                        $this->jobFilterField($filterKeyword, "Exact", $value, true)
//                            ), "OR");
//                }
//            } else {
//                $filter->WhereFilters[] = $this->jobFilterWhere(array(
//                    $this->jobFilterField($filterKeyword, "Exact", $entity, true)
//                        ), "OR");
//            }
//        }
//
//
# $employment_type
        if (!empty($employment_type)) {
            $all_option = "6";//Test "3";
            $entity = is_array($employment_type)?$employment_type:array($employment_type);
            if(!in_array($all_option, $entity)) array_push ($entity, $all_option);
            $filterKeyword = "EmploymentForm";

            
            if (is_array($entity)) {
                $jobFilterArray = array();
                foreach ($entity as $value) {
                    $jobFilterArray[] = $this->hunterJobFilterField($filterKeyword, "Exact", $value, true, $numericValue);
                }

                $filter->WhereFilters[] = $this->jobFilterWhere($jobFilterArray
                        , "OR");
            } else {
                $filter->WhereFilters[] = $this->jobFilterWhere(array(
                    $this->hunterJobFilterField($filterKeyword, "Exact", $entity, true, $numericValue)
                        ), "OR");
            }
        }


# Scope
        if (!empty($jobscop)) {
            $all_option = "3";//TEST "302";
            $entity = is_array($jobscop)?$jobscop:array($jobscop);
            if(!in_array($all_option, $entity)) array_push ($entity, $all_option);
            $filterKeyword = "JobScope";

            if (is_array($entity)) {
                $jobFilterArray = array();
                foreach ($entity as $value) {
                    $jobFilterArray[] = $this->hunterJobFilterField($filterKeyword, "Exact", $value, true, $numericValue);
                }

                $filter->WhereFilters[] = $this->jobFilterWhere($jobFilterArray
                        , "OR");
            } else {
                $filter->WhereFilters[] = $this->jobFilterWhere(array(
                    $this->hunterJobFilterField($filterKeyword, "Exact", $entity, true, $numericValue)
                        ), "OR");
            }
        }

# Keyword
        if (!empty($keyword)) {
            if (is_array($keyword)) {
                $count = count($keyword[0]);
                for ($i = 0; $i < $count; $i++) {

                    $type = $keyword[1][$i];
                    $kw = $keyword[0][$i];

                    $filter->WhereFilters[] = $this->jobFilterWhere(array(
                        $this->hunterJobFilterField("Description", $type, $kw, true, $textualSearch),
                        $this->hunterJobFilterField("Requiremets", $type, $kw, true, $textualSearch),
                        $this->hunterJobFilterField("JobTitle", $type, $kw, true, $textualSearch),
                        $this->hunterJobFilterField("Skills", $type, $kw, true, $textualSearch),
                        $this->hunterJobFilterField("JobCode", $type, $kw, true, $textualSearch),
                            ), "OR");
                }
            } else {
                $filter->WhereFilters[] = $this->jobFilterWhere(array(
                    $this->hunterJobFilterField("Description", "OneOrMore", $keyword, true, $textualSearch),
                    $this->hunterJobFilterField("Requiremets", "OneOrMore", $keyword, true, $textualSearch),
                    $this->hunterJobFilterField("JobTitle", "OneOrMore", $keyword, true, $textualSearch),
                    $this->hunterJobFilterField("Skills", "OneOrMore", $keyword, true, $textualSearch),
                    $this->hunterJobFilterField("JobCode", "Exact", $keyword, true, $textualSearch),
                        ), "OR");
            }
        }

# jobLocation
        if (!empty($jobLocation)) {
            $entity = $jobLocation;
            $searchFor = "";
            $filterKeyword = "RegionId";

            if (is_array($entity)) {
                $jobFilterArray = array();
                foreach ($entity as $value) {

                    $searchFor.=$value . ",";
                }
                $searchFor = rtrim($searchFor, ",");
            } else {
                $searchFor = $entity;
            }
            // Add all if one is selected (OZ requirment)
            $searchFor .= ",9";


            $filter->WhereFilters[] = $this->jobFilterWhere(array(
                $this->hunterJobFilterField($filterKeyword, "Exact", $searchFor, true, $numericValue)
                    ), "OR");
        }

# Company
        if (!empty($customerId)) {
            $entity = $customerId;
            $filterKeyword = "EmployerId";

            if (is_array($entity)) {
                $jobFilterArray = array();
                foreach ($entity as $value) {
                    $jobFilterArray[] = $this->hunterJobFilterField($filterKeyword, "Exact", $value, true, $TermsNonAnalyzed);
                }

                $filter->WhereFilters[] = $this->jobFilterWhere($jobFilterArray
                        , "OR");
            } else {
                $filter->WhereFilters[] = $this->jobFilterWhere(array(
                    $this->hunterJobFilterField($filterKeyword, "Exact", $entity, true, $TermsNonAnalyzed)
                        ), "OR");
            }
        }

# UpdatePeriod
        if (!empty($date_period)) {
            $filter->WhereFilters[] = $this->jobFilterWhere(array(
                $this->hunterJobFilterField("UpdateDate", "BetweenDates", $date_period, true, $datetimSearch)
                    ), "OR");
        }
//
        $filter->OrderByFilterSort = array(
            $this->jobFilterSort("UpdateDate", "Descending"),
                // $this->jobFilterSort("JobCode", "Ascending"),
        );



        return $filter;
    }

    public function totalJobsCount_hunter(
        $categoryId,
        $keyword,
        $expertise,
        $employment_type,
        $customerId,
        $lastId,
        $countPerPage, 
        $suplier_id, 
        $jobscop = "", 
        $jobLocation = "", 
        $date_period = "", 
        $sendToAgent = false
    ) {
        $user_name = "";
        $hunterStatus = "Temporary";
        $user_name = $this->session->get("user_credentials")["applicantID"][0];
        $hunterFilter = $this->hunterFiterBuilder($categoryId, $keyword, $expertise, $employment_type, $customerId, $lastId, $countPerPage, $suplier_id, $jobscop, $jobLocation, $date_period, $sendToAgent);
        $security = new nlsSecurity();
        $user_name_and_directory = nsoftConfig::$user_domain . "\\" . $user_name;
        $auth = $security->AuthenticateByConsumerKeyAndSecretKey($user_name_and_directory);
        $soap_headers = [
            new SoapHeader('_', 'NiloosoftCred1', $auth->plainToken),
            new SoapHeader('_', 'NiloosoftCred2', $auth->signedToken)
        ];
        $searchService = new SearchService($soap_headers);
        $hunter_id = guid::newGuid();
        $joblist = $searchService->JobHunterExecuteNewQuery2($hunter_id, $lastId, $countPerPage, $hunterFilter);
        return $joblist;
    }


    public function jobsSearch_hunter(
    /* int */$categoryId,
    /* string */ $keyword,
    /* int */ $expertise,
    /* int */ $employment_type,
    /* Guid */ $customerId,
    /* int */ $lastId,
    /* int */ $countPerPage, $suplier_id, $jobscop = "", $jobLocation = "", $date_period = "", $sendToAgent = false) {
//        var_dump($categoryId);
//        var_dump($expertise);
//        var_dump($keyword);
//        var_dump($customerId);
//        var_dump($lastId);
//        var_dump($countPerPage);
//        var_dump($jobscop);
//        var_dump($date_period);
//        var_dump($employment_type);
//        var_dump($jobLocation);
//        var_dump($suplier_id);
//        var_dump($sendToAgent);die;
        try {

            $user_name = "";
            $hunterStatus = "Temporary";
//  var_dump(!$this->isLogedIn() || !$sendToAgent);
            if (!$this->isLogedIn()) {

                $user_name = nsoftConfig::$hunter_user_for_non_loged_in_search;
            } else {
                if ($sendToAgent) {
                    $hunterStatus = "Automatic";
                }
                $user_name = $this->session->get("user_credentials")["applicantID"][0];
            }

            $hunterFilter = $this->hunterFiterBuilder($categoryId, $keyword, $expertise, $employment_type, $customerId, $lastId, $countPerPage, $suplier_id, $jobscop, $jobLocation, $date_period, $sendToAgent);
//    var_dump($hunterFilter);die;
            $security = new nlsSecurity();
            $user_name_and_directory = nsoftConfig::$user_domain . "\\" . $user_name;
            $auth = $security->AuthenticateByConsumerKeyAndSecretKey($user_name_and_directory);
//            var_dump($auth);
//            die;
            $soap_headers = array(new SoapHeader('_', 'NiloosoftCred1', $auth->plainToken),
                new SoapHeader('_', 'NiloosoftCred2', $auth->signedToken));
            $searchService = new SearchService($soap_headers);

// $hunter_id = $searchService->JobHunterCreateOrUpdate($user_name_and_directory, $hunterFilter, $hunterStatus);
// var_dump($hunter_id);
//  $hunter_id ="984C233F-1BA1-FA8C-276F-91D5674FEFD2";
//    $hunter_id = $searchService->JobHuntersGetForUser()->automaticHunters->HunterListItem;
//  var_dump($hunter_id);
//   if (!empty($hunter_id)) {

            $joblist = [];
            if ($sendToAgent && $this->isLogedIn()) {
                $hunter_id = $searchService->JobHunterCreateOrUpdate($user_name_and_directory, $hunterFilter, $hunterStatus);

                $joblist = $searchService->JobHunterExecuteByHunterId2($hunter_id, $lastId, $countPerPage, false)->JobHunterExecuteByHunterId2Result;
            } else {
                $hunter_id = guid::newGuid();
                $joblist = $searchService->JobHunterExecuteNewQuery2($hunter_id, $lastId, $countPerPage, $hunterFilter); //JobHunterExecuteByHunterId2($hunter_id, $lastId, $countPerPage, false)->JobHunterExecuteByHunterId2Result;
            }


//  var_dump($joblist);

            $result = [];
// var_dump($joblist->Results);
//                die;
// var_dump($joblist);
            $response = new stdClass();
            if (property_exists($joblist, "Results") && property_exists($joblist->Results, "JobInfo")) {

                $response->TotalHits = $joblist->TotalHits;
                $resultList = $joblist->Results->JobInfo;
                if (is_array($resultList)) {

                    foreach ($resultList as $job) {
                        $result[] = $this->hunterResultToJob($job);
                    }
                } elseif ($joblist->TotalHits == 1) {

                    $result[] = $this->hunterResultToJob($resultList);
                }

//                foreach ($result as $key => $val) {
//                    $date = $val["date"];
//                    $result[$key]["date"] = date("d/m/Y", strtotime($date));
//                }
//            

                if (isset($this->user_id) && !empty($this->user_id)) {
                    $filedJobs = $this->cache->call([$this, 'getFilledJobsList'], $this->user_id, 0, 1000);
                    //$filedJobs = $this->getFilledJobsList($this->user_id, 0, 1000);

                    foreach ($result as $key => $searchJob) {
                        $status = 0;
                        foreach ($filedJobs["jobs"] as $filedJob) {
                            if (intval($searchJob["jobid"]) === $filedJob["jobid"]) {
                                $status = 1;
                                break;
                            }
                        }
                        $result[$key]["status"] = $status;
                    }
                }
            }
//  }
            $response->result = $result;

            return $response;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function jobsSearch(
    /* int */$categoryId,
    /* string */ $keyword,
    /* int */ $expertise,
    /* int */ $employment_type,
    /* Guid */ $customerId,
    /* int */ $lastId,
    /* int */ $countPerPage, $suplier_id, $jobscop = "", $jobLocation = "", $date_period = "", $sendToAgent = false) {
//        var_dump($categoryId);
//        var_dump($expertise);
//        var_dump($keyword);
//        var_dump($customerId);
//        var_dump($lastId);
//        var_dump($countPerPage);
//        var_dump($jobscop);
//        var_dump($date_period);
//        var_dump($employment_type);
//        var_dump($jobLocation);
//        var_dump($suplier_id);
//        var_dump($sendToAgent);die;
        try {



            $filter = new stdClass();
            $filter->FromView = "Jobs";
            $filter->SelectFilterFields = array(
//  "CategoryId",
                "JobId",
                "JobTitle",
                "JobCode",
                "RegionText",
                "UpdateDate",
                "ExpertiseId",
                "EmploymentType",
                "EmployerId",
                "EmployerName",
                "JobScope",
                "Rank",
                "Description"
            );
            $filter->NumberOfRows = $countPerPage;
            $filter->OffsetIndex = $lastId;

            $filter->WhereFilters = array();
# Supplier
            $filter->WhereFilters[] = $this->jobFilterWhere(array(
                $this->jobFilterField("SupplierId", "Exact", $suplier_id)
                    ), "AND");
# Category

            if (!empty($categoryId)) {

                $entity = $categoryId;
                $filterKeyword = "CategoryId";

                if (is_array($entity)) {
                    $jobFilterArray = array();
                    foreach ($entity as $value) {
                        $jobFilterArray[] = $this->jobFilterField($filterKeyword, "Exact", $value);
                    }

                    $filter->WhereFilters[] = $this->jobFilterWhere($jobFilterArray
                            , "OR");
                } else {
                    $filter->WhereFilters[] = $this->jobFilterWhere(array(
                        $this->jobFilterField($filterKeyword, "Exact", $entity)
                            ), "AND");
                }
            }

# Expertise
            if (!empty($expertise)) {
                $entity = $expertise;
                $filterKeyword = "ExpertiseId";

                if (is_array($entity)) {
                    $jobFilterArray = array();
                    foreach ($entity as $value) {
                        $jobFilterArray[] = $this->jobFilterField($filterKeyword, "Exact", $value);
                    }

                    $filter->WhereFilters[] = $this->jobFilterWhere($jobFilterArray
                            , "OR");
                } else {
                    $filter->WhereFilters[] = $this->jobFilterWhere(array(
                        $this->jobFilterField($filterKeyword, "Exact", $entity)
                            ), "AND");
                }
            }

# Scope
            if (!empty($employment_type)) {
                $entity = $employment_type;
                $filterKeyword = "EmploymentType";

                if (is_array($entity)) {
                    $jobFilterArray = array();
                    foreach ($entity as $value) {
                        $jobFilterArray[] = $this->jobFilterField($filterKeyword, "Exact", $value);
                    }

                    $filter->WhereFilters[] = $this->jobFilterWhere($jobFilterArray
                            , "OR");
                } else {
                    $filter->WhereFilters[] = $this->jobFilterWhere(array(
                        $this->jobFilterField($filterKeyword, "Exact", $entity)
                            ), "AND");
                }
            }


# Scope
            if (!empty($jobscop)) {
                $entity = $jobscop;
                $filterKeyword = "JobScope";

                if (is_array($entity)) {
                    $jobFilterArray = array();
                    foreach ($entity as $value) {
                        $jobFilterArray[] = $this->jobFilterField($filterKeyword, "Exact", $value);
                    }

                    $filter->WhereFilters[] = $this->jobFilterWhere($jobFilterArray
                            , "OR");
                } else {
                    $filter->WhereFilters[] = $this->jobFilterWhere(array(
                        $this->jobFilterField($filterKeyword, "Exact", $entity)
                            ), "AND");
                }
            }

# Keyword
            if (!empty($keyword)) {
                if (is_array($keyword)) {
                    $count = count($keyword[0]);
                    for ($i = 0; $i < $count; $i++) {

                        $type = $keyword[1][$i];
                        $kw = $keyword[0][$i];

                        $filter->WhereFilters[] = $this->jobFilterWhere(array(
                            $this->jobFilterField("Description", $type, $kw),
                            $this->jobFilterField("Requiremets", $type, $kw),
                            $this->jobFilterField("JobTitle", $type, $kw),
                            $this->jobFilterField("Skills", $type, $kw),
                                ), "OR");
                    }
                } else {
                    $filter->WhereFilters[] = $this->jobFilterWhere(array(
                        $this->jobFilterField("Description", "OneOrMore", $keyword),
                        $this->jobFilterField("Requiremets", "OneOrMore", $keyword),
                        $this->jobFilterField("JobTitle", "OneOrMore", $keyword),
                        $this->jobFilterField("Skills", "OneOrMore", $keyword),
                            ), "OR");
                }
            }

# jobLocation
            if (!empty($jobLocation)) {
                $entity = $jobLocation;
                $filterKeyword = "RegionId";

                if (is_array($entity)) {
                    $jobFilterArray = array();
                    foreach ($entity as $value) {
                        $jobFilterArray[] = $this->jobFilterField($filterKeyword, "Exact", $value);
                    }

                    $filter->WhereFilters[] = $this->jobFilterWhere($jobFilterArray
                            , "OR");
                } else {
                    $filter->WhereFilters[] = $this->jobFilterWhere(array(
                        $this->jobFilterField($filterKeyword, "Exact", $entity)
                            ), "AND");
                }
            }

# Company
            if (!empty($customerId)) {
                $entity = $customerId;
                $filterKeyword = "EmployerId";

                if (is_array($entity)) {
                    $jobFilterArray = array();
                    foreach ($entity as $value) {
                        $jobFilterArray[] = $this->jobFilterField($filterKeyword, "Exact", $value);
                    }

                    $filter->WhereFilters[] = $this->jobFilterWhere($jobFilterArray
                            , "OR");
                } else {
                    $filter->WhereFilters[] = $this->jobFilterWhere(array(
                        $this->jobFilterField($filterKeyword, "Exact", $entity)
                            ), "AND");
                }
            }

# UpdatePeriod
            if (!empty($date_period)) {
                $filter->WhereFilters[] = $this->jobFilterWhere(array(
                    $this->jobFilterField("UpdateDate", "BetweenDates", $date_period)
                        ), "AND");
            }

            $filter->OrderByFilterSort = array(
                $this->jobFilterSort("UpdateDate", "Descending"),
                $this->jobFilterSort("JobCode", "Ascending"),
            );

            $transactionCode = guid::newGuid();
            $params = array(
                "jobFilter" => $filter,
                "LanguageId" => nsoftConfig::$language_code,
                "transactionCode" => $transactionCode
            );

            $res = $this->client->JobsGetByFilter($params);
//            echo("REQUEST" . $this->client->__getLastRequest());
//            echo("RESPONSE" . $this->client->__getLastResponse());
//            die;
// The jobs returned as xml wrapped by unnecessary tags
            $strJobs = $res->JobsGetByFilterResult->any;
            $docStart = strpos($strJobs, "<diffgr:diffgram");
            $docEnd = strrpos($strJobs, "</diffgr:diffgram>");
            $doc = substr($strJobs, $docStart);
            $xml = simplexml_load_string($doc);
            $result = $this->searchResultToArray($xml->DocumentElement->Jobs);

            foreach ($result as $key => $val) {
                $date = $val["date"];
                $result[$key]["date"] = date("d/m/Y", strtotime($date));
            }
//            echo("REQUEST".$this->client->__getLastRequest());
//            echo("RESPONSE".$this->client->__getLastResponse());
//            die;
            if (isset($this->user_id) && !empty($this->user_id)) {
                $filedJobs = $this->getFilledJobsList($this->user_id, 0, 1000);

                foreach ($result as $key => $searchJob) {
                    $status = 0;
                    foreach ($filedJobs["jobs"] as $filedJob) {
                        if (intval($searchJob["jobid"]) === $filedJob["jobid"]) {
                            $status = 1;
                            break;
                        }
                    }
                    $result[$key]["status"] = $status;
                }
            }




//            $user_name = $this->session->get("user_credentials")["applicantID"][0];
////  var_dump(!$this->isLogedIn() || !$sendToAgent);
//            if (!$this->isLogedIn() || !$sendToAgent) {
//                return $result;
//            }
//
//            $hunterFilter = $this->hunterFiterBuilder($categoryId, $keyword, $expertise, $employment_type, $customerId, $lastId, $countPerPage, $suplier_id, $jobscop, $jobLocation, $date_period, $sendToAgent);
////    var_dump($hunterFilter);die;
//            $security = new nlsSecurity();
//            $user_name_and_directory = nsoftConfig::$user_domain . "\\" . $user_name;
//            $auth = $security->AuthenticateByConsumerKeyAndSecretKey($user_name_and_directory);
//            $soap_headers = array(new SoapHeader('_', 'NiloosoftCred1', $auth->plainToken),
//                new SoapHeader('_', 'NiloosoftCred2', $auth->signedToken));
//            $searchService = new SearchService($soap_headers);
//            $searchService->JobHunterCreateOrUpdate($user_name_and_directory, $hunterFilter);
////    $searchService->JobHuntersGetForUser();
//// $searchService->JobHunterExecuteByHunterId2("effad4ba-fe74-4ec0-8337-964f298cfc65");



            return $result;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function isLogedIn() {


        $user_credentials = $this->session->get("applicant_user_id");
        if (empty($user_credentials)) {
            return false;
        }

        return true;
    }

    public function getNewJobCount() {
        return $this->getNewJobsList(0, 10000)["count"];
    }

    public function jobGetById($jobId) {

        $transactionCode = guid::newGuid();
        try {
            $params = array(
                "JobId" => $jobId,
                "transactionCode" => $transactionCode,
            );
            $res = $this->client->JobGet($params);
            $job = $res->JobGetResult;
            $job->Description = html_entity_decode($job->Description);

            $job->rankList = $this->getListJobRanks();
            
            return $job;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function applayedJobGetCount($userID) {
        return intval($this->getAppliedJobs($userID, 0, 1000)->totalNumResults);
//->totalNumResults;
    }

    public function getAppliedJobs($userID, $fromRow = null, $toRow = null) {

        $transactionCode = guid::newGuid();
        try {
            $params = array(
                "applicantId" => $userID,
                "transactionCode" => $transactionCode,
                "fromRow" => $fromRow,
                "toRow" => $toRow,
                "sortColumn" => "JobTitle",
                "isAscending" => true,
                "filter" => [],
            );

            $res = $this->client->ApplicantJobMatchesListGet($params);

            return $res;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function applayedJobs($userID) {
        $transactionCode = guid::newGuid();
        try {
            $params = array(
                "applicantId" => $userID,
                "transactionCode" => $transactionCode,
                "filter" => [],
            );
            $res = $this->client->ApplicantJobMatchesListGet($params);

            return $res;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function resumeGetCount($userID) {
        return count($this->getCVList($userID)["files"]);
    }

    public function totalJobsCount($userID) {
        $supplier_id = nsoftConfig::$categorySupplierId;
        $categoryClass = new stdClass();
        $categoryClass->cat = null;
        $categoryClass->isSub = false;
        //$returnVal = $this->jobsSearch_hunter($categoryClass, null, null, null, null, null, null, $supplier_id);
        $returnVal = $this->totalJobsCount_hunter($categoryClass, null, null, null, null, null, null, $supplier_id);
        return $returnVal;
    }

    public function newJobsGetCount($userID) {
// return 0;

        return $this->getNewJobsList(0, 1)["count"];
    }

    public function getCVList($userID) {

        $listData = ["files" => [
            ],
        ];

        $transactionCode = guid::newGuid();
        try {
            $params = array(
                "applicantId" => $userID,
                "transactionCode" => $transactionCode,
            );
            $res = $this->client->CvInfoGetCvVersions($params);
//echo("RESPONSE".$this->client->__getLastResponse());

            if (property_exists($res->CvInfoGetCvVersionsResult, "CvVersionInfo")) {
                $cvs = $res->CvInfoGetCvVersionsResult->CvVersionInfo;
                $cvs = is_array($cvs) ? $cvs : [$cvs];
            } else {
                return $listData;
            }

            $cvCount = count($cvs);
            if ($cvCount > 0) {
                foreach ($cvs as $value) {
                    $date = $value->CreationDate;
                    $tposition = strpos($date, "T");
                    $datetoshow = date_format(date_create(substr($date, 0, $tposition)), "d/m/Y");
                    $timetoshow = substr($date, $tposition + 1, strlen($date));
                    $listData["files"][] = [
                        "id" => $value->FileId,
                        "name" => "",
                        "date" => $datetoshow,
                        "time" => $timetoshow,
                        "fileId" => $value->FileId
//  "CardId" => $value->CardId
                    ];
                }
            }

            return $listData;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function getFileInfo($fileID, $card_id, $showContent = false) {
//  $fileID = 1010;
//   $card_id="451d2e28-bb7f-4076-a56e-0e6e2445e458";

        $transactionCode = guid::newGuid();
        try {

            $params = array(
                "transactionCode" => $transactionCode,
                "fileId" => $fileID,
                "cardId" => $card_id,
                "IncludeFileContent" => $showContent
            );
            $res = $this->client->FileGetByFileId($params);
            //echo "Request " . $this->client->__getLastRequest();die;
            return $res;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function fileGetCount($userID) {

        $transactionCode = guid::newGuid();
        try {

            $params = array(
                "transactionCode" => $transactionCode,
                "ParentId" => $userID,
                "filter" => []
            );
            $res = $this->client->FilesListGet($params);
//	     echo "Request " . $this->client->__getLastRequest();
//       echo "Response " . $this->client->__getLastResponse();
//	    die;
            return $res->totalNumResults;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function fileDelete($fileID, $card_id) {
        $transactionCode = guid::newGuid();
        try {

            $params = array(
                "transactionCode" => $transactionCode,
                "fileId" => $fileID,
                "cardId" => $card_id
            );
            $res = $this->client->FileDelete($params);

            return $res;
        } catch (SoapFault $ex) {

            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function getFileList($userID, $fromRow, $toRow) {

        $transactionCode = guid::newGuid();
        try {

            $params = array(
                "transactionCode" => $transactionCode,
                "ParentId" => $userID,
                "filter" => [],
                "fromRow" => $fromRow,
                "toRow" => $toRow,
                "sortColumn" => "Name",
            );
            $res = $this->client->FilesListGet($params);

            return $res;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function getEmploymenTypes($supplier_id, $listType = "JobEnploymentType") {
        return $this->getRelevantList($supplier_id, $listType);
    }

    public function getJobArea() {
        $dirService = new DirectoryService($this->soap_headers);
        return $dirService->getJobArea();
    }
    
    public function getListByListName($listname){
        $dirService = new DirectoryService($this->soap_headers);
        return $dirService->getlistbyname($listname);
    }

    public function getListJobRanks(){
        $dirService = new DirectoryService($this->soap_headers);
        return $dirService->getJobRanks();
    }

    public function getJobType($supplier_id, $listType = "JobScope") {
        return $this->getRelevantList($supplier_id, $listType);
    }

    public function getRelevantList($supplier_id, $listType) {
        $transactionCode = guid::newGuid();
        try {

// print_r($this->client->__getTypes());
            $params = array(
                "transactionCode" => $transactionCode,
                "supplierId" => $supplier_id
            );

            $res = $this->client->JobSiteGetRelevantLists($params)->JobSiteGetRelevantListsResult->PublishedJobsListsInfo;
//echo("REQUEST".$this->client->__getLastRequest());
//echo("RESPONSE".$this->client->__getLastResponse());
//var_dump($res);
//die;
            for ($i = 0; $i < count($res); $i++) {
                if ($res[$i]->JobListName === $listType) {
                    return $res[$i]->JobList->HunterListItem;
                }
            }

            return "";
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function getFiles($userID, $fromRow, $toRow) {


        $listData = ["files" => []];
        $result = $this->getFileList($userID, $fromRow, $toRow);
        if (property_exists($result->FilesListGetResult, "FileInfo")) {
            $fileListArray = $result->FilesListGetResult->FileInfo;
        } else {
            return $listData;
        }
        $filecount = $result->totalNumResults;
        $files = [];
        if ($filecount > 0) {
            if ($filecount == 1) {
                $fileListArray = [$fileListArray];
            }

            foreach ($fileListArray as $file) {
                $date = $file->CreatedDate;
                $tposition = strpos($date, "T");
                $datetoshow = substr($date, 0, $tposition);
                $timetoshow = substr($date, $tposition + 1, strlen($date));
                $files[] = ["id" => $file->FileId,
                    "name" => $file->Name,
                    "date" => $datetoshow,
                    "time" => $timetoshow,
                    "CardId" => $file->CardId,
                    "fileId" => $file->FileId
                ];
            }
        }

        $listData["files"] = $files;

        return $listData;
    }

    public function getAppliantByUserName($userName) {
        $dirService = new DirectoryService($this->soap_headers);
        return $dirService->getApplicantByUserName($userName);
    }

    public function addResume($cardID, $type) {
        $transactionCode = guid::newGuid();
//$CardInfo = guid::CardInfo();

        try {

            $myfile = fopen("webdictionary.txt", "r") or die("Unable to open file!");
            $file = fread($myfile, filesize("webdictionary.txt"));
            fclose($myfile);


            $params = array(
                'CardId' => $cardID,
                "oCvInfo" => [
//'CardId'=>'none',
                    'SupplierName' => 'ppp',
                    'LanguageId' => nsoftConfig::$language_code
                ],
                "oFileInfo" => [
                    'CardId' => $cardID,
                    'CreatedBy' => 1,
                    'FileContent' => $file,
                    'FolderId' => 12,
                    'FolderName' => 'cv1',
                    'Name' => 'asdasd1',
                    'Size' => filesize("webdictionary.txt"),
                    'Type' => $type
                ],
                "transactionCode" => $transactionCode,
            );


            $res = $this->client->CvAdd($params);
            $job = $res;
//echo("REQUEST".$this->client->__getLastRequest());
//echo("RESPONSE".$this->client->__getLastResponse());
            return $job;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function updateFile($card_id, $fileid, $fileContent, $isCv = false) {

        $file_info = $this->getFileInfo($fileid, $card_id)->FileGetByFileIdResult;

        $transactionCode = guid::newGuid();


        try {


            if ($isCv) {
                $params = array(
                    "oCvInfo" => [
                        'CardId' => $card_id,
                        'FileContent' => $fileContent,
                        "FileId" => $fileid,
                        "CreatedBy" => $file_info->CreatedBy,
                        "FolderId" => $file_info->FolderId,
                        "FolderName" => $file_info->FolderName,
                        "Name" => $file_info->Name,
                        "Size" => sizeof($fileContent),
                        "Type" => $file_info->Type
                    ],
                    "transactionCode" => $transactionCode,
                );
            } else {
                $params = array(
                    "oFileInfo" => [
                        'CardId' => $card_id,
                        'FileContent' => $fileContent,
                        "FileId" => $fileid,
                        "CreatedBy" => $file_info->CreatedBy,
                        "FolderId" => $file_info->FolderId,
                        "FolderName" => $file_info->FolderName,
                        "Name" => $file_info->Name,
                        "Size" => sizeof($fileContent),
                        "Type" => $file_info->Type
                    ],
                    "transactionCode" => $transactionCode,
                );
            }


//   echo("REQUEST" . $this->client->__getLastRequest());
// die;
            // Meni: TODO: Upload file to server (cv/other)
            $res = $this->client->CvUpdate($params);
            //$res = $this->client->FileInsertBinary($params["oCvInfo"], $params["transactionCode"]);

//echo("REQUEST" . $this->client->__getLastRequest());
// echo("RESPONSE" . $this->client->__getLastResponse());
            return $res;
        } catch (SoapFault $ex) {

            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function getCompanies() {
        $transactionCode = guid::newGuid();
        try {
            $res = $this->client->CustomerGetNameList(array("transactionCode" => $transactionCode));
            return $res->CustomerGetNameListResult->HunterListItem;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function getFilledJobsList($userId, $fromRow = null, $toRow = null) {

        $result = [];
        $list = $this->getAppliedJobs($userId, $fromRow, $toRow)->ApplicantJobMatchesListGetResult->JobsInfoAttachedToApplicant;
        // var_dump($list);
        if (isset($list) && isset($list->JobId)) {
            $list = [$list];
        }

        if (isset($list))
            foreach ($list as $job) {
                $result[] = $this->hunterResultToJob($job);
//                $result[] = [
//                    "id" => $job->JobId . $i,
//                    "name" => $job->JobTitle,
//                    "company" => $job->EmployerName,
//                    "jobid" => $job->JobId,
//                    "date" => date("d/m/y", strtotime($job->AttachedDate)),
//                    "description" => "Description",
//                    "status" => 1,
//                    "jobCode" => $job->JobCode
//                ];
//                $i++;
            }
        $listData = ["jobs" => $result
        ];

        return $listData;
    }

    public function getNewJobsList($lastId, $countPerPage) {
        $listData = ["jobs" => [], "count" => 0];

        $user_name = $this->session->get("user_credentials")["applicantID"][0];
        $security = new nlsSecurity();
        $user_name_and_directory = nsoftConfig::$user_domain . "\\" . $user_name;
        $auth = $security->AuthenticateByConsumerKeyAndSecretKey($user_name_and_directory);
        $soap_headers = array(
          new SoapHeader('_', 'NiloosoftCred1', $auth->plainToken),
          new SoapHeader('_', 'NiloosoftCred2', $auth->signedToken)
        );
        $searchService = new SearchService($soap_headers);
//$searchService->JobHunterCreateOrUpdate($user_name_and_directory, $filter);
        
        //$hunter_list1 = $searchService->JobHuntersGetForUser();
        $hunter_list = $searchService->JobHuntersGetForUser()->automaticHunters->HunterListItem;
        if (is_array($hunter_list)) {
            $joblist = $searchService->JobHunterExecuteByHunterId2($hunter_list[0]->Value, $lastId, $countPerPage)->JobHunterExecuteByHunterId2Result;
			//clear new jobs
            if (property_exists($joblist, "TotalHits") && $joblist->TotalHits > 0) {
			    $searchService->AutomaticHunterConfirmReset($hunter_list[0]->Value);
            }
// var_dump($joblist);
            $listData["jobs"] = [];
            $listData["count"] = $joblist->TotalHits;


            if (property_exists($joblist, "Results") && property_exists($joblist->Results, "JobInfo")) {
                $resultList = $joblist->Results->JobInfo;
                if (is_array($resultList)) {

                    foreach ($resultList as $job) {
                        $listData["jobs"][] = $this->hunterResultToJob($job);
                    }
                } elseif ($joblist->TotalHits > 1) {

                    $listData["jobs"][] = $this->hunterResultToJob($resultList);
                }
            }
        }


        $result = $listData["jobs"];
//        foreach ($result as $key => $val) {
//            $date = $val["date"];
//            $result[$key]["date"] = date("d/m/Y", strtotime($date));
//        }

        if (is_array($result) && count($result) > 0 && isset($this->user_id) && !empty($this->user_id)) {
            $filedJobs = $this->getFilledJobsList($this->user_id, 0, 1000);

            foreach ($result as $key => $searchJob) {
                $status = 0;
                foreach ($filedJobs["jobs"] as $filedJob) {
                    if (intval($searchJob["jobid"]) === $filedJob["jobid"]) {
                        $status = 1;
                        break;
                    }
                }
                $result[$key]["status"] = $status;
            }
        }

        $listData["jobs"] = $result;

        return $listData;
    }

    private function hunterResultToJob($job) {

        $companyName = "";
        if (isset($job->IsDiscreteJob) && $job->IsDiscreteJob == true) {
            $companyName = "חסוי";
        } else {
            $companyName = $job->EmployerName;
        }

        $currentJob = $this->jobGetById($job->JobId);
        $jobLink = "";
        if (isset($currentJob->ExtendedProperties->ExtendedProperty)){
            if (is_array($currentJob->ExtendedProperties->ExtendedProperty)){
                foreach ($currentJob->ExtendedProperties->ExtendedProperty as $property){
                    if ($property->PropertyName == "JobLink") $jobLink = $property->Value;
                }
            }
            else
            {
                if ($currentJob->ExtendedProperties->ExtendedProperty->PropertyName == "JobLink"){
                    $jobLink = $currentJob->ExtendedProperties->ExtendedProperty->Value;
                }
            }
        }
        
        $result = [
        "id" => $job->JobId,
        "name" => $job->JobTitle,
        "company" => $companyName,
        "address" => "",
        "jobid" => $job->JobId,
        "date" =>date("d/m/Y", strtotime($job->UpdateDate!=null?$job->UpdateDate:$job->AttachedDate)),//date("d/m/Y", strtotime($job->UpdateDate)),
        "description" => "Description",
        "jobCode" => $job->JobCode,
        "jobStatus" => $currentJob->Status,
        "customerSiteLink" => $jobLink
        ];

        return $result;
    }

    public function getHotJobsList($lastId, $countPerPage) {
        $result = $this->jobsSearch("", "", "", "", "", $lastId, $countPerPage, nsoftConfig::$hotjobsSupplierId);

        $listData = ["jobs" => $result,
            "status" => 0,
            "jobCount" => 10,
        ];
        return $listData;
    }

    public function applayForJob($userId, $jobId) {
        return true;
    }

    public function deleteFile($userId, $jobId) {
        return true;
    }

    public function uploadFile($userId, $jobId) {
        return true;
    }

    public function downloadFile($userId, $fileId) {
        $this->getFileInfo($fileId, $userId, true);
    }

    public function getJobAreaList($userId, $supplier) {

        $list = [[
        "id" => 109,
        "name" => "A"
            ],
            [
                "id" => 2,
                "name" => "B",
            ],
            [
                "id" => 38,
                "name" => "C",
        ]];
//    $catService = new CategoryService($this->soap_headers);
//   return $catService->getJobTypes();


        return $list;
    }

    public function getJobTypeList() {
        $dirService = new DirectoryService($this->soap_headers);
        $jopTypes = $dirService->getJobTypes();
        return $jopTypes;
    }

    public function getCategoryList($parentId = null) {

        $catService = new DirectoryService($this->soap_headers);

        return $catService->getcategories($parentId);
    }

    public function getCompanyList() {
        $companies = $this->getCompanies();

        $list = [];
        foreach ($companies as $com) {
            $list[] = ["id" => $com->Value,
                "name" => $com->Text];
        }
//        $list = array([
//                "id" => 1,
//                "name" => "Comp1"
//            ],
//            [
//                "id" => 2,
//                "name" => "Comp2"
//            ]
//        );

        return $list;
    }

    public function getJobUpdateDateList() {

        $list = array(
            [
                "id" => 1,
                "name" => "יום אחרון"
            ],
            [
                "id" => 2,
                "name" => "שבוע אחרון"
            ],
            [
                "id" => 3,
                "name" => "חודש אחרון"
            ]
        );

        return $list;
    }

    public function getExpertiseList($supplier_id) {
        $res = $this->getRelevantList($supplier_id, "ProfessionalExpertises");
        $list[] = ["id" => $res->Text,
            "name" => $res->Text];
        return $list;
    }

    public function getJobLocationList() {

        $catService = new DirectoryService($this->soap_headers);
        return $catService->getLocations();
    }

    public function getCCEmailListByJobId($jobId) {
        $transactionCode = guid::newGuid();
        try {

// print_r($this->client->__getTypes());
            $params = array(
                "transactionCode" => $transactionCode,
                "jobId" => $jobId
            );

            $res = $this->client->JobSiteGetEmailsList($params)->JobSiteGetEmailsListResult;
            $result = "";
            if (isset($res->string)) {
                $result = $res->string;
            }

            return $result;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    function sendHTMLemail($to, $subject, $from, $body) { 

        if (ereg("(.*)< (.*)>", $from, $regs)) {
            $from = '=?UTF-8?B?'.base64_encode($regs[1]).'?= < '.$regs[2].'>';
        } else {
            $from = $from;
        }

        $headers = "From: $from\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $boundary = uniqid("HTMLEMAIL");
        $headers .= "Content-Type: multipart/alternative;".
            "boundary = $boundary\r\n\r\n";
        $headers .= "This is a MIME encoded message.\r\n\r\n";
        $headers .= "--$boundary\r\n".
            "Content-Type: text/plain; UTF-8\r\n".
            "Content-Transfer-Encoding: base64\r\n\r\n";
        $headers .= chunk_split(base64_encode(strip_tags($body)));
        $headers .= "--$boundary\r\n".
            "Content-Type: text/html; charset=UTF-8\r\n".
            "Content-Transfer-Encoding: base64\r\n\r\n";
        $headers .= chunk_split(base64_encode($body)); 

        $result = mail($to,'=?UTF-8?B?'.base64_encode($subject).'?=',"",$headers);
        return $result;
    }    

    public function sendEmail($from_mail, $mailto, $from_name, $subject, $message, $attachments, $ccList = "") {
        $mailer = JFactory::getMailer();
        //$mailer->setFrom($from_mail, $from_name);
        $mailer->addRecipient($mailto);
        $mailer->addCc($ccList);
        if (strlen(nsoftConfig::$toMailCc) > 0) {
            $mailer->addCc(nsoftConfig::$toMailCc);
        }
        $mailer->Subject = $subject;
        $mailer->Body = $message;
        $mailer->isHtml(true);
        foreach ($attachments as $attachment) {
            $mailer->addStringAttachment($attachment['content'], $attachment['name']);
        }
//
//            file_put_contents('/tmp/meni.log', 
//                "-----------CC---------\n" .
//                "$ccList\n" .
//                "-----------MAILER---------\n" .
//                print_r($mailer, true) . "\n" .
//                "---------------------------\n"
//            );            

        return $mailer->Send() ? 1 : 0;

        
//        $subject= "=?utf-8?b?".base64_encode($subject)."?=";//Meni: encode
//        $headers = "From: =?utf-8?b?".base64_encode($from_name)."?= <".$from_mail.">\r\n";//"From: $from\r\n";
//        $headers .= "Cc: $ccList";
//        // boundary
//        $semi_rand = md5(time());
//        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
//
//        // headers for attachment
//        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"\r\n";
//        $headers.= "X-Mailer: PHP/" . phpversion();
//
//        // multipart boundary
//        $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=utf-8\n" .
//                "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
//
//        // preparing attachments
//        if (count($attachments) > 0) {
//            foreach ($attachments as $item) {
//
//                $message .= "--{$mime_boundary}\n";
//
//                if (function_exists('mb_strlen')) {
//                    $size = mb_strlen($item['content'], '8bit');
//                } else {
//                    $size = strlen($item['content']);
//                }
//
//                $data = chunk_split(base64_encode($item['content']));
//
//                $message .= "Content-Type: application/octet-stream; name=\" =?utf-8?b?".base64_encode($item['name'])."?= \"\n" .
//                        "Content-Description:  =?utf-8?b?".base64_encode($item['name'])."?= \n" .
//                        "Content-Disposition: attachment;\n" . " filename=\" =?utf-8?b?".base64_encode($item['name'])."?= \"; size=" . $size . ";\n" .
//                        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
//            }
//        }
//
//        $message .= "--{$mime_boundary}--";
//        $returnpath = "-f" . $from_mail;
//
//        //send email
//        $mail = mail($mailto, $subject, $message, $headers, $returnpath);
//        //function return true, if email sent, otherwise return fasle
//        return 1;
        //return array('result'=>1, 'subject'=>$subject, 'message'=>$message);
        
    }

    public function applyForJobAction($params) {

        $i = 0;
        $attachments = [];

        $dropbox_name = $params['apply-job-dropbox-fileName'];
        $dropbox_url = $params['apply-job-dropbox-fileUrl'];
        $file_upload_type = $params['apply-job-upload-file-type'];
        $apply_job_comment = strlen($params['apply-job-comment'])>0?"<p><h3>הערות מועמד:</h3>".$params['apply-job-comment']."</p>":"";
        $addNewFileResult;
        if (!empty($dropbox_name) && !empty($dropbox_url)) {
            $otherFileContent = file_get_contents($dropbox_url);

            if ($otherFileContent != "") {
                $attachments[$i]['name'] = $dropbox_name;
                $attachments[$i]['content'] = $otherFileContent;
                $i++;
                //   $this->InsertNewCvFile($otherFileContent);
                $filearr = explode(".", $dropbox_name);
                //$this->InsertNewCvFile($otherFileContent, $filearr[0], $filearr[1]);
                $addNewFileResult = $this->InsertNewFile($otherFileContent, $filearr[0], $filearr[1], $file_upload_type);
            }
        }

        $skydrive_name = $params['apply-job-skydrive-fileName'];
        $skydrive_url = $params['apply-job-skydrive-fileUrl'];

        if (!empty($skydrive_name) && !empty($skydrive_url)) {
            $otherFileContent = file_get_contents($skydrive_url);

            if ($otherFileContent != "") {
                $attachments[$i]['name'] = $skydrive_name;
                $attachments[$i]['content'] = $otherFileContent;
                $i++;
                //    $this->InsertNewCvFile($otherFileContent);
                $filearr = explode(".", $skydrive_name);
                //$this->InsertNewCvFile($otherFileContent, $filearr[0], $filearr[1]);
                $addNewFileResult = $this->InsertNewFile($otherFileContent, $filearr[0], $filearr[1], $file_upload_type);
            }
        }

        $local_name = $params['fileFromComp']['apply-job-localFile']["name"];
        $local_url = $params['fileFromComp']['apply-job-localFile']["tmp_name"];
// var_dump($local_name);
//  var_dump($local_url);
//  die;
        if (!empty($local_name) && !empty($local_url)) {
            $otherFileContent = file_get_contents($local_url);

            if ($otherFileContent != "") {
                $attachments[$i]['name'] = $local_name;
                $attachments[$i]['content'] = $otherFileContent;
                $i++;


                $filearr = explode(".", $local_name);
                //$this->InsertNewCvFile($otherFileContent, $filearr[0], $filearr[1]);
                $addNewFileResult = $this->InsertNewFile($otherFileContent, $filearr[0], $filearr[1], $file_upload_type);
            }
        }

        if (isset($params['selectedFile'])) {

            foreach ($params['selectedFile'] AS $itemFile) {
                $fileContent = $this->getFileInfo($itemFile, $this->user_id, true);
                $attachments[$i]['name'] = $fileContent->FileGetByFileIdResult->Name . "." . trim($fileContent->FileGetByFileIdResult->Type);
                $attachments[$i]['content'] = $fileContent->FileGetByFileIdResult->FileContent;
                $i++;
            }
        }
        if (isset($params['selectedCv'])) {

            foreach ($params['selectedCv'] AS $itemFile) {
                $fileContent = $this->getFileInfo($itemFile, $this->user_id, true);
                $attachments[$i]['name'] = $fileContent->FileGetByFileIdResult->Name . "." . trim($fileContent->FileGetByFileIdResult->Type);
                $attachments[$i]['content'] = $fileContent->FileGetByFileIdResult->FileContent;
                $i++;
            }
        }

        $from_mail = nsoftConfig::$fromMail;
        $mailto = nsoftConfig::$toMail;
        $fromName = nsoftConfig::$fromName;
// $subject = !empty($params['jobCode']) ? $params['jobCode'] : $params['jobId'];
        $jobCode = $params['jobCode'];
// $message = $this->user_id;
        $jobTitle = $params['jobTitle'];
        $cclist = $this->getCCEmailListByJobId($params['jobId']);
        //$mailCc = "";

        //if (is_array($cclist) && count($cclist) > 0)
        //    foreach ($cclist as $emailAddr) {
        //        $mailTo.=$emailAddr . "; ";
        //    }
//change by moshe
//       if (is_array($cclist) && count($cclist) > 0)   
//       {
//           foreach ($cclist as $emailAddr) {
//               $mailCc.=$emailAddr . "; ";
//           }
//       }
//       else if(isset($cclist) && strlen($cclist) > 0)
//       {
//           $mailCc.=$cclist . "; ";
//       }
//end change by moshe
        /* Set internal character encoding to UTF-8 */

        $session = &JFactory::getSession();
        $applicantName = $session->get("user_credentials")["fullName"][0];
        $file_content = file_get_contents("plugins/system/nsoft/assets/applyJobEmailTemplate.html");
        $message = str_replace("[applicantname]", $applicantName, $file_content);
        $message = str_replace("[jobtitle]", $jobTitle, $message);
        $message = str_replace("[applicantComment]", $apply_job_comment, $message);
        $subject = "קורות חיים מהמסלול האקדמי המכללה למנהל של $applicantName למשרה $jobTitle $jobCode ";
        
        //$attachmentsWM = $this->AddWatermark($attachments);

        $snedMailResponse = $this->sendEmail($from_mail, $mailto, $fromName, $subject, $message, $attachments, $cclist);
        $jobid = $params['jobId'];
        $this->ActivityAdd2($jobid);
        $addNewFileResult['sendMailResult'] = $snedMailResponse;
        $addNewFileResult['log'] = array('from'=>$from_mail, 'to'=>$mailto, 'name'=>$fromName, 'subject'=>$subject, 'cc'=>$mailCc, 'title'=>$jobTitle);
        $response = $addNewFileResult;
        
        echo json_encode($response);
        die;
    }
    
    public function AddWatermark($attachments){
        $attachmentsWM = array();
        foreach ($attachments as $file){
            switch (pathinfo($file["name"], PATHINFO_EXTENSION)){
                case "pdf":
                    $attachmentsWM[] = $this->pdfWatermark($file);
                    break;
                case "doc":
                    $attachmentsWM[] = $this->docWatermark($file);
                    break;
                case "docx":
                    $attachmentsWM[] = $this->docxWatermark($file);
                    break;
                case "rtf":
                    $attachmentsWM[] = $this->rtfWatermark($file);
                    break;
                default :
                    $attachmentsWM[] = $file;
                    break;
            }
        }
    }
    
    public function pdfWatermark($file){
        return $file;
    }

    public function docWatermark($file){
        return $file;
    }

    public function docxWatermark($file){
        return $file;
    }

    public function rtfWatermark($file){
        return $file;
    }

    public function ActivityAdd2($jobId) {
        $transactionCode = guid::newGuid();
        try {

// print_r($this->client->__getTypes());
            $params = array(
                "transactionCode" => $transactionCode,
                "options" => "ActivityId",
                "RelatedActivities" => null,
                "activityInfo" => array(
                    "CreationTime" => (new DateTime())->format('c'),
                    "StartDate" => (new DateTime())->format('c'),
                    "Title" => "הגשת מועמדות מאתר משרות",
                    "CardId" => $this->user_id,
                    "JobId" => $jobId,
                    "Type" => 1,
                    "JobMatchStatus" => 201),
            );

            $res = $this->client->ActivityAdd2($params);
//            var_dump($res);
//                echo "Request " . $this->client->__getLastRequest();
//                 echo "Response " . $this->client->__getLastResponse();
            return $res;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    
    // Meni: TODO: Will be replaced by InsertNewFile
    public function InsertNewCvFile($file, $name, $type) {

        $transactionCode = guid::newGuid();
        try {
            $session = &JFactory::getSession();
// print_r($this->client->__getTypes());
            $params = array(
                "TransactionCode" => $transactionCode,
                "CountryCode" => "IS",
                "SupplierId" => $this->siteId,
                "LanguageId" => nsoftConfig::$language_code,
                "file" => array(
                    "CardId" => $this->user_id,
                    "CreatedBy" => 2,
                    "FolderId" => 1,
                    "Type" => $type,
                    "Name" => $name,
                    "FileContent" => $file),
            );
            // var_dump($params);die;
            $res = $this->client->InsertNewCvFile($params);
            //       echo "Request " . $this->client->__getLastRequest();
            //echo "Response " . $this->client->__getLastResponse();
            //  die;
            return $res;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    // Meni: TODO: if CV insert CV, if other Insert other files
    public function InsertNewFile($file, $name, $type, $fileType) {

        $transactionCode = guid::newGuid();
        try {
            $session = &JFactory::getSession();
// print_r($this->client->__getTypes());
            if ($fileType=="cv"){
                $params = array(
                "TransactionCode" => $transactionCode,
                "CountryCode" => "IS",
                "SupplierId" => $this->siteId,
                "LanguageId" => nsoftConfig::$language_code,
                "file" => array(
                    "CardId" => $this->user_id,
                    "CreatedBy" => 2,
                    "FolderId" => 1,
                    "Type" => $type,
                    "Name" => $name,
                    "FileContent" => $file),
                );
                $res = $this->client->InsertNewCvFile($params);
            }else{
                $params = array(
                    "resumeInfo" => array(
                        "CardId" => $this->user_id,
                        "CreatedBy" => 2,
                        "FolderId" => 13,
                        "Type" => $type,
                        "Name" => $name,
                        "FileContent" => $file
                    ),
                    "transactionCode" => $transactionCode,
                );
                $res = $this->client->FileInsertBinary($params);
            }
            $count = $fileType=="cv"?$this->resumeGetCount($this->user_id):$this->fileGetCount($this->user_id);
            return array('res'=>$res, 'fileList'=>array('type'=>$fileType, 'count'=>$count));
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    /* == PRIVATE */

    private function jobFilterWhere(/* JobFilterField array */
    $filters, /* enum: OR / AND */ $condition) {
        $filterWhere = new stdClass();
        $filterWhere->Filters = $filters;
        $filterWhere->Condition = $condition;
        return $filterWhere;
    }

    private static function hunterJobFilterField(/* enum */$field, /* enum */ $searchPhrase, /* string */ $value, $isDirect = false, $filedType = "", $parentFiled = "") {
        $filterField = new stdClass();

        $filterField->SearchPhrase = $searchPhrase;
        $filterField->IncludeEmptyValues = false;
        $filterField->Value = $value;
        if ($isDirect) {
            $filterField->Field = $field;
            $filterField->FieldFilterType = $filedType;
            $filterField->Value = $value;
        } else {
            $filterField->Field = $parentFiled;
            $filterField->FieldFilterType = "Nested";
            $nestedFileds = new stdClass();
            $nestedFileds->Field = $field;
            $nestedFileds->SearchPhrase = $searchPhrase;

            $nestedFileds->FieldFilterType = $filedType;
            $nestedFileds->IncludeEmptyValues = false;
            $nestedFileds->Value = $value;
            $filterField->NestedFields = array();
            $filterField->NestedFields[] = $nestedFileds;
//TODO
        }


        return $filterField;
    }

    private static function jobFilterField(/* enum */$field, /* enum */ $searchPhrase, /* string */ $value) {

        $filterField = new stdClass();
        $filterField->Field = $field;
        $filterField->SearchPhrase = $searchPhrase;
        $filterField->Value = $value;
        return $filterField;
    }

    private static function jobFilterSort(/* enum */
    $field, /* enum: ASC / DESC */ $direction) {
        $filterSort = new stdClass();
        $filterSort->Field = $field;
        $filterSort->Direction = $direction;
        return $filterSort;
    }

    private function searchResultToArray($searchResult) {
        $result = [];

// if (is_array($searchResult))
        if (!empty($searchResult)) {
            foreach ($searchResult as $job) {
                $result[] = [
                    "id" => $job->JobId->__toString(),
                    "name" => $job->JobTitle->__toString(),
                    "company" => $job->EmployerName->__toString(),
                    "address" => $job->RegionText->__toString(),
                    "jobid" => $job->JobId->__toString(),
                    "date" => $job->UpdateDate->__toString(),
                    "description" => "Description",
                    "jobCode" => $job->JobCode->__toString()
                ];
            }
        }

        return $result;
    }

}

class DirectoryService {

    private $url;
    private $client;
    private $siteId;

    public function __construct($soapHeaders) {
        $this->url = nsoftConfig::$directoryServiceWsdlUrl; #		Prod: "https://xcardsservice.hunterhrms.com/huntercards.svc"
        $this->siteId = nsoftConfig::$nsoftSiteId; #								Prod: "2e066b0b-8f9a-44c9-b985-7195afd9ab53"
        $this->client = new SoapClient($this->url, array(
            'trace' => nsoftConfig::$trace,
            'exceptions' => nsoftConfig::$exceptions,
            'cache_wsdl' => nsoftConfig::$cache_wsdl
        ));

        $this->client->__setSoapHeaders($soapHeaders);
    }

    public function getlistbyname($listname = null) {
        $transactionCode = guid::newGuid();
        try {

// print_r($this->client->__getTypes());
            $params = array(
                "transactionCode" => $transactionCode,
                "parentItemId" => null,
                "listName" => $listname,
                "languageId" => nsoftConfig::$language_code
            );

            $res = $this->client->GetListItems($params)->GetListItemsResult; //->ListItemInfo;
            $list = [];

            if (property_exists($res, "ListItemInfo"))
                $res = $res->ListItemInfo;
            else
                return $list;


            foreach ($res as $cat) {
                $list[] = ["id" => $cat->ListItemId,
                    "name" => $cat->ValueTranslated];
            }


            return $list;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }
    
    public function getcategories($parentId = null) {
        $transactionCode = guid::newGuid();
        try {

// print_r($this->client->__getTypes());
            $params = array(
                "transactionCode" => $transactionCode,
                "parentItemId" => $parentId,
            );

            if ($parentId == null) {
                $params["listName"] = 'ProfessionalCategories';
            } else {
                $params["listName"] = 'ProfessionalFields';
            }

            $res = $this->client->GetListItems($params)->GetListItemsResult; //->ListItemInfo;
            $list = [];

            if (property_exists($res, "ListItemInfo"))
                $res = $res->ListItemInfo;
            else
                return $list;


            foreach ($res as $cat) {
                $list[] = ["id" => $cat->ListItemId,
                    "name" => $cat->ValueTranslated];
            }


            return $list;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function getLocations($parentId = null) {
        $transactionCode = guid::newGuid();
        try {

// print_r($this->client->__getTypes());
            $params = array(
                "transactionCode" => $transactionCode,
                "parentItemId" => $parentId,
                "listName" => 'Regions',
                "languageId" => nsoftConfig::$language_code
            );

            $res = $this->client->GetListItems($params)->GetListItemsResult->ListItemInfo;

            $list = [];

            foreach ($res as $cat) {
                // Do not display the All the country Option Test 9 Prod 9
                if ((int)$cat->ListItemId !== 9 )
                    $list[] = ["id" => $cat->ListItemId,
                                "name" => $cat->ValueTranslated];
            }


            return $list;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function getJobTypes() {
        $transactionCode = guid::newGuid();
        try {

// print_r($this->client->__getTypes());

            $params = array(
                "transactionCode" => $transactionCode,
                "listName" => 'JobScope',
                "languageId" => nsoftConfig::$language_code,
                "parentItemId" => null
            );
            $res = $this->client->GetListItems($params)->GetListItemsResult->ListItemInfo;


            $list = [];
            // Do not display all option Test 302 Prod 3
            foreach ($res as $cat) {
                if((int)$cat->ListItemId !== 3)
                    $list[] = ["id" => $cat->ListItemId, "name" => $cat->ValueTranslated];
            }

            return $list;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function getJobRanks() {
        $transactionCode = guid::newGuid();
        try {
            $params = array(
                "languageId" => nsoftConfig::$language_code,
                "listName" => 'JobRank',
                "transactionCode" => $transactionCode,
            );
            $res = $this->client->GetListByListName($params)->GetListByListNameResult->HunterListItem;


            $list = [];
            foreach ($res as $rank) {
                $list[] = ["id" => $rank->Value, "name" => $rank->Text];
            }

            return $list;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

    public function getjoblocations() {
        $list = array([
                "id" => 1,
                "name" => "aaaa"
            ],
            [
                "id" => 2,
                "name" => "bbb"
            ]
        );

        return $list;
    }

    public function getApplicantByUserName($username) {

        $transactionCode = guid::newGuid();
        try {


            $params = array(
                "transactionCode" => $transactionCode,
                "userName" => $username
            );

            $res = $this->client->GetCardIdByUserName2($params);

            return $res;
        } catch (SoapFault $ex) {
            var_dump($ex);
        } catch (Exception $ex) {
            var_dump($ex);
        }
    }

    public function getJobArea() {

        $transactionCode = guid::newGuid();
        try {

// print_r($this->client->__getTypes());
            $params = array(
                "transactionCode" => $transactionCode,
                "languageId" => nsoftConfig::$language_code,
                "listName" => 'EmploymentForm'
            );


            $res = $this->client->GetListByListName($params)->GetListByListNameResult;
//            echo("REQUEST" . $this->client->__getLastRequest());
//            echo("RESPONSE" . $this->client->__getLastResponse());
//            var_dump($res);
//            die;


            return $res;
        } catch (Exception $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        }
    }

}

class SearchService {

    private $url;
    private $client;
    private $siteId;

    public function __construct($soapHeaders) {
        $this->url = nsoftConfig::$searchServiceWsdlUrl; #		Prod: "https://xcardsservice.hunterhrms.com/huntercards.svc"
        $this->siteId = nsoftConfig::$nsoftSiteId; #								Prod: "2e066b0b-8f9a-44c9-b985-7195afd9ab53"
        $this->client = new SoapClient($this->url, array(
            'trace' => nsoftConfig::$trace,
            'exceptions' => nsoftConfig::$exceptions,
            'cache_wsdl' => nsoftConfig::$cache_wsdl
        ));

        $this->client->__setSoapHeaders($soapHeaders);
    }

    public function JobHuntersGetForUser() {
        $transactionCode = guid::newGuid();
        try {


            $params = array(
                "transactionCode" => $transactionCode,
                    // "status" => $status
            );

            $res = $this->client->JobHuntersGetForUser($params);

            return $res;
        } catch (SoapFault $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        } catch (Exception $ex) {
            var_dump($ex);
            $ex->transactionCode = $transactionCode;
            throw $ex;
        }
    }

    public function JobHunterCreateOrUpdate($user_name, $filter, $hunterStatus) {

        $transactionCode = guid::newGuid();
        $hunter_id = null;
        try {

            $hunter_id = guid::newGuid();



            $params = array(
                "transactionCode" => $transactionCode,
                "hunterId" => $hunter_id,
                "hunterStatus" => $hunterStatus,
                "name" => null,
                "jobId" => null,
                "externalId" => null,
                "userDefined1" => null,
                "userDefined2" => null,
                "filter" => $filter
            );

            $res = $this->client->JobHunterCreateOrUpdate($params);
//            echo "Request " . $this->client->__getLastRequest();
//            echo "Response " . $this->client->__getLastResponse();
//            die;
            return $hunter_id;
        } catch (SoapFault $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        } catch (Exception $ex) {
            var_dump($ex);
            $ex->transactionCode = $transactionCode;
            throw $ex;
        }
    }

    public function JobHunterExecuteByHunterId2($hunter_id, $from, $ofset, $sinceLastQuery = true) {


        $transactionCode = guid::newGuid();
        try {


            $params = array(
                "transactionCode" => $transactionCode,
                "HunterId" => $hunter_id,
                "queryConfig" => array("ResultRowLimit" => $ofset, "ResultRowOffset" => $from),
                "sinceLastQuery" => $sinceLastQuery
            );
//  var_dump($params);die;
//  die;
            $res = $this->client->JobHunterExecuteByHunterId2($params);
//  var_dump($res);
//            echo "Request " . $this->client->__getLastRequest();
//            echo "Response " . $this->client->__getLastResponse();
//            die;
//var_dump($res);
            return $res;
        } catch (SoapFault $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        } catch (Exception $ex) {
            var_dump($ex);
            $ex->transactionCode = $transactionCode;
            throw $ex;
        }
    }

    public function JobHunterExecuteNewQuery2($hunter_id, $from, $ofset, $filter) {


        $transactionCode = guid::newGuid();
        try {


            $params = array(
                "transactionCode" => $transactionCode,
                "HunterId" => $hunter_id,
                "queryConfig" => array("ResultRowLimit" => $ofset, "ResultRowOffset" => $from),
                "oQueryInfo" => $filter
            );
//  var_dump($params);die;
//  die;
            $res = $this->client->JobHunterExecuteNewQuery2($params)->JobHunterExecuteNewQuery2Result; //->Results;
            //var_dump($res);
            //echo "Request " . $this->client->__getLastRequest();
            // echo "Response " . $this->client->__getLastResponse();
            //die;
//var_dump($res);
            return $res;
        } catch (SoapFault $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        } catch (Exception $ex) {
            var_dump($ex);
            $ex->transactionCode = $transactionCode;
            throw $ex;
        }
    }
	
	public function AutomaticHunterConfirmReset($hunter_id) {
        $transactionCode = guid::newGuid();
        try {
            $params = array(
                "transactionCode" => $transactionCode,
                "HunterId" => $hunter_id
            );
			$this->client->AutomaticHunterConfirmReset($params);
        } catch (SoapFault $ex) {
            var_dump($ex);
            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        } catch (Exception $ex) {
            var_dump($ex);
            $ex->transactionCode = $transactionCode;
            throw $ex;
        }
    }

}
