<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}
?>
  <table width="100%" border="0">
    <tr>
      <td width="65%" valign="top">
		<? if($saved) { ?>
            <table width="100%" border="0" class="vl">
                <tr>
                    <td class="vl_success">Appendix Added!</td>
                </tr>
                <tr>
                    <td><img src="/appendix/images/spacer.gif" width="3" height="3" /></td>
                </tr>
            </table>
        <? } ?>
		<?
        switch($option) {
            case add:
				//check for missing variables
				$error=0;
				$error="";
				//sample type
				if(!$sampleTypeID)
					$error.="<br>No Sample Type Selected";
				//appendix
				if(!$appendix)
					$error.="<br>No Appendix provided";
				//position
				if(!$position)
					$error.="<br>No Position provided";

				//process
				if(!$error) {
					//ensure no duplicates
					if(!isQuery("select * from vl_appendix_samplerejectionreason where appendix='$appendix' and sampleTypeID='$sampleTypeID'")) {
						//insert into vl_appendix_samplerejectionreason
						mysqlquery("insert into vl_appendix_samplerejectionreason 
								(appendix,position,sampleTypeID,created,createdby) 
								values 
								('$appendix','$position','$sampleTypeID','$datetime','$_SESSION[VLADMIN]')");
						//flag
						$added=1;
					} else {
						$error.="<br>The supplied Appendix <strong>$appendix</strong> is already within the system for the Sample Type: ".getDetailedTableInfo2("vl_appendix_sampletype","id='$sampleTypeID'","appendix");
					}
				}
            break;
            case modify:
				//log table change
				logTableChange("vl_sampleTypeID_samplerejectionreason","sampleTypeID",$id,getDetailedTableInfo2("vl_sampleTypeID_samplerejectionreason","id='$id'","sampleTypeID"),$sampleTypeID);
				logTableChange("vl_appendix_samplerejectionreason","appendix",$id,getDetailedTableInfo2("vl_appendix_samplerejectionreason","id='$id'","appendix"),$appendix);
				logTableChange("vl_appendix_samplerejectionreason","position",$id,getDetailedTableInfo2("vl_appendix_samplerejectionreason","id='$id'","position"),$position);
				//update vl_appendix_samplerejectionreason
				mysqlquery("update vl_appendix_samplerejectionreason set sampleTypeID='$sampleTypeID',appendix='$appendix',position='$position' where id='$id'");
				//flag
				$modified=1;
            break;
            case remove:
				if(isQuery("select * from vl_appendix_samplerejectionreason where id='$id'")) {
					//remove
					logDataRemoval("delete from vl_appendix_samplerejectionreason where id='$id'");
					mysqlquery("delete from vl_appendix_samplerejectionreason where id='$id'");
					//flag
					$removed=1;
				}
            break;
            default:
                if($modify) {
                    $task="modify";
                }
            break;
		}
		
		//set task
		if(!$task) {
			$task="add";
		}
?>
        <script Language="JavaScript" Type="text/javascript">
		<!--
        function checkForm(appendicesForm) {
			//missing sample type
			if(!document.appendicesForm.sampleTypeID.value) {
				alert('Missing Mandatory Field: Sample Type');
				document.appendicesForm.sampleTypeID.focus();
				return (false);
			}
			//missing appendix
			if(!document.appendicesForm.appendix.value) {
				alert('Missing Mandatory Field: Appendix');
				document.appendicesForm.appendix.focus();
				return (false);
			}
			//missing position
			if(!document.appendicesForm.position.value) {
				alert('Missing Mandatory Field: Position');
				document.appendicesForm.position.focus();
				return (false);
			}
            return (true);
        }
        //-->
        </script>
        
        <form name="appendicesForm" method="post" action="?act=asamplerejectionreasons&nav=configuration" onsubmit="return checkForm(this)">
          <table width="90%" border="0" class="vl">
		<? if($added) { ?>
            <tr>
              <td class="vl_success">Added!</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
		<? } else if($modified) { ?>
            <tr>
              <td class="vl_success"><?=number_format((float)$modified)?> appendi<?=($modified!=1?"ces":"x")?> modified!</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
		<? } else if($removed) { ?>
            <tr>
              <td class="vl_success">Removed!</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
		<? } else if($error) { ?>
            <tr>
              <td class="vl_error">Unable to process your submission due to the following error(s): <?=$error?></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
		<? } ?>
        <? if($task=="add") { ?>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Add Appendix</td>
            </tr>
        <? } else { ?>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Manage Appendices</td>
            </tr>
        <? } ?>
            <tr> 
              <td style="padding:10px 0px 10px 0px"><table width="100%" border="0" class="vl">
                <tr>
                  <td width="30%">Sample&nbsp;Type</td>
                  <td width="70%"><select name="sampleTypeID" id="sampleTypeID" class="search">
						<?
						if($id) {
							$sampleTypeID=0;
							$sampleTypeID=getDetailedTableInfo2("vl_appendix_samplerejectionreason","id='$id'","sampleTypeID");
							$sampleType=0;
							$sampleType=getDetailedTableInfo2("vl_appendix_sampletype","id='$sampleTypeID'","appendix");
							echo "<option value=\"$sampleTypeID\" selected=\"selected\">$sampleType</option>";
						}
                        $squery=0;
						$squery=mysqlquery("select * from vl_appendix_sampletype order by position");
						if(mysqlnumrows($squery)) {
							while($sq=mysqlfetcharray($squery)) {
		                        echo "<option value=\"$sq[id]\">$sq[appendix]</option>";
							}
                        }
                        ?>
                        </select></td>
                </tr>
                <tr>
                  <td>Appendix</td>
                  <td><input type="text" name="appendix" id="appendix" class="search" size="25" value="<?=($id?getDetailedTableInfo2("vl_appendix_samplerejectionreason","id='$id'","appendix"):"")?>"></td>
                </tr>
                <tr>
                  <td>Position</td>
                  <td><select name="position" id="position" class="search">
						<?
						//get max number of records
						$position=0;
						if(!$id) {
							$position=getDetailedTableInfo3("vl_appendix_samplerejectionreason","appendix!=''","count(id)","num");
							$position+=1;
						} else {
							$position=getDetailedTableInfo2("vl_appendix_samplerejectionreason","id='$id'","position");
						}
						echo "<option value=\"$position\" selected=\"selected\">$position</option>";
                        for($j=1;$j<=50;$j++) {
	                        echo "<option value=\"$j\">$j</option>";
                        }
                        ?>
                        </select></td>
                </tr>
              </table></td>
            </tr>
            <tr> 
              <td style="border-top:1px solid #cccccc; padding-top:10px"> 
              <input type="submit" name="button" id="button" value="   Save   " /> 
              <? if($task=="modify") { ?>
              <button type="button" id="button" name="button" value="button" onclick="document.location.href='?act=asamplerejectionreasons&nav=configuration'">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>
              <input name="id" type="hidden" id="id" value="<?=$id?>"> 
              <? } ?>
              <input name="act" type="hidden" id="act" value="asamplerejectionreasons">
              <input name="option" type="hidden" id="option" value="<?=$task?>">
              </td>
            </tr>
          </table>
        </form>

		<?
        $query=0;
        $query=mysqlquery("select * from vl_appendix_samplerejectionreason order by position");
		$num=0;
		$num=mysqlnumrows($query);
        if($num) {
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="vl_tdsub" style="padding-left:16px" width="1%"><strong>#</strong></td>
                          <td class="vl_tdsub" style="padding-left:16px" width="99%"><strong>Appendix</strong></td>
                        </tr>
					</table>
                </td>
            </tr>
            <tr>
                <td style="padding:5px 0px 5px 0px" align="center">
                	<div style="height: 200px; border: 1px solid #ccccff; overflow: auto">
					<table width="95%" border="0" cellspacing="0" cellpadding="0" class="vl">
                    	<?
                        $count=0;
                        $q=array();
                        while($q=mysqlfetcharray($query)) {
                            $count+=1;
                        ?>
                            <tr>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="1%"><?=$q["position"]?></td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="70%">
									<div><?=$q["appendix"]?></div>
                                    <div class="vls_grey" style="padding:3px 0px"><?=getDetailedTableInfo2("vl_appendix_sampletype","id='$q[sampleTypeID]'","appendix")?></div>
                                </td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="29%"><a href="?act=asamplerejectionreasons&nav=configuration&modify=modify&id=<?=$q["id"]?>">edit</a> :: <a href="javascript:if(confirm('Are you sure?')) { document.location.href='?act=asamplerejectionreasons&nav=configuration&option=remove&id=<?=$q["id"]?>'; }">delete</a></td>
                            </tr>
                        <? } ?>
                    </table>
                    </div>
                </td>
            </tr>
        </table>
		<? } ?>
      </td>
      <td width="35%" valign="top" style="padding:3px 0px 0px 12px">
        <table border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #d5d5d5" width="100%">
          <tr>
            <td style="padding:10px"><table width="100%" border="0" class="vl">
              <tr>
                <td><strong>MANAGE SAMPLE REJECTION REASONS</strong></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#d5d5d5" style="padding:10px">Create, Delete or Manage Appendices</td>
              </tr>
            </table></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>