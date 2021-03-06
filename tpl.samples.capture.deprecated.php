<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

if($saveSample) {
	//validations
	$lrCategory=validate($lrCategory);
	$lrEnvelopeNumber=validate($lrEnvelopeNumber);
	if($lrEnvelopeNumber=="Envelope #") {
		$lrEnvelopeNumber="";
	}
	$lrNumericID=validate($lrNumericID);
	
	$formNumber=validate($formNumber);
	$facilityID=validate($facilityID);
	$hubID=validate($hubID);
	$districtID=validate($districtID);

	$collectionDateDay=validate($collectionDateDay);
	$collectionDateMonth=validate($collectionDateMonth);
	$collectionDateYear=validate($collectionDateYear);
	$receiptDateDay=validate($receiptDateDay);
	$receiptDateMonth=validate($receiptDateMonth);
	$receiptDateYear=validate($receiptDateYear);
	$sampleTypeID=validate($sampleTypeID);

	$artNumber=validate($artNumber);
	$otherID=validate($otherID);
	$gender=validate($gender);
	$dateOfBirthDay=validate($dateOfBirthDay);
	$dateOfBirthMonth=validate($dateOfBirthMonth);
	$dateOfBirthYear=validate($dateOfBirthYear);
	$dateOfBirthAge=validate($dateOfBirthAge);
	$dateOfBirthIn=validate($dateOfBirthIn);
	$patientPhone=validate($patientPhone);

	$treatmentLast6Months=validate($treatmentLast6Months);
	$treatmentInitiationDateDay=validate($treatmentInitiationDateDay);
	$treatmentInitiationDateMonth=validate($treatmentInitiationDateMonth);
	$treatmentInitiationDateYear=validate($treatmentInitiationDateYear);
	$currentRegimenID=validate($currentRegimenID);
	$treatmentInitiationID=validate($treatmentInitiationID);
	$treatmentInitiationOther=validate($treatmentInitiationOther);
	$treatmentStatusID=validate($treatmentStatusID);
	$reasonForFailureID=validate($reasonForFailureID);
	$viralLoadTestingID=validate($viralLoadTestingID);
	$pregnant=validate($pregnant);
	$pregnantANCNumber=validate($pregnantANCNumber);
	$breastfeeding=validate($breastfeeding);
	$activeTBStatus=validate($activeTBStatus);
	$tbTreatmentPhaseID=validate($tbTreatmentPhaseID);
	$arvAdherenceID=validate($arvAdherenceID);

	if($viralLoadTestingIndication=="vlTestingRoutineMonitoring") {
		$routineMonitoringLastVLDateDay=validate($routineMonitoringLastVLDateDay);
		$routineMonitoringLastVLDateMonth=validate($routineMonitoringLastVLDateMonth);
		$routineMonitoringLastVLDateYear=validate($routineMonitoringLastVLDateYear);
		$routineMonitoringValue=validate($routineMonitoringValue);
		$routineMonitoringSampleTypeID=validate($routineMonitoringSampleTypeID);
		$routineMonitoringLastVLDate=0;
		$routineMonitoringLastVLDate="$routineMonitoringLastVLDateYear-$routineMonitoringLastVLDateMonth-$routineMonitoringLastVLDateDay";
	}
	
	if($viralLoadTestingIndication=="vlTestingRepeatTesting") {
		$repeatVLTestLastVLDateDay=validate($repeatVLTestLastVLDateDay);
		$repeatVLTestLastVLDateMonth=validate($repeatVLTestLastVLDateMonth);
		$repeatVLTestLastVLDateYear=validate($repeatVLTestLastVLDateYear);
		$repeatVLTestValue=validate($repeatVLTestValue);
		$repeatVLTestSampleTypeID=validate($repeatVLTestSampleTypeID);
		$repeatVLTestLastVLDate=0;
		$repeatVLTestLastVLDate="$repeatVLTestLastVLDateYear-$repeatVLTestLastVLDateMonth-$repeatVLTestLastVLDateDay";
	}
	
	if($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure") {
		$suspectedTreatmentFailureLastVLDateDay=validate($suspectedTreatmentFailureLastVLDateDay);
		$suspectedTreatmentFailureLastVLDateMonth=validate($suspectedTreatmentFailureLastVLDateMonth);
		$suspectedTreatmentFailureLastVLDateYear=validate($suspectedTreatmentFailureLastVLDateYear);
		$suspectedTreatmentFailureValue=validate($suspectedTreatmentFailureValue);
		$suspectedTreatmentFailureSampleTypeID=validate($suspectedTreatmentFailureSampleTypeID);
		$suspectedTreatmentFailureLastVLDate=0;
		$suspectedTreatmentFailureLastVLDate="$suspectedTreatmentFailureLastVLDateYear-$suspectedTreatmentFailureLastVLDateMonth-$suspectedTreatmentFailureLastVLDateDay";
	}
	
	//validate data
	$error=0;
	$error=checkFormFields("Form_Number::$formNumber Facility_Name::$facilityID Gender::$gender Sample_Type::$sampleTypeID Current_Regimen::$currentRegimenID Treatment_Status::$treatmentStatusID Viral_Load_Testing::$viralLoadTestingID Pregnancy_Status::$pregnant Breastfeeding_Status::$breastfeeding Viral_Load_Testing::$viralLoadTestingID Whether_Patient_has_been_on_Treatment_for_last_6_months::$treatmentLast6Months");

	//to be edited: ensure lrCategory, lrEnvelopeNumber and lrNumericID are unique if supplied
	if($lrCategory && $lrEnvelopeNumber && $lrEnvelopeNumber!="Envelope #" && $lrNumericID) {
		if(getDetailedTableInfo2("vl_samples","lrCategory='$lrCategory' and lrEnvelopeNumber='$lrEnvelopeNumber' and lrNumericID='$lrNumericID' limit 1","id")) {
			$error.="<br /><strong>Duplicate ".($lrCategory=="V"?"Location":"Rejection")." ID '$lrCategory"."$lrEnvelopeNumber/$lrNumericID'.</strong><br />The ".($lrCategory=="V"?"Location":"Rejection")." ID <strong>$lrCategory"."$lrEnvelopeNumber/$lrNumericID</strong> was entered on <strong>".getFormattedDate(getDetailedTableInfo2("vl_samples","lrCategory='$lrCategory' and lrEnvelopeNumber='$lrEnvelopeNumber' and lrNumericID='$lrNumericID' limit 1","created"))."</strong> by <strong>".getDetailedTableInfo2("vl_samples","lrCategory='$lrCategory' and lrEnvelopeNumber='$lrEnvelopeNumber' and lrNumericID='$lrNumericID' limit 1","createdby")."</strong> <a href=\"#\" onclick=\"iDisplayMessage('/verify/preview/".getDetailedTableInfo2("vl_samples","lrCategory='$lrCategory' and lrEnvelopeNumber='$lrEnvelopeNumber' and lrNumericID='$lrNumericID' limit 1","id")."/1/noedit/')\">Click here to see the entry</a>.<br /> Kindly input this record with an alternative ".($lrCategory=="V"?"Location":"Rejection")." ID.<br />";
		}
	}

	//to be activated: ensure lrCategory, lrEnvelopeNumber and lrNumericID are supplied
	if(!$lrCategory && (!$lrEnvelopeNumber || $lrEnvelopeNumber==$default_envelopeNumber) && !$lrNumericID) {
		$error.="<br /><strong>".($lrCategory=="V"?"Location":"Rejection")." ID is Missing</strong><br />Kindly provide a ".($lrCategory=="V"?"Location or Rejection":"Rejection or Location")." ID<br />";
	}
	
	//to be edited: ensure envelope number is valid
	if($lrCategory && $lrEnvelopeNumber && $lrEnvelopeNumber!="Envelope #" && $lrNumericID) {
		if(!preg_match("/^[0-9]{4}[\-]{1}[0-9]{1,5}$/",$lrEnvelopeNumber)) {
			$error.="<br /><strong>Incorrect Envelope Number '$lrEnvelopeNumber'.</strong><br />Correct Envelope Number Format is ".getFormattedDateYearShort($datetime).getFormattedDateMonth($datetime)."-00001.<br /> Kindly resubmit with a Valid Envelope Number.<br />";
		}
	}

	//ensure form number is unique
	if(getDetailedTableInfo2("vl_samples","formNumber='$formNumber' limit 1","id")) {
		$error.="<br /><strong>Duplicate Form Number '$formNumber'.</strong><br />The Form Number <strong>$formNumber</strong> was entered on <strong>".getFormattedDate(getDetailedTableInfo2("vl_samples","formNumber='$formNumber' limit 1","created"))."</strong> by <strong>".getDetailedTableInfo2("vl_samples","formNumber='$formNumber' limit 1","createdby")."</strong> <a href=\"#\" onclick=\"iDisplayMessage('/verify/preview/".getDetailedTableInfo2("vl_samples","formNumber='$formNumber' limit 1","id")."/1/noedit/')\">Click here to see the entry</a>.<br /> Kindly input this record with an alternative Form Number.<br />";
	}

	//ensure form number is numeric
	if(!is_numeric($formNumber)) {
		$error.="<br /><strong>Form Number '$formNumber' is Not Numeric.</strong><br />The Form Number should be Numeric i.e it should not contain alphanumeric characters e.g A-Z.<br />";
	}

	//ensure form number is valid
	if(!getDetailedTableInfo2("vl_forms_clinicalrequest","formNumber='$formNumber' or formNumber='".($formNumber/1)."' limit 1","id")) {
		$error.="<br /><strong>Invalid Form Number '$formNumber'.</strong><br />The Form Number <strong>$formNumber</strong> does not exist in the list of valid Form Numbers.<br /> Kindly input this record with a valid Form Number.<br />";
	}

	//ensure facility is valid
	if(!getDetailedTableInfo2("vl_facilities","id='$facilityID'","id")) {
		$error.="<br /><strong>Incorrect Facility '$facilityID'.</strong><br />Kindly select an existing Facility from the list or Request an Administrator to first add this Facility '$facilityID' to the System's Database before Proceeding.<br />";
	}
	
	//are both ART and Other ID Number are missing
	if(!$artNumber && !$otherID) {
		$error.="<br /><strong>ART Number is Missing</strong><br />Kindly provide an ART Number<br />";
	}
	
	//date of birth missing?
	if((!$dateOfBirthDay || !$dateOfBirthMonth || !$dateOfBirthYear) && (!$dateOfBirthAge || !$dateOfBirthIn)) {
		$error.="<br /><strong>Date of Birth Missing</strong><br />Kindly provide the Date of Birth<br />";
	}
	
	//is both date of birth and age in years/months missing?
	$dateOfBirth=0;
	if($dateOfBirthYear && $dateOfBirthMonth && $dateOfBirthDay) {
		$dateOfBirth="$dateOfBirthYear-$dateOfBirthMonth-$dateOfBirthDay";
	} else {
		if($dateOfBirthIn=="Months") {
			$dateOfBirth=subtractFromDate($datetime,($dateOfBirthAge*30.5));
			/*
			* 20/Jun/14: suggestion by stakeholders;
			* If Patient Provides "Age in Years", then date of birth should be == 1/Jan (year when the date was computed)
			*/
			$dateOfBirth=getFormattedDateYear($dateOfBirth)."-01-01";
		} elseif($dateOfBirthIn=="Years") {
			$dateOfBirth=subtractFromDate($datetime,($dateOfBirthAge*12*30.5));
			/*
			* 20/Jun/14: suggestion by stakeholders;
			* If Patient Provides "Age in Years", then date of birth should be == 1/Jan (year when the date was computed)
			*/
			$dateOfBirth=getFormattedDateYear($dateOfBirth)."-01-01";
		} else {
			$error.="<br /><strong>Date of Birth is Missing</strong><br />Kindly provide the Date of Birth or Age in Months/Years<br />";
		}
	}
	
	/*
	* is pregnant, ANC # should be provided
	* 20/Jun/14: suggestion by stakeholders;
	* ANC Number is desirable but not mandatory
	if($pregnant=="Yes" && !$pregnantANCNumber) {
		$error.="<br /><strong>ANC Number is Missing</strong><br />Kindly provide an ANC Number<br />";
	}
	*/

	//sample collection date
	if(!$collectionDateDay || !$collectionDateMonth || !$collectionDateYear) {
		$error.="<br /><strong>Sample Collection Date Missing</strong><br />Kindly provide the Sample Collection Date<br />";
	}

	//treatment initiation date
	if(!$treatmentInitiationDateDay || !$treatmentInitiationDateMonth || !$treatmentInitiationDateYear) {
		$error.="<br /><strong>Treatment Initiation Date Missing</strong><br />Kindly provide the Treatment Initiation Date<br />";
	}

	//is gender male and pregnancy set to yes?
	if($gender=="Male" && $pregnant=="Yes") {
		$error.="<br /><strong>Possible Error</strong><br /> Gender has been supplied as Male however Patient has also been reported as being Pregnant.<br />";
	}

	//concatenations
	if($collectionDateYear && $collectionDateMonth && $collectionDateDay) {
		$collectionDate=0;
		$collectionDate="$collectionDateYear-$collectionDateMonth-$collectionDateDay";
	}

	if($treatmentInitiationDateYear && $treatmentInitiationDateMonth && $treatmentInitiationDateDay) {
		$treatmentInitiationDate=0;
		$treatmentInitiationDate="$treatmentInitiationDateYear-$treatmentInitiationDateMonth-$treatmentInitiationDateDay";
	}
	
	if($receiptDateYear && $receiptDateMonth && $receiptDateDay) {
		$receiptDate=0;
		$receiptDate="$receiptDateYear-$receiptDateMonth-$receiptDateDay";
	}

	//input data
	if(!$error) {
		//concatenations
		$uniqueID=0;
		if($artNumber || $otherID) {
			$uniqueID=$facilityID."-".($artNumber?"A-$artNumber":"O-$otherID");
		}

		//log patient, if unique
		$patientID=0;
		if(!getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and artNumber='$artNumber' and otherID='$otherID' limit 1","id")) {
			mysqlquery("insert into vl_patients 
							(uniqueID,artNumber,otherID,gender,dateOfBirth,created,createdby) 
							values 
							('$uniqueID','$artNumber','$otherID','$gender','$dateOfBirth','$datetime','$trailSessionUser')");
			if(mysqlerror())
				die("1: ".mysqlerror());
			$patientID=getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and (artNumber='$artNumber' or otherID='$otherID') limit 1","id");
		} else {
			$patientID=getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and (artNumber='$artNumber' or otherID='$otherID') limit 1","id");
		}

		//log patient phone number, if unique
		if($patientPhone && !getDetailedTableInfo2("vl_patients_phone","patientID='$patientID' and phone='$patientPhone' limit 1","id")) {
			mysqlquery("insert into vl_patients_phone 
							(patientID,phone,created,createdby) 
							values 
							('$patientID','$patientPhone','$datetime','$trailSessionUser')");
			if(mysqlerror())
				die("2: ".mysqlerror());
		}

		//log patient samples, if unique
		if(!getDetailedTableInfo2("vl_samples","patientID='$patientID' and formNumber='$formNumber' limit 1","id")) {
			//unique sample number
			$vlSampleID=0;
			$vlSampleID=generateSampleNumber();
			
			mysqlquery("insert into vl_samples 
							(patientID,
								patientUniqueID,vlSampleID,formNumber,districtID,
								hubID,facilityID,currentRegimenID,pregnant,
								pregnantANCNumber,breastfeeding,activeTBStatus,collectionDate,
								receiptDate,treatmentLast6Months,treatmentInitiationDate,sampleTypeID,
								viralLoadTestingID,treatmentInitiationID,treatmentInitiationOther,treatmentStatusID,
								reasonForFailureID,tbTreatmentPhaseID,arvAdherenceID,
								
								vlTestingRoutineMonitoring,
								routineMonitoringLastVLDate,
								routineMonitoringValue,
								routineMonitoringSampleTypeID,
								
								vlTestingRepeatTesting,
								repeatVLTestLastVLDate,
								repeatVLTestValue,
								repeatVLTestSampleTypeID,
								
								vlTestingSuspectedTreatmentFailure,
								suspectedTreatmentFailureLastVLDate,
								suspectedTreatmentFailureValue,
								suspectedTreatmentFailureSampleTypeID,
								
								lrCategory,lrEnvelopeNumber,lrNumericID,
								
								created,createdby) 
							values 
							('$patientID',
								'$uniqueID','$vlSampleID','$formNumber','$districtID',
								'$hubID','$facilityID','$currentRegimenID','$pregnant',
								'$pregnantANCNumber','$breastfeeding','$activeTBStatus','$collectionDate',
								'$receiptDate','$treatmentLast6Months','$treatmentInitiationDate','$sampleTypeID',
								'$viralLoadTestingID','$treatmentInitiationID','$treatmentInitiationOther','$treatmentStatusID',
								'$reasonForFailureID','$tbTreatmentPhaseID','$arvAdherenceID',
								
								'".($viralLoadTestingIndication=="vlTestingRoutineMonitoring"?1:0)."',
								'".($viralLoadTestingIndication=="vlTestingRoutineMonitoring"?$routineMonitoringLastVLDate:"")."',
								'".($viralLoadTestingIndication=="vlTestingRoutineMonitoring"?$routineMonitoringValue:"")."',
								'".($viralLoadTestingIndication=="vlTestingRoutineMonitoring"?$routineMonitoringSampleTypeID:"")."',
								
								'".($viralLoadTestingIndication=="vlTestingRepeatTesting"?1:0)."',
								'".($viralLoadTestingIndication=="vlTestingRepeatTesting"?$repeatVLTestLastVLDate:"")."',
								'".($viralLoadTestingIndication=="vlTestingRepeatTesting"?$repeatVLTestValue:"")."',
								'".($viralLoadTestingIndication=="vlTestingRepeatTesting"?$repeatVLTestSampleTypeID:"")."',
								
								'".($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure"?1:0)."',
								'".($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure"?$suspectedTreatmentFailureLastVLDate:"")."',
								'".($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure"?$suspectedTreatmentFailureValue:"")."',
								'".($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure"?$suspectedTreatmentFailureSampleTypeID:"")."',
								
								'$lrCategory','$lrEnvelopeNumber','$lrNumericID',
								
								'$datetime','$trailSessionUser')");
			
			if(mysqlerror())
				die("3: ".mysqlerror());

			//review logs and fix any duplicates
			fixDuplicateSampleIDs();
			
			//redirect accordingly
			go("/samples/success/".vlEncrypt(getDetailedTableInfo2("vl_samples","createdby='$trailSessionUser' order by created desc limit 1","vlSampleID"))."/");
		} else {
			$error.="<br /><strong>Duplicate Data Entry</strong><br /> Patient with ART Number <strong>$artNumber</strong> from <strong>".getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility")."</strong> has already been entered with Form Number <strong>$formNumber</strong>.<br /> Kindly input this record with an alternative Form or ART Number.<br />";
		}
	}
}
?>
<script Language="JavaScript" Type="text/javascript">
<!--
function validate(samples) {
	//check for missing information
	if(!document.samples.lrCategory.value) {
		alert('Missing Mandatory Field: Location/Rejection ID');
		document.samples.lrCategory.focus();
		return (false);
	}
	if(!document.samples.lrEnvelopeNumber.value || document.samples.lrEnvelopeNumber.value=='<?=$default_envelopeNumber?>') {
		alert('Missing Mandatory Field: Location/Rejection ID');
		document.samples.lrEnvelopeNumber.focus();
		return (false);
	}
	if(!document.samples.lrNumericID.value) {
		alert('Missing Mandatory Field: Location/Rejection ID');
		document.samples.lrNumericID.focus();
		return (false);
	}
	if(!document.samples.formNumber.value) {
		alert('Missing Mandatory Field: Form Number');
		document.samples.formNumber.focus();
		return (false);
	}
	if(!document.samples.facilityID.value) {
		alert('Missing Mandatory Field: Facility Name');
		document.samples.facilityID.focus();
		return (false);
	}
	if(!document.samples.artNumber.value && !document.samples.otherID.value) {
		alert('Missing Mandatory Field: ART Number');
		document.samples.artNumber.focus();
		return (false);
	}
	if(!document.samples.gender.value) {
		alert('Missing Mandatory Field: Gender');
		document.samples.gender.focus();
		return (false);
	}
	if((!document.samples.dateOfBirthDay.value || !document.samples.dateOfBirthMonth.value || !document.samples.dateOfBirthYear.value) && (!document.samples.dateOfBirthAge.value || !document.samples.dateOfBirthIn.value)) {
		alert('Missing Mandatory Field: Date of Birth or Patient Age');
		document.samples.dateOfBirthDay.focus();
		return (false);
	}
	if(!document.samples.collectionDateDay.value || !document.samples.collectionDateMonth.value || !document.samples.collectionDateYear.value) {
		alert('Missing Mandatory Field: Sample Collection Date');
		document.samples.collectionDateDay.focus();
		return (false);
	}
	if(!document.samples.sampleTypeID.value) {
		alert('Missing Mandatory Field: Sample Type');
		document.samples.sampleTypeID.focus();
		return (false);
	}
	if(!document.samples.currentRegimenID.value) {
		alert('Missing Mandatory Field: Current Regimen');
		document.samples.currentRegimenID.focus();
		return (false);
	}
	if(!document.samples.treatmentStatusID.value) {
		alert('Missing Mandatory Field: Treatment Status');
		document.samples.treatmentStatusID.focus();
		return (false);
	}
	if(!document.samples.viralLoadTestingID.value) {
		alert('Missing Mandatory Field: Viral Load Testing');
		document.samples.viralLoadTestingID.focus();
		return (false);
	}
	if(!document.samples.pregnant.value) {
		alert('Missing Mandatory Field: Pregnancy Status');
		document.samples.pregnant.focus();
		return (false);
	}
	/*
	if(document.samples.pregnant.value=="Yes" && !document.samples.pregnantANCNumber.value) {
		alert('Missing Mandatory Field: Pregnancy Status Supplied but no ANC Number');
		document.samples.pregnantANCNumber.focus();
		return (false);
	}
	*/
	if(!document.samples.breastfeeding.value) {
		alert('Missing Mandatory Field: Breastfeeding Status');
		document.samples.breastfeeding.focus();
		return (false);
	}
	if(!document.samples.viralLoadTestingID.value) {
		alert('Missing Mandatory Field: Viral Load Testing');
		document.samples.viralLoadTestingID.focus();
		return (false);
	}
	if(!document.samples.treatmentInitiationDateDay.value || !document.samples.treatmentInitiationDateMonth.value || !document.samples.treatmentInitiationDateYear.value) {
		alert('Missing Mandatory Field: Treatment Initiation Date');
		document.samples.treatmentInitiationDateDay.focus();
		return (false);
	}
	if(!document.samples.treatmentLast6Months.value) {
		alert('Missing Mandatory Field: Whether Patient has been on Treatment for last 6 months');
		document.samples.treatmentLast6Months.focus();
		return (false);
	}
	//logical
	if(document.samples.gender.value=="Male" && document.samples.pregnant.value=="Yes") {
		alert('Possible Error: Gender is indicated as Male, Patient should not be reported as Pregnant.');
		return (false);
	}
	return (true);
}

function checkGender(theField) {
	if(theField.value=="Male") {
		document.samples.pregnant.value="No";
		checkPregnancy(document.samples.pregnant);
		document.samples.breastfeeding.value="No";
	} else {
		document.samples.pregnant.value="";
		checkPregnancy(document.samples.pregnant);
		document.samples.breastfeeding.value="";
	}
}

function checkOptions(theField) {
	if(theField.value==<?=getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix like '%other%' limit 1","id")?>) {
		loadInput('treatmentInitiationOther','treatmentInitiationIDTD','');
	} else {
		document.getElementById("treatmentInitiationIDTD").innerHTML="";
	}
}

function checkPregnancy(theField) {
	if(theField.value=='Yes') {
		document.getElementById("pregnancyTextID").innerHTML="ANC Number";
		loadInput('pregnantANCNumber','pregnancyID','');
	} else {
		document.getElementById("pregnancyTextID").innerHTML="";
		document.getElementById("pregnancyID").innerHTML="";
	}
}

function checkForHubDistrict(){
	//facilityID
	var theFacilityID=document.samples.facilityID.value;
	
	document.getElementById("checkHubDistrictID").innerHTML="loading hub and district ...";
	//get hub
	vlDC_XloadHub('samples','checkHubDistrictID','hubID',theFacilityID);
	//get district
	vlDC_XloadDistrict('samples','checkHubDistrictID','districtID',theFacilityID);
}

function checkMonthDay(theField) {
	if(theField.value && !document.samples.dateOfBirthMonth.value && !document.samples.dateOfBirthDay.value) {
		//default to first day/month
		document.samples.dateOfBirthDay.value="01";
		document.samples.dateOfBirthMonth.value="01"
	}
}

function selectVLTesting(theField) {
	//auto select viralLoadTestingIndication, options are vlTestingRoutineMonitoring, vlTestingRepeatTesting, vlTestingSuspectedTreatmentFailure
	if(theField.value==<?=getDetailedTableInfo2("vl_appendix_viralloadtesting","appendix like '%Routine monitoring%' limit 1","id")?>) {
		document.getElementById("vlTestingRoutineMonitoring").checked = true;
	} else if(theField.value==<?=getDetailedTableInfo2("vl_appendix_viralloadtesting","appendix like '%Repeat viral load%' limit 1","id")?>) {
		document.getElementById("vlTestingRepeatTesting").checked = true;
	} else if(theField.value==<?=getDetailedTableInfo2("vl_appendix_viralloadtesting","appendix like '%Suspected treatment failure%' limit 1","id")?>) {
		document.getElementById("vlTestingSuspectedTreatmentFailure").checked = true;
	} else if(theField.value==<?=getDetailedTableInfo2("vl_appendix_viralloadtesting","appendix like '%Left Blank%' limit 1","id")?>) {
		document.getElementById("vlTestingRoutineMonitoring").checked = false;
		document.getElementById("vlTestingRepeatTesting").checked = false;
		document.getElementById("vlTestingSuspectedTreatmentFailure").checked = false;
	}
}

function validateEnvelopeNumber(field) {
	var clear=true;
	var val = field.value;
	var valueLength = val.length;
	//check position of the dash
	if(valueLength>4) {
		var key4=val.charAt(4);
		if(key4!="-") {
			//display message
			document.getElementById('envelopeInfoID').style.display='inline';
		} else {
			//hide
			document.getElementById('envelopeInfoID').style.display='none';
		}
	} else {
		//hide
		document.getElementById('envelopeInfoID').style.display='none';
	}
	//only numeric and - entries
	for(var i=0;i<valueLength;++i) {
		var new_key=val.charAt(i);
		if(((new_key<"0") || (new_key>"9")) && !(new_key=="-")) {
			clear=false;
			break;
		}
	}
	//effect the clearing
	if(!clear) {
		var submission = val.substring(0,(valueLength-1));
		field.value=submission;
	}
}
//-->
</script>
<form name="samples" method="post" action="/samples/capture/" onsubmit="return validate(this)">
<table width="100%" border="0" class="vl">
          <? if($success) { ?>
            <tr>
                <td class="vl_success">Data Captured Successfully!</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
			<? } elseif($error) { ?>
            <tr>
                <td class="vl_error"><?=$error?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? } ?>
            <tr>
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/samples/">SAMPLES</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/samples/capture/">Samples&nbsp;Capture</a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>FORM/FACILITY CREDENTIALS</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                          <tr>
                            <td></td>
                            <td><span id="envelopeInfoID" class="hint-down" style="margin-top: -50px; width: 310px; display:none">Incorrect Envelope # Format. Correct Format is <?=getFormattedDateYearShort($datetime).getFormattedDateMonth($datetime)."-001"?>. <span class="hint-down-pointer"></span></span></td>
                          </tr>
                            <tr>
                              <td width="20%">Location/Rejection&nbsp;ID</td>
                              <td width="80%">
                                <table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                  <tr>
                                    <td><select name="lrCategory" id="lrCategory" class="search" style="height:21px">
                                    		<?
											if($lrCategory) {
												echo "<option value=\"$lrCategory\" selected=\"selected\">$lrCategory</option>
														<option value=\"V\">V</option>
														<option value=\"R\">R</option>";
											} else {
												echo "<option value=\"V\" selected=\"selected\">V</option>
														<option value=\"R\">R</option>";
											}
											?>
                                          </select></td>
                                    <td style="padding:0px 0px 0px 3px"><input type="text" name="lrEnvelopeNumber" id="lrEnvelopeNumber" value="<?=($lrEnvelopeNumber?$lrEnvelopeNumber:$default_envelopeNumber)?>" class="search_pre" size="10" maxlength="10" onkeyup="return validateEnvelopeNumber(this)" onfocus="if(value=='Envelope #') {value=''}" onblur="if(value=='') {value='Envelope #'}" /></td>
                                    <td style="padding:0px 3px">/</td>
                                    <td><input type="text" name="lrNumericID" id="lrNumericID" value="<?=$lrNumericID?>" class="search_pre" size="1" maxlength="3" onkeyup="return isNumber(this,'0')" /></td>
                                    </tr>
                                </table>
                            </td>
                            </tr>
                            <tr>
                              <td>Form&nbsp;#&nbsp;<font class="vl_red">*</font></td>
                              <td><input type="text" name="formNumber" id="formNumber" value="<?=$formNumber?>" class="search_pre" size="15" maxlength="50" /></td>
                            </tr>
                        <tr>
                          <td>Facility&nbsp;Name&nbsp;<font class="vl_red">*</font></td>
                          <td>
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="20%">
                                        <select name="facilityID" id="facilityID" class="search" onchange="checkForHubDistrict()">
                                            <?
                                            $query=0;
                                            $query=mysqlquery("select * from vl_facilities where facility!='' order by facility");
                                            if($facilityID) {
                                                echo "<option value=\"$facilityID\" selected=\"selected\">".getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility")."</option>";
                                            } else {
                                                echo "<option value=\"\" selected=\"selected\"></option>";
                                            }
                                            if(mysqlnumrows($query)) {
                                                while($q=mysqlfetcharray($query)) {
                                                    echo "<option value=\"$q[id]\">$q[facility]</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                        <script>
                                            var z = dhtmlXComboFromSelect("facilityID");
                                            z.enableFilteringMode(true);
                                        </script>
                                    </td>
                                    <td width="80%" style="padding:0px 0px 0px 10px" id="checkHubDistrictID">&nbsp;</td>
                                  </tr>
                                </table>
                          </td>
                        </tr>
                        <tr>
                          <td>Hub</td>
                          <td>
                          <select name="hubID" id="hubID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_hubs order by hub");
								if($hubID) {
									echo "<option value=\"$hubID\" selected=\"selected\">".getDetailedTableInfo2("vl_hubs","id='$hubID' limit 1","hub")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Hub</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[hub]</option>";
									}
								}
								?>
                                </select>
                          </td>
                        </tr>
                        <tr>
                          <td>District</td>
                          <td>
                          <select name="districtID" id="districtID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_districts order by district");
								if($districtID) {
									echo "<option value=\"$districtID\" selected=\"selected\">".getDetailedTableInfo2("vl_districts","id='$districtID' limit 1","district")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select District</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[district]</option>";
									}
								}
								?>
                                </select>
                          </td>
                        </tr>
                      </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>SAMPLE DETAILS</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                            <tr>
							<td width="20%">Collection&nbsp;Date&nbsp;<font class="vl_red">*</font></td>
                              <td width="80%">
								<table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="collectionDateDay" id="collectionDateDay" class="search">
                                          <?
											if($collectionDate) {
												echo "<option value=\"".getFormattedDateDay($collectionDate)."\" selected=\"selected\">".getFormattedDateDay($collectionDate)."</option>";
											} else {
												echo "<option value=\"".getFormattedDateDay($datetime)."\" selected=\"selected\">".getFormattedDateDay($datetime)."</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="collectionDateMonth" id="collectionDateMonth" class="search">
                                          <? 
											if($collectionDate) {
												echo "<option value=\"".getFormattedDateMonth($collectionDate)."\" selected=\"selected\">".getFormattedDateMonthname($collectionDate)."</option>";
											} else {
												echo "<option value=\"".getFormattedDateMonth($datetime)."\" selected=\"selected\">".getFormattedDateMonthname($datetime)."</option>"; 
											}
										  ?>
                                          <option value="01">Jan</option>
                                          <option value="02">Feb</option>
                                          <option value="03">Mar</option>
                                          <option value="04">Apr</option>
                                          <option value="05">May</option>
                                          <option value="06">Jun</option>
                                          <option value="07">Jul</option>
                                          <option value="08">Aug</option>
                                          <option value="09">Sept</option>
                                          <option value="10">Oct</option>
                                          <option value="11">Nov</option>
                                          <option value="12">Dec</option>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="collectionDateYear" id="collectionDateYear" class="search">
                                          		<?
												if($collectionDate) {
													echo "<option value=\"".getFormattedDateYear($collectionDate)."\" selected=\"selected\">".getFormattedDateYear($collectionDate)."</option>";
												} else {
													echo "<option value=\"".getFormattedDateYear($datetime)."\" selected=\"selected\">".getFormattedDateYear($datetime)."</option>"; 
												}
                                                for($j=(getFormattedDateYear($datetime)-1);$j>=(getCurrentYear()-10);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        </tr>
                                    </table>
                              </td>
                            </tr>
                            <tr>
							<td width="20%">Received&nbsp;at&nbsp;CPHL&nbsp;<font class="vl_red">*</font></td>
                              <td width="80%">
								<table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="receiptDateDay" id="receiptDateDay" class="search">
                                          <?
											if($receiptDate) {
												echo "<option value=\"".getFormattedDateDay($receiptDate)."\" selected=\"selected\">".getFormattedDateDay($receiptDate)."</option>";
											} else {
												echo "<option value=\"".getFormattedDateDay($datetime)."\" selected=\"selected\">".getFormattedDateDay($datetime)."</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="receiptDateMonth" id="receiptDateMonth" class="search">
                                          <? 
											if($receiptDate) {
												echo "<option value=\"".getFormattedDateMonth($receiptDate)."\" selected=\"selected\">".getFormattedDateMonthname($receiptDate)."</option>";
											} else {
												echo "<option value=\"".getFormattedDateMonth($datetime)."\" selected=\"selected\">".getFormattedDateMonthname($datetime)."</option>"; 
											}
										  ?>
                                          <option value="01">Jan</option>
                                          <option value="02">Feb</option>
                                          <option value="03">Mar</option>
                                          <option value="04">Apr</option>
                                          <option value="05">May</option>
                                          <option value="06">Jun</option>
                                          <option value="07">Jul</option>
                                          <option value="08">Aug</option>
                                          <option value="09">Sept</option>
                                          <option value="10">Oct</option>
                                          <option value="11">Nov</option>
                                          <option value="12">Dec</option>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="receiptDateYear" id="receiptDateYear" class="search">
                                          		<?
												if($receiptDate) {
													echo "<option value=\"".getFormattedDateYear($receiptDate)."\" selected=\"selected\">".getFormattedDateYear($receiptDate)."</option>";
												} else {
													echo "<option value=\"".getFormattedDateYear($datetime)."\" selected=\"selected\">".getFormattedDateYear($datetime)."</option>"; 
												}
                                                for($j=(getFormattedDateYear($datetime)-1);$j>=(getCurrentYear()-10);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        </tr>
                                    </table>
                              </td>
                            </tr>
                            <tr>
                              <td>Sample&nbsp;Type&nbsp;<font class="vl_red">*</font></td>
                              <td>
								<select name="sampleTypeID" id="sampleTypeID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_sampletype order by position");
								if($sampleTypeID) {
									echo "<option value=\"$sampleTypeID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_sampletype","id='$sampleTypeID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Sample Type</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                        </tr>
                      </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>PATIENT INFORMATION</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
                          <table width="100%" border="0" class="vl">
                            <tr>
                              <td width="20%">ART&nbsp;Number&nbsp;<font class="vl_red">*</font></td>
                              <td width="80%"><input type="text" name="artNumber" id="artNumber" value="<?=$artNumber?>" class="search_pre" size="25" maxlength="20" /></td>
                            </tr>
                            <tr>
                              <td>Other&nbsp;ID</td>
                              <td><input type="text" name="otherID" id="otherID" value="<?=$otherID?>" class="search_pre" size="25" maxlength="50" /></td>
                            </tr>
                            <tr>
                              <td>Gender&nbsp;<font class="vl_red">*</font></td>
                              <td>
								<select name="gender" id="gender" class="search" onchange="checkGender(this)">
                                	<?
									if($gender) {
										echo "<option value=\"$gender\" selected=\"selected\">$gender</option>";
									} else {
										echo "<option value=\"\" selected=\"selected\">Select Gender</option>";
									}
									?>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Missing Gender">Missing Gender</option>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Date&nbsp;of&nbsp;Birth&nbsp;<font class="vl_red">*</font></td>
                              <td>
                                  <table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="dateOfBirthDay" id="dateOfBirthDay" class="search">
                                          <?
										  	if($dateOfBirth) {
												echo "<option value=\"".getFormattedDateDay($dateOfBirth)."\" selected=\"selected\">".getFormattedDateDay($dateOfBirth)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Date</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="dateOfBirthMonth" id="dateOfBirthMonth" class="search">
                                          <? 
										  	if($dateOfBirth) {
												echo "<option value=\"".getFormattedDateMonth($dateOfBirth)."\" selected=\"selected\">".getFormattedDateMonthname($dateOfBirth)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Month</option>"; 
											}
										  ?>
                                          <option value="01">Jan</option>
                                          <option value="02">Feb</option>
                                          <option value="03">Mar</option>
                                          <option value="04">Apr</option>
                                          <option value="05">May</option>
                                          <option value="06">Jun</option>
                                          <option value="07">Jul</option>
                                          <option value="08">Aug</option>
                                          <option value="09">Sept</option>
                                          <option value="10">Oct</option>
                                          <option value="11">Nov</option>
                                          <option value="12">Dec</option>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="dateOfBirthYear" id="dateOfBirthYear" class="search" onchange="checkMonthDay(this)">
                                          		<?
												if($dateOfBirth) {
													echo "<option value=\"".getFormattedDateYear($dateOfBirth)."\" selected=\"selected\">".getFormattedDateYear($dateOfBirth)."</option>";
												} else {
													echo "<option value=\"\" selected=\"selected\">Select Year</option>";
												}
                                                for($j=getFormattedDateYear(getDualInfoWithAlias("last_day(now())","lastmonth"));$j>=(getCurrentYear()-100);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        <td style="padding:0px 5px 0px 5px">or</td>
                                        <td style="padding:0px 0px 0px 5px"><select name="dateOfBirthAge" id="dateOfBirthAge" class="search">
                                          		<?
												if($dateOfBirthAge) {
													echo "<option value=\"$dateOfBirthAge\" selected=\"selected\">$dateOfBirthAge</option>";
												} else {
													echo "<option value=\"\" selected=\"selected\">Select Age</option>";
												}
                                                for($j=1;$j<=120;$j++) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="dateOfBirthIn" id="dateOfBirthIn" class="search">
                                          		<?
													if($dateOfBirthIn) {
														echo "<option value=\"$dateOfBirthIn\" selected=\"selected\">$dateOfBirthIn</option>";
													} else {
														echo "<option value=\"\" selected=\"selected\">in Years or Months</option>";
													}
												?>
                                                    <option value="Years">Years</option>
                                                    <option value="Months">Months</option>
                                          </select></td>
                                        </tr>
                                    </table>
                              </td>
                            </tr>
                            <tr>
                              <td>Patient&nbsp;Phone</td>
                              <td><table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><input type="text" name="patientPhone" id="patientPhone" value="<?=$patientPhone?>" class="search_pre" size="15" maxlength="20" /></td>
                                        </tr>
                                    </table></td>
                            </tr>
                          </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
                    <legend><strong>TREATMENT INFORMATION</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
                          <table width="100%" border="0" class="vl">
                            <tr>
                              <td width="20%">
                              	Has&nbsp;Patient&nbsp;Been&nbsp;on&nbsp;Treatment
                                for&nbsp;the&nbsp;Last&nbsp;6&nbsp;Months&nbsp;<font class="vl_red">*</font>
                                </td>
                              <td width="80%">
								<select name="treatmentLast6Months" id="treatmentLast6Months" class="search">
                                <?
								if($treatmentLast6Months) {
									echo "<option value=\"$treatmentLast6Months\" selected=\"selected\">$treatmentLast6Months</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Appropriate Status</option>";
								}
								echo "<option value=\"Yes\">Yes</option>";
								echo "<option value=\"No\">No</option>";
								echo "<option value=\"Left Blank\">Left Blank</option>";
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Treatment&nbsp;Initiation&nbsp;Date&nbsp;<font class="vl_red">*</font></td>
                              <td>
                                  <table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="treatmentInitiationDateDay" id="treatmentInitiationDateDay" class="search">
                                          <?
											if($treatmentInitiationDate) {
												echo "<option value=\"".getFormattedDateDay($treatmentInitiationDate)."\" selected=\"selected\">".getFormattedDateDay($treatmentInitiationDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Date</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="treatmentInitiationDateMonth" id="treatmentInitiationDateMonth" class="search">
                                          <? 
											if($treatmentInitiationDate) {
												echo "<option value=\"".getFormattedDateMonth($treatmentInitiationDate)."\" selected=\"selected\">".getFormattedDateMonthname($treatmentInitiationDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Month</option>"; 
											}
										  ?>
                                          <option value="01">Jan</option>
                                          <option value="02">Feb</option>
                                          <option value="03">Mar</option>
                                          <option value="04">Apr</option>
                                          <option value="05">May</option>
                                          <option value="06">Jun</option>
                                          <option value="07">Jul</option>
                                          <option value="08">Aug</option>
                                          <option value="09">Sept</option>
                                          <option value="10">Oct</option>
                                          <option value="11">Nov</option>
                                          <option value="12">Dec</option>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="treatmentInitiationDateYear" id="treatmentInitiationDateYear" class="search">
                                          		<?
												if($treatmentInitiationDate) {
													echo "<option value=\"".getFormattedDateYear($treatmentInitiationDate)."\" selected=\"selected\">".getFormattedDateYear($treatmentInitiationDate)."</option>";
												} else {
													echo "<option value=\"\" selected=\"selected\">Select Year</option>";
												}
												for($j=getFormattedDateYear(getDualInfoWithAlias("last_day(now())","lastmonth"));$j>=(getCurrentYear()-50);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        </tr>
                                    </table>
                              </td>
                            </tr>
                            <tr>
                              <td>Current&nbsp;Regimen&nbsp;<font class="vl_red">*</font></td>
                              <td>
								<select name="currentRegimenID" id="currentRegimenID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_regimen order by position");
								if($currentRegimenID) {
									echo "<option value=\"$currentRegimenID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_regimen","id='$currentRegimenID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Current Regimen</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Indication&nbsp;for&nbsp;Treatment&nbsp;Initiation</td>
                              <td>
								<select name="treatmentInitiationID" id="treatmentInitiationID" class="search" onchange="checkOptions(this)">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_treatmentinitiation order by position");
								if($treatmentInitiationID) {
									echo "<option value=\"$treatmentInitiationID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_treatmentinitiation","id='$treatmentInitiationID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Treatment Initiation</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td></td>
                              <td id="treatmentInitiationIDTD"></td>
                            </tr>
                            <tr>
                              <td>Patient&nbsp;Treatment&nbsp;Line&nbsp;<font class="vl_red">*</font></td>
                              <td>
								<select name="treatmentStatusID" id="treatmentStatusID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_treatmentstatus order by position");
								if($treatmentStatusID) {
									echo "<option value=\"$treatmentStatusID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_treatmentstatus","id='$treatmentStatusID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Treatment Status</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Failure&nbsp;Reason</td>
                              <td>
								<select name="reasonForFailureID" id="reasonForFailureID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_failurereason order by position");
								if($reasonForFailureID) {
									echo "<option value=\"$reasonForFailureID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_failurereason","id='$reasonForFailureID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Failure Reason</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Viral&nbsp;Load&nbsp;Testing&nbsp;<font class="vl_red">*</font></td>
                              <td>
								<select name="viralLoadTestingID" id="viralLoadTestingID" class="search" onchange="selectVLTesting(this)">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_viralloadtesting order by position");
								if($viralLoadTestingID) {
									echo "<option value=\"$viralLoadTestingID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_viralloadtesting","id='$viralLoadTestingID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Viral Load Testing</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Pregnant&nbsp;<font class="vl_red">*</td>
                              <td>
								<select name="pregnant" id="pregnant" class="search" onchange="checkPregnancy(this)">
                                <?
								if($pregnant) {
									echo "<option value=\"$pregnant\" selected=\"selected\">$pregnant</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Pregnancy Status</option>";
								}
								echo "<option value=\"Yes\">Yes</option>";
								echo "<option value=\"No\">No</option>";
								echo "<option value=\"Left Blank\">Left Blank</option>";
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td id="pregnancyTextID"></td>
                              <td id="pregnancyID"></td>
                            </tr>
                            <tr>
                              <td>Breastfeeding&nbsp;<font class="vl_red">*</td>
                              <td>
								<select name="breastfeeding" id="breastfeeding" class="search">
                                <?
								if($breastfeeding) {
									echo "<option value=\"$breastfeeding\" selected=\"selected\">$breastfeeding</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Breastfeeding Status</option>";
								}
								echo "<option value=\"Yes\">Yes</option>";
								echo "<option value=\"No\">No</option>";
								echo "<option value=\"Left Blank\">Left Blank</option>";
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Active&nbsp;TB&nbsp;Status</td>
                              <td>
								<select name="activeTBStatus" id="activeTBStatus" class="search">
                                <?
								if($activeTBStatus) {
									echo "<option value=\"$activeTBStatus\" selected=\"selected\">$activeTBStatus</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Active TB Status</option>";
								}
								echo "<option value=\"Yes\">Yes</option>";
								echo "<option value=\"No\">No</option>";
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>TB&nbsp;Treatment&nbsp;Phase</td>
                              <td>
								<select name="tbTreatmentPhaseID" id="tbTreatmentPhaseID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_tbtreatmentphase order by position");
								if($tbTreatmentPhaseID) {
									echo "<option value=\"$tbTreatmentPhaseID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_tbtreatmentphase","id='$tbTreatmentPhaseID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Failure Reason</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>ARV&nbsp;Adherence</td>
                              <td>
								<select name="arvAdherenceID" id="arvAdherenceID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_arvadherence order by position");
								if($arvAdherenceID) {
									echo "<option value=\"$arvAdherenceID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_arvadherence","id='$arvAdherenceID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select ARV Adherence</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                          </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
              <td>
				<fieldset style="width: 100%">
					<legend><strong>INDICATION FOR VIRAL LOAD TESTING</strong></legend>
						<div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                            <tr>
                              <td width="1%"><input name="viralLoadTestingIndication" id="vlTestingRoutineMonitoring" type="radio" value="vlTestingRoutineMonitoring" <?=($viralLoadTestingIndication=="vlTestingRoutineMonitoring"?" checked=\"checked\"":"")?> /></td>
                              <td width="69%">Routine Monitoring</td>
                              <td width="5%" align="right">Last&nbsp;Viral&nbsp;Load&nbsp;Date:</td>
                              <td width="5%" style="padding:0px 0px 0px 5px">
                                  <table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="routineMonitoringLastVLDateDay" id="routineMonitoringLastVLDateDay" class="search">
                                          <?
											if($routineMonitoringLastVLDate) {
												echo "<option value=\"".getFormattedDateDay($routineMonitoringLastVLDate)."\" selected=\"selected\">".getFormattedDateDay($routineMonitoringLastVLDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Date</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="routineMonitoringLastVLDateMonth" id="routineMonitoringLastVLDateMonth" class="search">
                                          <? 
											if($routineMonitoringLastVLDate) {
												echo "<option value=\"".getFormattedDateMonth($routineMonitoringLastVLDate)."\" selected=\"selected\">".getFormattedDateMonthname($routineMonitoringLastVLDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Month</option>"; 
											}
										  ?>
                                          <option value="01">Jan</option>
                                          <option value="02">Feb</option>
                                          <option value="03">Mar</option>
                                          <option value="04">Apr</option>
                                          <option value="05">May</option>
                                          <option value="06">Jun</option>
                                          <option value="07">Jul</option>
                                          <option value="08">Aug</option>
                                          <option value="09">Sept</option>
                                          <option value="10">Oct</option>
                                          <option value="11">Nov</option>
                                          <option value="12">Dec</option>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="routineMonitoringLastVLDateYear" id="routineMonitoringLastVLDateYear" class="search">
                                          		<?
												if($routineMonitoringLastVLDate) {
													echo "<option value=\"".getFormattedDateYear($routineMonitoringLastVLDate)."\" selected=\"selected\">".getFormattedDateYear($routineMonitoringLastVLDate)."</option>";
												} else {
													echo "<option value=\"\" selected=\"selected\">Select Year</option>";
												}
												for($j=getFormattedDateYear(getDualInfoWithAlias("last_day(now())","lastmonth"));$j>=(getCurrentYear()-10);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        </tr>
                                    </table>
                              </td>
                              <td width="5%" style="padding: 0px 0px 0px 10px" align="right">Value:</td>
                              <td width="5%" style="padding:0px 0px 0px 5px"><input type="text" name="routineMonitoringValue" id="routineMonitoringValue" value="<?=$routineMonitoringValue?>" class="search_pre" size="7" maxlength="10" /></td>
                              <td width="5%" align="right">Sample&nbsp;Type:</td>
                              <td width="5%" style="padding:0px 0px 0px 5px">
								<select name="routineMonitoringSampleTypeID" id="routineMonitoringSampleTypeID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_sampletype order by position");
								if($routineMonitoringSampleTypeID) {
									echo "<option value=\"$routineMonitoringSampleTypeID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_sampletype","id='$routineMonitoringSampleTypeID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Sample Type</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td><input name="viralLoadTestingIndication" id="vlTestingRepeatTesting" type="radio" value="vlTestingRepeatTesting" <?=($viralLoadTestingIndication=="vlTestingRepeatTesting"?" checked=\"checked\"":"")?> /></td>
                              <td>Repeat Viral Load Test after detectable viraemia and 6 months adherence counseling</td>
                              <td width="10%" align="right">Last&nbsp;Viral&nbsp;Load&nbsp;Date:</td>
                              <td width="10%" style="padding:0px 0px 0px 5px">
                                  <table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="repeatVLTestLastVLDateDay" id="repeatVLTestLastVLDateDay" class="search">
                                          <?
											if($repeatVLTestLastVLDate) {
												echo "<option value=\"".getFormattedDateDay($repeatVLTestLastVLDate)."\" selected=\"selected\">".getFormattedDateDay($repeatVLTestLastVLDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Date</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="repeatVLTestLastVLDateMonth" id="repeatVLTestLastVLDateMonth" class="search">
                                          <? 
											if($repeatVLTestLastVLDate) {
												echo "<option value=\"".getFormattedDateMonth($repeatVLTestLastVLDate)."\" selected=\"selected\">".getFormattedDateMonthname($repeatVLTestLastVLDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Month</option>"; 
											}
										  ?>
                                          <option value="01">Jan</option>
                                          <option value="02">Feb</option>
                                          <option value="03">Mar</option>
                                          <option value="04">Apr</option>
                                          <option value="05">May</option>
                                          <option value="06">Jun</option>
                                          <option value="07">Jul</option>
                                          <option value="08">Aug</option>
                                          <option value="09">Sept</option>
                                          <option value="10">Oct</option>
                                          <option value="11">Nov</option>
                                          <option value="12">Dec</option>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="repeatVLTestLastVLDateYear" id="repeatVLTestLastVLDateYear" class="search">
                                          		<?
												if($repeatVLTestLastVLDate) {
													echo "<option value=\"".getFormattedDateYear($repeatVLTestLastVLDate)."\" selected=\"selected\">".getFormattedDateYear($repeatVLTestLastVLDate)."</option>";
												} else {
													echo "<option value=\"\" selected=\"selected\">Select Year</option>";
												}
												for($j=getFormattedDateYear(getDualInfoWithAlias("last_day(now())","lastmonth"));$j>=(getCurrentYear()-10);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        </tr>
                                    </table>
                              </td>
                              <td style="padding: 0px 0px 0px 10px" align="right">Value:</td>
                              <td style="padding:0px 0px 0px 5px"><input type="text" name="repeatVLTestValue" id="repeatVLTestValue" value="<?=$repeatVLTestValue?>" class="search_pre" size="7" maxlength="10" /></td>
                              <td align="right">Sample&nbsp;Type:</td>
                              <td style="padding:0px 0px 0px 5px">
								<select name="repeatVLTestSampleTypeID" id="repeatVLTestSampleTypeID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_sampletype order by position");
								if($repeatVLTestSampleTypeID) {
									echo "<option value=\"$repeatVLTestSampleTypeID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_sampletype","id='$repeatVLTestSampleTypeID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Sample Type</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
							<td><input name="viralLoadTestingIndication" id="vlTestingSuspectedTreatmentFailure" type="radio" value="vlTestingSuspectedTreatmentFailure" <?=($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure"?" checked=\"checked\"":"")?> /></td>
                              <td>Suspected Treatment Failure</td>
                              <td width="10%" align="right">Last&nbsp;Viral&nbsp;Load&nbsp;Date:</td>
                              <td width="10%" style="padding:0px 0px 0px 5px">
                                  <table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="suspectedTreatmentFailureLastVLDateDay" id="suspectedTreatmentFailureLastVLDateDay" class="search">
                                          <?
											if($suspectedTreatmentFailureLastVLDate) {
												echo "<option value=\"".getFormattedDateDay($suspectedTreatmentFailureLastVLDate)."\" selected=\"selected\">".getFormattedDateDay($suspectedTreatmentFailureLastVLDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Date</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="suspectedTreatmentFailureLastVLDateMonth" id="suspectedTreatmentFailureLastVLDateMonth" class="search">
                                          <? 
											if($suspectedTreatmentFailureLastVLDate) {
												echo "<option value=\"".getFormattedDateMonth($suspectedTreatmentFailureLastVLDate)."\" selected=\"selected\">".getFormattedDateMonthname($suspectedTreatmentFailureLastVLDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Month</option>"; 
											}
										  ?>
                                          <option value="01">Jan</option>
                                          <option value="02">Feb</option>
                                          <option value="03">Mar</option>
                                          <option value="04">Apr</option>
                                          <option value="05">May</option>
                                          <option value="06">Jun</option>
                                          <option value="07">Jul</option>
                                          <option value="08">Aug</option>
                                          <option value="09">Sept</option>
                                          <option value="10">Oct</option>
                                          <option value="11">Nov</option>
                                          <option value="12">Dec</option>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="suspectedTreatmentFailureLastVLDateYear" id="suspectedTreatmentFailureLastVLDateYear" class="search">
                                          		<?
												if($suspectedTreatmentFailureLastVLDate) {
													echo "<option value=\"".getFormattedDateYear($suspectedTreatmentFailureLastVLDate)."\" selected=\"selected\">".getFormattedDateYear($suspectedTreatmentFailureLastVLDate)."</option>";
												} else {
													echo "<option value=\"\" selected=\"selected\">Select Year</option>";
												}
												for($j=getFormattedDateYear(getDualInfoWithAlias("last_day(now())","lastmonth"));$j>=(getCurrentYear()-10);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        </tr>
                                    </table>
                              </td>
                              <td style="padding: 0px 0px 0px 10px" align="right">Value:</td>
                              <td style="padding:0px 0px 0px 5px"><input type="text" name="suspectedTreatmentFailureValue" id="suspectedTreatmentFailureValue" value="<?=$suspectedTreatmentFailureValue?>" class="search_pre" size="7" maxlength="10" /></td>
                              <td align="right">Sample&nbsp;Type:</td>
                              <td style="padding:0px 0px 0px 5px">
								<select name="suspectedTreatmentFailureSampleTypeID" id="suspectedTreatmentFailureSampleTypeID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_sampletype order by position");
								if($suspectedTreatmentFailureSampleTypeID) {
									echo "<option value=\"$suspectedTreatmentFailureSampleTypeID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_sampletype","id='$suspectedTreatmentFailureSampleTypeID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Sample Type</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
						</table>
                        </div>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td style="padding:10px 0px 0px 0px"><input type="submit" name="saveSample" id="saveSample" class="button" value="  Save Samples  " /></td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/samples/">Return to Samples</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
          </table>
</form>