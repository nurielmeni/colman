<?php

defined('_JEXEC') or die;
use Joomla\CMS\Log\Log;
class apiController extends nlsCards {

    public function __construct($username_token, $password_token) {
        parent::__construct($username_token, $password_token);
    }

    public function downloadFileAction($param) {


        $result = $this->getFileInfo($param["fileId"], $this->user_id, true);
        $name = $result->FileGetByFileIdResult->Name;
        $content = $result->FileGetByFileIdResult->FileContent;
        $size = $result->FileGetByFileIdResult->Size;
        $type = trim($result->FileGetByFileIdResult->Type);

        if (is_null($size)) {
            if (function_exists('mb_strlen')) {
                $size = mb_strlen($content, '8bit');
            } else {
                $size = strlen($content);
            }
        }
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $name . "." . $type . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        header('Content-Length: ' . $size);

        echo $content;
        exit;
    }

    public function myAction() {

        $cvCount = $this->resumeGetCount($this->user_id);
        $applayedJobs = $this->applayedJobGetCount($this->user_id);
        $fileCount = $this->fileGetCount($this->user_id);

        //var_dump($this->fileGetCount('451D2E28-BB7F-4076-A56E-0E6E2445E458'));die;
        // $hotjobs_count = 0;//$this->getNewJobCount(); // count($this->getHotJobsList(0, 1000)["jobs"]);
        //  $hotjobs_count = count($this->getHotJobsList(0,1000, "next")["jobs"]);

        $newJobsCount = $this->newJobsGetCount($this->user_id);
        $totalJobsCount = $this->totalJobsCount($this->user_id);







        $result = [
            'resumeCount' => $cvCount,
            'applayedJobsCount' => $applayedJobs,
            'fileCount' => $fileCount,
            'newJobsCount' => $newJobsCount,
            'totalJobsCount' => $totalJobsCount->TotalHits
        ];

        echo json_encode($result);
        die;
    }

    public function getJobContentAction($params) {
        Log::add('function: getJobContentAction', Log::DEBUG, 'colman-slowness');
        $from = intval($params['lastId']) * intval($params['countPerPage'] - 1) + 1;
        $to = $from + intval($params['countPerPage'] - 1);

        if ($params['cont_type'] === "filled-jobs") {
            Log::add('filled-jobs: start: ' . microtime(), Log::DEBUG, 'colman-slowness');
            $listData = $this->getFilledJobsList($this->user_id, $from, $to);
            Log::add('filled-jobs: end: ' . microtime(), Log::DEBUG, 'colman-slowness');
        } elseif ($params['cont_type'] === "new-jobs") {
            Log::add('new-jobs: start: ' . microtime(), Log::DEBUG, 'colman-slowness');
            $listData = $this->getNewJobsList($params['lastId'] * ($params['countPerPage'] - 1), $params['countPerPage']);
            Log::add('new-jobs: end: ' . microtime(), Log::DEBUG, 'colman-slowness');
        } elseif ($params['cont_type'] === "cv") {
            Log::add('cv: start: ' . microtime(), Log::DEBUG, 'colman-slowness');
            $listData = $this->getCvList($this->user_id);
            Log::add('cv: end: ' . microtime(), Log::DEBUG, 'colman-slowness');
        } elseif ($params['cont_type'] === "files") {
            Log::add('files: start: ' . microtime(), Log::DEBUG, 'colman-slowness');
            $listData = $this->getFiles($this->user_id, $from, $to);
            Log::add('files: end: ' . microtime(), Log::DEBUG, 'colman-slowness');
        }
        echo json_encode($listData);
        die;
    }

    public function deleteFileAction($param) {

        $this->fileDelete($param["fileId"], $this->user_id);
        if ($param["cont_type"] == "cv") {
            $response = $this->resumeGetCount($this->user_id);
        } else {
            $response = $this->fileGetCount($this->user_id);
        }

        echo json_encode($response);
        die;
    }

//    public function downloadFileAction($param) {
//
//
//        $response = $this->getFileInfo($param["fileId"], $this->user_id, true);
//        $name = $response->FileGetByFileIdResult->Name;
//        $type = $response->FileGetByFileIdResult->Type;
//        $size = $response->FileGetByFileIdResult->Size;
//        $content = chunk_split(base64_encode($response->FileGetByFileIdResult->FileContent));
////print_r($response);
//        header('Content-Description: File Transfer');
//        header('Content-Type: application/octet-stream');
//        header('Content-Disposition: attachment; filename=' . $name);
//        header('Expires: 0');
//        header('Cache-Control: must-revalidate');
//        header('Pragma: public');
//        header('Content-Length: ' . $size);
//
//        echo $content;
//        exit;
//    }


    public function uploadFileAction($params) {

        //  var_dump($this->getFileInfo(1010, "451d2e28-bb7f-4076-a56e-0e6e2445e458", true));
        //  $this->updateFile("451d2e28-bb7f-4076-a56e-0e6e2445e458", 1010, "yyyyyyy");
        //  var_dump($this->getFileInfo(1010, "451d2e28-bb7f-4076-a56e-0e6e2445e458", true));
        //  die;
        //  var_dump($params);die;
        $fileContent = file_get_contents($params['uploadFile']['tmp_name']);
        $filename = pathinfo($params['uploadFile']['name'], PATHINFO_FILENAME);
        $filetype = pathinfo($params['uploadFile']['name'], PATHINFO_EXTENSION);

        $response = $this->InsertNewFile($fileContent, $filename, $filetype, $params['isCV']=="true"?"cv":"file");
        echo json_encode($response);
        die;
    }

//    public function getApplyOptionsAction() {
//
//        //  $employment_types = $this->getEmploymenTypes('a6b6cb37-071f-48f4-81ca-c3c3f44323ea');
//        // var_dump($employment_types);
//        /* $listData['jobArea'] = [["name" => $employment_types->Value,
//          "id" => $employment_types->Value]];
//         */
//        $file_list = $this->getFileList($this->user_id, 0, 1000);
//        if (property_exists($file_list->FilesListGetResult, "FileInfo")) {
//            if (is_array($file_list->FilesListGetResult->FileInfo)) {
//                $listData['fileList'] = $file_list->FilesListGetResult->FileInfo;
//            } else {
//                $listData['fileList'] = [$file_list->FilesListGetResult->FileInfo];
//            }
//        } else {
//            $listData['fileList'] = [];
//        }
//
//
//        $listData['cvList'] = $this->getCVList($this->user_id)['files'];
//
//        echo json_encode($listData);
//        die;
//    }

    public function getJobDescription($params) {
        $listData = $this->jobGetById($params['jobId']);
        
        echo json_encode($listData);
        die;
    }

}
