<?php
$dbo =& JFactory::getDBO();
$user = & JFactory::getUser();
$dbo->setQuery("select host,connect_string,user_name,password,disaster_host,disaster_connect_string,disaster_user_name,disaster_password,system_mode,log_only from cput_system_setup where system_name='mas_test'");
$row = $dbo->loadObject();
$oci_host = $row->host;
$oci_connect_string = $row->connect_string;
$oci_user = $row->user_name;
$oci_pass = $row->password;


if (isset($_GET['function'])) {

    if($_GET['function'] == 'table') {
        $cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass; 
        
       echo "<table border='0' width='45%' style='table-layout: fixed'>";
       
       echo "<tr>";
            echo "<td style='width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px'><strong>Student Number</strong>";
            echo "<td style='width: 15%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px'><strong>Name</strong></td>";
            echo "<td style='width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px'><strong>Surname</strong></td>";
            echo "<td style='width: 10%; height: 24px; border: 1px solid #3C619C; background-color: #3C619C; color: #FFFFFF; padding: 1px'><strong>Cellphone Number</strong></td>";
        echo "</tr>";

        $sql = sprintf("select distinct iadstno,iadnames,iadsurn,iadinit,IAGQUAL,IAGOT,
                        stud.get_address(iadstno, 'CE',1,'I','S') cell_telephone
                        from stud.iadbio, stud.iagenr
                        where iadstno = iagstno
                        and iagcyr = 2014
                        and iagenr.IAGCANCDATE is null
                        and iagenr.IAGQUAL = 'PGCEFT'
                        and iagprimary = 'Y'");
        //echo $sql;
        $data = array();
        $con = oci_connect($uname,$pass,$cs);
        $result = oci_parse($con,$sql);
        oci_execute($result);
                while ($row = oci_fetch_object($result)) {
                    echo "<tr>";
                        echo "<td style='overflow: hidden; width: 10%; height: 11px; border: 1px solid #FFFFFF; background-color: #FFFFFF; color: #000000'>".$row->IADSTNO.'</td>';
                        echo "<td style='overflow: hidden; width: 15%; height: 11px; border: 1px solid #FFFFFF; background-color: #FFFFFF; color: #000000'>".$row->IADNAMES.'</td>';
                        echo "<td style='overflow: hidden; width: 10%; height: 11px; border: 1px solid #FFFFFF; background-color: #FFFFFF; color: #000000'>".$row->IADSURN.'</td>';
                        echo "<td style='overflow: hidden; width: 10%; height: 11px; border: 1px solid #FFFFFF; background-color: #FFFFFF; color: #000000'>".$row->CELL_TELEPHONE.'</td>';
                        //$data[] = $row->IADSTNO.';'.$row->IADSURN.';'.$row->CELL_TELEPHONE;
                    echo "</tr>";    
                }
                echo '</table>';        
               // $data[] = '21201235'.';'.'Mali'.';'.'0834942638';
        oci_close($con);
        echo json_encode($data);

    }
               	
}
exit();
?>

<script type="text/javascript">
	window.parent.$j.colorbox.resize({'width': 620,'height': 400});
        
</script>