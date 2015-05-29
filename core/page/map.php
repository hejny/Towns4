<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
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



//============================================================časování


if($GLOBALS['get']['play']){

	//--------------------------------------------------------

	if(!$history)$history=1;

	$timenow=time();
    $xcu=0;
    $ycu=0;
    if($GLOBALS['ss']["map_xc"])$xcu=$GLOBALS['ss']["map_xc"];
    if($GLOBALS['ss']["map_yc"])$ycu=$GLOBALS['ss']["map_yc"];
    $xu=($ycu+$xcu)*5+1;
    $yu=($ycu-$xcu)*5+1;
	//(!mobile){
	$range="(x-y)>($xu-$yu)-".(logged()?20:26)." AND (x+y)>($xu+$yu)+".(logged()?5:2)." AND (x-y)<($xu-$yu)+".(logged()?35:22)." AND (x+y)<($xu+$yu)+".(logged()?60:55)."";
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
}

js('unittimes=['.implode(',',$times).'];document.maptime='.time().';');
//============================================================časování staré

/*if($_GET['history'] or $_GET['play']){

	//--------------------------------------------------------
	/*function script_($script){e('<script type="text/javascript" src="'.rebase(url.base.'/'.$script).'"></script>');}
	function css_($css){e('<link rel="stylesheet" href="'.rebase(url.base.'/'.$css).'" type="text/css" />');}
	script_('lib/jquery/js/jquery-1.6.2.min.js');
	js('w_open=function(){alert(1);};');* /
	//--------------------------------------------------------
	$play=$_GET['play'];
	$history=$_GET['history']-1+1;
	if(!$history)$history=1;

	$timenow=time();
    $xcu=0;
    $ycu=0;
    if($GLOBALS['ss']["map_xc"])$xcu=$GLOBALS['ss']["map_xc"];
    if($GLOBALS['ss']["map_yc"])$ycu=$GLOBALS['ss']["map_yc"];
    $xu=($ycu+$xcu)*5+1;
    $yu=($ycu-$xcu)*5+1;
	if(!mobile){
	$range="(x-y)>($xu-$yu)-".((!$_GET['first'])?20:26)." AND (x+y)>($xu+$yu)+".((!$_GET['first'])?5:2)." AND (x-y)<($xu-$yu)+".((!$_GET['first'])?35:22)." AND (x+y)<($xu+$yu)+".((!$_GET['first'])?60:55)."";
	}else{
	$range="(x-y)>($xu-$yu)-20 AND (x+y)>($xu+$yu)+5 AND (x-y)<($xu-$yu)+10 AND (x+y)<($xu+$yu)+50";
	}
	$starttimes=sql_array('SELECT DISTINCT starttime FROM `[mpx]pos_obj` WHERE ww='.$GLOBALS['ss']["ww"].' AND `type`=\'building\' AND '.$range,1);
	$stoptimes=sql_array('SELECT DISTINCT stoptime FROM `[mpx]pos_obj` WHERE ww='.$GLOBALS['ss']["ww"].' AND `type`=\'building\' AND '.$range,1);
	$times=array();
	foreach($starttimes as $row){$times[]=$row[0];}
	foreach($stoptimes as $row){$times[]=$row[0];}
	$times[]=$timenow;
	$times=array_unique($times,SORT_NUMERIC);
	sort($times);

	$lasttime=false;$i=0;
	foreach($times as $time){
		if($time!=0 and $time!=$timenow){
			$timex=timer($time);
		}elseif($time==$timenow){
			$timex=lr('time_now');
		}else{
			$timex=lr('time_beginning');
		}
		if($lasttime!==false){
			if($history==$i){
				$GLOBALS['showtime']=$lasttime?$lasttime:1;
				e(imgr('quest/quest_finished.png',$lasttimex,17,17));
			}else{
				e('<a href="?e=map&amp;history='.$i.'&amp;play='.$play.'">'.imgr('quest/quest_nonefinished.png',$lasttimex,17,17).'</a>');
			}
		}
		$lasttime=$time;
		$lasttimex=$timex;
		$i++;
	}

	if($play)
	if($history<$i-1){
		js('setTimeout(function(){window.location.href = "?e=map&history='.($history+1).'&play=1";},200);');
	}
	if(debug)e(objt());
}*/

//============================================================
?>


<?php
//if(nothing){e('nothing=1');}else{e('nothing=0');}
//if((!$_GET['first'])){e('logged=1');}else{e('logged=0');}

	if(!$nothing/** and true/**/){


   if(logged()){ ?>


        <script type="text/javascript">


    /*---------------------------------POSITION*/
        function pos2pos(xt,yt){
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

		/*---------------------------------ANTISRAČKA*/
		$('#draglayer').attr('unselectable','on')
			 .css({'-moz-user-select':'-moz-none',
				   '-moz-user-select':'none',
				   '-o-user-select':'none',
				   '-khtml-user-select':'none', /* you could also put this in a class */
				   '-webkit-user-select':'none',/* and add the CSS class here instead */
				   '-ms-user-select':'none',
				   'user-select':'none'


			 }).bind('selectstart', function(){ return false; });



        /*---------------------------------DRAG*/
        drag=0;
        /*parseMap();*/



		$('#draglayer').draggable({ disabled: <?php e($_GET['first']?'true':'false') ?>, distance:<?php e($GLOBALS['dragdistance']); ?> });
        $( "#draglayer" ).bind( "dragstart", function(event, ui){
	    /*alert('startdrag');*/
            drag=1;
	       $('#draglayer').disableSelection();
            $('#map_context').css('display','none');
	    $('#build_button').css('display','none');
	    $('#create-build_message').html('<?php info(lr('create_move')); ?>');
        });
	   $('#draglayer').disableSelection();
         $( "#draglayer" ).bind( "dragstop", function(event, ui){

            setTimeout(function(){drag=0;},100);
            parseMap();

            

        });



    	/*$(".drag")
            .hammer({ drag_max_touches:0})
            .on("touch drag", function(ev) {
                var touches = ev.gesture.touches;

                ev.gesture.preventDefault();

                for(var t=0,len=touches.length; t<len; t++) {
                    var target = $(touches[t].target);
                    target.css({
                        zIndex: 1337,
                        left: touches[t].pageX-50,
                        top: touches[t].pageY-50
                    });
                }
            });*/





        /*---------------------------------POSITIONCLICK*/
        $("#map_context").click(function() {
            /*$('#map_context').css('display','none');*/
        });/**/



        /*$(".tabulkamapy").draggable();*/
        $(".clickmap").click(function(hovno) {
		/*alert(drag);*/
            if(drag!=1){
                /*alert("click");/**/
                $('#map_context').css('left',hovno.pageX-10);
                $('#map_context').css('top',hovno.pageY-10);
		        $('#map_context').css('border-color','#22222');
                $('#map_context').css('display','block');/**/
                offset =  $("#map_canvas").offset();



                xt=(hovno.pageX-offset.left);/*pozice myši px*/
                yt=(hovno.pageY-offset.top);
                tmp=pos2pos(xt,yt);
                xxc=tmp[0];
                yyc=tmp[1];

                //document.title=(xxc+','+yyc);

                /*alert(mouseX+','+mouseY+','+Math.round(xxc)+','+Math.round(yyc));*/
                /*$("#copy").html(xt+","+yt+" = "+(Math.round(xxc*100)/100)+","+Math.round(Math.round(yyc*100)/100)+";"+xxt+","+yyt);
                */
                tmp=1;
                title='...';/*(Math.round(xxc*tmp)/tmp)+","+Math.round(Math.round(yyc*tmp)/tmp);*/
                
                $('#map_context').html(title);
                 <?php if(logged){ ?>
		//alert('?token=<?php e($_GET['token']); ?>&e=minimenu&w=&xc='+xxc+'&yc='+yyc);
                $(function(){$.get('?token=<?php e($_GET['token']); ?>&e=minimenu&w=&xc='+(xxc)+'&yc='+(yyc), function(vystup){if(vystup.length>30)$('#map_context').html(vystup);});});
                 <?php } ?>
            }
        });

        /*---------------------------------CONTEXTCLICK*/
        /*$(document).bind("contextmenu",function(hovno){
            if(drag!=1){   
                $('#map_context').css('left',hovno.pageX-10);
                $('#map_context').css('top',hovno.pageY-10);
                $('#map_context').css('display','block');
		$('#map_context').css('border-color','#22222');
                $('#map_context').html('context');
            }
        });*/
	$(document).bind("contextmenu",function(hovno){
            if(drag!=1){

                $('#map_context').css('left',hovno.pageX-10);
                $('#map_context').css('top',hovno.pageY-10);
		        $('#map_context').css('border-color','#22222');
                $('#map_context').css('display','block');/**/
                offset =  $("#map_canvas").offset();
                /*alert(hovno.pageX);*/
                xt=(hovno.pageX-offset.left);/*pozice myši px*/
                yt=(hovno.pageY-offset.top);
                tmp=pos2pos(xt,yt);
                xxt=tmp[0];
                yyt=tmp[1];
                /*$("#copy").html(xt+","+yt+" = "+(Math.round(xxc*100)/100)+","+Math.round(Math.round(yyc*100)/100)+";"+xxt+","+yyt);
                */
                tmp=1;
                title='...';/*(Math.round(xxc*tmp)/tmp)+","+Math.round(Math.round(yyc*tmp)/tmp);*/
                
                $('#map_context').html(title);
                 <?php if(logged){ ?>
		/*alert('?e=minimenu&w=&terrain=1&x='+xxc+'&token='+yyc);*/
                $(function(){$.get('?token=<?php e($_GET['token']); ?>&e=menu&menuid=menu_map', function(vystup){if(vystup.length>30)$('#map_context').html(vystup);});});
                 <?php } ?>
            }
        });



        /*---------------------------------MENUCLICK*/
		  aac_clickset=function(hovno) {

        $(".menu").not(".x-menu-registered").click(function(hovno) {
            if(drag!=1){   
                $('#map_context').css('left',hovno.pageX-10);
                $('#map_context').css('top',hovno.pageY-10);
		$('#map_context').css('border-color','#999999');
                $('#map_context').css('display','block');
                $('#map_context').html('...');
		name=$(this).attr('id');
                $(function(){$.get('?token=<?php e($_GET['token']); ?>&e=menu&menuid='+name, function(vystup){$('#map_context').html(vystup);});});
            }
        }).addClass("x-menu-registered");


        /*---------------------------------UNITCLICK*/
        $(".unit").not(".x-unit-minimenu-registered").click(function(hovno) {
            if(drag!=1){   
                $('#map_context').css('left',hovno.pageX-10);
                $('#map_context').css('top',hovno.pageY-10);
		$('#map_context').css('border-color','#22222');
                $('#map_context').css('display','block');
                title=$(this).attr('title');
                name=$(this).attr('id');



                offset =  $("#map_canvas").offset();
                /*alert(hovno.pageX);*/
                xt=(hovno.pageX-offset.left);/*pozice myši px*/
                yt=(hovno.pageY-offset.top);
                tmp=pos2pos(xt,yt);
                xxt=tmp[0];
                yyt=tmp[1];



		if(ifcache('minimenu_'+name)){
			$('#map_context').html(cache('minimenu_'+name));
		}else{
            $('#map_context').html(title);
		}
		
		
                 <?php if(logged){ ?>
                $(function(){$.get('?token=<?php e($_GET['token']); ?>&e=minimenu&w=&contextid='+name+'&contextname='+title+'&xc='+xxc+'&yc='+yyc, function(vystup){$('#map_context').html(vystup);});});
                 <?php } ?>
            }
        }).addClass("x-unit-minimenu-registered");/**/


		  };/**/
		  aac_clickset();
        /*---------------------------------CENTER*/
        <?php if($GLOBALS['get']['center']){ ?>
        
        
		xc=parseInt($( "#draglayer" ).css('left'));
		yc=parseInt($( "#draglayer" ).css('top'));

        $( "#draglayer" ).css('left',xc-1050+($( window ).width()/2));
        $( "#draglayer" ).css('top',yc+200/*((window.height-120)/2)*/);
		setTimeout(function(){         
        parseMapF(
		function(){
        $('#map_context').css('left',($( window ).width()/2));
        $('#map_context').css('top',195);
        $('#map_context').css('display','block');
        $(function(){$.get('?token=<?php e($_GET['token']); ?>&e=minimenu&w=&contextid='+<?php e($GLOBALS['get']['center']); ?>, function(vystup){$('#map_context').html(vystup);});});
		}
		);
        },23);/**/
        <?php } ?>





        /*---------------------------------------------------------------------Upload to map*/

        <?php if(logged){ ?>

        $('.clickmap').filedrop({


            paramname:'file',

            maxfiles: 1,
            maxfilesize: <?=intval(ini_get('post_max_size')); ?>,
            url: '?e=create-post_file',

            allowedfileextensions: ['.jpg','.jpeg','.png','.gif','.bmp','.wbmp'],

            uploadFinished:function(i,file,response){
            $.data(file).addClass('done');
            // response is the JSON object that post_file.php returns
            },

            error: function(err, file) {
                switch(err) {
                    case 'BrowserNotSupported':
                        alert('<?= lr('upload_error_browser_not_supported') ?>');
                        break;
                    case 'TooManyFiles':
                        // user uploaded more than 'maxfiles'
                        alert('<?= lr('upload_error_more_files',1) ?>');
                        break;
                    case 'FileTooLarge':
                        // program encountered a file whose size is greater than 'maxfilesize'
                        // FileTooLarge also has access to the file which was too large
                        // use file.name to reference the filename of the culprit file
                        alert('<?= lr('upload_error_file_too_large',intval(ini_get('post_max_size'))) ?>');
                        break;
                    case 'FileExtensionNotAllowed':
                        // The file extension is not in the specified list 'allowedfileextensions'
                        alert('<?= lr('upload_error_wrong_extension','.jpg, .jpeg, .png, .gif, .bmp nebo .wbmp'); ?>');
                        break;
                    default:
                        break;
                }
            },

            // Called before each upload is started
            beforeEach: function(file){

                startloading();

                offset =  $("#map_canvas").offset();

                xt=(mouseX-offset.left);
                yt=(mouseY-offset.top);
                tmp=pos2pos(xt,yt);
                xxc=tmp[0];
                yyc=tmp[1];

                //alert(mouseX+','+mouseY+','+Math.round(xxc)+','+Math.round(yyc));

                this.url=this.url+'&xc='+(xxc)+'&yc='+(yyc);

                if(!file.type.match(/^image\//)){
                    //alert('Only images are allowed!');

                    // Returning false will cause the
                    // file to be rejected
                    return false;
                }
            },


            uploadFinished: function(i, file, response, time) {

                /*alert('aaa');*/
                <?php urlx('e=map;noi=1;'.js2('stoploading()'),0) ?>
            }


        });
        <?php } ?>
        /*---------------------------------------------------------------------*/


        /*------------------------------------NEWVALS*/
        xc=<?php echo($xc); ?>;
        yc=<?php echo($yc); ?>;
        countdowns=[ ];
        windows="";
});
</script>





<!--================BUILD===================-->
<div  id="create-build"  name="create-build" style="position:absolute;display:none;top:0; left:0;z-index:25;">&nbsp;</div>
<script type="text/javascript">
            /* 3.66    3.02*/
            build_x=0;
            build_y=0;
            //window.build_master=false;
            //window.build_id=false;
            $("#create-build").css("left",(screen.width/2)-55);
            $("#create-build").css("top",(screen.height/2)-154);
            build=function(master,id,func) {//alert(master+','+id+','+func);
                window.build_master=master;
                window.build_id=id;
                window.build_func=func;
                turnmap('expand',true);
                $("#create-build").css("display","block");
		
                $("#create-build").draggable({ distance:<?php e($GLOBALS['dragdistance']); ?>});
                $( "#create-build" ).bind( "dragstop", function(event, ui){
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
                    
                    /*$('#build_button').css('display','none');*/
                    
                    
                    $('#create-build_message').html(nacitacihtml);
                    //=======================================================================================================================MEGATEST
                    <?php /*

                        $res=sql_1data("SELECT res FROM `[mpx]pos_obj` WHERE id='$id' AND ".objt());
                        //mail('ph@towns.cz','tmp',$res);

                        if(substr($res,0,1)=='{' or strpos($res,'{}')){           
                        $x=round($x);
                        $y=round($y);
                        $GLOBALS['ss']['query_output']->add("nocd",1);
                        }
                        $rx=round($x);
                        $ry=round($y);    

                            if(true){    

                            //OLD COLLAPSE//$hard=hard($rx,$ry);
                            //OLD COLLAPSE//if($x>=0 and $y>=0 and $x<=mapsize and $y<=mapsize and $hard<supportF($id,'resistance','hard')){
                                //OLD COLLAPSE//if(intval(sql_1data("SELECT COUNT(1) FROM `[mpx]pos_obj` WHERE own!='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND POW($x-x,2)+POW($y-y,2)<=POW(collapse,2)"))==0){

                                if(!($walltype=sql_1data("SELECT `type` FROM `[mpx]pos_obj` WHERE own!='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND ".objt()." AND block!=0 AND POW($x-x,2)+POW($y-y,2)<=POW(".distance_wall.",2)"))){


                                $resistance=supportF($id,'resistance','resistance');
                                if(!$resistance){
                                        $q=(!($blocktest=block1test('B',$x,$y)));
                                }else{
                                        $q=true;
                                }


                                if($q){

                                if(!($blocktest=block2test('B',$x,$y))){


                            if(intval(sql_1data("SELECT COUNT(1) FROM `[mpx]pos_obj` WHERE own='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND ".objt()." AND POW($x-x,2)+POW($y-y,2)<=POW(expand,2)"))>=1){


                                $fc=new hold(sql_1data("SELECT fc FROM `[mpx]pos_obj` WHERE id='$id' AND ".objt()));
                                if((!$test)?($GLOBALS['ss']['use_object']->hold->takehold($fc)):($GLOBALS['ss']['use_object']->hold->testchange($fc))){

                                    if($rot and strpos($res,'/1.png'))$res=str_replace('1.png',(($rot/15)+1).'.png',$res);

                                    if(substr($res,0,1)!='{' and !strpos($res,'{}')){
                                        $res=explode(':',$res);$res=$res[0].':'.$res[1].':'.$res[2];
                                    }


                                    $tol=sqrt(2)/2;
                                    //define('create_error',"SELECT id FROM `[mpx]pos_obj`  WHERE `ww`=".$GLOBALS['ss']["ww"]." AND  `x`>$rx-$tol AND `y`>$ry-$tol AND  `x`<$rx+$tol AND `y`<$ry+$tol AND `own`='".$GLOBALS['ss']['useid']."' ORDER BY POW(`x`-$rx,2)+POW(`y`-$ry,2) LIMIT 1");

                                   //foreach(sql_array("SELECT id,name,own FROM `[mpx]pos_obj`  WHERE `ww`=".$GLOBALS['ss']["ww"]." AND `own`='".$GLOBALS['ss']['useid']."' AND  `x`>$rx-$tol AND `y`>$ry-$tol AND  `x`<$rx+$tol AND `y`<$ry+$tol ORDER BY POW(`x`-$rx,2)+POW(`y`-$ry,2)") as $row){print_r($row);br();}


                                    $func=func2list(sql_1data('SELECT func FROM `[mpx]pos_obj` WHERE id='.$id.' AND '.objt()));
                                    list(list($jid,$jname,$jown,$jfs,$jfp,$jfunc,$jorigin,$jres))=sql_array("SELECT id,name,own,fs,fp,func,origin,res FROM `[mpx]pos_obj`  WHERE `ww`=".$GLOBALS['ss']["ww"]." AND ".objt()."  AND `own`='".$GLOBALS['ss']['useid']."' AND type='building' AND `x`>$rx-$tol AND `y`>$ry-$tol AND  `x`<$rx+$tol AND `y`<$ry+$tol ORDER BY POW(`x`-$rx,2)+POW(`y`-$ry,2) LIMIT 1");// AND `own`='".$GLOBALS['ss']['useid']."'
                                    if(!$jid){//e('ahoj');


                                        if($func['join']['profile']['type']==2){
                                                define('object_build',true);
                                                define('create_error',lr('create_error_join_type2'));
                                                $GLOBALS['ss']['query_output']->add('error',lr("create_error_join_type2"));
                                                return;
                                        }else{

                                        if(!$test){
                                                $nextid=nextid();
                                                define('object_id',$nextid);
                                                $GLOBALS['object_ids']=array($nextid);
                                                sql_query("INSERT INTO `[mpx]pos_obj` (`id`, `name`, `type`, `origin`, `fs`, `fp`, `fc`, `fr`, `fx`, `func`, `hold`, `res`, `profile`, `set`, `hard`, `expand`, `block`, `attack`, `own`, `superown`, `in`, `ww`, `x`, `y`, `t`, `starttime`)
                        SELECT ".$nextid.", `name`, `type`, `origin`, `fs`, `fp`, `fc`, `fr`, `fx`, `func`, `hold`, CONCAT('$res',':$rot'), `profile`, 'x', `hard`, `expand`, `block`, `attack`,'".$GLOBALS['ss']['useid']."','".$GLOBALS['ss']['logid']."', `in`, ".$GLOBALS['ss']["ww"].", $x, $y, ".time().", ".time()." FROM `[mpx]pos_obj` WHERE id='$id'");
                                        }

                                        $GLOBALS['ss']['query_output']->add("1",1);

                                        }
                                        //define('create_ok','{create_ok_place}');

                                    }else{
                                        //e('bhoj');

                                        $jfunc=func2list($jfunc);

                                        if($func['join']['profile']['type']==3){
                                                define('object_build',true);//die(1);
                                                define('create_error',lr('create_error_join_type3'));
                                                $GLOBALS['ss']['query_output']->add('error',lr('create_error_join_type3'));
                                                return;
                                        }elseif($jfunc['join']['profile']['type']==3){
                                                define('object_build',true);//die(2);
                                                define('create_error',lr('create_error_join_type3x'));
                                                $GLOBALS['ss']['query_output']->add('error',lr('create_error_join_type3x'));
                                                return;
                                        }elseif($func['join']['profile']['type']==1 or $func['join']['profile']['type']==4){
                                                define('object_build',true);//die(3);
                                                define('create_error',lr('create_error_join_type1'));
                                                $GLOBALS['ss']['query_output']->add('error',lr('create_error_join_type1'));
                                                return;
                                        }elseif($jfunc['join']['profile']['type']==4 and strpos($jres,'[-4,-4,')===false){
                                                define('object_build',true);//die(4);
                                                define('create_error',lr('create_error_join_type4'));
                                                $GLOBALS['ss']['query_output']->add('error',lr('create_error_join_type4'));
                                                return;
                                        }elseif(!$jorigin){
                                                define('object_build',true);//die(5);
                                                define('create_error',lr('create_error_join_noorigin'));
                                                $GLOBALS['ss']['query_output']->add('error',lr('create_error_join_noorigin'));
                                                return;
                                        }elseif($jown!=$GLOBALS['ss']['useid']){
                                                define('object_build',true);
                                                define('create_error',lr('create_error_join_noown'));
                                                $GLOBALS['ss']['query_output']->add('error',lr('create_error_join_noown'));
                                                return;
                                        }elseif($jname==id2name($GLOBALS['config']['register_building'])){
                                                define('object_build',true);
                                                define('create_error',lr('create_error_join_main'));
                                                $GLOBALS['ss']['query_output']->add('error',lr('create_error_join_main'));
                                                return;
                                        }elseif($tmaster?$jid==$tmaster:$jid==$GLOBALS['ss']['aac_object']->id){
                                                define('object_build',true);
                                                define('create_error',lr('create_error_join_self'));
                                                $GLOBALS['ss']['query_output']->add('error',lr('create_error_join_self'));
                                                return;
                                        }elseif($jfs!=$jfp){
                                                define('object_build',true);
                                                define('create_error',lr('create_error_join_fsfp'));
                                                $GLOBALS['ss']['query_output']->add('error',lr('create_error_join_fsfp'));
                                                return;
                                        }else{


                                                define('create_ok',contentlang(lr('create_ok_join',$jname)));
                                                if(!$test){
                                                        define('join_id',$jid);
                                                        define('object_id',$jid);


                                                        trackobject($jid);//záloha původního objektu, nastavení časů

                                                        $GLOBALS['object_ids']=array($jid);
                                                        $joint=new object($jid);
                                                        $joint->join($id,$res.':'.$rot,$rot);
                                                        $joint->update(true,true);
                                                        unset($joint);
                                                }
                                                $GLOBALS['ss']['query_output']->add("1",1);



                                        }


                                    }

                                    //POZDEJI//changemap($x,$y);

                                if(!$test){
                        //==============================OPRAVA SPOJů

                        //==============================


                                 }else{
                                    define('object_build',true);
                                    define('create_error',lr('create_error_price'));
                                    $GLOBALS['ss']['query_output']->add('error',lr('create_error_price'));
                                }
                            }else{
                                define('object_build',true);
                                define('create_error',lr('create_error_expand'));
                                $GLOBALS['ss']['query_output']->add('error',lr('create_error_expand'));
                            }
                                }else{
                                define('object_build',true);
                                        if(is_numeric($blocktest))$blocktest='object';
                                define('create_error',lr('create_error_block_'.$blocktest));
                                $GLOBALS['ss']['query_output']->add('error',lr('create_error_block_'.$blocktest));
                            }}else{
                                define('object_build',true);
                                //$sql="SELECT (SELECT IF(`terrain`='t1' OR `terrain`='t11',1,0) FROM `[mpx]map`  WHERE `[mpx]map`.`ww`=".$GLOBALS['ss']["ww"]." AND  `[mpx]map`.`x`=$y AND `[mpx]map`.`y`=$x)+(SELECT SUM(`[mpx]pos_obj`. `hard`) FROM `[mpx]pos_obj` WHERE `[mpx]pos_obj`.`ww`=".$GLOBALS['ss']["ww"]." AND  ROUND(`[mpx]pos_obj`.`x`)=$y AND ROUND(`[mpx]pos_obj`.`y`)=$x)";
                                //$hard=sql_1data($sql);// WHERE `ww`=".$GLOBALS['ss']["ww"]." AND `x`=$x AND `y`=$y");
                                define('create_error',lr('create_error_resistance_'.$blocktest));
                                $GLOBALS['ss']['query_output']->add('error',lr('create_error_resistance_'.$blocktest));
                            }}else{
                                define('object_build',true);
                                define('create_error',lr('create_error_'.$walltype.'_distance'));
                                $GLOBALS['ss']['query_output']->add('error',lr('create_error_wall_distance'));
                            }
                    */ ?>
                            
                    //======================================================================================================================END OF MEGATEST
                    
                    
                    //alert('?token=<?php e($_GET['token']); ?>&e=create-build_message&id='+window.build_id+'&master='+window.build_master+'&xx='+build_x+'&yy='+build_y);
                    
                    $.get('?token=<?php e($_GET['token']); ?>&e=create-build_message&id='+window.build_id+'&master='+window.build_master+'&xx='+build_x+'&yy='+build_y, function(vystup){$('#create-build_message').html(vystup);});
                    
                    
                    
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

	    buildx = function(master,id,func,build_x,build_y,_rot) {
		/*alert(_rot);*/
		/*$.get('?token=<?php e($_GET['token']) ?>&e=map_units&q='+master+'.'+func+' '+id+','+build_x+','+build_y+','+_rot, function(vystup){$('#map_units').html(vystup);});*/
		qbuffer=master+'.'+func+' '+id+','+build_x+','+build_y+','+_rot;
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


<!--================TERRAIN===================-->
<?php /* ?><div  id="terrain-build"  name="terrain-build" style="position:absolute;display:none;top:0; left:0;z-index:25;">&nbsp;</div>
<script type="text/javascript">
            tbuild_x=0;
            tbuild_y=0;
            $("#terrain-build").css("left",(screen.width/2)-55);
            $("#terrain-build").css("top",(screen.height/2)-154);
            terrain=function(master,id,func) {
                window.tbuild_master=master;
                window.tbuild_id=id;
                window.tbuild_func=func;
                $("#expandarea").css("display","block");
                $("#terrain-build").css("display","block");
                $("#terrain-build").draggable();
                $( "#terrain-build" ).bind( "dragstop", function(event, ui){
                    bx=parseFloat($("#terrain-build").css("left"));
                    by=parseFloat($("#terrain-build").css("top"));
                    offset =  $("#tabulkamapy").offset();
                    xt=(bx-offset.left);
                    yt=(by-offset.top);
                    tmp=pos2pos(xt,yt);
                    xxc=xxc+4.57;
                    yyc=yyc+3.67;
                    tbuild_x=xxc;
                    tbuild_y=yyc;
                });
                $.get('?token=<?php e($_GET['token']); ?>&e=terrain-build&master='+master+'&func='+func+'&id='+id, function(vystup){$('#terrain-build').html(vystup);});
		
            }
            <?php
                if(defined('terrain_build')){
                    e('terrain('.$GLOBALS['ss']['master'].',\''.$GLOBALS['ss']['object_build_id'].'\',\''.$GLOBALS['ss']['object_build_func'].'\');');
                }
                if(defined('terrain_error')){
                    e('alert("'.terrain_error.'");');
                }
            ?>
</script><?php */ ?>



<?php } ?>

<!--===================================-->
<?php /*<div style="position:absolute;width:100%;height:100%;z-index:10;">
<div style="position:relative;top:0px;left:0px;width:100%;height:100%;z-index:10;">
<?php htmlmap(false,false,'100%'); ?>
</div></div>
 onmousedown="alert(1)" onmouseup="alert(2)" onmouseout=""
onclick="key_up=true" onmouseup="key_up=false" onmouseout="key_up=false"

 onmousedown="key_left=true" onmouseup="key_left=false" onmouseout="key_left=false"
*/ ?>

<?php if((!$_GET['first']) and false){ ?>
<div style="position:absolute;top:40px;left:0px;width:100%;height:27px;z-index:550;">
<a onclick="key_up=true;key_count=key_count+2;">
<img src="<?php imageurle('design/blank.png'); ?>" id="navigation_up" border="0" alt="<?php le('navigation_up'); ?>" title="<?php le('navigation_up'); ?>" width="100%" height="100%">
</a>
</div>

<div style="position:absolute;top:0px;left:0px;width:27px;height:100%;z-index:550;">
<a onclick="key_left=true;key_count=key_count+2;">
<img src="<?php imageurle('design/blank.png'); ?>" id="navigation_left" border="0" alt="<?php le('navigation_left'); ?>" title="<?php le('navigation_left'); ?>" width="100%" height="100%">
</a>
</div>

<div style="position:absolute;top:100%;left:0px;width:100%;height:47px;z-index:550;">
<div style="position:relative;top:-47px;left:0px;width:100%;height:100%;">
<a onclick="key_down=true;key_count=key_count+2;">
<img src="<?php imageurle('design/blank.png'); ?>" id="navigation_down" border="0" alt="<?php le('navigation_down'); ?>" title="<?php le('navigation_down'); ?>" width="100%" height="100%">
</a>
</div></div>

<div style="position:absolute;top:0px;left:100%;width:27px;height:100%;z-index:550;">
<a onclick="key_right=true;key_count=key_count+2;">
<div style="position:relative;top:0px;left:-27px;width:100%;height:100%;">
<img src="<?php imageurle('design/blank.png'); ?>" id="navigation_right" border="0" alt="<?php le('navigation_right'); ?>" title="<?php le('navigation_right'); ?>" width="100%" height="100%">
</a>
</div></div>

<?php /*<script type="text/javascript" >
        /*----------------------------------------------------------NAVIGATOR---/
        //$('#navigation_up').mousedown(function() {key_up=true;});
        $('#navigation_down').mousedown(function() {key_down=true;});
        $('#navigation_left').mousedown(function() {key_left=true;});
        $('#navigation_right').mousedown(function() {key_right=true;});

        $(document).mouseup(function(e) {
            /*---------UP,DOWN,LEFT,RIGHT/
            //key_up=false;
            key_down=false;
            key_left=false;
            key_right=false;
            /*---------/
        });
        /*------------------------------------/
</script>*/ ?>


<?php } ?>



<div style="top:<?php  echo($yy); ?>;left:<?php  echo($xx); ?>;z-index:20;" id="draglayer">
<?php

if(!$_GLOBALS['noxmap']){

    $_GLOBALS['noxmap']=false;

    $stream1='';
    $stream2='';
    $stream3='';
    //$mapsize=20;
    $screen=1270;

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
    $size=$screen/($xm+$xm+1);//750;


    $canvasjs='


        var all_images=[];
        var canvas = document.getElementById("map_canvas");

        $("#map_canvas").attr("width",parseInt($(window).width()*1.6));
        $("#map_canvas").attr("height",parseInt($(window).height()*1.6));

        var ctx = canvas.getContext("2d");


    ';

    $aii=0;


    for($y=$yc; $y<=$ym+$yc; $y++){


        for ($x=-$xm+$xc; $x<=$xm+$xc+$xmp; $x++) {

        $q=1;

            $url=htmlmap($x,$y,1,1,$y-$yc/*,$_GLOBALS['map_night']*/);
            /*$canvasjs.='
              var imageObj'.md5($url).' = new Image();

              imageObj'.md5($url).'.onload = function() {
                ctx.drawImage(imageObj'.md5($url).', '.(round(424/$zoom)*($x-(-$xm+$xc))).', '.round(211/$zoom)*($y-$yc).');
              };
              imageObj'.md5($url).'.src = "'.rebase(url.$url).'";

            ';*/

            $canvasjs.="all_images[$aii] = new Image();";
            $canvasjs.="all_images[$aii].src='{$url}';";
            $canvasjs.="all_images[$aii].xx=".(round(424/$zoom)*($x-(-$xm+$xc))).";";
            $canvasjs.="all_images[$aii].yy=".(round(211/$zoom)*($y-$yc)).";";
            $canvasjs.="all_images[$aii].width=".round(424/$zoom).";";
            $canvasjs.="all_images[$aii].height=".round(211/$zoom).";";
            $canvasjs.="all_images[$aii].ll='terrain';";
            $canvasjs.="all_images[$aii].starttime=0;";
            $canvasjs.="all_images[$aii].stoptime=0;";
            $aii++;


            $canvasjs.="all_images[$aii] = new Image();";
            $canvasjs.="all_images[$aii].src='".mapgrid()."';";
            $canvasjs.="all_images[$aii].xx=".(round(424/$zoom)*($x-(-$xm+$xc))).";";
            $canvasjs.="all_images[$aii].yy=".(round(211/$zoom)*($y-$yc)).";";
            $canvasjs.="all_images[$aii].width=".round(424/$zoom).";";
            $canvasjs.="all_images[$aii].height=".round(211/$zoom).";";
            $canvasjs.="all_images[$aii].ll='grid';";
            $canvasjs.="all_images[$aii].starttime=0;";
            $canvasjs.="all_images[$aii].stoptime=0;";
            $aii++;



        }

    }
    //-------------------------------

    //e('<div style="position:absolute;width:0px;height:0px;"><div style="position:relative;top:'.(htmlbgc/$zoom).'px;left:0px;z-index:100;">'.$stream1.'</div></div>');


    subexec('map_units');



    foreach($GLOBALS['all_images'] as $image){


        $image[2]=round($image[2]);
        $image[3]=round($image[3]);
        $image[4]=round($image[4]);
        $image[5]=round($image[5]);

        $canvasjs.="all_images[$aii] = new Image();";
        $canvasjs.="all_images[$aii].objectid={$image[0]};";
        $canvasjs.="all_images[$aii].src='{$image[1]}';";
        $canvasjs.="all_images[$aii].xx={$image[2]};";
        $canvasjs.="all_images[$aii].yy={$image[3]};";
        $canvasjs.="all_images[$aii].width={$image[4]};";
        $canvasjs.="all_images[$aii].height={$image[5]};";
        $canvasjs.="all_images[$aii].ll='{$image[6]}';";
        $canvasjs.="all_images[$aii].starttime='{$image[7]}';";
        //$canvasjs.="all_images[$aii].readytime='{$image[8]}';"; @todo udělat postupné stavění v timeplay
        $canvasjs.="all_images[$aii].stoptime='{$image[9]}';";

        //var allImages['.md5($modelurl).'] = new Image();
        //$canvasjs.='all_images[]=['.implode(',',$image).'];';

        $aii++;
    }



    $canvasjs.='

        var imgs_count = all_images.length;
        var imgs_loaded = 0;


        ctx.clearRect ( 0 , 0 , canvas.width, canvas.height );
        i=0;while(i<imgs_count){

            if(jQuery.inArray(all_images[i].ll,drawmaplayers)!=-1)
                ctx.drawImage(all_images[i],all_images[i].xx,all_images[i].yy,all_images[i].width,all_images[i].height);
            i++;
        }



        $(all_images).load(function() {

            if(jQuery.inArray(this.ll,drawmaplayers)!=-1)
                ctx.drawImage(this,this.x,this.y,this.width,this.height);

            imgs_loaded++;

            if(imgs_loaded === imgs_count) {

                drawmap();

                setTimeout(function(){
                    drawmap();
                },1000);


            }

        });


    ';

    js($canvasjs);


    e('<div style="position:absolute;width:0px;height:0px;"><div style="position:relative;top:'.round(htmlbgc/$zoom).'px;z-index:100;">
    <canvas id="map_canvas" style="cursor:move;" class="clickmap" width="'./*round(424/$zoom)*(($xm*2)+2)*/round($GLOBALS['ss']['screenwidth']*gr).'" height="'./*round(211/$zoom)*($ym+1)*/round($GLOBALS['ss']['screenheight']*gr).'"></canvas>
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

