<?php

if ($_GET['func'] == 'srch') {

    $tns1 = '(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.18.4.73)(PORT = 1521)) (CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi01) ))';
    $conns1 = oci_connect('dirxml', 'dirxml', $tns1) or die ('ERR');

    //check for user in m08 if user is not here user needs to update details at HR

    //$data = array();
    $pnum = $_GET['uid1'];
    //ECHO $pnum;
    $sql = sprintf("select count(staffno) as cnt from dirxml.staffinfo where staffno = '%s'", $pnum);
    //ECHO $sql;
    $result = oci_parse($conns1, $sql);
    oci_execute($result);
    $row = oci_fetch_object($result);
    $cnt = $row->CNT;


    //echo $cnt;

    if (intval($cnt) == 0) {
        //goes to js file and gives message for user to update details at HR
        echo "0";
        exit();

    } //if an entry was found in the m08
    elseif (intval($cnt) >= 1) {

        //echo $cnt;
        $tns2 = '(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.18.4.73)(PORT = 1521)) (CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi01) ))';
        $conn2 = oci_connect('dirxml', 'dirxml', $tns2) or die ('ERR');
        $conns2 = oci_connect('miss', 'miss', $tns2) or die ('ERR');
        $data = array();
        $stno = $_GET['uid1'];
        //check if user is in adstaffinfo
        $sql2 = sprintf("select count(staffno) as cnt from dirxml.staffinfo  where staffno = '%s'", $stno);
        $result2 = oci_parse($conn2, $sql2);
        oci_execute($result2);
        $row2 = oci_fetch_object($result2);
        $cnt2 = $row2->CNT;

        //if user is not in adstaffinfo
        if (intval($cnt2) == 0) {
            // echo 'ok'.$cnt2;
            //check in m08 if staff member is an exclusion case
            $sql3 = sprintf("select COUNT(personnel_number) as cnt from miss.m08v_curr_personnel_detail x
               where x.appointment_names not like '%CLAIM ACAD 25%'
                and x.appointment_names not like '%CLAIM ACAD<24%'
                and x.appointment_names not like '%CLAIM STUD<24%'
                and x.appointment_names not like '%MODERATOR%'
                and x.appointment_names not like '%INVIG<24%'
                and x.appointment_names not like '%CLAIM ADMIN 25%'
                and personnel_number = '%s'", $stno);
            $result3 = oci_parse($conns2, $sql3);
            oci_execute($result3);
            $row3 = oci_fetch_object($result3);
            $cnt3 = $row3->CNT;

            //get excluded staff information from staffinfo view
            // $sql = sprintf("select staffno, login_name, username, email from dirxml.staffinfo where staffno = '%s'",$stno);
            //  $result = oci_parse($conn,$sql);
            //echo $sql;
            // oci_execute($result);
            // $row = oci_fetch_object($result);
            // $data[] =  $row->STAFFNO.";".$row->LOGIN_NAME.";".$row->USERNAME.";".$row->EMAIL;

            // oci_close($conn);
            // $data = array();
            // echo json_encode($data);
            if (intval($cnt3 == 0)) {
                $sql4 = sprintf("select staffno, login_name, username, email from dirxml.staffinfo where staffno = '%s'", $stno);
                $result4 = oci_parse($conn2, $sql4);
                //echo $sql;
                oci_execute($result4);
                $row4 = oci_fetch_object($result4);
                // $data[] =  $row->STAFFNO.";".$row->LOGIN_NAME.";".$row->USERNAME.";".$row->EMAIL;

                $sql5 = sprintf("select COUNT(staffno) as cnt from dirxml.staffxrules  where staffno = '%s'", $stno);
                $result5 = oci_parse($conn2, $sql5);
                oci_execute($result5);
                $row5 = oci_fetch_object($result5);
                $cnt5 = $row5->CNT;

                if (intval($cnt5) == 0) {



                    //Call Procedure to insert a record
                    /* $sql6 = "BEGIN adstaffinfo_inserts_test; adstaffinfo_updates_test; commit; END;";
                     $result6 = oci_parse($conn2,$sql6);
                     oci_execute($result6);

                     /*if($result6)
                     {
                         echo '1';
                     }
                     else{
                         echo '0';
                     }*/
                    echo '1';
                    exit();
                }


            }


        } elseif (intval($cnt2) >= 1) {

            $tns1 = '(DESCRIPTION =  (ADDRESS = (PROTOCOL = TCP)(HOST = 10.18.4.73)(PORT = 1521)) (CONNECT_DATA =  (SERVER = DEDICATED)   (SID = prodi01) ))';
            $conn11 = oci_connect('dirxml','dirxml',$tns1) or die ('ERR');

            $data = array();
            $stno11 = $_GET['uid1'];
            //echo $stno;
            $sql11 = sprintf("select staffno, login_name, username, email from dirxml.staffinfo where staffno = '%s'",$stno11);
            $result11 = oci_parse($conn11,$sql11);
            //echo $sql;
            oci_execute($result11);
            $row11 = oci_fetch_object($result11);
            $data11[] =  $row11->STAFFNO.";".$row11->LOGIN_NAME.";".$row11->USERNAME.";".$row11->EMAIL;

            oci_close($conn11);
            // $data = array();
            echo json_encode($data11);

            //not and exlusion



            //exit();
        }


    }
}
?>