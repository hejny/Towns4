<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   core/page/map.php

   Mapa
*/
//==============================
//---------------------
/*if(!$GLOBALS['mapzoom']){
	if(!$GLOBALS['mobile']){
	    $GLOBALS['mapzoom']=1;
	}else{
	    $GLOBALS['mapzoom']=pow(gr,(1/2));
	}
}*/

//sleep(4);


//==============================

$GLOBALS['mapzoom']=1;//todo Ne natrvdo
if($_GET['glob']){$glob=true;}else{$glob=false;}
$zoom=$GLOBALS['mapzoom'];
if(!$zoom)$zoom=1;
//==============================


?>


<?php
    
    if($GLOBALS['get']['ww']){
       $GLOBALS['ss']["ww"]=intval($GLOBALS['get']['ww']);
    }


    if(logged()){
        $xc=$GLOBALS['ss']['log_object']->set->ifnot("map_xc",1);
        $yc=$GLOBALS['ss']['log_object']->set->ifnot("map_yc",1);
        $xx=$GLOBALS['ss']['log_object']->set->ifnot("map_xx",0);
        $yy=$GLOBALS['ss']['log_object']->set->ifnot("map_yy",0);
    }else{
        //$xc=1;
        //$yc=1;
        $xx=0;
        $yy=0;
        /*
        $ym=ceil(mapsize/5);//-1;
        $xm=ceil((mapsize/5-1)/2);
        $q=1.85;
        
        $xc=rand(ceil(-$xm/$q),floor($xm/$q));
        $yc=rand(ceil($ym*((1/$q)/2)),floor($ym*((1/$q)*1.5)));
        */
    

    $file=tmpfile2('positions','txt','text');

   if(debug and $_GET['addposition']){
	$position=explode(',',$_GET['addposition']);
	if(file_exists($file)){
		$positions=unserialize(file_get_contents($file));
		$positions=array_merge($positions,array($position));
		//print_r($positions);
	}else{
		$positions=array($position);
		//print_r($positions);
	}
	file_put_contents($file,serialize($positions));

   }

    if(!file_exists($file) or debug){
	    $array=sql_array('SELECT x,y FROM `[mpx]pos_obj` WHERE ww='.ww.' AND '.(rand(1,10)>7?'1':'type=\'building\' AND  own!=\'\'').' AND '.objt().' ORDER BY RAND() LIMIT 1 ');
	    list($x,$y)= $array[0];
	    //echo("$x,$y");
	    $tmp=3;
	    $xc=(-(($y-1)/10)+(($x-1)/10));
	    $yc=((($y-1)/10)+(($x-1)/10));
	    $xx=(($xc-intval($xc))*-414);
	    $yy=(($yc-intval($yc)+$tmp)*-211);
	    $xc=intval($xc)+1;
	    $yc=intval($yc)-$tmp+2;
		if(edit){
			$GLOBALS['addend']='
			<a href="?addposition='.$xc.','.$yc.','.$xx.','.$yy.'">'.lr('position_add').'</a> - 
			<a href="?addposition=">'.lr('position_cancel').'</a>';
		}
    }else{	//br(3);
		//e($file);
		$positions=unserialize(file_get_contents($file));
		//print_r($positions);
		//shuffle($positions);
		list($xc,$yc,$xx,$yy)=$positions[rand(0,count($positions)-1)];


    }    






    //$yy=$yy-200;
    }

    

    $GLOBALS['lxx']=$GLOBALS['ss']["map_xx"];
    $GLOBALS['lyy']=$GLOBALS['ss']["map_yy"];
    //if($GLOBALS['ss']["get"]["xc"]!=""){$GLOBALS['ss']["map_xc"]=$GLOBALS['ss']["get"]["xc"];}
    //if($GLOBALS['ss']["get"]["yc"]!=""){$GLOBALS['ss']["map_yc"]=$GLOBALS['ss']["get"]["yc"];}
    if($_GET["xc"]!=""){$xc=$_GET["xc"];$set=1;}
    if($_GET["yc"]!=""){$yc=$_GET["yc"];$set=1;}
    if($_GET["xx"]!=""){$xx=$_GET["xx"];$set=1;}
    if($_GET["yy"]!=""){$yy=$_GET["yy"];$set=1;}
    if($GLOBALS['get']["xc"]!=""){$xc=$GLOBALS['get']["xc"];$set=1;}
    if($GLOBALS['get']["yc"]!=""){$yc=$GLOBALS['get']["yc"];$set=1;}
    if($GLOBALS['get']["xx"]!=""){$xx=$GLOBALS['get']["xx"];$set=1;}
    if($GLOBALS['get']["yy"]!=""){$yy=$GLOBALS['get']["yy"];$set=1;}
    $GLOBALS['ss']["map_xc"]=$xc;
    $GLOBALS['ss']["map_yc"]=$yc;
    $GLOBALS['ss']["map_xx"]=$xx;
    $GLOBALS['ss']["map_yy"]=$yy;
    $GLOBALS['xc']=$xc;
    $GLOBALS['yc']=$yc;
    $GLOBALS['xx']=$xx;
    $GLOBALS['yy']=$yy;
    //------------------------------
    if(logged() and $set==1){
        $GLOBALS['ss']['log_object']->set->add("map_xc",$xc);
        $GLOBALS['ss']['log_object']->set->add("map_yc",$yc);
        $GLOBALS['ss']['log_object']->set->add("map_xx",$xx);
        $GLOBALS['ss']['log_object']->set->add("map_yy",$yy);
    }
    
    
    if(logged()){
	$xc_=$GLOBALS['ss']['log_object']->set->ifnot("map_xc",false);
	$yc_=$GLOBALS['ss']['log_object']->set->ifnot("map_yc",false);
	$xx_=$GLOBALS['ss']['log_object']->set->ifnot("map_xx",false);
	$yy_=$GLOBALS['ss']['log_object']->set->ifnot("map_yy",false);
	//e("$xc_,$yc_,$xx_,$yy_");
	if($xc_===false or $yc_===false or $xx_===false or $yy_===false){//e('888');
		//$url=centerurl($GLOBALS['hl'],$GLOBALS['hl_x'],$GLOBALS['hl_y'],$GLOBALS['hl_ww']);
		//click($url,1);
		$nothing=true;

	    /*$x=$GLOBALS['hl_x'];
	    $y=$GLOBALS['hl_y'];
	    //e("($x,$y)");
		if(!$GLOBALS['mobile']){
			$posuv=0;
		}else{
			$posuv=400;//530;
		}
	    $tmp=3;
	    $xc=(-(($y-1)/10)+(($x-1)/10));
	    $yc=((($y-1)/10)+(($x-1)/10));
	    $xx=(($xc-intval($xc))*-414)-($posuv);
	    $yy=(($yc-intval($yc)+$tmp)*-211);
	    $xx=round($xx/$GLOBALS['mapzoom']);
	    $yy=round($yy/$GLOBALS['mapzoom']);
	    $xc=intval($xc);
	    $yc=intval($yc)-$tmp;
	    $posuv=($posuv/$GLOBALS['mapzoom'])+(2.5*(424-(424/$GLOBALS['mapzoom'])));
	    $set=1;*/

	}
    }
    
    
    
    //e("$xc,$yc,$xx,$yy");

//------------------------------------------------------------------------------------------------------WHERE PREPARE

$say="''";//"(SELECT IF((`[mpx]text`.`timestop`=0 OR ".time()."<=`[mpx]text`.`timestop`),`[mpx]text`.`text`,'')  FROM `[mpx]text` WHERE `[mpx]text`.`from`=`[mpx]pos_obj`.id AND `[mpx]text`.`type`='chat' ORDER BY `[mpx]text`.time DESC LIMIT 1)";
//$say="'ahoj'";
$profileown="(SELECT `profile` from `[mpx]pos_obj` as x WHERE x.`id`=`[mpx]pos_obj`.`own` LIMIT 1) as `profileown`";
$xcu=0;
$ycu=0;
if($GLOBALS['ss']["map_xc"])$xcu=$GLOBALS['ss']["map_xc"];
if($GLOBALS['ss']["map_yc"])$ycu=$GLOBALS['ss']["map_yc"];
//echo($xcu.','.$ycu);

$xu=($ycu+$xcu)*5+1;
$yu=($ycu-$xcu)*5+1;

//echo(tab(150).$xxu);
$rxp=424*2.5;//+$xxu;
$ryp=0;//+$yyu;
//$p=(200*0.75*((212)/375));
$px=424/10;$py=$px/2;


$whereplay=($GLOBALS['get']['play']?'':' AND '.objt());

//============================================================časování


if($GLOBALS['get']['play']){

	//--------------------------------------------------------

	/*if(!$history)$history=1;

	$timenow=time();
    $xcu=0;
    $ycu=0;
    if($GLOBALS['ss']["map_xc"])$xcu=$GLOBALS['ss']["map_xc"];
    if($GLOBALS['ss']["map_yc"])$ycu=$GLOBALS['ss']["map_yc"];
    $xu=($ycu+$xcu)*5+1;
    $yu=($ycu-$xcu)*5+1;
	//(!mobile){



	$range="(x-y)>($xu-$yu)-".(20)." AND (x+y)>($xu+$yu)+".(5)." AND (x-y)<($xu-$yu)+".(35)." AND (x+y)<($xu+$yu)+".(60)."";
	//}else{
	//$range="(x-y)>($xu-$yu)-20 AND (x+y)>($xu+$yu)+5 AND (x-y)<($xu-$yu)+10 AND (x+y)<($xu+$yu)+50";
	//}


	$starttimes=sql_array('SELECT DISTINCT starttime FROM `[mpx]pos_obj` WHERE ww='.$GLOBALS['ss']["ww"].' AND `type`=\'building\' AND '.$range,1);
	$stoptimes=sql_array('SELECT DISTINCT stoptime FROM `[mpx]pos_obj` WHERE ww='.$GLOBALS['ss']["ww"].' AND `type`=\'building\' AND '.$range,1);
	$times=array();
	foreach($starttimes as $row){$times[]=$row[0];}
	foreach($stoptimes as $row){$times[]=$row[0];}
	$times[]=$timenow;
	$times=array_unique($times,SORT_NUMERIC);
	sort($times);

	$lasttime=false;$i=0;
	/*foreach($times as $time){
		if($time!=0 and $time!=$timenow){
			$timex=timer($time);
		}elseif($time==$timenow){
			$timex=lr('time_now');
		}else{
			$timex=lr('time_beginning');
		}
		//if($lasttime!==false){
			$history[]=$time;
		//}
		$lasttime=$time;
		$lasttimex=$timex;
		$i++;
	*/
}else{
    $times=array(time());

    $left=20;
    $rigth=40;
    $top=5;
    $down=60;


    $range="(x-y)>($xu-$yu)-".($left)." AND (x+y)>($xu+$yu)+".($top)." AND (x-y)<($xu-$yu)+".($rigth)." AND (x+y)<($xu+$yu)+".($down)."";


}

js('unittimes=['.implode(',',$times).'];document.maptime='.time().';');

//============================================================
?>


<?php
//if(nothing){e('nothing=1');}else{e('nothing=0');}
//if((!$_GET['first'])){e('logged=1');}else{e('logged=0');}

	if(!$nothing/** and true/**/){


   if(logged()){ ?>



    <script type="text/javascript">


    /*---------------------------------POSITION*/
    var pos2pos= function(xt,yt){
        yt=yt+<?php e(htmlbgc); ?>;
        xt=xt*<?php e($GLOBALS['mapzoom']); ?>;
        yt=yt*<?php e($GLOBALS['mapzoom']); ?>;
        xxt=(yt/212*5)+(xt/424*5);
        yyt=(yt/212*5)-(xt/424*5); /*aaa*/
        xc=<?php e($xc); ?>;
        yc=<?php e($yc); ?>;
        /*alert(yc);*/
        xxc=(yc*5)+(xc*5)-12.5+xxt; /*-17.5*/
        yyc=(yc*5)-(xc*5)+12.5+yyt; /*+17.5*/
        return([xxc,yyc]);
    }






    $(function() {



        /*---------------------------------DRAG*/
        drag=0;
        /*parseMap();*/



        $('#draglayer').draggable({ disabled: <?php e($_GET['first']?'true':'false') ?>, distance:<?php e($GLOBALS['dragdistance']); ?> });
        $( "#draglayer" ).bind( "dragstart", function(event, ui){
            /*alert('startdrag');*/
            drag=1;
            $('#draglayer').disableSelection();
            /*$('#map_context').css('display','none');*/
            $('#build_button').css('display','none');
            $('#create-build_message').html('<?php info(lr('create_move')); ?>');
        });
        $('#draglayer').disableSelection();
        $( "#draglayer" ).bind( "dragstop", function(event, ui){

            setTimeout(function(){drag=0;},100);
            parseMap();



        });


        /*---------------------------------CENTER*/
        <?php if($GLOBALS['get']['center']){ ?>
        
        
		xc=parseInt($( "#draglayer" ).css('left'));
		yc=parseInt($( "#draglayer" ).css('top'));

        $( "#draglayer" ).css('left',xc-1050+($( window ).width()/2));
        $( "#draglayer" ).css('top',yc+200/*((window.height-120)/2)*/);
		setTimeout(function(){         
        parseMapF(
		function(){
            /*$('#map_context').css('left',($( window ).width()/2));
            $('#map_context').css('top',195);
            $('#map_context').css('display','block');*/

            $('#window_objectmenu').stop(true,true);
            $("#window_objectmenu").animate({left:0}, 200);

            $(function(){$.get('?token=<?php e($_GET['token']); ?>&e=objectmenu&w=&contextid='+<?php e($GLOBALS['get']['center']); ?>, function(vystup){$('#objectmenu').html(vystup);});});
		}
		);
        },23);/**/
        <?php } ?>






        /*------------------------------------NEWVALS*/
        xc=<?php echo($xc); ?>;
        yc=<?php echo($yc); ?>;
        countdowns=[ ];
        windows="";



        aac_clickset();



});
</script>





<?php
       //---------------------------------------------------------------------------------------------------------------build
?>
<div  id="create-build"  name="create-build" style="position:absolute;display:none;top:0; left:0;z-index:25;">&nbsp;</div>
<script type="text/javascript">
            /* 3.66    3.02*/
            build_x=0;
            build_y=0;
            //window.build_master=false;
            //window.build_id=false;
            $("#create-build").css("left",(screen.width/2)-55);
            $("#create-build").css("top",(screen.height/2)-154);


            build=function(master,id,func) {

                //console.log(master+','+id+','+func);
                window.build_master=master;
                window.build_id=id;
                window.build_func=func


                turnmap('expand',true);
                $('#create-build').css('display','block');
		
                $('#create-build').draggable({
                    'distance': <?php e($GLOBALS['dragdistance']); ?>,
                    'stop': function(event, ui){

                        //alert('bbb');
                        //-----------------------------------------------------@todo sjednotit kopie
                        bx=parseFloat($("#create-build").css("left"));
                        by=parseFloat($("#create-build").css("top"));
                        offset =  $("#map_canvas").offset();
                        xt=(bx-offset.left);/*pozice myši px*/
                        yt=(by-offset.top);
                        tmp=pos2pos(xt,yt);
                        xxc=xxc+4.57;
                        yyc=yyc+3.67;
                        build_x=xxc;
                        build_y=yyc;


                        $('#create-build_message').html(nacitacihtml);


                        $.get('?token=<?php e($_GET['token']); ?>&e=create-build_message&id='+window.build_id+'&master='+window.build_master+'&xx='+build_x+'&yy='+build_y, function(vystup){$('#create-build_message').html(vystup);});

                        //-----------------------------------------------------



                    }
                });
		        
		   
		    //prompt(123,'cache_create-build_'+id);
		    //OLD CACHE//if($('#cache_create-build_'+id).html()){
		    if(ifcache('create-build_'+id)){
		    
		        //alert(1);
		        html=cache('create-build_'+id);//$('#cache_create-build_'+id).html
		        //alert(html);
		        html=html.split(1234).join(build_master);
		        $('#create-build').html(html);
		    
		    }else{
		    
		        //alert(2);
		        /*alert('?e=object_build&master='+master+'&id='+id);*/
                $.get('?token=<?php e($_GET['token']); ?>&e=create-build&master='+master+'&func='+func+'&id='+id, function(vystup){$('#create-build').html(vystup);});
		        $('#create-build').html(nacitacihtml);
		        
		   
            }
            
            
            

            }

<?php
       //---------------------------------------------------------------------------------------------------------------buildx
?>

	    buildx = function(master,id,func,build_x,build_y,_rot) {
		/*alert(_rot);*/
		/*$.get('?token=<?php e($_GET['token']) ?>&e=map_units&q='+master+'.'+func+' '+id+','+build_x+','+build_y+','+_rot, function(vystup){$('#map_units').html(vystup);});*/
		qbuffer.push(master+'.'+func+' '+id+','+build_x+','+build_y+','+_rot);
		//prompt('qbuffer',master);
		//prompt('qbuffer',qbuffer);

		newhtml=$('#build_model_'+_rot).html();
		newhtml = newhtml.replace('2px', '0px'); 
		   /*alert(newhtml);*/
                    bx=parseFloat($("#create-build").css("left"));
                    by=parseFloat($("#create-build").css("top"));
                    offset =  $("#map_canvas").offset();
                    xt=(bx-offset.left);/*pozice myši px*/
                    yt=(by-offset.top);

		newhtml='<div class="clickmap" style="position:absolute;width:0px;height:0px;"><div class="clickmap" style="position:relative;left:'+(xt)+';top:'+(yt)+';z-index:1000;">'+newhtml+'</div></div>';

		$('#units_new').html($('#units_new').html()+newhtml);



            //----------------------------------------
            infinario.track('create', {
                name: id+' created on ['+build_x+','+build_y+']',
                status: 'completed',
                player_level: <?=sql_1number('SELECT COUNT(`id`) FROM [mpx]objects WHERE type=\'building\' AND `own`='.$GLOBALS['ss']['useid'])?>
            });
            //----------------------------------------


	    }

            <?php
                /*if(defined('object_hybrid')){
                    e('alert("'.object_hybrid.'");');
                }*/
                if(defined('object_build')){
                    e('build('.$GLOBALS['ss']['master'].','.$GLOBALS['ss']['object_build_id'].',\''.$GLOBALS['ss']['object_build_func'].'\');');
                }
                if(defined('create_error')){
                    e('alert("'.create_error.'");');
                }
            ?>
</script>
<?php
if(defined('object_hybrid')){
    click('e=content;ee=create-upgrade;submenu=1;start=1;id='.object_hybrid);
}
?>



<?php } ?>


<?php
    //------------------------------------------------------------------------------------------------------------------
?>



<div style="top:<?php  echo($yy); ?>;left:<?php  echo($xx); ?>;z-index:20;" id="draglayer">
<?php

if(!$_GLOBALS['noxmap']) {

    $_GLOBALS['noxmap'] = false;

    $stream1 = '';
    $stream2 = '';
    $stream3 = '';
    //$mapsize=20;
    /* $screen=1270;

     if(!$glob){
     if(1){
         $ym=6;//6;//$mapsize/5+1;//-1;
         $xm=5;//5;//5;//ceil(($mapsize/5-1)/2);
     }else{
         $ym=3;
         $xm=2;
     }
     }else{
         $ym=11;
         $xm=8;
     }

     $xmp=1;
     //echo($xm);
     $ym=$ym-1;$xm=$xm-1;$xm=$xm/2;
     $size=$screen/($xm+$xm+1);//750;*/


    $canvasjs = '

        document.maptime=' . time() . ';

        var all_images=[];
        var canvas = document.getElementById("map_canvas");

        $("#map_canvas").attr("width",parseInt($(window).width()*1.6));
        $("#map_canvas").attr("height",parseInt($(window).height()*1.6));

        var ctx = canvas.getContext("2d");





        var canvas_bg = document.getElementById("map_canvas_bg");

        $("#map_canvas_bg").attr("width",parseInt($(window).width()*1.6));
        $("#map_canvas_bg").attr("height",parseInt($(window).height()*1.6));

        var ctx_bg = canvas_bg.getContext("2d");


    ';

    /*$aii_bg=0;


    for($y=$yc; $y<=$ym+$yc; $y++){


        for ($x=-$xm+$xc; $x<=$xm+$xc+$xmp; $x++) {

        $q=1;

            $url=htmlmap($x,$y,1);
            /*$canvasjs.='
              var imageObj'.md5($url).' = new Image();

              imageObj'.md5($url).'.onload = function() {
                ctx.drawImage(imageObj'.md5($url).', '.(round(424/$zoom)*($x-(-$xm+$xc))).', '.round(211/$zoom)*($y-$yc).');
              };
              imageObj'.md5($url).'.src = "'.rebase(url.$url).'";

            ';

            $url=rebase(url.$url);

            //ebr($url);


            $canvasjs.="all_images_bg[$aii_bg] = new Image();";
            $canvasjs.="all_images_bg[$aii_bg].src='{$url}';";
            $canvasjs.="all_images_bg[$aii_bg].style.left=".(round(424/$zoom)*($x-(-$xm+$xc))).";";
            $canvasjs.="all_images_bg[$aii_bg].style.top=".(round(211/$zoom)*($y-$yc)).";";
            $canvasjs.="all_images_bg[$aii_bg].width=".round(424/$zoom).";";
            $canvasjs.="all_images_bg[$aii_bg].height=".round(211/$zoom).";";
            $canvasjs.="all_images_bg[$aii_bg].ll='terrain';";
            $canvasjs.="all_images_bg[$aii_bg].starttime=0;";
            $canvasjs.="all_images_bg[$aii_bg].stoptime=0;";
            $aii_bg++;


            $canvasjs.="all_images_bg[$aii_bg] = new Image();";
            $canvasjs.="all_images_bg[$aii_bg].src='".mapgrid()."';";
            $canvasjs.="all_images_bg[$aii_bg].style.left=".(round(424/$zoom)*($x-(-$xm+$xc))).";";
            $canvasjs.="all_images_bg[$aii_bg].style.top=".(round(211/$zoom)*($y-$yc)).";";
            $canvasjs.="all_images_bg[$aii_bg].width=".round(424/$zoom).";";
            $canvasjs.="all_images_bg[$aii_bg].height=".round(211/$zoom).";";
            $canvasjs.="all_images_bg[$aii_bg].ll='grid';";
            $canvasjs.="all_images_bg[$aii_bg].starttime=0;";
            $canvasjs.="all_images_bg[$aii_bg].stoptime=0;";
            $aii_bg++;



        }

    }*/

    //--------------------------------------------------------


    $maparray=sql_list("SELECT `x`,`y`,`id` FROM `[mpx]positions` WHERE ww=".$GLOBALS['ss']["ww"]." AND id>999 AND id<2000 AND ".$range.$whereplay.' ORDER BY `y`,`x`');


    /*$maparray=array();
    $i=-1;
    $py=false;
    foreach($sql as $row){
        list($x,$y,$res)=$row;
        if($y!=$py){$i++;$maparray[$i]=array();}

        //list($res)=explode(':',$res);
        //$res=substr($res,1);
        $maparray[$i][]="'$res'";

    }



    foreach($maparray as &$row){
        $row=implode(',',$row);
        $row="[$row]";
    }
    $maparray=implode(',',$maparray);
    $maparray="maparray=[$maparray];";*/

    foreach($maparray as &$row){

        /*$row[0]=intval($row[0]);
        $row[1]=intval($row[1]);*/
        $row[2]=$row[2]-1000;

        $row=implode(',',$row);
        $row=str_replace('.000','',$row);
        $row="[$row]";

    }
    $maparray=implode(',',$maparray);
    $maparray="maparray=[$maparray];";
    $canvasjs.=$maparray;

    //-------------------------------

    //e('<div style="position:absolute;width:0px;height:0px;"><div style="position:relative;top:'.(htmlbgc/$zoom).'px;left:0px;z-index:100;">'.$stream1.'</div></div>');


    subexec('map_units');

    $aii=0;

    foreach($GLOBALS['all_images'] as $image){


        $image[2]=round($image[2]);
        $image[3]=round($image[3]);
        $image[4]=round($image[4]);
        $image[5]=round($image[5]);
        $image[6]=round($image[6]);
        $image[7]=round($image[7]);

        $image[9]=round($image[9]);
        $image[10]=round($image[10]);
        $image[11]=round($image[11]);

        $canvasjs.="all_images[$aii] = new Image();";
        $canvasjs.="all_images[$aii].setAttribute('objectid',{$image[0]});";
        $canvasjs.="all_images[$aii].src='{$image[1]}';";

        $canvasjs.="all_images[$aii].setAttribute('x',{$image[2]});";
        $canvasjs.="all_images[$aii].setAttribute('y',{$image[3]});";
        $canvasjs.="all_images[$aii].setAttribute('x2',{$image[4]});";
        $canvasjs.="all_images[$aii].setAttribute('y2',{$image[5]});";

        $canvasjs.="all_images[$aii].width={$image[6]};";
        $canvasjs.="all_images[$aii].height={$image[7]};";


        /*$canvasjs.="all_images[$aii].setAttribute('ll',{$image[8]});";
        $canvasjs.="all_images[$aii].setAttribute('starttime',{$image[9]});";
        $canvasjs.="all_images[$aii].setAttribute('readytime',{$image[10]});"; //@todo udělat postupné stavění v timeplay
        $canvasjs.="all_images[$aii].setAttribute('stoptime',{$image[11]});";*/


        $canvasjs.="all_images[$aii].ll='{$image[8]}';";
        $canvasjs.="all_images[$aii].starttime={$image[9]};";
        $canvasjs.="all_images[$aii].readytime={$image[10]};";
        $canvasjs.="all_images[$aii].stoptime={$image[11]};";


        $canvasjs.="all_images[$aii].expand=".intval($image[12]).';'.nln;
        $canvasjs.="all_images[$aii].block=".intval($image[13]).';'.nln;
        $canvasjs.="all_images[$aii].attack=".intval($image[14]).';'.nln;
        $canvasjs.="all_images[$aii].speed=".intval($image[15]).';'.nln;

        //$canvasjs.="all_images[$aii].starttime=0;";
        //$canvasjs.="all_images[$aii].readytime=0;";
        //$canvasjs.="all_images[$aii].stoptime=0;";

        //var allImages['.md5($modelurl).'] = new Image();
        //$canvasjs.='all_images[]=['.implode(',',$image).'];';

        $aii++;
    }
    //------------------------------------------------------------------------------------------------------------------drawbg
    $canvasjs.='


        var imgs_count = all_images.length;

        //console.log(all_images_bg);
        //alert(imgs_loaded_bg+"/"+imgs_count_btr);

        drawbg=function(){
            ww=all_images_bg[maparray[0][2]*'.maxseed.'].width;
            hh=all_images_bg[maparray[0][2]*'.maxseed.'].height;
        ';

        foreach(array('tree','rock') as $treerock)
        $canvasjs .= '
            ww_'.$treerock.'=82*1.11/'.$GLOBALS['mapzoom'].';//119;//all_images_'.$treerock.'[0].width;
            hh_'.$treerock.'=156*1.11/'.$GLOBALS['mapzoom'].';//254;//all_images_'.$treerock.'[0].height;
        ';


        $canvasjs .= '
            i=0;
            while(maparray.length>i){


                var x=maparray[i][0];
                var y=maparray[i][1];

                var xx=x-('.$xu.');
                var yy=y-('.$yu.');

                var rx=Math.round((('.$px.'*xx)-('.$px.'*yy)+'.$rxp.'-(ww/2))/'.$GLOBALS['mapzoom'].');
                var ry=Math.round((('.$py.'*xx)+('.$py.'*yy)+'.$ryp.'-(hh/1.41))/'.$GLOBALS['mapzoom'].');

                var k=0.6;
                var q=(Math.abs(Math.sin(x*x)*Math.cos(y*y)*k)+1);
                var ww_=ww*q;
                var hh_=hh*q;


                //console.log(rx+","+ry+","+ww+","+hh);

                var seed=((Math.pow(x,2)+Math.pow(y,3))%'.maxseed.');

                //console.log(seed);


                ctx_bg.drawImage(all_images_bg[maparray[i][2]*'.maxseed.'+seed], rx, ry, ww_,hh_);';

    //--------------------------------------------------------

    foreach(array('tree','rock') as $treerock) {
        $var = 'maxseed_' . $treerock;
        $canvasjs .= '
                if(maparray[i][2]==' . ($treerock == 'tree' ? 10 : 5) . '){

                    var maxmulti=' . ($treerock == 'tree' ? '(Math.pow(x,2)+Math.pow(y,2))%2' : 0) . ';

                    for(var multi = 0; multi < maxmulti+1; multi++){

                        //console.log(rx+","+ry+","+ww+","+hh);
                        //ctx.drawImage(all_images_' . $treerock . '[0], rx, ry-100, ww_' . $treerock . ',hh_' . $treerock . ');
                                                all_images[imgs_count] = new Image();

                        var seed=((Math.pow(x,2)+Math.pow(y,3)+multi)%' . ($treerock=='tree'?maxseed_tree:maxseed_rock) . ');

                        //console.log(seed);
                        all_images[imgs_count].src=all_images_' . $treerock . '[seed].src;


                        var k=' . ($treerock == 'tree' ? 0.1 : 0.5) . ';
                        var q=((Math.sin(x/(2+multi))*Math.cos(y)*k)+(1*(1-k)))*' . ($treerock == 'tree' ? 1 : 2) . ';
                        var ww_'.$treerock.'_=ww_'.$treerock.'*q;
                        var hh_'.$treerock.'_=hh_'.$treerock.'*q;


                        var rx_' . $treerock . '=((' . $px . '*xx)-(' . $px . '*yy)+' . $rxp . '-(ww_' . $treerock . '_/2))/' . $GLOBALS['mapzoom'] . ';
                        var ry_' . $treerock . '=((' . $py . '*xx)+(' . $py . '*yy)+' . $ryp . '-(hh/4)-(hh_' . $treerock . '_))/' . $GLOBALS['mapzoom'] . ';


                        rx_' . $treerock . '+=Math.sin(x+y*y+multi)*(ww/3);
                        ry_' . $treerock . '+=Math.cos(x*x-y+multi)*(hh/3);

                        //rx_' . $treerock . '+=Math.random()*(ww/2);
                        //ry_' . $treerock . '+=Math.random()*(hh/2);



                        all_images[imgs_count].setAttribute(\'x\',Math.round(rx_' . $treerock . '));
                        all_images[imgs_count].setAttribute(\'y\',Math.round(ry_' . $treerock . '));


                        //console.log(all_images[0].width+\',\'+all_images[0].height);
                        all_images[imgs_count].width=ww_' . $treerock . '_;
                        all_images[imgs_count].height=hh_' . $treerock . '_;


                        all_images[imgs_count].setAttribute(\'ll\',\'' . $treerock . '\');
                        all_images[imgs_count].setAttribute(\'starttime\',timestamp());
                        all_images[imgs_count].setAttribute(\'readytime\',timestamp());
                        all_images[imgs_count].setAttribute(\'stoptime\',0);


                        all_images[imgs_count].ll=\'' . $treerock . '\';
                        all_images[imgs_count].starttime=timestamp();
                        all_images[imgs_count].readytime=timestamp();
                        all_images[imgs_count].stoptime=0;

                        imgs_count++;

                    }


                }';
    }



    //--------------------------------------------------------
    $canvasjs.='
                i++;

            }




            all_images.sort(function(a, b){return parseInt(a.getAttribute(\'y\'))-parseInt(b.getAttribute(\'y\'));});
            drawmap();

        };


        drawbg();

        ';

    //------------------------------------------------------------------------------------------------------------------



        /*xx=((x-y)*(ww/3))+($("#map_canvas_bg").attr("width")/2);
          yy=(x+y)*(hh/3);*/




    $canvasjs.='

        var imgs_count = all_images.length;
        var imgs_loaded = 0;


        ctx.clearRect ( 0 , 0 , canvas.width, canvas.height );
        i=0;while(i<imgs_count){

            if(jQuery.inArray(all_images[i].ll,drawmaplayers)!=-1){


                 pos = position(
                    all_images[i].getAttribute(\'x\'),
                    all_images[i].getAttribute(\'y\'),
                    all_images[i].getAttribute(\'x2\'),
                    all_images[i].getAttribute(\'y2\'),

                    all_images[i].starttime,
                    all_images[i].readytime

                );
                ctx.drawImage(all_images[i], Math.round(pos[0]), Math.round(pos[1]), all_images[i].width, all_images[i].height);

            }



            i++;
        }
        ';


        /*
        $(all_images).load(function() {

            if(jQuery.inArray(this.ll,drawmaplayers)!=-1){



                pos = position(
                    this.getAttribute(\'x\'),
                    this.getAttribute(\'y\'),
                    this.getAttribute(\'x2\'),
                    this.getAttribute(\'y2\'),

                    this.starttime,
                    this.readytime

                );
                ctx.drawImage(this,pos[0],pos[1],this.width,this.height);


            }

            imgs_loaded++;

            if(imgs_loaded === imgs_count) {

                drawmap();

                setInterval(function(){
                    drawmap();
                },1000);


            }

        });*/



    js($canvasjs);


    e('<div style="position:absolute;width:0px;height:0px;" class="noselect"><div style="position:relative;top:'.round(htmlbgc/$zoom).'px;z-index:100;">
    <canvas id="map_canvas" style="cursor:move;" class="clickmap" width="'./*round(424/$zoom)*(($xm*2)+2)*/round($GLOBALS['ss']['screenwidth']*gr).'" height="'./*round(211/$zoom)*($ym+1)*/round($GLOBALS['ss']['screenheight']*gr).'"></canvas>
    </div></div>');

    e('<div style="position:absolute;width:0px;height:0px;" class="noselect"><div style="position:relative;top:'.round(htmlbgc/$zoom).'px;z-index:90;">
    <canvas id="map_canvas_bg" width="'.round($GLOBALS['ss']['screenwidth']*gr).'" height="'.round($GLOBALS['ss']['screenheight']*gr).'"></canvas>
    </div></div>');





    e('<div style="position:absolute;width:0px;height:0px;"><div style="position:relative;top:'.(htmlunitc/$zoom).'px;left:0px;z-index:400;" id="units_stream">'.$GLOBALS['units_stream']/**/.'</div></div>');


    e('<div style="position:absolute;width:0px;height:0px;"><div style="position:relative;top:'.(htmlbgc/$zoom).'px;left:0px;z-index:500;" class="clickmap" id="units_new"></div></div>');

}

//---------------------------------------------------------------------------------------------------


/*echo('<script type="text/javascript">'.nln);
$d=17;
$xa=intval($GLOBALS['ss']['use_object']->x-$d);
$xb=intval($GLOBALS['ss']['use_object']->x+$d);
$ya=intval($GLOBALS['ss']['use_object']->y-$d);
$yb=intval($GLOBALS['ss']['use_object']->y+$d);
if($xa<1){$xa=1;$xb=1+$d+$d;}
if($ya<1){$ya=1;$yb=1+$d+$d;}
if($xb>mapsize){$xb=mapsize;}
if($yb>mapsize){$yb=mapsize;}
echo('area_x='.$xa.';'.nln);
echo('area_y='.$ya.';'.nln);
//e('alert('.$GLOBALS['ss']['use_object']->x.');');
echo('area=['.nln);
foreach(sql_array("SELECT x,y,hard FROM `[mpx]map` WHERE ww=".$GLOBALS['ss']["ww"]." AND x>=$xa AND y>=$ya AND x<=$xb AND y<=$yb ORDER BY y,x") as $row){
    list($area_x,$area_y,$area_hard)=$row;
    $q=1-$area_hard;
    if($q<0.2)$q=0;
    if($q>1)$q=1;
    //echo("($area_x==$xa)");
    if($area_x==$xa)echo('[');
    echo($q);
    if($area_x!=$xb)echo(',');
    if($area_x==$xb)echo(']');
    if($area_x==$xb and $area_y!=$yb)echo(','.nln);
}
echo('];'.nln);
echo('</script>');*/





//-------------------------------


?>

</div>


<?php


}
?>

