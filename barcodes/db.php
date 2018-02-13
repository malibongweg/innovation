<?php
define('_JEXEC', 1);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
//require_once ( JPATH_BASE .'/includes/defines.php' );
//require_once ( JPATH_BASE .'/includes/framework.php' );
//require_once ( JPATH_BASE .'/libraries/joomla/factory.php' );
require_once ( JPATH_BASE .'/scripts/system/functions.php');
$dbo = &JFactory::getDBO();

$dbo->setQuery("select host,connect_string,user_name,password,disaster_host,disaster_connect_string,disaster_user_name,disaster_password,system_mode,log_only from cput_system_setup where system_name='its'");
$row = $dbo->loadObject();
$oci_host = $row->host;
$oci_connect_string = $row->connect_string;
$oci_user = $row->user_name;
$oci_pass = $row->password;
$cs = $oci_connect_string; $uname = $oci_user; $pass = $oci_pass;

$con = oci_connect($uname,$pass,$cs);
if (!$con){
	$return = array();
	$return['Error'] = -1;
	echo json_encode($return);
	exit();
}

if (isset($_GET)) {
	if ($_GET['action'] == 'srchStudent'){
		$return = array();

		//Get photo
		$sql = sprintf("select location from identity.photos where userid = %d",$_GET['stno']);
		$dbo->setQuery($sql);
		$dbo->query();
		if ($dbo->getNumRows() == 0){
			$return['imgLocation'] = '';
		} else {
			$result = $dbo->loadResult();
			$return['imgLocation'] = $result;
		}

		//Get ITS details
		$sql = sprintf("select IADTITLE||' '||iadinit||' '||iadsurn as fullname,iadmagstrip from stud.iadbio where iadstno=%d",$_GET['stno']);
		$result = oci_parse($con,$sql);
		oci_execute($result);
		$arr = oci_fetch_object($result);//,$rows,0,-1,OCI_FETCHSTATEMENT_BY_ROW);

		$return['itsMag'] = $arr->IADMAGSTRIP;
		$return['itsError'] = 0;
		$return['fullName'] = $arr->FULLNAME;
		$return['stdNo'] = $_GET['stno'];

		//Get copier details
		$sql = "select host,user_name,password,database_name from cput_system_setup where system_name='copies'";
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$copyCon = pg_connect("host=".$row->host." dbname=".$row->database_name." user=".$row->user_name." password=".$row->password);
		if (!$copyCon) {
			$return['copyError'] = -1;
		} else {
			$sql = sprintf("select count(1) as cnt,card from users where trim(reference) = '%s' group by card",$_GET['stno']);
			$cres = pg_query($copyCon,$sql);
			$crow = pg_fetch_object($cres);
			if (intval($crow->cnt) == 0){
				$return['copyError'] = -2;
			} else {
				$return['copyError'] = 0;
				$return['copyMag'] = $crow->card;
			}
		}
		
		//Get Meals details
		$sql = "select host,user_name,password,connect_string from cput_system_setup where system_name='meals'";
		$dbo->setQuery($sql);
		$row = $dbo->loadObject();
		$meals_db = $row->host.$row->connect_string;
		$conMeals = ibase_connect($meals_db,$row->user_name,$row->password);
		if (!$conMeals){
			$return['mealsError'] = 3;
		} else {
			$return['mealsError'] = 0;
			$sql = sprintf("select cardno from cards where userno = %d",$_GET['stno']);
			$res=ibase_query($conMeals,$sql);
			$row = ibase_fetch_object($res);
			$return['mealsMag'] = $row->CARDNO;
		}
		echo json_encode($return);
	}
	else if ($_GET['action'] == 'updateCopier'){
				$return = array();
				$sql = "select host,user_name,password,database_name from portal.cput_system_setup where system_name='copies'";
				$dbo->setQuery($sql);
				$dbo->query();
				if ($dbo->getNumRows() == 0) {
					$return['Error'] = -1;
					echo json_encode($return);
					exit();
				}
				$rowp = $dbo->loadObject();
				//Connect to copy system
				$copy = pg_connect("host=".$rowp->host." dbname=".$rowp->database_name." user=".$rowp->user_name." password=".$rowp->password);
				if (!$copy)
				{
					$return['Error'] = -1;
					echo json_encode($return);
					exit();
				}
				$y = getdate();
				$x = date("d-m-Y",mktime(0,0,0,12,31,$y['year']));
				//Does the user exist?
				$sql = sprintf("select count(1) as cnt from users where trim(reference) = '%s'",$_GET['stno']);
				$res = pg_query($copy,$sql);
				$rowx = pg_fetch_object($res);
					
					if ($rowx->cnt == 0)
					{
						///Check if the card exist.....
						$sql = sprintf("select count(1) as ccount from users where card = %d",$_GET['cardno']);
						$cresult = pg_query($copy,$sql);
						$crow = pg_fetch_object($cresult);
						$card_count = intval($crow->ccount);  //0=No card, 1=card exists

						///0=No card, insert new record with new card no
							if ($card_count == 0){
								$xx = "insert into users (userid,reference,name,card,issue,groupid,credit,debit,copiesmade,lastused, expires,useraccess)
								values (nextval('user_id_seq'),'".$_GET['stno']."','".$_GET['stno']."',".$_GET['cardno'].",0,1,0,0,0,null,to_date('".$x."','dd-mm-yyyy'),1)";
								$xxres = pg_query($copy,$xx);

								$ls = sprintf("select logevent(11,'%s','%s',0,1,0,0)",$_GET['stno'],"added #".$_GET['cardno']." ".$_GET['stno']);
								$sres = pg_query($copy,$ls);
								} else {  ///1=Card exists, change old rec and assign to new user....
								$chg_card = (intval($_GET['cardno']) - 1010101); //Change card no to 6-digit value...
								$sql = sprintf("update users set card=%d where card=%d",$chg_card,$_GET['cardno']);///Update user with chg card.
								$cresult = pg_query($copy,$sql);

								///Insert user with new card no.
								$xx = "insert into users (userid,reference,name,card,issue,groupid,credit,debit,copiesmade,lastused,expires,useraccess)
								//values (nextval('user_id_seq'),'".$_GET['stno']."','".$_GET['stno']."',".$_GET['cardno'].",0,1,0,0,0,null,to_date('".$x."','dd-mm-yyyy'),1)";
								$xxres = pg_query($copy,$xx);

								$ls = sprintf("select logevent(11,'%s','%s',0,1,0,0)",$_GET['stno'],"added #".$_GET['cardno']." ".$_GET['stno']);
								$sres = pg_query($copy,$ls);
					
								}
						$return['Error'] = 0;
						echo json_encode($return);
						exit();
					
					} else	{
						///The user exists, check if the card is the same.....
						$sql = sprintf("select count(1) as ucount from users where reference = '%s' and card=%d",$_GET['stno'],$_GET['cardno']);
						$uresult = pg_query($copy,$sql);
						$urow = pg_fetch_object($uresult);
						if ($urow->ucount == 1) {
							//User and card the same, ignore....
							$return['Error'] = 0;
							echo json_encode($return);
							exit();
						}
						///If the above condition is not met, continue with normal logic....

						///The user does exist.......check if the card exists....
						$sql = sprintf("select count(1) as ccount from users where card = %d",$_GET['cardno']);
						$cresult = pg_query($copy,$sql);
						$crow = pg_fetch_object($cresult);
						$card_count = intval($crow->ccount);  //0=No card, 1=card exists

						if ($card_count == 0) {
							////Card does not exist....
							$xx = sprintf("update users set card=%d, expires=to_date('".$x."','dd-mm-yyyy') where reference = '%s'",$_GET['cardno'],$_GET['stno']);
							$xxres = pg_query($copy,$xx);
							$ls = sprintf("select logevent(14,'%s','%s',0,1,0,0)",$_GET['stno'],"Card changed.".$_GET['cardno']);
							$le = pg_query($copy,$ls);
						} else {
							/////Card exists....
							$chg_card = (intval($_GET['cardno']) - 1010101); //Change card to 6-digit value...
							$sql = sprintf("update users set card=%d where card=%d",$chg_card,$_GET['cardno']);///Update user with chg card value...
							$cresult = pg_query($copy,$sql);

							/////Update user with new card no.
							$xx = sprintf("update users set card=%d, expires=to_date('".$x."','dd-mm-yyyy') where reference = '%s'",$_GET['cardno'],$_GET['stno']);

							$xxres = pg_query($copy,$xx);
							$ls = sprintf("select logevent(14,'%s','%s',0,1,0,0)",$_GET['stno'],"Card changed.".$_GET['cardno']);
							$le = pg_query($copy,$ls);
						}
					}
					$return['Error'] = 0;
					echo json_encode($return);
					exit();
	} else if ($_GET['action'] == 'updateMeals'){
				$return = array();
				$sql = "select host,user_name,password,database_name,connect_string from portal.cput_system_setup where system_name='meals'";
				$dbo->setQuery($sql);
				$dbo->query();
				if ($dbo->getNumRows() == 0) {
					$return['Error'] = -1;
					echo json_encode($return);
					exit();
				}
				$rowp = $dbo->loadObject();
				$meals_db = $rowp->host.$rowp->connect_string;
				
				$con = ibase_connect($meals_db,$rowp->user_name,$rowp->password);
				
					if (!$con)
					{ 
						echo ibase_errmsg();
						$return['Error'] = -2;
						echo json_encode($return);
						exit();						
					 } else {
						 $sql=sprintf("insert into cardfix (userno,oldcard,newcard,whodone,updflag) values (%d,%d,%d,'OPA',NULL)",$_GET['stno'],$_GET['cardno'],$_GET['cardno']);
								$res=ibase_query($con,$sql);
								if (!$res)
								{
									$return['Error'] = -3;
									echo json_encode($return);
									exit();
								}
								else
								{
									$return['Error'] = 0;
									echo json_encode($return);
									exit();
								}
					 }
	} else if ($_GET['action'] == 'mailMeals'){
		
				$return = array();
				$sql = "select host,user_name,password,database_name,connect_string from portal.cput_system_setup where system_name='meals'";
				$dbo->setQuery($sql);
				$dbo->query();
				if ($dbo->getNumRows() == 0) {
					$return['Error'] = -1;
					echo json_encode($return);
					exit();
				}
				$rowp = $dbo->loadObject();
				$meals_db = $rowp->host.$rowp->connect_string;
				
				$con = ibase_connect($meals_db,$rowp->user_name,$rowp->password);
				
					if (!$con)
					{ 
						echo ibase_errmsg();
						$return['Error'] = -2;
						echo json_encode($return);
						exit();						
					 } else {
						 $sql = sprintf("select pin from swipestuds where userno=%d",$_GET['stno']);
						 $res=ibase_query($con,$sql);
								if (!$res)
								{
									$return['Error'] = -3;
									echo json_encode($return);
									exit();
								}
								else
								{
									$return['Error'] = 0;
									$email = $_GET['stno']."@mycput.ac.za";
									$row = ibase_fetch_object($res);
									$email_details = "Cape Peninsula University of Technology.<br /><br />";
									$email_details = $email_details . "Meals System Pin...<br />";
									$email_details = $email_details . "PIN: ".$row->PIN."<br /><br />";
									$email_details = $email_details . "<b>PLEASE NOTE:</b> Please keep your meals pin in a safe place. /><br /><br />";
									$email_details = $email_details . "CTS Department.";
									$myemail = array();
									$myemail[] = $email;
									$email = serialize($myemail);
									sendMail($email,'Meals System Pin Code',$email_details);
									//echo $email_details;
									echo json_encode($return);
								}
					 }
	}
exit();
}

?>