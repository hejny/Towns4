<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================

ob_end_flush();

?>
<h3>Object</h3>
Tato funkce slouží k vytvoření stavebních plánů.<br/>
<b>Upozornění: </b>Tato funkce může při nesprávném použití poškodit nultý podsvět!<br />
<b>Upozornění: </b>Tento proces může trvat i několik minut.<br/>
<b>NEXTID: </b><?php echo(sql_1data("SELECT max(id) FROM `[mpx]pos_obj` WHERE ww!='0'")-(-1)); ?><br/>
<form id="form1" name="form1" method="post" action="">
<table  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><strong>Soubor:</strong></td>

    <td><label>
      <?php echo(adminfile); ?>objects/<input name="filename" type="text" id="filename" value="models.txt" />
    </label></td>
  </tr>
    <tr>
 <td><strong>Own:</strong></td>
    <td><label>
<input name="own" type="text" id="own" value="1000078" />
    </label></td>
  </tr>
    <tr>
 <td><strong>Brutal:</strong></td>
    <td><label>
<input name="brutal" type="text" id="brutal" value="0" />
    </label></td>
  </tr>
    <tr>
    <td colspan="2"><label>
      <input type="submit" name="submit" value="OK" />
      
    </label></td>
    </tr>
</table>
</form>

<?php
//set_time_limit(4);<input type="submit" name="submit" value="B" />



if($_POST['filename']){
//echo('files/'.$_POST['filename'].'.xml');
	if(file_exists(adminfile.'objects/'.$_POST['filename'])){
		//echo('<b>hotovo</b>');
		//$sql=('DELETE FROM `'.mpx.'objects` WHERE `ww` = 0');
		//hr();echo($sql);			
		//sql_query($sql);
		//$limit=14;
		//e(adminroot.'objects/'.$_POST['filename']);
		$models=file_get_contents(adminfile.'objects/'.$_POST['filename']);
		//echo($models);
		$q=false;//$i=-1;
		foreach(split(nln,$models) as $tmp){//$i++;
		//if($ii>=$limit){exit;}
		if(trim($tmp)){
			if($q/*substr($tmp,0,1)=="[" or substr($tmp,0,1)=="{" or substr($tmp,0,1)=="("*/){

				if(strpos($tmp,'=')){

					list($key,$value)=split('=',$tmp);
					$value=trim($value);
					if(substr($value,-1)==';')$value=substr($value,0,strlen($value)-1);
					//r($key.' = '.$value);
					if($key=='id'){
						$id=intval($value);
					}elseif($key=='type'){
						$type=$value;
						/*if($_POST['submit']=='B' and $type!='building'){

						}*/
					}elseif($key=='origin'){
						$origin=$value;
						$origin=explode(',',$origin);
						$i=0;while($origin[$i]){
							if(!is_numeric($origin[$i])){
								$origin[$i]=sql_1data('SELECT id FROM `[mpx]pos_obj` WHERE `name`=\'{'.$origin[$i].'}\'');
							}
						$i++;}
						$origin=implode(',',$origin);
					}elseif($key=='description'){
						$description=$value;
					}else{
						if(strpos($key,'__')){
							list($a,$b)=split('__',$key);
							$func->addF($a,$b,$value,'profile');
						}elseif(strpos($key,'_')){
							list($a,$b)=split('_',$key);
							$func->addF($a,$b,$value);
						}else{
							$func->addF($key,$key,$value);
						}
					}

				}else{
					$q=false;
					$res=$tmp;
				}
			}else{
				$id=false;
				$func=new func();
				$description='';
				$type='building';
				$origin=false;
				$q=true;
				$name=$tmp;
				$name=str_replace(":","",$name);

			}
		}
		if($res){
			$object=new object('create');
			$object->name=trim($name);
			$object->type=$type;
			$object->origin=($origin?explode(',',$origin):array($id));
			if($origin){
				foreach(explode(',',$origin) as $oid){
					$ofunc=sql_1data('SELECT func FROM `[mpx]pos_obj` WHERE `name`=\'{'.$oid.'}\' OR id=\''.$oid.'\'');
					$ofunc=new func($ofunc);
					$func->join($ofunc);
				}
			}
			$object->func=$func;
			$object->profile->add('description',$description);
			$object->res=trim($res);
			$object->ww=0;
			$object->own=$_POST['own'];
			
			textb($object->name);br();

			r('name: '.$name);
			r('id: '.$id);
			r('description: '.$description);
			r('type: '.$object->type);
			r('origin: '.implode(',',$object->origin));
			//Wr('id: '.$id);
			r($object->func->vals2list());
			r($res);
			
			//r('id: '.$id);
			$object->update(true,true);
			
			//r('id: '.$id);

			

			if(/*$id==register_building*/$type=='building'){
				//$objectx=new object($object->id);
				$upd_name=$object->name;
				$upd_origin=implode(',',$object->origin);
				$upd_func=$object->func->vals2str();
				$upd_fs=$object->fs;
				//$reg_fp=$object->fp;
				$upd_fr=$object->fr;
				$upd_fc=$object->fc;
				$upd_fx=$object->fx;
				$upd_hard=$object->hard;
				$upd_expand=$object->expand_;
				$upd_block=$object->block_;


				$aff=sql_query("UPDATE `[mpx]pos_obj` SET name='$upd_name' ,func='$upd_func', fs='$upd_fs', fp='$upd_fs', fr='$upd_fr', fc='$upd_fc', fx='$upd_fx', hard='$upd_hard', expand='$upd_expand', block='$upd_block' WHERE (name='$upd_name' OR name='$upd_name (1)' OR name='$upd_name (2)' OR name='$upd_name (3)' OR name='$upd_name (4)') ".($_POST['brutal']?'':"AND origin='$upd_origin'")." AND ww!=0",2);br();
				//die();
				//echo(' - '.$aff);
			}

				

			if($id/* and $object->id!=$id*/){
				sql_query('DELETE FROM `[mpx]pos_obj` WHERE id='.$id,1);
				sql_query('UPDATE `[mpx]pos_obj` SET id='.$id.' WHERE id='.$object->id,2);br();
				r('reid: '.$object->id.' >>> '.$id);
			}

			r();
			br();

			//if($type=='building')exit;
			//exit;
			//$sql=file_get_contents(adminroot.'sql/unique.sql');
			//$sql=str_replace('[id]',nextid(),$sql);
			//$sql=str_replace('[mpx]',mpx,$sql);
			//$sql=str_replace('[name]',trim($name),$sql);
			//$sql=str_replace('[res]',trim($res),$sql);
			//hr();echo($sql);			
			//sql_query($sql);
			$res="";
		}

		}

		//br();
		/*if($reg_name){
			sql_query("UPDATE `[mpx]pos_obj` SET func='$reg_func', fs='$reg_fs', fp='$reg_fs', fr='$reg_fr', fc='$reg_fc', fx='$reg_fx', hard='$reg_hard', expand='$reg_expand', collapse='$reg_collapse' WHERE name='$reg_name'",2);

		}*/


		sql_query("UPDATE `[mpx]pos_obj` SET fp=fs WHERE ww='0'");
		br();echo('<b>hotovo</b>');
	}else{
		echo('Soubor neexistuje!');
		br();
	}
}
?>

