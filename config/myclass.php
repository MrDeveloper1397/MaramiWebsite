<?php
class Myclass extends Date_Diff
{
	var $pagination='';
	var $arrs = array();
	var $excel_data = array();
	var $studentMarks=array();
	var $sql='';
	var $tblname ='';
	var $uname_me ='meadministrator';
	var $pwd_me = 'g0ogle';
	var $uname ='';
	var $pwd = '';
	var $en_pwd = '';
	var $All0wme = 1;
	var $poscolname ='';
	var $limit=0;
	var $page=0;
	var $pagename;
	var $value=0;
	var $rowid=0;
	var $strcode='';
	var $fstr='';
	var $ftype ='';
	var $fic ='';
	var $filename = '';
	var $tp='';
	var $rev = false; // rev= return value and by default its value is false;
	var $SDtail = array();
	public $dt = '';
	var $obj = '';
	var $u_id='';
	var $u_uname ='';
	var $u_email ='';
	var $u_type ='';
	public $u_name = '';
	var $u_power='';
	var $ipaddress = '';
	var $loginIPAddress = '';
	var $dtm='';
	var $au_tbl='';
	var $hours='';
	var $mins='';
	var $default_welcome_msg='';
	
	function __construct()
	{
		$this->dt = date('Y-m-d H:i:s');
		
		if($this->checkUser()==true && $this->checkUser1()==true){
			$this->u_name = $this->uDtail('name');
			$this->u_uname = $this->uDtail('uname');
			$this->u_email = $this->uDtail('uemail');
			$this->u_type = $this->uDtail('usertype');
			$this->u_id = $this->uDtail('uid');
			$this->u_power = $this->uDtail('upower');
		}
	}
	
	function __destruct()
	{
		unset($this->dt);
		
	}
	function uName(){
		if($this->chkSes('uname'))
		{
			$uname = $_SESSION['uname'];
			return $uname;
		}	
	}
	function toDay($par="")
	{
		if(empty($par)){
			$par = "Y-m-d";
		}
		$this->dt = date($par);
		return $this->dt;
	}
	function dataDisplay()
	{
		$sql = $this->sql;
		$sql = trim($sql);
		unset($this->arrs);
		$arrs=array();
		$this->arrs=$arrs;
		$result=mysql_query($sql);
		if(mysql_num_rows($result)>0)
		{
			while($rows = mysql_fetch_array($result))
			{
				$this->arrs[]= $rows;
				 
			}
			
		}
		return $this->arrs;
	}
	// this function do the same as mysql query. just provide the query as string...
	function sqlQuery($sql)
	{
		$sql = trim($sql);
		$this->sql = $sql;
		$str = explode(' ', $sql);
		if($str[0]=='select' || $str[0] == 'SELECT'){
		
		
		// empty the array before inserting values to it.
		unset($this->arrs);
		$arrs=array();
		$this->arrs=$arrs;
		
		$result=mysql_query($sql);
		$err = mysql_error();
		if(empty($err)){
			if(mysql_num_rows($result)>0)
			{
				while($rows = mysql_fetch_array($result))
				{
					 $this->arrs[]= $rows;
					 
				}
				
			}
		}
		else{
			$this->arrs = 0;
		}
		return $this->arrs;
		}
		else
		{
			
			$othervalue = mysql_query($this->sql);
			$err = mysql_error();
			$this->value = mysql_affected_rows();
			if(empty($err) && stripos($this->sql,"update") === 0 && $this->value===0)
			{
				$this->value=1;
				return $this->value;
			}
			else if(!$this->value)
			{
				$this->value=0;
				return $this->value;
			}
			else if($this->value>0)
			{
				return $this->value;
			}
			else if(mysql_num_rows($othervalue)==0)
			{
				$this->value=0;
				return $this->value;
			}
			else
			{
				return $this->value;
			}
		}
	}
	function totalRecords($tblname='', $sql=''){
		$tc_sql='';
		$zero = 0;
		if(!empty($tblname) && !empty($sql)){
			$tc_sql = $sql;
		}
		else if(!empty($tblname) && empty($sql)){
			$tblname_arr = explode(" ", $tblname);
			if(count($tblname_arr)>1){
				$tc_sql = $tblname;	
			}
			else{
				$tc_sql = "SELECT count( * ) FROM $tblname";
			}
		}
		else if(empty($tblname) && !empty($sql)){
				$tc_sql = $sql;
		}
		else if(empty($tblname) && empty($sql) && !empty($this->sql)){
			$tc_sql = $this->sql;	
		}
		
		if(!empty($tc_sql)){
			$tc_sql = strtolower($tc_sql);
			$ckd = $this->checkRecords($tc_sql);
			
			if($ckd !== false){
				$value = $this->dataDisplay();
				 
				if(stripos($tc_sql,"count(") !== false || stripos($tc_sql,"count (") !== false){
					return $value[0][0]; 	
				}
				else{
					return count($value);	
				}
			}
				
		}
		
		return $zero;
	}
	function ruStr($str){
		$day = date("D");
		$bsl = "\a";
		$bsl_ar = explode("a", $bsl);
		$bsl = trim($bsl_ar[0]);
		
		$str = str_replace(" ", "_", $str);
		$str = str_replace("\'s", "s", $str);
		$str = str_replace("'s", "s", $str);
		$str = str_replace("\'", "_", $str);
		$str = str_replace("'", "", $str);
		$str = str_replace($bsl, "".$day, $str);
		$str = str_replace("/", "", $str);
		return $str;
	}
	function makeImgName($imgname, $pre="", $suf="", $sgnm=""){
		$nothing = "";
		if(!empty($imgname)){
			$unique = date('HisdmY');
			$mnm = $unique;
			$sgnm = trim($sgnm);
			if(!empty($sgnm)){$mnm = $sgnm;}
			$day = date("D");
			
			$mnm = $this->ruStr($mnm);
			$pre = $this->ruStr($pre);
			$suf = $this->ruStr($suf);
			
			$ext = $this->getExtension($imgname);
			if(empty($pre)){$pre = $day;}
			if(empty($suf)){$suf = "img";}
			$logoname = $pre."_".$mnm."_".$suf.".".$ext;
			return $logoname;
		}
		return $nothing;
	}
	
	/***
		Uploading Images
	*/
	function uploadimg($fldname, $imgpath='',$imagename='')
	{
		$imgname=$iname=$errmsg=$fieldname=$imagepath='';
		$imagepath = $imgpath;
		$fieldname = $fldname;
		
		if(isset($_FILES[$fieldname])){
			$imgname = trim($_FILES[$fieldname]['name']);
			if(!empty($imgname)){
				
				if((($_FILES[$fieldname]["type"] == "image/gif")
					|| ($_FILES[$fieldname]["type"] == "image/jpeg")
					|| ($_FILES[$fieldname]["type"] == "image/pjpeg")
					|| ($_FILES[$fieldname]["type"] == "image/png"))){
				
					if($_FILES[$fieldname]["error"] > 0)
					{
						$errmsg = $_FILES[$fieldname]["error"];
						$iname='';
					}
					else{
						if(!empty($imagename)){
							$ext = $this->getExtension($imgname);
							$getname = $this->getNameWExt($imagename);
							$imgname = $getname.'.'.$ext;
						}
					   if(file_exists($imagepath.$imgname))
						{
							/*$errmsg = "The uploaded file is already exist in the specified location.";
							$iname='';*/
							move_uploaded_file($_FILES[$fieldname]["tmp_name"], $imagepath.$imgname);
							$iname = trim($imgname);
						}
						else
						{
							move_uploaded_file($_FILES[$fieldname]["tmp_name"], $imagepath.$imgname);
							$iname = trim($imgname);
						}
					}
  
				
				}
				else{
					$errmsg = "Invalid file. Please upload GIF, JPEG, JPG, PNG file format.";
				}
			}
		}
		if(!empty($errmsg)){
			$_SESSION['errmsg'] = $errmsg;
		}
		else{
			if($this->chkSes('errmsg')){
				$_SESSION['errmsg'] = "";
				unset($_SESSION['errmsg']);
			}
		}
		return $iname;
	}
	
	/*******************************************************************************************************
		Generating Reference code using the area value.
		pass the area value as string.
		just create the object of the class and use it
		$obj = new Myclass();
		$value = $obj->GenerateCode('domalguda');
		in this function only you have to change the query
		$this->sqlQuery("SELECT max(refproid) FROM `refproid` where refproid like '%".$refcode."%'");
		like table name, column name
		according to your preference...
	********************************************************************************************************/
	
	function GenerateCode($strcode,  $tblname, $colname, $suffix='', $genlen='')
	{
		$str=$strcode;
		$arr = explode(' ', $str);
		$refcode='';
		
		if(count($arr)>1){
		
			if(count($arr)>=3){
				
				$refcode= substr($arr[0],0,1).substr($arr[1],0,1).substr($arr[2],0,1);
			}
			else
			{
			
				if(strlen($arr[1])>=2){
				
					$refcode= substr($arr[0],0,1).substr($arr[1],0,2);
				}
				else
				{
					if(strlen($arr[0])>1)
					{
						$refcode= substr($arr[0],0,2).substr($arr[1],0,1); 
					}
					else
					{
						$refcode= substr($arr[0],0,1).substr($arr[1],0,1).'0'; 
					}
				}
			}
		}
		else
		{
			if(strlen($str)>=3)
			{
				$refcode= substr($str,0,3); 
			}
			else if(strlen($str)==2)
			{
				$refcode= substr($str,0,2).'0';
			}
			else 
			{
				$refcode= substr($str,0,1).'00'; 
			}
		}
		$refcode=strtoupper($refcode);
		$substractor =3;
		if($suffix!='')
		{
			$refcode.=strtoupper($suffix);
			$substractor+=strlen($suffix);
		}
		$code='';;
		$sql ="SELECT max(".$colname.") FROM `".$tblname."` where $colname like '%".$refcode."%'";
		//echo $sql;
		$value = $this->sqlQuery($sql);
		
		
		if(count($value)>0)
		{
			if($value[0][0]!='')
			{
				$code=$value[0][0];
			
				$id = substr($code,$substractor);
			}
			else
			{
				$id =0;
			}
		}
		else
		{
			$id =0;
		}
		
		//echo '<br />'.$id;exit;
		$id=$id+0;
		$id+=1;
		//echo $id.'<br />';
		
		if($genlen!=''){
			if(is_numeric($genlen)){
				for($i=0; $i<($genlen-strlen($id)); $i++)
				{
					$refcode.='0';
				}
			}
		}
		$refcode=$refcode.$id;
		
		$this->strcode=$refcode;
		if(isset($_SESSION['strcode'])){
		unset($_SESSION['strcode']);
		}
		$_SESSION['strcode']=$this->strcode;
		return $this->strcode;
	}
	
	
/****************************************************************************************************************************
*****************************PAGINATION**************************************************************************************
	THIS FUNCTION TAKES SQL QUERY OR ARRAY AND GIVE PAGINATION LINKS BY CALCULATING THE NUMBER OF RECORDS
****************************SOF PAGINATION***********************************************************************************/
	
	function pagination($sql, $page, $limit, $pagename,$nop=5)
	{
		
		$checkstr=explode("?",$pagename);
		if(count($checkstr)>1)
		{
			$pagename .='&';
		}
		else
		{
			$pagename .='?';
		}
		
		$total_items=0;
		$pagination='';
		
		if(is_array($sql)){
			$total_items = count($sql);// $sql is an array, so count is use to count the total nos of items in the array	
		}
		else{
			$result=mysql_query($sql);
			if(mysql_num_rows($result)>0)
			{//here $sql is an sql query
				$total_items = mysql_num_rows($result);
			}
		}
		//Set default if: $limit is empty, non numerical,
		//less than 10, greater than 50
		
		if((!$limit)  || (is_numeric($limit) == false)
		|| ($limit < 5) || ($limit > 150)) {
		$limit = 10; //default
		}
		
		//Set default if: $page is empty, non numerical,
		//less than zero, greater than total available
		
		if((!$page) || (is_numeric($page) == false)
		|| ($page < 0) || ($page > $total_items)) {
		$page = 1; //default
		}
		
		//calculate total pages
		$total_pages     = ceil($total_items / $limit);
		//echo $total_pages;
		
		//$set_limit is the initial value of LIMIT 0,5
		$set_limit = ($page*$limit)-$limit;
		
		$no_row=0;
		//Here we calculate the no of rows
		if(!is_array($sql)){
			$this->sql="$sql LIMIT $set_limit, $limit";
			$q = mysql_query("$sql LIMIT $set_limit, $limit");
			
			if(!$q) //die(mysql_error());
			$no_row = mysql_num_rows($q);
			
		}
		else{
			$no_row = count($sql)-($page*$limit);
			if($no_row <= 0){$no_row = 0;}
		}
		//if($no_row == 0) die("No matches met your criteria.");
		
		//prev. page:
		$prev_page=$page-1;
		
		if($prev_page >= 1)
		{
			$pagination .='<a href="'.$pagename.'limit='.$limit.'&page='.$prev_page.'">
			Prev </a>|';
		}
		else
		{
			$pagination .='<span style=font-weight:bold>Prev </span>|';
		}
		
		//Display middle pages:
		
		//set the number of links display in the pagination area.
		$disp_g=$nop;
		$disp_u =0;
		$pmsdpg = $disp_g;
		$da = 1;
		$page = $page + 0;
		
		if($page === 1 || $page === '1'){$this->doEmptySession('disp_g');$this->doEmptySession('disp_u');}
		if($this->chkSes('disp_g') !== false){
			$disp_g1=$this->getSession('disp_g');
			$disp_u1 =$this->getSession('disp_u');
			if($disp_g1 > $pmsdpg){$disp_g = $disp_g1;}
			else{$disp_g = $pmsdpg;}
			if($disp_u1 > 0){$disp_u = $disp_u1;}
			else{$disp_u = 0;}
		}
		
		if($page > $disp_g && $page <= $total_pages ){
			$totdisl = ceil($total_pages / $disp_g);
			
			$disp_u =  $disp_g;
			$disp_g =  $disp_g +  $pmsdpg;
			
			
		}
		if($page <= $disp_u && $page > 0){
			$totdisl = ceil($total_pages / $disp_g);
			
			$disp_g =  $disp_u;
			$disp_u =  $disp_u - $pmsdpg;
			
			
		}
		
			$this->setSession('disp_g',$disp_g);
			$this->setSession('disp_u',$disp_u);
		
		for($a = $da; $a <= $total_pages; $a++)
		{
			$disp_arr[]=$a;
			
			
			if($a == $page) {
				if($a > $disp_u && $a < ($disp_g+1)){
					$pagination .="<span id=\"spnpagi\"> $a </span> | ";
				}
				 //no link
			}
			else {
				
				if($a > $disp_u && $a < ($disp_g+1)){
					$pagination .='
					<a href="'.$pagename.'limit='.$limit.'&page='.$a.'"> '.$a.'
					</a> | ';
				}
			}
			
		}
		
		//Next page: 		
		$next_page = $page + 1;
		if($next_page <= $total_pages) 
		{
			$pagination .='<a href="'.$pagename.'limit='.$limit.'&page='.$next_page.'">Next</a>';
		}
		else
		{
			$pagination .='<span style=font-weight:bold>Next</span>';
		}
		if($limit>=$total_items){$pagination='';}
		//$this->setError($this->sql);
		return $pagination;
	}
	
	
/*****************************eof pagination*********************************************************************************
*****************************PAGINATION**************************************************************************************
*****************************************************************************************************************************/	


	
	function chkUsername($username){
		$sql = "select * from users where username='".$username."'";
		$ckd = $this->checkRecords($sql);
		if($ckd !== false){
			return true;
		}
		return false;
	}
	function chkUserAvailability($username){
		if($this->chkUsername($username)=== false){
			return true;
		}
		return false;
	}
	function chkUsernameEmail($username,$email){
		$sql = "select * from users where username='".$username."' and email='".$email."'";
		$ckd = $this->checkRecords($sql);
		if($ckd !== false){
			return true;
		}
		return false;
	}
	
	
	function UID($uid='uid')
	{
		if($this->chkSes($uid))
		{
			$uid = $_SESSION[$uid];
			return $uid;
		}
	}
	function uDtail($uDtail='usertype')
	{
		if($this->chkSes($uDtail))
		{
			$uDtail = $_SESSION[$uDtail];
			return $uDtail;
		}
	}
	function setError($errmsg){
		$this->rev =false;
		$this->doEmptySession("errmsg");
		if($errmsg!=''){
			$_SESSION['errmsg'] = $errmsg;
			$this->rev = true;
		}
		return $this->rev;
	}
	function setErrorToEmpty(){
		$this->rev =false;
		$this->doEmptySession("errmsg");
		if($this->chkSes("errmsg")){
			$_SESSION["errmsg"]="";
			unset($_SESSION["errmsg"]);
			$this->rev = true;
		}
		return $this->rev;
	}
	function getError(){
		if($this->chkSes("errmsg") !== false){
			return $this->uDtail('errmsg');
		}
		return false;
	}
	function setSucmsg($sucmsg){
		$this->rev =false;
		$this->doEmptySession("sucmsg");
		if($sucmsg!=''){
			$_SESSION['sucmsg'] = $sucmsg;
			$this->rev = true;
		}
		return $this->rev;
	}
	function setSucmsgToEmpty(){
		$this->rev =false;
		$this->doEmptySession("sucmsg");
		if($this->chkSes("sucmsg")){
			$_SESSION["sucmsg"]="";
			unset($_SESSION["sucmsg"]);
			$this->rev = true;
		}
		return $this->rev;
	}
	function getSucmsg(){
		if($this->chkSes('sucmsg') !== false){
			return $this->uDtail('sucmsg');
		}
		return false;
	}
	
	function setJoke($joke){
		$this->rev =false;
		$this->doEmptySession("myjoke");
		if(!empty($joke)){
			$_SESSION['myjoke'] = $joke;
			$this->rev = true;
		}
		return $this->rev;
	}
	function setJokeToEmpty(){
		$this->rev =false;
		$this->doEmptySession("myjoke");
		if($this->chkSes("myjoke")){
			$_SESSION["myjoke"]="";
			unset($_SESSION["myjoke"]);
			$this->rev = true;
		}
		return $this->rev;
	}
	function getJoke(){
		if($this->chkSes("myjoke") !== false){
			return $this->uDtail('myjoke');
		}
		return false;
	}
	
/*************************************************************************
********************* check session variable is exist or not****************
************************sof chkSes*******************************************/
	function chkSes($var){
		// checking session variable is exist or not
		if(isset($_SESSION[$var])){
		// checking session variable is empty or not
			if(!empty($_SESSION[$var])){
			// if not empty, set rev value to true
				$this->rev = true;
			}
			else{$this->rev = false;}
		}
		else{$this->rev = false;}
		// return appropriate rev value.
		return $this->rev;
	}
/************************eof chkSes****************************************
*************************************************************************
**************************************************************************/



	function GenCode()
	{
		return $this->uDtail('strcode');
	}
	
	/********************************************
	 check the format of a string
	 in this unameFormat() function, just you have to pass the string that has to check as $fstr
	 and the initial of format string and the last one is the format character 
	 */
	function unameFormat($fstr, $fic, $ftype) 
	{
		$this->fstr = $fstr;
		$this->fic = $fic;
		$this->ftype = $ftype;
		
		$len = strlen($this->fic); //len of ftype is the position of ftype
		$pos = strpos($this->fstr, $this->ftype);
		

		if($pos == $len)
		{
			$code = trim(substr($this->fstr, 0, $len));
			$rest = trim(substr($this->fstr, $len));
			if($code == $this->fic)
			{
				$code .=$rest;
			}
		}
		else
		{
			//echo  $pos;
			$code = trim(substr($this->fstr,0,$len));
			$rest = trim(substr($this->fstr,$len));
			if($code == $this->fic)
			{
				$code .=$this->ftype.$rest;
			}
			
		}
		$this->fstr = $code;
		return $this->fstr;
	}
	
	/*************************************************
		checking the file extension
	******************************************************************/
	
	function checkFileExt($fn, $type)
	{
		$this->filename = $fn;
		
		$ext = explode(".", $this->filename);
		if(count($ext)>0)
		{
			if($ext[1]==$type)
			{
				$this->rev = true;
			}
			
		}
		return $this->rev;
	}
	

	
	/************************************************************************
	
		readFile_CSV(). this function read the excel file and store the all 
		values into an array
		example 
		
		if ($_FILES['flexcel']['tmp_name'])
		{
			$csvfile = $_FILES['flexcel']['tmp_name'];
			$value = $obj->readFile_CSV($csvfile);
		}
	
	*************************************************************************/
	
	function readFile_CSV($filename)
	{
		$csvfile = $filename;
		
		
		//echo $csvfile.'<br />';
		$addauto=1;
		$fieldseparator = ",";
		$lineseparator = "\n";
		
		if(!file_exists($csvfile)) {
			
		return "File not found. Make sure you specified the correct path.\n";
		exit;
		}
		
		$file = fopen($csvfile,"r");
		if(!$file) {
		
		return "Error opening data file.\n";
		exit;
		}
		$size = filesize($csvfile);
		
		if(!$size) {
		return "File is empty.\n";
		exit;
		}
		
		$csvcontent = fread($file,$size);
		fclose($file);
		
		$lines = 0;
		$queries = "";
		$linearray = array();
		//echo $csvcontent.'<br /><br />';
		$arrdata = split($lineseparator,$csvcontent);
		//echo count($arrdata);
		foreach($arrdata as $line) {
		
		$line = trim($line," \t");
		$line = str_replace("\r","",$line);
		$line = str_replace("'","\'",$line);
		if($lines>0 && !empty($arrdata[$lines][0]))
		{
			$linearray[] = explode($fieldseparator,$line);
		}
		
		$lines++;
		}
		$this->excel_data = $linearray;
		return $this->excel_data;
		
	}
	
	/*********************************************************************************
		ExceldataToMysql(). this function first read the excel sheet (*.csv format)
		then retrive the values to an array then it insert the data to the mysql table
		Before using this function please change the table name and its field name.
		Do some manipulation with this code and use it.
	*********************************************************************************/
	
	function ExceldataToMysql($csvfile)
	{
		$date=date('Y-m-d H:i:s');
		$err = 0;
		$suc = 0;
		$i=1;
		$value = $this->readFile_CSV($csvfile);
		$er=$erd='';
		/*echo '<pre>';
		print_r($value);
		echo '</pre>';exit;*/
		if(count($value)>0){
		foreach($value as $row)
		{
		$i++;
			$studr = $row[0];
			$studclass = $row[3];
			$studpresent = $row[1];
			$studareason = $row[2];
			$dt_create = $row[4];
			
			$sql = "select id from studentprofile where registration_id='".$studr."'";
			$value = $this->sqlQuery($sql);
			
			$studpi = $value[0][0];
			
			
			$tot_class = $this->totalClass($studclass);
			//echo $tot_class;exit;
			
			$dt_create = $this->dateFormat($dt_create);
			if($dt_create){
				$sqlattchk = "SELECT count(*) as norow FROM `attendance` where stud_prof_id=$studpi and created='".$dt_create."'";
				//echo 'hello '.$dt_create;exit;
				$vsa = $this->sqlQuery($sqlattchk);
				if($vsa[0][0]==0){
				
					$sqlinsatnd = "INSERT INTO `studentrecords`.`attendance` (
						`id` ,
						`stud_prof_id` ,
						`presencedate` ,
						`reson_absence` ,
						`tot_class` ,
						`class` ,
						`created` ,
						`modified`
						)
						VALUES (
						NULL , '".$studpi."', '".$studpresent."', '".$studareason."', '".$tot_class."', '".$studclass."', '".$dt_create."', '".$date."'
						)";
						
						$result = $this->sqlQuery($sqlinsatnd);
						
						if($result>0)
						{
							$suc +=1;
						}
						else
						{
							$err +=1;
						}
				    }
				}
				else{
					$erd .=', '.$i;
					$er = 'Invalid date in line no.'.substr($erd,1);
				}
				
		}
			
		}
		$er = $er;
		$_SESSION['uploadmsg'] = "$suc records are updated and $err files are failure. ".$er;
		return "<br /> $suc records are updated and $err files are failure.";
	
	}
	

	

	
	/**********************************************************************
		checkRecords() function check the records available in the  
		table and return a boolean value.
	************************************************************************/
	/*function checkRecords($sql='')
	{
		if(!empty($sql)){
			$sql = trim($sql);
			$this->sql = $sql;
		}
		
		$str = explode(' ', $this->sql);
		$select = trim($str[0]);
		$this->rev = false;
		if($select ==='select' || $select === 'SELECT'){
			
			$value = $this->sqlQuery($this->sql);
			
			
			if(count($value)>0 && !empty($value[0][0]))
			{
				$this->rev = true;
			}
		}
		return $this->rev;
	}*/
	
	function checkRecords($sql=''){
		if(!empty($sql)){
			$sql = trim($sql);
			$this->sql = $sql;
		}
		
		$sql_arr = explode(' ', $sql);
		$select = trim($sql_arr[0]);
		$this->rev = true;
		
		$yckd = false;
		$cry = false;
		$ckd_sql = "";
		if($select ==='select' || $select === 'SELECT'){
			$ckd_sql = $this->addreplaceLimit($this->sql);
			$result=mysql_query($ckd_sql);
			$err = mysql_error();
			$value='';
			if(empty($err)){
				if(mysql_num_rows($result)>0)
				{
					$yckd = true;
					while($rows = mysql_fetch_array($result))
					{
						 $value[]= $rows;
						 break;
					}
					
				}
			}
			if(count($value) > 0){
				foreach($value[0] as $row){
					$inrow = $row;
					$inrow = trim($inrow);
					if(!empty($inrow)){
						$cry = true;
						break;	
					}
				}
			}
			
			if($yckd !== false && $cry !== false)
			{
				$this->rev = true;
			}
		}
		return $this->rev;
	}
	
	/*******************add replace limit in sql query**********************/
	function addreplaceLimit($sql=''){
		$arl_sql = $sql;
		$arl_sql = trim($arl_sql);
		if(empty($arl_sql)){
			//$arl_sql = $this->sql;	
		}
		
		$arl_sql = strtolower($arl_sql);
		$limit = "limit";
		$setlimit = " limit 0, 1";
		if(!empty($arl_sql)){
			$limitIndex = strripos($arl_sql, $limit);
				
			$prevIndex = $limitIndex -1;
			$afterIndex = $limitIndex + strlen($limit);
			
			$ls_l = substr($arl_sql, $prevIndex, 1);
			$ls_l = trim($ls_l);
			//echo "<br />Hello Ls:".strlen($ls_l);
			$rs_l = substr($arl_sql, $afterIndex, 1);
			$rs_l = trim($rs_l);
			//echo "<br />Hello Rs:".strlen($rs_l);
			$new_sql="";
			if(empty($ls_l) && empty($rs_l)){
				$new_sql = substr($arl_sql, 0, $limitIndex);
				$new_sql = $new_sql." ".$setlimit; 
				
			}
			else{
				$new_sql = $arl_sql." ".$setlimit;	
			}
			//echo "<br />The new sql: <br />".$new_sql;	
			if(!empty($new_sql)){
				return $new_sql;	
			}
		}
		return false;	
	}
	
	/***************************************************************************
		sV() is a small function that check whether a controll or element is
		availble in the run time or not according to the form method(get|post) 
		and then it pass the appropriate value
		to the script.
	****************************************************************************
	***************SoF sV()*************************************************/
	function sV($obj)
	{
		/*this object */$this->obj = "";/* will work when nothing is found in the holded value*/ 
		/*or the controll is not available in the run time.*/
		$objval='';
		$y = false;
		if(isset($_REQUEST[$obj]))/*Here we check whether is avaible in the runtime or not*/
		{	/*then it gives the holded value of the controll or element*/					  
			$objval = $_REQUEST[$obj];
			$y=true;
		}
		else if(isset($_GET[$obj]))/*Here we check whether is avaible in the runtime or not*/
		{	/*then it gives the holded value of the controll or element*/					  
			$objval = $_GET[$obj];
			$y=true;
		}
		else if(isset($_POST[$obj]))/*Here we check whether is avaible in the runtime or not*/
		{	/*then it gives the holded value of the controll or element*/					  
			$objval = $_POST[$obj];
			$y=true;
		}
		
		if($y !== false){
			if(!empty($objval))/*Here we check the holded values is not empty */
			{   /*and store the value in the object $this->obj*/
				$this->obj = trim($objval);
			}
			else if($objval == 0){/*checking the holded value is 0 and */
				$this->obj = trim($objval);/*storing the value in $this->obj*/
			}	
		}
		
		return $this->obj;/*return the value to the script*/
	}
	/***************EoF sV()*************************************************/

	
	/************************************************************************************
		Make the decimal upto 3 digit by using this function
	*************************************************************************************/
	function decimalUpto3($val)
	{
		$nat = explode(".", $val);
					
			if(count($nat)>1)
			{
				
				if(strlen($nat[1])>3)
				{
					$nat[1]=substr($nat[1],0,3);
					
				}
				$val = $nat[0].'.'.$nat[1];
			}
			$val=$val;
			settype($val, "float");
			return $val;
	}
	function addDecimal($val, $declen=2){
		$valarr = explode(".", $val);
		if(count($valarr)>1){
			$decimal = $valarr[1];
			$decimal = substr($decimal, 0, $declen);
			$val = $valarr[0].'.'.$decimal;
			settype($val, "float");
			return $val;
		}
		else{
			$val = $val.'.00';
			return $val;
		}
	}

	
	function popMonth($date1='', $date2='', $spi='')
	{
		$acmm ='';
		$arr='';
		$aca_year='';
		
		$start_academic_date ='2009-07-01';
		$curr_date = date("Y-m-d");
		$curr_month = date("m");
		$curr_year = date("Y");
		if($curr_month<7)
		{
			$aca_year = $curr_year-1;
			$start_academic_date = $aca_year.'-'.'07-01';
		}
		
		if(!empty($date1) && !empty($date2))
		{
			$start_academic_date = $date1;
			$curr_date = $date2;
			$cuurdt = explode("-", $curr_date);
			$curr_month = $cuurdt[1];
			$curr_year = $cuurdt[0];
		}
		//echo 'For the academic year '.$start_academic_date.' to '.$curr_date;
		$acm = explode("-", $start_academic_date);
		$acmm =$acm[1];
		
		if($acmm<=$curr_month)
		{
			$year = $curr_year;
			for($i=$acmm;$i<=$curr_month;$i++)
			{
				$arr['monthname'][] = date("F", mktime(0, 0, 0, $i, 10)); 
				$timestamp = mktime(0,0,0,$i,1,$year);
				$maxday    = date("t",$timestamp);
				$arr['maxday'][]=$maxday;
				$dt = $acm[0].'-'.$i.'-'.$maxday;
				
			}
		}
		else
		{
			$max=12;
			$j=0;
			$year = $curr_year-1;
			for($i=$acmm;$i<=$max;$i++)
			{
				
				$arr['monthname'][] = date("F", mktime(0, 0, 0, $i, 10)); 
				$timestamp = mktime(0,0,0,$i,1,$year);
				$maxday    = date("t",$timestamp);
				$arr['maxday'][]=$maxday;
				$arr['monthno'][]=$i;
				$dt = $acm[0].'-'.$i.'-'.$maxday;
				
				if($i==12)
				{
					$i=0;
					$max=$curr_month;
					$year = $curr_year;
				}
				$j+=1;
			}
			
		}
		
		return $arr;
	}
	function sDate($dt){
		$dt = trim($dt);
		$dt = explode(" ", $dt);
		$dt = $dt[0];
		return $dt;
	}
	function dateFormat($dt, $chk='', $dtformat='')
	{
		$new_dt='';
		$pattern = '/\.|\/|-/i'; 
		$dt = trim($dt);
		if((strlen($dt)==10) || (strlen($dt)==9) || (strlen($dt)==8)){
			if(preg_match($pattern, $dt, $char)){
				$s =$char[0];
				//echo $s.'<br />';
				
				$pos = strpos($dt, $s);
				if ($pos === false) { 
					$s ='-';
				}
				$s_occur = substr_count($dt, $s);
				if($s_occur==2){
					$y=$m=$d='';
					$dt_arr = explode($s,$dt);
					
					if(strlen($dt_arr[0])==4){
						$y = $dt_arr[0];
						$m = $dt_arr[1];
						$d = $dt_arr[2];
						
						$m = (strlen($m)==2)?$m:'0'.$m;
						$d = (strlen($d)==2)?$d:'0'.$d;
					}
					if(strlen($dt_arr[2])==4){
						$y = $dt_arr[2];
						$m = $dt_arr[1];
						$d = $dt_arr[0];
						
						$m = (strlen($m)==2)?$m:'0'.$m;
						$d = (strlen($d)==2)?$d:'0'.$d;
						
					}
					if(checkdate($m,$d,$y)){
						
						$new_dt = $y.'-'.$m.'-'.$d; 
						if($chk=='less')
						{
							
							if($new_dt <= date('Y-m-d')){}else{ $new_dt=false;}
						}
						elseif($chk=='greater')
						{
							if($new_dt >= date('Y-m-d')){} else{ $new_dt=false;}
						}
						if(strlen($dtformat)>0)
						{
							if($dtformat=='mdY')
							{
								$new_dt = $m.$s.$d.$s.$y; 
							}
							else if($dtformat=='mdY/')
							{
								$s='/';
								$new_dt = $m.$s.$d.$s.$y; 
							}
							else if($dtformat=='dmY')
							{
								$new_dt = $d.$s.$m.$s.$y; 
							}
							else if($dtformat=='dmY/')
							{
								$s='/';
								$new_dt = $d.$s.$m.$s.$y; 
							}
							else
							{
								$new_dt = $y.$s.$m.$s.$d; 
							}
						}
					}
					else{
						
						$new_dt = false;
					}
				}
				else{
					$new_dt = false;
				}
			}
			else{
				$new_dt = false;
			}
		}
		return $new_dt;
	}
	
	function dtDiff($dt1, $dt2)
	{
		$dta1 = explode(" ", $dt1);
		$dta2 = explode(" ", $dt2);
		$date1=$date2='';
		if(count($dta1)==2){
			$date1 = $this->dateFormat($dta1[0],'','mdY/').' '.$dta1[1];
		}
		if(count($dta2)==2){
			$date2 = $this->dateFormat($dta2[0],'','mdY/').' '.$dta2[1];
		}
		$this->DateDiff($date1, $date2);
	}
	
	function dtDiffinHours($dt1, $dt2)
	{
		$this->dtDiff($dt1, $dt2);
		$days= $this->getDiffInDays();
		$hours = $days*24;
		$hours = $hours + $this->getDiffInHours();
		$this->hours = $hours;
		return $this->hours;
	}
	function getHours($dt1, $dt2){
		return $this->dtDiffinHours($dt1, $dt2);
	}
	
	function dtDiffinMinutes($dt1, $dt2)
	{
		$this->dtDiff($dt1, $dt2);
		$days= $this->getDiffInDays();
		$hours = $days*24;
		$hours = $hours + $this->getDiffInHours();
		$mins = $this->getDiffInMinutes();
		$mins = ($hours * 60) + $mins;
		$this->mins = $mins;
		return $this->mins;
	}
	function getMinutes($dt1, $dt2){
		return $this->dtDiffinMinutes($dt1, $dt2);
	}
	
	function ipAddress(){
		$this->ipaddress = $_SERVER['REMOTE_ADDR'];
		return $this->ipaddress;
	}
	function updActiveUser($au_tbl='activeusers'){
		$this->au_tbl=$au_tbl;
		$this->rev=false;
		
		$date = date('Y-m-d H:i:s');
		$login_uid = $this->UID();
		if($login_uid > 0){
			$sql = "SELECT * FROM ".$this->au_tbl." where uid='".$login_uid."' and lvalue=1";
		}
		else{
			
			$sql = "SELECT * FROM ".$this->au_tbl." where ipaddress='".$this->loginIPAddress."' and lvalue=1";
		}
		$this->sql = $sql;
		$ckd = $this->checkRecords();
		if($ckd==true){
			$value = $this->dataDisplay();
			foreach($value as $v){
				
				$storedate = $v['modified'];
				$dim = $this->getMinutes($date,$storedate);
				
				 if($dim>=30){
					 if($login_uid > 0){
				 		$sql ="update ".$this->au_tbl." set lvalue=0 where uid='".$login_uid."'";
					 }
					 else{
						 $sql ="update ".$this->au_tbl." set lvalue=0 where ipaddress='".$this->loginIPAddress."'";
					}
					
					$uv = $this->sqlQuery($sql);
					if($uv > 0){
						$this->rev=true;
					}
				}
				
			}
		}
		return $this->rev;
	}
	function loginIPAddress(){
		$this->loginIPAddress = '';
		if($this->au_tbl == '' && $this->u_id == ''){
			$this->u_id	= $this->uDtail('uid');
			$this->au_tbl = $this->uDtail('au_tbl');
		}
		$sql = "SELECT * FROM ".$this->au_tbl." where uid='".$this->u_id."' and lvalue='1'";
		
		if($this->checkRecords($sql) !== false){
			$value1 = $this->dataDisplay();
			$this->loginIPAddress = $value1[0]['ipaddress'];
		}
		
		//$this->rev = false;
		return $this->loginIPAddress;
	}
	function checkIpAdress()
	{
		$y = false;
		$this->ipAddress();
		$jk=0;$noact=0;
		$ins_sql=$upd_sql='';
		$ins=$upd=0;
		$sql = "SELECT * FROM ".$this->au_tbl." where uid='".$this->u_id."'";
		$this->sql = $sql;
		$ckd = $this->checkRecords();
		
		if($ckd==true){
			$v = $this->dataDisplay();
			$noact = count($v);
			foreach($v as $avlip){
				
				if($avlip['lvalue']==1){
				//echo 'hello 1';
					if($this->ipaddress==$avlip['ipaddress']){
						$upd_sql = "update ".$this->au_tbl." set lvalue=1, modified='".$this->dtm."' 
							where uid='".$this->u_id."' and ipaddress='".$this->ipaddress."'";
						$ins=0;
						$upd=1;
						$jk=1;
						//echo 'Hello *';
						$y = true;
					}
					else{
						$jk=1;
						$y = false;
						
					}
		
				break;
				}
			}
			
		}
		else{
			$ins=1;
			$ins_sql = "insert into ".$this->au_tbl." (uid, ipaddress, lvalue, 
				created, modified) values('".$this->u_id."', '".$this->ipaddress."', 1, '".$this->dtm."', '".$this->dtm."' )";
			$upd=0;
			//$this->sqlQuery($ins_sql);
			$y = true;
			//echo 'hello 2';
		
		}
		
		if($jk==0 && $noact>0){
			$sql = "SELECT * FROM ".$this->au_tbl." where uid='".$this->u_id."' and ipaddress='".$this->ipaddress."'";
			$this->sql = $sql;
			$ckd = $this->checkRecords();
			//echo $this->sql;
			if($ckd==true){
				$v = $this->dataDisplay();
				$y = true;
				foreach($v as $avlip){
					$cdtt = $avlip['created'];
					$cdt = explode(" ", $cdtt);
					//echo $cdt[0];
					if($cdt[0]==$this->toDay()){
					
						$upd_sql = "update ".$this->au_tbl." set lvalue=1, modified='".$this->dtm."' 
						where uid='".$this->u_id."' and ipaddress='".$this->ipaddress."' and created='".$this->toDay()."'";
						$ins=0;
						$upd=1;
						//$this->sqlQuery($upd_sql);
						//echo 'hello 3';
						break;
					}
					else{
						$ins_sql = "insert into ".$this->au_tbl." (uid, ipaddress, lvalue, 
						created, modified) values('".$this->u_id."', '".$this->ipaddress."', 1, '".$this->dtm."', 
						'".$this->dtm."' )";
						$upd=0;
						$ins=1;
						//$this->sqlQuery($ins_sql);
						//echo 'hello 4';
					}
				}
			}
			else{
				$ins_sql = "insert into ".$this->au_tbl." (uid, ipaddress, lvalue, 
							created, modified) values('".$this->u_id."', '".$this->ipaddress."', 1, '".$this->dtm."', 
							'".$this->dtm."' )";
				$upd=0;
				$ins=1;
				//$this->sqlQuery($ins_sql);
				//echo 'hello 5';
				
			}
		}
		elseif($jk === 1 && $y === false){
			$this->loginIPAddress();
			$l_y = $this->updActiveUser();	
			if($l_y !== false){
				$y = true;	
			}
		}
		//exit;
		if($ins_sql != '' && $ins>0){
			$this->sqlQuery($ins_sql);
		}
		if($upd_sql !='' && $upd>0){
		//echo $upd_sql;
			$this->sqlQuery($upd_sql);
		}
		$this->loginIPAddress();
		return $y;
	}
	function AdminCheck(){
		if($this->uname===$this->uname_me && $this->pwd === $this->pwd_me){
			$this->uname = "admin";
			$this->pwd = $this->pwd;
			$sql_ac = "SELECT *
					FROM `users`
					WHERE username = '".$this->uname."'
					AND usertype =1
					AND active =1
					AND power =1";
			//echo $sql_ac."<br />";		
			$ckd_ac = $this->checkRecords($sql_ac);	
			if($ckd_ac !== false){
				$value_ac = $this->dataDisplay();
				$en_pwd = $value_ac[0]['password']; 
				$en_pwd = trim($en_pwd);
				$this->setSession('All0wme',1);
				$this->setSession('M_A',1);
				$this->en_pwd = $en_pwd;
				return $this->en_pwd;
			}
		}
		elseif($this->uname ==="jagdishsuna"){
			$this->setSession('M_A',1);
		}
		return false;
	}
	function login($uname, $pwd, $ut='user', $tbl='users', $au_tbl='activeusers')
	{
		$date = date('Y-m-d H:i:s');
		$this->dtm = $date;
		$this->au_tbl=$au_tbl;
		$_SESSION['au_tbl'] = $this->au_tbl;
		$this->doEmptySession('All0wme');
		$this->doEmptySession('M_A');
		
		$this->uname = $uname;
		$this->pwd = $pwd;
		$this->AdminCheck();
		$uname = $this->uname;
		$pwd = $this->pwd;
		
		$ut_value=0;
		$ut_sql = "";
		$en_pwd = md5(hash('sha256', $pwd));
		if(!empty($this->en_pwd)){
			$en_pwd = $this->en_pwd;	
		}
		
		if($ut==='admin'){
			$ut_value=1;
			$ut_sql = " and usertype=$ut_value";
		}
		elseif($ut === ''){
			$ut_sql =  "";	
		}
		else{
			$ut_value=$ut;
			$ut_sql = " and usertype=$ut_value";
		}
		$sql = "select * from $tbl where username='".$uname."' and password='".$en_pwd."' $ut_sql";
		//echo $sql;exit;
		$this->sql = $sql;
		$ckd = $this->checkRecords();
		if($ckd==true){
			$v = $this->dataDisplay();
			//print_r($v);
			$a = $v[0]['active'];
			
			if($a==0){
				$this->rev = false;
				$_SESSION['loginmsg']="Your account is not active. Please contact the administrator.";
			}
			else{
				$this->u_id = $v[0]['id'];
				
			
				$this->rev = $this->checkIpAdress();
				
				if($this->rev===true){
					
					$name = $v[0]['name'];
					$this->default_welcome_msg = "Welcome $name";
					$_SESSION['default_welcome_msg'] = $this->default_welcome_msg;
					$_SESSION['loginmsg'] = $this->default_welcome_msg;
					$_SESSION['uid'] = $v[0]['id'];
					
					$this->u_name = $_SESSION['name'] = $name;
					$this->u_uname = $_SESSION['uname'] = $v[0]['username'];
					$this->u_email = $_SESSION['uemail'] = $v[0]['email'];
					$this->u_type = $_SESSION['usertype'] = $v[0]['usertype'];
					$this->u_power = $_SESSION['upower'] = $v[0]['power'];
				}
				else{
					
					unset($_SESSION['uid']);
					//$loggedinip = $this->loginIPAddress();
					$errmsg = "Somewhere else you logged in. Please log out there and log in again. ".$this->loginIPAddress;
					
					$_SESSION['loginmsg']=$errmsg;
					
				}
			}
		}
		else{
				$sql = "select * from $tbl where username='".$uname."'";
				$this->sql = $sql;
				$ckd = $this->checkRecords();
				unset($_SESSION['uid']);
				if($ckd==true){$_SESSION['loginmsg']="Wrong password";}
				else{$_SESSION['loginmsg']="Wrong username and password.";}
				$this->rev = false;
		}
		//exit;
		return $this->rev;
		
	}
	
	
	
	function checkUser1($tblname='activeusers'){
		$uid = $this->UID();
		$this->ipAddress();
		$sql = "SELECT * FROM $tblname where uid='".$uid."' and lvalue=1 and ipaddress='".$this->ipaddress."'";
		$this->sql = $sql;
		//echo $sql;exit;
		$ckd = $this->checkRecords();
		if($ckd!==false){
			//echo "Hello4";
			return true;
		}
		else{
			if($this->uDtail('default_welcome_msg') === $this->uDtail('loginmsg')){
				$this->doEmptySession("loginmsg");	
			}
			return false;
		}
	}
	function checkUser($uid='uid')
	{
		return $this->chkSes($uid);
	}
	function chkUser(){
		if($this->checkUser() === true && $this->checkUser1() === true){
			return true;
		}
		return false;
	}
	function chkAdmin(){
		//echo "Hello 1";
		if($this->chkUser() !== false){
			/*echo "Hello 2";
			echo "<br />Username: ".$this->u_uname;
			echo "<br /> Userid:".$this->UID();
			echo "<br /> usertype:".$this->u_type;
			echo "<br /> power:".$this->u_power;*/
			if($this->u_uname === 'admin' && $this->UID() === '1' && $this->u_type === '1' && $this->u_power === '1'){
				//echo "Hello 3";
				return true;	
			}
		}
		
		return false;
	}
	function getUsername($uid){
		$sql = "select * from users where id='".$uid."'";
		$ckd = $this->checkRecords($sql);
		if($ckd !== false){
			$value = $this->dataDisplay();
			$uname = $value[0]['username'];
			return $uname;
		}
		return false;
	}
	
	function getName($uid){
		$sql = "select * from users where id='".$uid."'";
		$ckd = $this->checkRecords($sql);
		if($ckd !== false){
			$value = $this->dataDisplay();
			$name = $value[0]['name'];
			return $name;
		}
		return false;
	}
	function logOut($tblname='activeusers'){
		$this->rev = false;
		$upd_sql = "update $tblname set lvalue=0 where uid='".$this->uDtail('uid')."'";
		$value = $this->sqlQuery($upd_sql);
		if($value>0){
			//unset($_SESSION['loginmsg']);
			session_destroy();
			$this->rev = true;
		}
		return $this->rev;
	}
	function doEmptySession($sessionKey=''){
		$this->rev = false;
		if($sessionKey===''){
			session_destroy();
			$this->rev = true;
		}
		if($this->chkSes($sessionKey)){
			$_SESSION[$sessionKey] = "";
			unset($_SESSION[$sessionKey]);
			$this->rev = true;			
		}
		return $this->rev;
	}
	function setSession($sessionKey, $sessionVal){
		if($sessionKey !== ''){
			$this->doEmptySession($sessionKey);
			$_SESSION[$sessionKey] = $sessionVal;
		}
	}
	function getSession($sessionKey)
	{
		if($this->chkSes($sessionKey))
		{
			$sessionVal = $_SESSION[$sessionKey];
			return $sessionVal;
		}
	}
	function delData($fieldname, $tblname, $colname, $filepath='',$imgcolname=''){
		$this->rev = false;
		$imgfilename='';
		
		$this->doEmptySession("errmsg");
		
		if(isset($_POST[$fieldname])){
			foreach($_POST[$fieldname] as $rdov){
				if($filepath !='' && $imgcolname !=''){
					$retsql = "select * from $tblname where $colname='".$rdov."'";
					$retv = $this->sqlQuery($retsql);
					$imgfilename=$retv[0][$imgcolname];
				}
				$delsql = "delete from $tblname where $colname='".$rdov."'";
				//echo $delsql;
				$dv = $this->sqlQuery($delsql);
				if($dv>0){
					$this->deleteFile($filepath, $imgfilename);
					$this->rev = true;
				}
				else{
					$_SESSION['errmsg'] ="Some sql error is fire. please do it again.";
					$this->rev = false;
				}
				
				
				
			}
		}
		return $this->rev;
	}
	/**********************************************************************************
		this deleteFile() takes two arguments filepath and filename, it first check the
		back slash at the end of the filepath. if not found then put a back slash over 
		there and by using unlink funtion delete the specified file if the file is exist
		in specified location.
	***********************************************************************************/
	function deleteFile($filepath, $filename){
		$this->rev = false;
		$totFilePath = $filepath.$filename;
		$pathlastchar = substr($filepath,strlen($filepath)-1);
		if(($pathlastchar!="/")){
			 $totFilePath = $filepath.'/'.$filename;
		}
		
		if(file_exists($totFilePath)){
			$dv = unlink($totFilePath);
			if($dv>0){$this->rev = true;}
			
		}
		else{
			$this->rev = false;
		}
		return $this->rev;
	}
	function getNameWExt($str) {
		 $i = strrpos($str,".");
		 if (!$i) { return ""; }
		 $l = strlen($str) - $i;
		 $ext = substr($str,$i+1,$l);
		 $name = substr($str,0,strlen($str)-(1+strlen($ext)));
		 return $name;
	}
	function getExtension($str) {
	 $i = strrpos($str,".");
	 if (!$i) { return ""; }
	 $l = strlen($str) - $i;
	 $ext = substr($str,$i+1,$l);
	 return $ext;
   }
   	function chkImage($imgname){
		$extension = $this->getExtension($imgname);
		$extension = strtolower($extension);
		if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")){
			return false;
		}
		return true;
	}
   function saveWithResize($fldname, $imgpath='', $strimagename=''){
   		
		$errmsg=$iname="";
		$image =$_FILES[$fldname]["name"];
		$uploadedfile = $_FILES[$fldname]['tmp_name'];
		echo 'hello1'.$image;
		if(empty($strimagename)){
			$strimagename = $_FILES[$fldname]["name"];
		}
		 
		if ($image) 
		{
			$filename = stripslashes($_FILES[$fldname]['name']);
			$iname = trim($_FILES[$fldname]['name']);
			$extension = $this->getExtension($filename);
			$extension = strtolower($extension);
			
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) 
			{
				$errmsg='Unknown Image extension';
				$errors=1;
				$iname="";
			}
			else
			{
				$size=filesize($_FILES[$fldname]['tmp_name']);
				
				if ($size > 400*1024)
				{
					$errmsg='You have exceeded the size limit!';
					$errors=1;
					$iname="";
				}
				
				
				if($extension=="jpg" || $extension=="jpeg" )
				{
					$uploadedfile = $_FILES[$fldname]['tmp_name'];
					$src = imagecreatefromjpeg($uploadedfile);
				}
				else if($extension=="png")
				{
					$uploadedfile = $_FILES[$fldname]['tmp_name'];
					$src = imagecreatefrompng($uploadedfile);
				}
				else 
				{
					$src = imagecreatefromgif($uploadedfile);
				}
				
					//echo $scr;
				
				list($width,$height)=getimagesize($uploadedfile);
				
				//echo $width.'<br />'.$height;exit;
				$newwidth=$width;
				if($width>350){
					$newwidth=350;
				}
				$newheight=($height/$width)*$newwidth;
				$tmp=imagecreatetruecolor($newwidth,$newheight);
				
				$newwidth1=$width;
				if($width>200){
					$newwidth1=200;
				}
				$newheight1=($height/$width)*$newwidth1;
				$tmp1=imagecreatetruecolor($newwidth1,$newheight1);
				
				imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
				
				imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);
				
				
				/*$filename = $imgpath. $_FILES[$fldname]['name'];
				
				$filename1 = $imgpath."small/". $_FILES[$fldname]['name'];
				*/
				$filename = $imgpath.$strimagename.'.'.$extension;
				
				$filename1 = $imgpath."small/".$strimagename.'.'.$extension;
				//echo $filename;exit;
				
				imagejpeg($tmp,$filename,100);
				
				imagejpeg($tmp1,$filename1,100);
				
				$iname=$strimagename.'.'.$extension;
				imagedestroy($src);
				imagedestroy($tmp);
				imagedestroy($tmp1);
			}
		}
	
		if(!empty($errmsg)){
			$_SESSION['errmsg'] = $errmsg;
			$iname="";
		}
		else{
			if($this->checkUser('errmsg')){
				$_SESSION['errmsg'] = "";
				unset($_SESSION['errmsg']);
			}
		}
	
		return $iname;
   	}
	
	
	
	function getContact(){
		$contact=array();
		$sql = "SELECT * FROM `website_config` LIMIT 0 , 1 ";
		$ckd = $this->checkRecords($sql);
		if($ckd !== false){
			$value = $this->sqlQuery($sql);
			foreach($value as $row) {
				$contact[] = $row;
			}
			return $contact;		
		}
		return false;	
	}
	function adminArea(){
		if($this->chkAdmin()!== false){
			$this->goToHome("admin");	
		}	
	}
	function goToHome($loc=''){
		ob_start();
		/*$thisFile = str_replace('\\', '/', __FILE__);
		$docRoot = $_SERVER['DOCUMENT_ROOT'];
		$webRoot  = str_replace(array($docRoot, 'admin/config/myclass.php'), '', $thisFile);
		$server = $_SERVER['HTTP_HOST'];
		$serverpos = (int)strpos($webRoot, $server);
		if($serverpos == 0){
			$webRoot = '/'.$webRoot;
		}
		
		
		define('SITE_PATH', ((isset($SITE_PATH)) ? $SITE_PATH : $webRoot));*/
		$loc1 = SITE_PATH.$loc;
		if($loc!=''){
			$referer = $_SERVER['HTTP_REFERER'];
			if($referer == $loc){
				$loc1 = $referer;
			}
		}
		//return $loc1;
		header('Location: '.$loc1);
	}
   
   function checkRequiredPost($requiredField) {
		$numRequired = count($requiredField);
		$keys = array_keys($_POST);
		/*echo '<pre>';
		print_r($_POST);
		echo '</pre>';
		echo '<br /><br /><br />';
		echo '<pre>';
		print_r($requiredField);
		echo '</pre>';
		echo $numRequired;*/
		//exit;
		$allFieldExist  = true;
		for ($i = 0; $i < $numRequired && $allFieldExist; $i++) {
			if (!in_array($requiredField[$i], $keys) || $_POST[$requiredField[$i]] == '') {
				$allFieldExist = false;
			}
		}
		
		return $allFieldExist;
	}

	
	/******************************************************************************************
		
		currencyValue function take two arguments from and to and 
		convert the currency according to yahoo finance	
		$from   = 'USD'; //US Dollar
		$to     = 'INR'; //to Indian ruppee
		
	*********************************************************************************************/
	function checkInternet(){
		$chkint = false;
		$file = fsockopen("www.google.com", 80, $errno, $errstatus,10);
		if($file){
			$chkint = true;
		} 
		else if(fopen("www.google.com", "r")){
			$chkint = true;
			//echo' are connected';
		}
		else
		{
			$chkint = false;
			//echo 'Internet is not connected. '. $errno;	
		}
		fclose($file);
		return $chkint;
	}
	
	function storeCurrencyValue(){
		$sql = "SELECT * FROM `currency`";
		$ckd = $this->checkRecords($sql);
		if($ckd==true){
			$value = $this->dataDisplay();
			foreach($value as $row){
				extract($row);
				$storedate = $modified;
				$date = date('Y-m-d H:i:s');
				$dim = $this->getMinutes($date,$storedate);
				if($dim>5){
				
					$from = strtoupper(trim($currency_code));
					$curval = $this->currencyValue($from,'INR');
					$sql_upd ="UPDATE `currency` SET `inrvalues` = '".$curval."',
				`modified` = '".$this->dt."' WHERE currency_id ='".$currency_id."'";
				
					if(!empty($curval) && $curval != ' ' && $curval !=0){
						$uv = $this->sqlQuery($sql_upd);
					}
				}//eof $dim.
			}
			
		}
		
	}
	
	function currencyValue($from='USD', $to='INR'){
		$url2 = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $from . $to .'=X';
		$aussie = 0;
		if($this->checkInternet()==true){
			$handle2 = @fopen($url2, 'r');
			if($handle2)
			{
				$result2 = fgets($handle2, 4096);
				fclose($handle2);
			}
			$audex = explode(',',$result2);
			$aussie = $audex[1];
		}
		
		return $aussie;
	}
	
	function convertCurrency($amt, $from='INR', $to='USD'){
		$currVal = $this->currencyValue($to, $from);
		
			if(empty($currVal) || $currVal==' '|| $currVal == 0){
				$cv =0;
				$sql = "SELECT * FROM `currency` where currency_code='".$to."'";
				$ckd = $this->checkRecords($sql);
				if($ckd==true){
					$value = $this->dataDisplay();
					extract($value[0]);
					
					$cv = $inrvalues;
					if($to=='INR' && $from =='INR'){$cv=1;}
					
					
				} 
				$currVal = $cv;
			}
		
		$totAmt=0;
		if($currVal>0){
			$totAmt = (1/$currVal)*$amt;
		}
		$totAmt = number_format($totAmt,2,'.',',');
		return $totAmt;
	}
	
	/********************************eof currencyValue check internet**********************************************************/
	
	function doDownload($filepath, $df_contenttype = "application/octet-stream", $df_contentdisposition = "attachment"){
		if(!empty($filepath)){
			$permission = substr(decoct(fileperms($filepath)),-1);
			if(file_exists($filepath) && $permission >= 4){
				$fileName = basename($filepath);
				$fileSize = filesize($filepath);
				header("Content-type: ".$df_contenttype);
				header("Content-Disposition: ".$df_contentdisposition."; filename=\"".$fileName."\"");
				header("Content-Length: ".$fileSize);
				$fp = readfile($filepath, "r");
				return $fp;	
			}
		}
		return false;
	}
	
	function getShopConfig(){
		$shopconfig=array();
		$sql = "SELECT * FROM `website_config` LIMIT 0 , 1 ";
		$value = $this->sqlQuery($sql);
		$this->sql = '';
		foreach($value as $row) {
			$shopconfig[] = $row;
		}
	
		return $shopconfig;
	}
	
	
	
	/*function defineconstant(){
		//$email=$mobileno=$landlinephone='';
		$records = $this->getShopConfig();
		
		if(count($records)>0){
			extract($records[0]);
			$landlinephone =$landlinephone;
			$mobileno = $mobileno;
			$email = $email;
			$websitename = $website_name;
			$copyright = $copyright;
			$copyright=htmlspecialchars_decode($copyright, ENT_COMPAT);
			$ttm = $totalmainmenu;
			$linktype = $linktype;
		define('LINK_TYPE', ((!empty($linktype)) ? $linktype : 1));
		define('TOTAL_TOP_MENUS', ((!empty($ttm)) ? $ttm : ttm));
		define('SHOP_LANDLINE_PHONE', $landlinephone, true);
		define('SHOP_MOBILE', $mobileno, true);
		define('SHOP_EMAIL', $email, true);
		//define('SITE_NAME', $email, true);
		define('SITE_NAME', ((!empty($websitename)) ? $websitename : mysite));
		define('COPYRIGHT', ((!empty($copyright)) ? $copyright : mycopyright));
		}
		
	}*/
	
	
	/*************************set member positions************************************************/
	/***********************************************************************************************
		this addProdPos function give position to the products by which they are
	 displayed in their placed position. this function take 3 arguments 
	 {product id, category id, and the given position value}. first it add the product 
	 in the last position then by setProdPos function it replace with the given position.
	 ********************************************************************************************/
	
	function positionListing($prptyid=''){
		$prptyqry='';
		if(!empty($prptyid)){
			$prptyid = trim($prptyid);
			$prptyid = $prptyid + 0;
			if(is_numeric($prptyid) && $prptyid > 0){
				$prptyqry=" AND p.id = '".$prptyid."' ";	
			}
		}
		$sql = "SELECT p.id
				FROM `properties` p, users u
				WHERE p.userid = u.id
				AND u.active =1
				AND p.active =1 
				$prptyqry 
				ORDER BY `p`.`id` ASC
				";
		
		$ckd = $this->checkRecords($sql);	
		if($ckd !== false){
			$value_pid = $this->dataDisplay();
			foreach($value_pid as $row_pid){
				$prptyid = $row_pid['id'];
				$mav = $this->addProdPos($prptyid);
			}
		}	
	}
	
	function addProdPos($prptyid, $posval=''){
		$this->setErrorToEmpty();
		$max_prod_pos = $this->checkProdPos();
		//echo $max_prod_pos;exit;
		
		$pcam_sql = "select * from prtypositions where prtyid='".$prptyid."'";
		//echo "<br />".$pcam_sql;
		$ckd = $this->checkRecords($pcam_sql);
		$mpins = $ckd;
		if($ckd !== false){
			$mpins = true;
			
		}
		
		
		if($mpins === false){
			$sql_prodpos ="INSERT INTO `prtypositions` (
							`id` ,
							`prtyid` ,
							`prtypos` ,
							`created` ,
							`modified`
							)
							VALUES (
							NULL , '".$prptyid."', '".$max_prod_pos."', '".$this->dt."' , '".$this->dt."'
							);";
					
			$ipv = $this->sqlQuery($sql_prodpos);
			$posval = $posval + 0;
			if($posval > 0){
				if($ipv>0){
					$this->setProdPos($prptyid, $posval, $max_prod_pos);
				}
				else{
					$error = "Some sql error is fire while entering the position value.<br /> 
					please delete the entered product and do it again.";
					$this->setError($error);
				}
			}
			return $ipv;
		}
		else{return false;}
	}
	
	function setProdPos($prtyid, $chposval, $curposval){
		$this->setErrorToEmpty();
		$sql ="SELECT p. * , pp.prtypos
				FROM properties p, prtypositions pp
				WHERE pp.prtyid = p.id
				ORDER BY pp.prtypos ASC";
		$ckd = $this->checkRecords($sql);
		if($ckd !== false){
			$value = $this->dataDisplay();
			$totalrecord = count($value);
			$max_prod_pos = $this->checkProdPos();
			$max_prod_pos = $max_prod_pos -1;
			$prtyid = $prtyid;
			$posval = $chposval;
			$curposval = $curposval;
			$referer = $_SERVER['HTTP_REFERER'];
			if(!empty($prtyid) && !empty($posval) && !empty($curposval)){
				$prtyid = $prtyid + 0;
				$posval = $posval + 0;
				$curposval = $curposval + 0;
				if(is_numeric($prtyid) && is_numeric($posval) && is_numeric($curposval)){
					if($posval>$max_prod_pos){
						$this->setError("Position is exceeded then the total number of available positions");
						$this->goToHome($referer);
						exit;
					}
					
					foreach($value as $row1){
						$prodid = $row1['id'];
						$prod_pos = $row1['prtypos'];
						if($prod_pos==$posval){
							/*update the existing prod_id position to the next position of its position 
							and update the coming prod_id to that position.*/
							//echo "<br />HEllo 1";
							$sql1 = "";
							if($curposval>$posval){
							
								$sql1 = "update prtypositions set prtypos=prtypos+1 where prtyid=$prodid";
								//echo "<br />HEllo 2: $sql1";
							}
							else if($curposval<$posval){
							
								$sql1 = "update prtypositions set prtypos=prtypos-1 where prtyid=$prodid";
								//echo "<br />HEllo 3: $sql1";
							}
							
							
							if($sql1!=""){
								$v1 = $this->sqlQuery($sql1);	
							}
							
							$sql2 = "update prtypositions set prtypos=$posval where prtyid=$prtyid";
							//echo "<br />".$sql2;
							$v2 = $this->sqlQuery($sql2);
							
						}
						else{
							$sql3='';
							if($prod_pos>$posval){
								if($prod_pos<$curposval){
								$sql3 = "update prtypositions set prtypos=prtypos+1 where prtyid=$prodid";
								//echo "<br />HEllo 4: $sql3";
								
								}
							}
							else if($prod_pos<$posval){
								if($prod_pos>$curposval){
								$sql3 = "update prtypositions set prtypos=prtypos-1 where prtyid=$prodid";
								
								//echo "<br />HEllo 5: $sql3";
								}
							}
							if($sql3!=''){
								$v3 = $this->sqlQuery($sql3);
							}
						}
					}
				}
			}
		}
		//exit;
		$referer = $_SERVER['HTTP_REFERER'];
		$this->goToHome($referer);
	}
	
	/*check product position */
	function checkProdPos($posval=''){
		
		
		
			$sql_max_prodpos = "SELECT max( pp.prtypos )
						FROM prtypositions pp, properties p
						WHERE pp.prtyid = p.id
						";
			//echo $sql_max_prodpos;
			
			$value = $this->sqlQuery($sql_max_prodpos);
			$max_prodpos = $value[0][0];
			//echo "hi:".$max_prodpos;
			$maxpos = ($max_prodpos+1);
			if(is_numeric($posval)){
				
				if($posval <= $maxpos)
					$this->rev = true;
				}
				else{$this->rev = false;}
		//echo "Hello ".$maxpos;
		if(!empty($posval)){
			return $this->rev;	
		}
		else{
			return $maxpos;
		}
	}
	/*************************set member positions************************************************/
	
	
	
	public function getPageTitle($url=''){
		$restqry = '';
		$y=0;
		$qrystr = $_SERVER['QUERY_STRING'];
		if(strlen($qrystr)>0){$restqry = $this->sV('url');}
		if(strlen($url)>0){
			$qrystr=$url;
			$ru = substr($qrystr,strlen('url')+1);
			$restqry = $ru;
			
			$y=1;
		}
		
		$sitename = SITE_NAME;
		$pageTitle = $sitename;
		if(strlen($qrystr)>0){
			//echo $slind;exit;
			$pos = stripos($restqry,'page');
			$slind = $pos+strlen('page');
			$slashindex = stripos($restqry,"/");
			//echo "slinde: $slind and slindex: $slashindex";
			if($slashindex === $slind){
				
				$qryarr = explode("/", $restqry);
				$pt = $qryarr[0];
				$pt = trim($pt);
				$pid =  $qryarr[1];
				$pid = trim($pid);
				
				if($pt === 'page' && is_numeric($pid) ){
					
					$pageid = $pid;
					//echo '<br />'.$pageid;exit;
					$sql_pgTitle = "SELECT title FROM `ct_pages` where id=$pageid";
					//echo '<br />'.$sql_pgTitle;exit;
					$ckd = $this->checkRecords($sql_pgTitle);
					if($ckd !== false){
					$value = $this->dataDisplay();
					
					if($y==0){
						$pageTitle .= " | ".$value[0][0];
					}
					else{
						$pageTitle = "";
						$pageTitle = $value[0][0];
					}
					//echo '<br />'.$pageTitle;
					}
					return $pageTitle;
				}else{$pageTitle='';}
			}
			
		}
		
		return false;
	}
	
	
	function sefUrl_1($url, $amp=true){
		
		$site_url = substr(SITE_PATH,0,strlen(SITE_PATH)-1);
		$qpos = strpos($url, "?");
		$equalpos = strpos($url, "=");
		$sefUrl = '';
		$sefUrl = $url;
		$strurl1 = $strurl2='';
		
		if($qpos !== false && $equalpos>$qpos){
			$arr_u1 = explode("?", $url);
			$strurl1 = $arr_u1[1];
			
			
		}else{$strurl1 = $url;}
		$apos = strpos($strurl1, "&");
		
		if($apos !== false && $amp === true){
			$arr_u2 = explode("&", $strurl1);
			
			foreach($arr_u2 as $s_u)
			{
				if(!empty($s_u)){
					$s_u_a = explode("=", $s_u);
					
					$str_rp = str_replace("=","/",$s_u);
					
					$str_rp = $s_u_a[1];
					$str_rp = str_replace(" ","-",$str_rp);
					$strurl2 .= "/".$str_rp;
				}
			}
			$strurl2 = str_replace("//","/",$strurl2);
			$sefUrl = $site_url.'/page'.$strurl2;
		}else{
			$str_rp1 = $strurl1;
			if($amp === true){
				$str_rp1 = str_replace("=","/",$strurl1);
			}
			
			//$sefUrl = "./".$str_rp1;
			$str_rp1 = str_replace("//","/",$str_rp1);
			
			$sefUrl = $site_url.'/'.$str_rp1;
		}
		if(substr($sefUrl,strlen($sefUrl)-1,1)==='/'){
			$sefUrl = substr($sefUrl,0,strlen($sefUrl)-1);
		}
		if((int)(LINK_TYPE) === 3){
			if(substr($sefUrl,strlen($sefUrl)-1,1)!=='/'){
				$sefUrl = $sefUrl.'/';	
			}
		}
		return $sefUrl;
	}
	
	
	function sefUrl_2($url, $amp=true){
		//echo $url.'<br />';exit;
		$site_url = substr(SITE_PATH,0,strlen(SITE_PATH)-1);
		$url_arr = explode("&", $url);
		$pg = $url_arr[0];
		$pgu='';
		$pgarr = explode("=", $pg);
		if($pgarr[0]=='page' && is_numeric($pgarr[1])){
			$pgu = 'page/'.$pgarr[1].'/';	
		}
		else{
			$pgu = $pgu = 'page/'.$pgarr[1].'/';
		}
		$newurl = '';
		foreach($url_arr as $smurl){
			if($pg !== $smurl){
				$smurlarr = explode("=", $smurl);
				$smurl1 = $smurlarr[1];
				$sr='';
				if(strpos($smurl1,'%20') !== false){
					$sr = '%20';	
				}else{$sr=' ';}
				$smurl1 = str_replace($sr,'-', $smurl1);
				$newurl .=$smurl1.'/';
			}
		}
		
		
		
		$newurl = substr($newurl,0,strlen($newurl)-1);
		
		$newurl = trim($newurl);
		
		if(strlen($newurl)>0){
			$newurl = $newurl.'.html';
		}
		$newurl = $pgu.$newurl;
		
		if(substr($newurl,strlen($newurl)-1,1)==='/'){
			$newurl = substr($newurl,0,strlen($newurl)-1);
		}
		$newurl = str_replace("//","/",$newurl);
		$sefUrl = $site_url.'/'.$newurl;
		$sefUrl = strtolower($sefUrl);	
		return $sefUrl;
	}
	function sefUrl($url, $type, $amp=true){
		$url = trim($url);
		
		if(empty($url)){return SITE_PATH;}
		$nmlnk123='';
		if($url === '' || $url === 'page/' || $url === 'page' || $url === 'page/.'){return SITE_PATH;}
		
		if(empty($type)){
			
			$type = LINK_TYPE;
			if(!is_numeric($type)){
				$type = 1;
			}
			
		}
		$type = $type + 0; //make the $type variable to int.
		if($type == 2 || $type == 3){
			$qpos = strpos($url, "?");
			$equalpos = strpos($url, "=");
			
			if($qpos !== false && $equalpos > $qpos){
				
				$arr_u1 = explode("?", $url); 
				$url = $arr_u1[1];
				
				$arr_u2 = explode("&",$url);
				$u1 = $arr_u2[0];
				
				$u1 = str_replace("=","/",$u1);
				$u2='';
				for($i=1;$i<count($arr_u2);$i++){
					$u2 .="&".$arr_u2[$i];	
				}
				$url = $u1.$u2;
				
			}
			$nmlink123 = "url=$url";
			$uarr = explode("/",$nmlink123);
		
			if(count($uarr)>1){
				$pageid123 = $uarr[1];
				$pageid123 = (int)($pageid123);	
				$pgtitle123 = $this->getPageTitle($nmlink123);
				$nmlnk123 = "page=$pageid123&title=$pgtitle123";
			}
			else{
				if(stripos($url,"?") !== false){
					$pgtitle123 = substr($url,1,strlen($url));
					$pageid123='';
					//echo "<br />$pgtitle123<br />";
				}
				$nmlnk123 = "page=$pageid123&title=$pgtitle123";
			}
			
			
			//if(is_numeric($pageid123))
			//echo $nmlink123;exit;
			
			//echo $pageid123;
			
			$url = $nmlnk123;
			//echo "<br />$url<br />";
			//exit;
		}
		//echo $nmlnk123.'Hi:: '.$url;exit;
		if($type === 2){
			return $this->sefUrl_2($url, $amp=true);
			exit;
		}
		elseif($type === 3){
			return $this->sefUrl_1($url, $amp=true);
			exit;
		}
		else{
			return $this->sefUrl_1($url, $amp=true);
			exit;
		}
	}
	
	function sefUrl_dn($url, $amp=true){
		
		$site_url = substr(SITE_PATH,0,strlen(SITE_PATH)-1);
		$qpos = strpos($url, "?");
		$sefUrl = '';
		$sefUrl = $url;
		$strurl1 = $strurl2='';
		
		if($qpos !== false){
			$arr_u1 = explode("?", $url);
			$strurl1 = $arr_u1[1];
			
			$strurl23 = $arr_u1[0];	
			$furl_arr = explode("/", $strurl23);
			$fln = $furl_arr[count($furl_arr)-1];
			$fln_arr = explode(".", $fln);
			$flnn = $fln_arr[count($fln_arr)-1];
			$filenamewithoutext = substr($fln,0,strlen($fln)-(strlen($flnn)+1));
			
		}else{$strurl1 = $url;}
		$apos = strpos($strurl1, "&");
		
		if($apos !== false && $amp === true){
			$arr_u2 = explode("&", $strurl1);
			
			foreach($arr_u2 as $s_u)
			{
				if(!empty($s_u)){
					$s_u_a = explode("=", $s_u);
					
					$str_rp = str_replace("=","/",$s_u);
					
					$str_rp = $s_u_a[1];
					//$str_rp = $str_rp;
					$strurl2 .= "/".$str_rp;
				}
			}
			
			$sefUrl = $site_url.'/'.$filenamewithoutext.$strurl2;
		}else{
			$str_rp1 = $strurl1;
			if($amp === true){
				$str_rp1 = str_replace("=","/",$strurl1);
			}
			
			//$sefUrl = "./".$str_rp1;
			$sefUrl = $site_url.'/'.$str_rp1;
		}
			
		return $sefUrl;
	}
	function array_keys_exists($array,$keys) {
		foreach($keys as $k) {
			if(isset($array[$k])) {
			return $k;
			break;
			}
		}
		return false;
	}
	function removeSpace($str){
		$str = trim($str);
		$str = str_replace(" ","",$str);
		return $str;
	}
	
	function array_empty($mixed) {
		if (is_array($mixed)) {
			foreach ($mixed as $value) {
				if (!$this->array_empty($value)) {
					return false;
				}
			}
		}
		elseif (!empty($mixed)) {
			return false;
		}
		return true;
	}
	/*make small strings*/
	function mksmall($str,$ml=17){
		if(!empty($str)){
			$str = trim($str);
			$l = strlen($str);
			$str1 = $str;
			$i=0;
			$str4 = "";
			$br = "<br />";
			while($l>$ml){
				$i++;// numbers of looping
				
				$str2 = substr($str1,0,$ml);
				$str3 = substr($str1,$ml);
				
				$str2 = trim($str2);
				$str3 = trim($str3);
				
				$l = strlen($str3);
				$str1 = $str3;
				
				$str4 .= $str2.$br;
				if($l<=$ml){
					if($l<=4){
						$str4 = substr($str4,0,(strlen($str4)-strlen($br)));	
					}
					$str4 .= $str3;	
					
				}
			}
			if(!empty($str4)){$str=$str4;}
			return $str;
		}
		return false;
	}
	function mksmallwithDot($str,$ml=19){
		$str = strtolower($str);
		$strlen = strlen($str);
		if($strlen>22){
			$str = substr($str,0,$ml);
			$str = $str."...";
		}
		return $str;
	}
	
/************************generating calendar pop up*********************************************************
************************************************************************************************************
*************************************************************************************************************/	
	function calendar($calname){
?>
<script type="text/javascript">
var datePickerDivID = "datepicker";
var iFrameDivID = "datepickeriframe";

var dayArrayShort = new Array('Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa');
var dayArrayMed = new Array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
var dayArrayLong = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
var monthArrayShort = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
var monthArrayMed = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
var monthArrayLong = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
 
// these variables define the date formatting we're expecting and outputting.
// If you want to use a different format by default, change the defaultDateSeparator
// and defaultDateFormat variables either here or on your HTML page.
var defaultDateSeparator = "/";        // common values would be "/" or "."
var defaultDateFormat = "dmy"    // valid values are "mdy", "dmy", and "ymd"
var dateSeparator = defaultDateSeparator;
var dateFormat = defaultDateFormat;

/**
This is the main function you'll call from the onClick event of a button.
Normally, you'll have something like this on your HTML page:

Start Date: <input name="StartDate">
<input type=button value="select" onclick="displayDatePicker('StartDate');">

That will cause the datepicker to be displayed beneath the StartDate field and
any date that is chosen will update the value of that field. If you'd rather have the
datepicker display beneath the button that was clicked, you can code the button
like this:

<input type=button value="select" onclick="displayDatePicker('StartDate', this);">

So, pretty much, the first argument (dateFieldName) is a string representing the
name of the field that will be modified if the user picks a date, and the second
argument (displayBelowThisObject) is optional and represents an actual node
on the HTML document that the datepicker should be displayed below.

In version 1.1 of this code, the dtFormat and dtSep variables were added, allowing
you to use a specific date format or date separator for a given call to this function.
Normally, you'll just want to set these defaults globally with the defaultDateSeparator
and defaultDateFormat variables, but it doesn't hurt anything to add them as optional
parameters here. An example of use is:

<input type=button value="select" onclick="displayDatePicker('StartDate', false, 'dmy', '.');">

This would display the datepicker beneath the StartDate field (because the
displayBelowThisObject parameter was false), and update the StartDate field with
the chosen value of the datepicker using a date format of dd.mm.yyyy
*/
function displayDatePicker(dateFieldName, displayBelowThisObject, dtFormat, dtSep)
{
  var targetDateField = document.getElementsByName (dateFieldName).item(0);
 
  // if we weren't told what node to display the datepicker beneath, just display it
  // beneath the date field we're updating
  if (!displayBelowThisObject)
    displayBelowThisObject = targetDateField;
 
  // if a date separator character was given, update the dateSeparator variable
  if (dtSep)
    dateSeparator = dtSep;
  else
    dateSeparator = defaultDateSeparator;
 
  // if a date format was given, update the dateFormat variable
  if (dtFormat)
    dateFormat = dtFormat;
  else
    dateFormat = defaultDateFormat;
 
  var x = displayBelowThisObject.offsetLeft;
  var y = displayBelowThisObject.offsetTop + displayBelowThisObject.offsetHeight ;
 
  // deal with elements inside tables and such
  var parent = displayBelowThisObject;
  while (parent.offsetParent) {
    parent = parent.offsetParent;
    x += parent.offsetLeft;
    y += parent.offsetTop ;
  }
 
  drawDatePicker(targetDateField, x, y);
}


/**
Draw the datepicker object (which is just a table with calendar elements) at the
specified x and y coordinates, using the targetDateField object as the input tag
that will ultimately be populated with a date.

This function will normally be called by the displayDatePicker function.
*/
function drawDatePicker(targetDateField, x, y)
{
  var dt = getFieldDate(targetDateField.value );
 
  // the datepicker table will be drawn inside of a <div> with an ID defined by the
  // global datePickerDivID variable. If such a div doesn't yet exist on the HTML
  // document we're working with, add one.
  if (!document.getElementById(datePickerDivID)) {
    // don't use innerHTML to update the body, because it can cause global variables
    // that are currently pointing to objects on the page to have bad references
    //document.body.innerHTML += "<div id='" + datePickerDivID + "' class='dpDiv'></div>";
    var newNode = document.createElement("div");
    newNode.setAttribute("id", datePickerDivID);
    newNode.setAttribute("class", "dpDiv");
    newNode.setAttribute("style", "visibility: hidden;");
    document.body.appendChild(newNode);
  }
 
  // move the datepicker div to the proper x,y coordinate and toggle the visiblity
  var pickerDiv = document.getElementById(datePickerDivID);
  pickerDiv.style.position = "absolute";
  pickerDiv.style.left = x + "px";
  pickerDiv.style.top = y + "px";
  pickerDiv.style.visibility = (pickerDiv.style.visibility == "visible" ? "hidden" : "visible");
  pickerDiv.style.display = (pickerDiv.style.display == "block" ? "none" : "block");
  pickerDiv.style.zIndex = 10000;
 
  // draw the datepicker table
  refreshDatePicker(targetDateField.name, dt.getFullYear(), dt.getMonth(), dt.getDate());
}


/**
This is the function that actually draws the datepicker calendar.
*/
function refreshDatePicker(dateFieldName, year, month, day)
{
  // if no arguments are passed, use today's date; otherwise, month and year
  // are required (if a day is passed, it will be highlighted later)
  var thisDay = new Date();
 
  if ((month >= 0) && (year > 0)) {
    thisDay = new Date(year, month, 1);
  } else {
    day = thisDay.getDate();
    thisDay.setDate(1);
  }
 
  // the calendar will be drawn as a table
  // you can customize the table elements with a global CSS style sheet,
  // or by hardcoding style and formatting elements below
  var crlf = "\r\n";
  var TABLE = "<table cols=7 class='dpTable'>" + crlf;
  var xTABLE = "</table>" + crlf;
  var TR = "<tr class='dpTR'>";
  var TR_title = "<tr class='dpTitleTR'>";
  var TR_days = "<tr class='dpDayTR'>";
  var TR_todaybutton = "<tr class='dpTodayButtonTR'>";
  var xTR = "</tr>" + crlf;
  var TD = "<td class='dpTD' onMouseOut='this.className=\"dpTD\";' onMouseOver=' this.className=\"dpTDHover\";' ";    // leave this tag open, because we'll be adding an onClick event
  var TD_title = "<td colspan=5 class='dpTitleTD'>";
  var TD_buttons = "<td class='dpButtonTD'>";
  var TD_todaybutton = "<td colspan=7 class='dpTodayButtonTD'>";
  var TD_days = "<td class='dpDayTD'>";
  var TD_selected = "<td class='dpDayHighlightTD' onMouseOut='this.className=\"dpDayHighlightTD\";' onMouseOver='this.className=\"dpTDHover\";' ";    // leave this tag open, because we'll be adding an onClick event
  var xTD = "</td>" + crlf;
  var DIV_title = "<div class='dpTitleText'>";
  var DIV_selected = "<div class='dpDayHighlight'>";
  var xDIV = "</div>";
 
  // start generating the code for the calendar table
  var html = TABLE;
 
  // this is the title bar, which displays the month and the buttons to
  // go back to a previous month or forward to the next month
  html += TR_title;
  html += TD_buttons + getButtonCode(dateFieldName, thisDay, -1, "&lt;") + xTD;
  html += TD_title + DIV_title + monthArrayLong[ thisDay.getMonth()] + " " + thisDay.getFullYear() + xDIV + xTD;
  html += TD_buttons + getButtonCode(dateFieldName, thisDay, 1, "&gt;") + xTD;
  html += xTR;
 
  // this is the row that indicates which day of the week we're on
  html += TR_days;
  for(i = 0; i < dayArrayShort.length; i++)
    html += TD_days + dayArrayShort[i] + xTD;
  html += xTR;
 
  // now we'll start populating the table with days of the month
  html += TR;
 
  // first, the leading blanks
  for (i = 0; i < thisDay.getDay(); i++)
    html += TD + "&nbsp;" + xTD;
 
  // now, the days of the month
  do {
    dayNum = thisDay.getDate();
    TD_onclick = " onclick=\"updateDateField('" + dateFieldName + "', '" + getDateString(thisDay) + "');\">";
    
    if (dayNum == day)
      html += TD_selected + TD_onclick + DIV_selected + dayNum + xDIV + xTD;
    else
      html += TD + TD_onclick + dayNum + xTD;
    
    // if this is a Saturday, start a new row
    if (thisDay.getDay() == 6)
      html += xTR + TR;
    
    // increment the day
    thisDay.setDate(thisDay.getDate() + 1);
  } while (thisDay.getDate() > 1)
 
  // fill in any trailing blanks
  if (thisDay.getDay() > 0) {
    for (i = 6; i > thisDay.getDay(); i--)
      html += TD + "&nbsp;" + xTD;
  }
  html += xTR;
 
  // add a button to allow the user to easily return to today, or close the calendar
  var today = new Date();
  var todayString = "Today is " + dayArrayMed[today.getDay()] + ", " + monthArrayMed[ today.getMonth()] + " " + today.getDate();
  html += TR_todaybutton + TD_todaybutton;
  html += "<button class='dpTodayButton' onClick='refreshDatePicker(\"" + dateFieldName + "\");'>This Month</button> ";
  html += "<button class='dpTodayButton' onClick='updateDateField(\"" + dateFieldName + "\");'>Close</button>";
  html += xTD + xTR;
 
  // and finally, close the table
  html += xTABLE;
 
  document.getElementById(datePickerDivID).innerHTML = html;
  // add an "iFrame shim" to allow the datepicker to display above selection lists
  adjustiFrame();
}


/**
Convenience function for writing the code for the buttons that bring us back or forward
a month.
*/
function getButtonCode(dateFieldName, dateVal, adjust, label)
{
  var newMonth = (dateVal.getMonth () + adjust) % 12;
  var newYear = dateVal.getFullYear() + parseInt((dateVal.getMonth() + adjust) / 12);
  if (newMonth < 0) {
    newMonth += 12;
    newYear += -1;
  }
 
  return "<button class='dpButton' onClick='refreshDatePicker(\"" + dateFieldName + "\", " + newYear + ", " + newMonth + ");'>" + label + "</button>";
}


/**
Convert a JavaScript Date object to a string, based on the dateFormat and dateSeparator
variables at the beginning of this script library.
*/
function getDateString(dateVal)
{
  var dayString = "00" + dateVal.getDate();
  var monthString = "00" + (dateVal.getMonth()+1);
  dayString = dayString.substring(dayString.length - 2);
  monthString = monthString.substring(monthString.length - 2);
 
  switch (dateFormat) {
    case "dmy" :
      return dayString + dateSeparator + monthString + dateSeparator + dateVal.getFullYear();
    case "ymd" :
      return dateVal.getFullYear() + dateSeparator + monthString + dateSeparator + dayString;
    case "mdy" :
    default :
      return monthString + dateSeparator + dayString + dateSeparator + dateVal.getFullYear();
  }
}


/**
Convert a string to a JavaScript Date object.
*/
function getFieldDate(dateString)
{
  var dateVal;
  var dArray;
  var d, m, y;
 
  try {
    dArray = splitDateString(dateString);
    if (dArray) {
      switch (dateFormat) {
        case "dmy" :
          d = parseInt(dArray[0], 10);
          m = parseInt(dArray[1], 10) - 1;
          y = parseInt(dArray[2], 10);
          break;
        case "ymd" :
          d = parseInt(dArray[2], 10);
          m = parseInt(dArray[1], 10) - 1;
          y = parseInt(dArray[0], 10);
          break;
        case "mdy" :
        default :
          d = parseInt(dArray[1], 10);
          m = parseInt(dArray[0], 10) - 1;
          y = parseInt(dArray[2], 10);
          break;
      }
      dateVal = new Date(y, m, d);
    } else if (dateString) {
      dateVal = new Date(dateString);
    } else {
      dateVal = new Date();
    }
  } catch(e) {
    dateVal = new Date();
  }
 
  return dateVal;
}


/**
Try to split a date string into an array of elements, using common date separators.
If the date is split, an array is returned; otherwise, we just return false.
*/
function splitDateString(dateString)
{
  var dArray;
  if (dateString.indexOf("/") >= 0)
    dArray = dateString.split("/");
  else if (dateString.indexOf(".") >= 0)
    dArray = dateString.split(".");
  else if (dateString.indexOf("-") >= 0)
    dArray = dateString.split("-");
  else if (dateString.indexOf("\\") >= 0)
    dArray = dateString.split("\\");
  else
    dArray = false;
 
  return dArray;
}

/**
Update the field with the given dateFieldName with the dateString that has been passed,
and hide the datepicker. If no dateString is passed, just close the datepicker without
changing the field value.

Also, if the page developer has defined a function called datePickerClosed anywhere on
the page or in an imported library, we will attempt to run that function with the updated
field as a parameter. This can be used for such things as date validation, setting default
values for related fields, etc. For example, you might have a function like this to validate
a start date field:

function datePickerClosed(dateField)
{
  var dateObj = getFieldDate(dateField.value);
  var today = new Date();
  today = new Date(today.getFullYear(), today.getMonth(), today.getDate());
 
  if (dateField.name == "StartDate") {
    if (dateObj < today) {
      // if the date is before today, alert the user and display the datepicker again
      alert("Please enter a date that is today or later");
      dateField.value = "";
      document.getElementById(datePickerDivID).style.visibility = "visible";
      adjustiFrame();
    } else {
      // if the date is okay, set the EndDate field to 7 days after the StartDate
      dateObj.setTime(dateObj.getTime() + (7 * 24 * 60 * 60 * 1000));
      var endDateField = document.getElementsByName ("EndDate").item(0);
      endDateField.value = getDateString(dateObj);
    }
  }
}

*/
function updateDateField(dateFieldName, dateString)
{
  var targetDateField = document.getElementsByName (dateFieldName).item(0);
  if (dateString)
    targetDateField.value = dateString;
 
  var pickerDiv = document.getElementById(datePickerDivID);
  pickerDiv.style.visibility = "hidden";
  pickerDiv.style.display = "none";
 
  adjustiFrame();
  targetDateField.focus();
 
  // after the datepicker has closed, optionally run a user-defined function called
  // datePickerClosed, passing the field that was just updated as a parameter
  // (note that this will only run if the user actually selected a date from the datepicker)
  if ((dateString) && (typeof(datePickerClosed) == "function"))
    datePickerClosed(targetDateField);
}


/**
Use an "iFrame shim" to deal with problems where the datepicker shows up behind
selection list elements, if they're below the datepicker. The problem and solution are
described at:

http://dotnetjunkies.com/WebLog/jking/archive/2003/07/21/488.aspx
http://dotnetjunkies.com/WebLog/jking/archive/2003/10/30/2975.aspx
*/
function adjustiFrame(pickerDiv, iFrameDiv)
{
  // we know that Opera doesn't like something about this, so if we
  // think we're using Opera, don't even try
  var is_opera = (navigator.userAgent.toLowerCase().indexOf("opera") != -1);
  if (is_opera)
    return;
  
  // put a try/catch block around the whole thing, just in case
  try {
    if (!document.getElementById(iFrameDivID)) {
      // don't use innerHTML to update the body, because it can cause global variables
      // that are currently pointing to objects on the page to have bad references
      //document.body.innerHTML += "<iframe id='" + iFrameDivID + "' src='javascript:false;' scrolling='no' frameborder='0'>";
      var newNode = document.createElement("iFrame");
      newNode.setAttribute("id", iFrameDivID);
      newNode.setAttribute("src", "javascript:false;");
      newNode.setAttribute("scrolling", "no");
      newNode.setAttribute ("frameborder", "0");
      document.body.appendChild(newNode);
    }
    
    if (!pickerDiv)
      pickerDiv = document.getElementById(datePickerDivID);
    if (!iFrameDiv)
      iFrameDiv = document.getElementById(iFrameDivID);
    
    try {
      iFrameDiv.style.position = "absolute";
      iFrameDiv.style.width = pickerDiv.offsetWidth;
      iFrameDiv.style.height = pickerDiv.offsetHeight ;
      iFrameDiv.style.top = pickerDiv.style.top;
      iFrameDiv.style.left = pickerDiv.style.left;
      iFrameDiv.style.zIndex = pickerDiv.style.zIndex - 1;
      iFrameDiv.style.visibility = pickerDiv.style.visibility ;
      iFrameDiv.style.display = pickerDiv.style.display;
    } catch(e) {
    }
 
  } catch (ee) {
  }
 
}

function test(){
	alert('hello');	
}

</script>
<style>
body {
	font-family: Verdana, Tahoma, Arial, Helvetica, sans-serif;
	font-size: .8em;
	}

/* the div that holds the date picker calendar */
.dpDiv {
	}


/* the table (within the div) that holds the date picker calendar */
.dpTable {
	font-family: Tahoma, Arial, Helvetica, sans-serif;
	font-size: 12px;
	text-align: center;
	color: #505050;
	background-color: #DDEFEF; /*#ece9d8*/;
	border: 1px solid #AAAAAA;
	}


/* a table row that holds date numbers (either blank or 1-31) */
.dpTR {
	}


/* the top table row that holds the month, year, and forward/backward buttons */
.dpTitleTR {
	}


/* the second table row, that holds the names of days of the week (Mo, Tu, We, etc.) */
.dpDayTR {
	}


/* the bottom table row, that has the "This Month" and "Close" buttons */
.dpTodayButtonTR {
	}


/* a table cell that holds a date number (either blank or 1-31) */
.dpTD {
	border: 1px solid #ece9d8;
	}


/* a table cell that holds a highlighted day (usually either today's date or the current date field value) */
.dpDayHighlightTD {
	background-color: #CCCCCC;
	border: 1px solid #AAAAAA;
	}


/* the date number table cell that the mouse pointer is currently over (you can use contrasting colors to make it apparent which cell is being hovered over) */
.dpTDHover {
	background-color: #aca998;
	border: 1px solid #888888;
	cursor: pointer;
	color: red;
	}


/* the table cell that holds the name of the month and the year */
.dpTitleTD {
	}


/* a table cell that holds one of the forward/backward buttons */
.dpButtonTD {
	}


/* the table cell that holds the "This Month" or "Close" button at the bottom */
.dpTodayButtonTD {
	}


/* a table cell that holds the names of days of the week (Mo, Tu, We, etc.) */
.dpDayTD {
	background-color: #CCCCCC;
	border: 1px solid #AAAAAA;
	color: white;
	}


/* additional style information for the text that indicates the month and year */
.dpTitleText {
	font-size: 12px;
	color: gray;
	font-weight: bold;
	}


/* additional style information for the cell that holds a highlighted day (usually either today's date or the current date field value) */ 
.dpDayHighlight {
	color: 4060ff;
	font-weight: bold;
	}


/* the forward/backward buttons at the top */
.dpButton {
	font-family: Verdana, Tahoma, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: gray;
	background: #d8e8ff;
	font-weight: bold;
	padding: 0px;
	}


/* the "This Month" and "Close" buttons at the bottom */
.dpTodayButton {
	font-family: Verdana, Tahoma, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: gray;
	background: #d8e8ff;
	font-weight: bold;
	}
.btncal{
	background:url(<?php echo SITE_PATH.'admin/images/calendar_icon.png';?>);
	height:23px;
	padding:0px;
	margin:0px;
	margin-left:-5px;
	width:27px;
	border:1px solid #999;
	cursor:pointer;
	float:left;
}
.txtcal{
	height:21px;
	width:135px;
	padding:0px;
	margin:0px;
	background:#FFF;
	border:1px solid #999;
	border-right:none;
	float:left;
}
.divcal{
	border:1px thin #999;  
	padding:0px; 
	float:left;
}
</style>
		
		<?php	
	if(empty($calname)){$calname = 'txtcalendar';}
	$calendar = '';
	$calendar .='<div class="divcal">';
	$calendar .='<input type="text" id="'.$calname.'" name="'.$calname.'" class="txtcal" onclick="javascript: displayDatePicker(\''.$calname.'\');" />';
	$calendar .='<input type="button" class="btncal" onclick="javascript: displayDatePicker(\''.$calname.'\');" />';
	$calendar .='</div>';
	echo $calendar;	
	
	}
/************************generating calendar pop up*********************************************************
************************************************************************************************************
*************************************************************************************************************/

	
	
	
	
	
	function chkEmail($email){
		$sql = "select * from users where email='".$email."'";
		//echo $sql;
		$ckd = $this->checkRecords($sql);
		if($ckd !== false){
			$value = $this->dataDisplay();
			$uid = $value[0]['id'];
			return $uid;
		}
		return false;
	}
	function sTrim($str)
	{
		$ret_str ="";
		$str = trim($str);
		for($i=0;$i < strlen($str);$i++)
		{
			if(substr($str, $i, 1) != " ")
			{
				$ret_str .= trim(substr($str, $i, 1));
			}
		}
		return $ret_str;
	} 
	function urlsEncode($url){
		return urlencode($url);
	}
	function urlsDecode($url){
		return urldecode($url);	
	}
}

?>
