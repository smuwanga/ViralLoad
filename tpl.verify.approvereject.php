<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

//validation
$id=validate($id);
$pg=validate($pg);
$outcome=validate($outcome);
$outcomeReasonsID=validate($outcomeReasonsID);
$comments=validate($comments);

//encrypted samples
$searchQuery=0;
$searchQueryCurrentPosition=0;
$searchQueryNextPosition=0;
if($encryptedSample) {
	$searchQuery=validate(vlDecrypt($encryptedSample));
	$searchQueryCurrentPosition=getDetailedTableInfo3("vl_samples y","y.vlSampleID='".getDetailedTableInfo2("vl_samples","id='$id'","vlSampleID")."'","(select count(x.id) from vl_samples x where (x.formNumber='$searchQuery' or x.vlSampleID='$searchQuery' or concat(x.lrCategory,x.lrEnvelopeNumber,'/',x.lrNumericID) like '$searchQuery%') and x.vlSampleID<=y.vlSampleID order by if(x.lrCategory='',1,0),x.lrCategory, if(x.lrEnvelopeNumber='',1,0),x.lrEnvelopeNumber, if(x.lrNumericID='',1,0),x.lrNumericID,x.created desc)","position");
	if($searchQueryCurrentPosition) {
		$searchQueryNextPosition=getDetailedTableInfo2("vl_samples","id not in (select sampleID from vl_samples_verify) and (formNumber='$searchQuery' or vlSampleID='$searchQuery' or concat(lrCategory,lrEnvelopeNumber,'/',lrNumericID) like '$searchQuery%') order by if(lrCategory='',1,0),lrCategory, if(lrEnvelopeNumber='',1,0),lrEnvelopeNumber, if(lrNumericID='',1,0),lrNumericID,created desc limit $searchQueryCurrentPosition,1","id");
		if(!$searchQueryNextPosition) {
			$searchQueryNextPosition=getDetailedTableInfo2("vl_samples","id not in (select sampleID from vl_samples_verify) and id!='$id' and (formNumber='$searchQuery' or vlSampleID='$searchQuery' or concat(lrCategory,lrEnvelopeNumber,'/',lrNumericID) like '$searchQuery%') order by if(lrCategory='',1,0),lrCategory, if(lrEnvelopeNumber='',1,0),lrEnvelopeNumber, if(lrNumericID='',1,0),lrNumericID,created desc limit 1","id");
		}
	}
}

//envelope Number From
$searchQueryFrom=0;
$searchQueryTo=0;
//$searchQueryCurrentPosition=0;
//$searchQueryNextPosition=0;
if($envelopeNumberFrom && $envelopeNumberTo) {
	$searchQueryFrom=validate(vlDecrypt($envelopeNumberFrom));
	$searchQueryTo=validate(vlDecrypt($envelopeNumberTo));
	$searchQueryCurrentPosition=getDetailedTableInfo3("vl_samples y","y.vlSampleID='".getDetailedTableInfo2("vl_samples","id='$id'","vlSampleID")."'","(select count(x.id) from vl_samples x where concat(x.lrCategory,x.lrEnvelopeNumber)>='$searchQueryFrom' and concat(x.lrCategory,x.lrEnvelopeNumber)<='$searchQueryTo' and x.vlSampleID<=y.vlSampleID order by if(x.lrCategory='',1,0),x.lrCategory, if(x.lrEnvelopeNumber='',1,0),x.lrEnvelopeNumber, if(x.lrNumericID='',1,0),x.lrNumericID,x.created desc)","position");
	if($searchQueryCurrentPosition) {
		$searchQueryNextPosition=getDetailedTableInfo2("vl_samples","id not in (select sampleID from vl_samples_verify) and concat(lrCategory,lrEnvelopeNumber)>='$searchQueryFrom' and concat(lrCategory,lrEnvelopeNumber)<='$searchQueryTo' order by if(lrCategory='',1,0),lrCategory, if(lrEnvelopeNumber='',1,0),lrEnvelopeNumber, if(lrNumericID='',1,0),lrNumericID,created desc limit $searchQueryCurrentPosition,1","id");
		if(!$searchQueryNextPosition) {
			$searchQueryNextPosition=getDetailedTableInfo2("vl_samples","id not in (select sampleID from vl_samples_verify) and id!='$id' and concat(lrCategory,lrEnvelopeNumber)>='$searchQueryFrom' and concat(lrCategory,lrEnvelopeNumber)<='$searchQueryTo' order by if(lrCategory='',1,0),lrCategory, if(lrEnvelopeNumber='',1,0),lrEnvelopeNumber, if(lrNumericID='',1,0),lrNumericID,created desc limit 1","id");
		}
	}
}

if($saveChangesReturn || $saveChangesProceed) {
	//validate data
	$error=0;
	$error=checkFormFields("Received_Status::$outcome");

	//is gender male and pregnancy set to yes?
	if($outcome=="Rejected" && !$outcomeReasonsID) {
		$error.="<br /><strong>Received Status is Rejected but no Rejection Reason Provided.<br />";
	}

	//input data
	if(!$error) {
		//log status
		mysqlquery("insert into vl_samples_verify 
						(sampleID,outcome,outcomeReasonsID,comments,created,createdby) 
						values 
						('$id','$outcome','$outcomeReasonsID','$comments','$datetime','$trailSessionUser')");
		//redirect to home with updates on the tracking number
		if($saveChangesProceed) {
			//proceed to next sample within the search results
			if($encryptedSample && $searchQueryCurrentPosition && $searchQueryNextPosition) {
				go("/verify/approve.reject/$searchQueryNextPosition/$pg/search/$encryptedSample/1/");
			} elseif($envelopeNumberFrom && $envelopeNumberTo && $searchQueryCurrentPosition && $searchQueryNextPosition) {
				go("/verify/approve.reject/$searchQueryNextPosition/$pg/search/$envelopeNumberFrom/$envelopeNumberTo/1/");
			}
		} elseif(!$searchQueryNextPosition || $saveChangesReturn) {
			if($encryptedSample) {
				go("/verify/search/$encryptedSample/pg/$pg/modified/");
			} elseif($envelopeNumberFrom && $envelopeNumberTo) {
				go("/verify/search/$envelopeNumberFrom/$envelopeNumberTo/pg/$pg/modified/");
			} else {
				go("/verify/$pg/modified/");
			}
		} else {
			go("/verify/$pg/modified/");
		}
	}
}
?>
<script Language="JavaScript" Type="text/javascript">
<!--
function validate(samples) {
	//check for missing information
	if(!document.samples.outcome.value) {
		alert('Missing Mandatory Field: Received Status');
		document.samples.outcome.focus();
		return (false);
	}
	if(document.samples.outcome.value=='Rejected' && !document.samples.outcomeReasonsID.value) {
		alert('Received Status is Rejected but Reason has not been specified');
		document.samples.outcomeReasonsID.focus();
		return (false);
	}
	return (true);
}

function checkOutcome() {
	if(document.samples.outcome.value=='Rejected') {
		var outcome='';
		outcome='<select name="outcomeReasonsID" id="outcomeReasonsID" class="search">';
		outcome+='<option value="">Select Rejection Reason</option>';
		<?
		$query=0;
		$query=mysqlquery("select * from vl_appendix_samplerejectionreason where sampleTypeID='".getDetailedTableInfo2("vl_samples","id='$id'","sampleTypeID")."' order by position");
		if(mysqlnumrows($query)) {
			while($q=mysqlfetcharray($query)) {
				echo "outcome+='<option value=\"$q[id]\">".preg_replace("/'/s","\'",$q["appendix"])."</option>';";
			}
		}
		?>
		outcome+='</select>';
		document.getElementById("outcomeID").innerHTML=outcome;
	} else {
		document.getElementById("outcomeID").innerHTML="";
	}
}
//-->
</script>
<!--<form name="samples" method="post" action="/verify/approve.reject/<?=$id?>/<?=$pg?>/" onsubmit="return validate(this)">-->
<form name="samples" method="post" action="/verify/approve.reject/<?=$id?>/<?=$pg?>/">
<table width="100%" border="0" class="vl">
          <? if($success) { ?>
            <tr>
                <td class="vl_success">Sample Approval/Rejection Status Captured Successfully!</td>
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
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/verify/">VERIFY SAMPLES</a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>APPROVE/REJECT SAMPLE</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                        <tr>
                          <td>Sample&nbsp;Reference&nbsp;#</td>
                          <td><?=getDetailedTableInfo2("vl_samples","id='$id'","vlSampleID")?></td>
                        </tr>
                        <? if(getDetailedTableInfo2("vl_samples","id='$id'","lrNumericID")) { ?>
                        <tr>
                          <td><?=($lrCategory=="V"?"Location":"Rejection")?>&nbsp;ID</td>
                          <td><?=getDetailedTableInfo2("vl_samples","id='$id'","lrCategory").getDetailedTableInfo2("vl_samples","id='$id'","lrEnvelopeNumber")."/".getDetailedTableInfo2("vl_samples","id='$id'","lrNumericID")?></td>
                        </tr>
                        <? } ?>
                        <tr>
                          <td>Form&nbsp;#</td>
                          <td><?=getDetailedTableInfo2("vl_samples","id='$id'","formNumber")?></td>
                        </tr>
                        <tr>
                          <td>Facility Name</td>
                          <td><?=getDetailedTableInfo2("vl_facilities","id='".getDetailedTableInfo2("vl_samples","id='$id'","facilityID")."'","facility")?></td>
                        </tr>
                        <tr>
                          <td>Collection&nbsp;Date</td>
                          <td><?=getFormattedDateLessDay(getDetailedTableInfo2("vl_samples","id='$id'","collectionDate"))?></td>
                        </tr>
                        <tr>
                          <td>Sample&nbsp;Type</td>
                          <td><?=getDetailedTableInfo2("vl_appendix_sampletype","id='".getDetailedTableInfo2("vl_samples","id='$id'","sampleTypeID")."' limit 1","appendix")?></td>
                        </tr>
                        <tr>
                          <td>ART&nbsp;#</td>
                          <td><?=getDetailedTableInfo2("vl_patients","id='".getDetailedTableInfo2("vl_samples","id='$id'","patientID")."'","artNumber")?></td>
                        </tr>
                        <tr>
                          <td>Other&nbsp;ID</td>
                          <td><?=getDetailedTableInfo2("vl_patients","id='".getDetailedTableInfo2("vl_samples","id='$id'","patientID")."'","otherID")?></td>
                        </tr>
                        <tr>
                          <td>Date of Birth</td>
                          <td>
                            <? $dob=getDetailedTableInfo2("vl_patients","id='".getDetailedTableInfo2("vl_samples","id='$id'","patientID")."'","dateOfBirth") ?>
                            <?=getFormattedDate($dob)?></td>
                        </tr>
                        <tr>
                          <td>Treatment&nbsp;Initiation&nbsp;Date</td>
                          <td><?=getFormattedDateLessDay(getDetailedTableInfo2("vl_samples","id='$id'","treatmentInitiationDate"))?></td>
                        </tr>

                        <? if(getDetailedTableInfo2("vl_samples","id='$id' limit 1","vlTestingRoutineMonitoring")) { ?>
                            <tr>
                              <td style="padding:5px 0px; border-bottom: 1px dashed #dfe6e6" colspan="2"><strong>Routine Monitoring</strong></td>
                            </tr>
                            <tr>
                              <td>Last&nbsp;Viral&nbsp;Load&nbsp;Date:</td>
                              <td><?=getFormattedDateLessDay(getDetailedTableInfo2("vl_samples","id='$id' limit 1","routineMonitoringLastVLDate"))?></td>
                            </tr>
                            <tr>
                              <td>Value:</td>
                              <td><?=getDetailedTableInfo2("vl_samples","id='$id' limit 1","routineMonitoringValue")?></td>
                            </tr>
                            <tr>
                              <td>Sample&nbsp;Type:</td>
                              <td><?=getDetailedTableInfo2("vl_appendix_sampletype","id='".getDetailedTableInfo2("vl_samples","id='$id' limit 1","routineMonitoringSampleTypeID")."' limit 1","appendix")?></td>
                            </tr>
                        <? } ?>

                        <? if(getDetailedTableInfo2("vl_samples","id='$id' limit 1","vlTestingRepeatTesting")) { ?>
                            <tr>
                              <td style="padding:5px 0px; border-bottom: 1px dashed #dfe6e6" colspan="2"><strong>Repeat Viral Load Test after detectable viraemia and 6 months adherence counseling</strong></td>
                            </tr>
                            <tr>
                              <td>Last&nbsp;Viral&nbsp;Load&nbsp;Date:</td>
                              <td><?=getFormattedDateLessDay(getDetailedTableInfo2("vl_samples","id='$id' limit 1","repeatVLTestLastVLDate"))?></td>
                            </tr>
                            <tr>
                              <td>Value:</td>
                              <td><?=getDetailedTableInfo2("vl_samples","id='$id' limit 1","repeatVLTestValue")?></td>
                            </tr>
                            <tr>
                              <td>Sample&nbsp;Type:</td>
                              <td><?=getDetailedTableInfo2("vl_appendix_sampletype","id='".getDetailedTableInfo2("vl_samples","id='$id' limit 1","repeatVLTestSampleTypeID")."' limit 1","appendix")?></td>
                            </tr>
                        <? } ?>

                        <? if(getDetailedTableInfo2("vl_samples","id='$id' limit 1","vlTestingSuspectedTreatmentFailure")) { ?>
                            <tr>
                              <td style="padding:5px 0px; border-bottom: 1px dashed #dfe6e6" colspan="2"><strong>Suspected Treatment Failure</strong></td>
                            </tr>
                            <tr>
                              <td>Last&nbsp;Viral&nbsp;Load&nbsp;Date:</td>
                              <td><?=getFormattedDateLessDay(getDetailedTableInfo2("vl_samples","id='$id' limit 1","suspectedTreatmentFailureLastVLDate"))?></td>
                            </tr>
                            <tr>
                              <td>Value:</td>
                              <td><?=getDetailedTableInfo2("vl_samples","id='$id' limit 1","suspectedTreatmentFailureValue")?></td>
                            </tr>
                            <tr>
                              <td>Sample&nbsp;Type:</td>
                              <td><?=getDetailedTableInfo2("vl_appendix_sampletype","id='".getDetailedTableInfo2("vl_samples","id='$id' limit 1","suspectedTreatmentFailureSampleTypeID")."' limit 1","appendix")?></td>
                            </tr>
                        <? } ?>

                        <tr>
                          <td>Captured&nbsp;By</td>
                          <td><?=getDetailedTableInfo2("vl_samples","id='$id' limit 1","createdby")?></td>
                        </tr>
                        <tr>
                          <td>On</td>
                          <td><?=getFormattedDate(getDetailedTableInfo2("vl_samples","id='$id' limit 1","created"))?></td>
                        </tr>
                        <tr>
                          <td width="20%">Received&nbsp;Status</td>
                          <td width="80%">
                          <select name="outcome" id="outcome" class="search" onchange="checkOutcome()">
							<option value="">Select Outcome</option>
							<option value="Accepted">Accepted</option>
							<option value="Rejected">Rejected</option>
							<!--<option value="Repeat">Repeat</option>-->
                          </select>
                          </td>
                        </tr>
                        <tr>
                          <td></td>
                          <td id="outcomeID"></td>
                        </tr>
                        <tr>
                          <td>Lab&nbsp;Comments</td>
                          <td><textarea name="comments" id="comments" cols="40" rows="3" class="searchLarge"></textarea></td>
                        </tr>
                      </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
              <td style="padding:10px 0px 0px 0px">
              	<? if($searchQueryNextPosition) { ?><!--<input type="submit" name="saveChangesProceed" id="saveChangesProceed" class="button" value="  Save Changes then proceed to next Sample (<?=getDetailedTableInfo2("vl_samples","id='$searchQueryNextPosition' limit 1","formNumber")?>)  " />--><input type="submit" name="saveChangesProceed" id="saveChangesProceed" class="button" value="  Save Changes then proceed to next Sample  " /><? } ?>
              	<input type="submit" name="saveChangesReturn" id="saveChangesReturn" class="button" value="  Save Changes and Return  " />
                <input type="hidden" name="encryptedSample" id="encryptedSample" value="<?=$encryptedSample?>" />
                <input type="hidden" name="envelopeNumberFrom" id="envelopeNumberFrom" value="<?=$envelopeNumberFrom?>" />
                <input type="hidden" name="envelopeNumberTo" id="envelopeNumberTo" value="<?=$envelopeNumberTo?>" />
              </td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/verify/">Return to Samples</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
          </table>
</form>