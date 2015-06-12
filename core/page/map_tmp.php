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

if($_GET['delete']){
	if(strpos($_GET['delete'],'tmp/'.w.'/mapbg/')===0){
		unlink($_GET['delete']);
	}else{e('ahoj');}
}

//==============================

if($_GET['glob']){$glob=true;}else{$glob=false;}
$zoom=$GLOBALS['mapzoom'];
if(!$zoom)$zoom=1;
//==============================


?>
<?php
error('Potřeba zprovoznit htmlmap!');
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
	    $array=sql_array('SELECT x,y FROM `[mpx]pos_obj` WHERE ww='.ww.' AND '.(rand(1,10)>7?'1':'type=\'building\' AND  own!=\'\'').' ORDER BY RAND() LIMIT 1 ');
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
?>


<?php
//if(nothing){e('nothing=1');}else{e('nothing=0');}
//if(logged()){e('logged=1');}else{e('logged=0');}

	if(!$nothing/** and true/**/){
    if(logged()){
?>
<script type="text/javascript">

	<?php /*if($_GET['q'] and true){subjs('quest-mini');}*/ ?>

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
                return([xxt,yyt]);
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

      

		$('#draglayer').draggable({ disabled: false, distance:<?php e($GLOBALS['dragdistance']); ?> });
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
                offset =  $("#tabulkamapy").offset();
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
                /*alert("click");*/
                $('#map_context').css('left',hovno.pageX-10);
                $('#map_context').css('top',hovno.pageY-10);
		$('#map_context').css('border-color','#22222');
                $('#map_context').css('display','block');/**/
                offset =  $("#tabulkamapy").offset();
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
                $(function(){$.get('?token=<?php e($_GET['token']); ?>&e=minimenu&w=&terrain=1&xc='+xxc+'&yc='+yyc, function(vystup){if(vystup.length>30)$('#map_context').html(vystup);});});
                 <?php } ?>
            }
        });



        /*---------------------------------MENUCLICK*/
		  aac_clickset=function(hovno) {

        $(".menu").click(function(hovno) {
            if(drag!=1){   
                $('#map_context').css('left',hovno.pageX-10);
                $('#map_context').css('top',hovno.pageY-10);
		$('#map_context').css('border-color','#999999');
                $('#map_context').css('display','block');
                $('#map_context').html('...');
		name=$(this).attr('id');
                $(function(){$.get('?token=<?php e($_GET['token']); ?>&e=menu&menuid='+name, function(vystup){$('#map_context').html(vystup);});});
            }
        });


        /*---------------------------------UNITCLICK*/
        $(".unit").click(function(hovno) {
            if(drag!=1){   
                $('#map_context').css('left',hovno.pageX-10);
                $('#map_context').css('top',hovno.pageY-10);
		$('#map_context').css('border-color','#22222');
                $('#map_context').css('display','block');
                title=$(this).attr('title');
                name=$(this).attr('id');
                
		/*if($('#cache_minimenu_'+name).length!=0){
			cache=$('#cache_minimenu_'+name).html();
			//alert(cache);
			$('#map_context').html(cache);
		}else{
                	$('#map_context').html(title);
		}*/
		
		if(ifcache('minimenu_'+name)){
			$('#map_context').html(cache('minimenu_'+name));
		}else{
            $('#map_context').html(title);
		}
		
		
                 <?php if(logged){ ?>
                $(function(){$.get('?token=<?php e($_GET['token']); ?>&e=minimenu&w=&contextid='+name+'&contextname='+title, function(vystup){$('#map_context').html(vystup);});});
                 <?php } ?>
            }
        });/**/


		  };/**/
		  aac_clickset();
        /*---------------------------------CENTER*/
        <?php if($GLOBALS['get']['center']){ ?>
        
        
		xc=parseInt($( "#draglayer" ).css('left'));
		yc=parseInt($( "#draglayer" ).css('top'));     
        $( "#draglayer" ).css('left',xc-400/*(window.width)*/);
        $( "#draglayer" ).css('top',yc+200/*((window.height-120)/2)*/);
		setTimeout(function(){         
        parseMapF(
		function(){        
        $('#map_context').css('left',645<?php if($GLOBALS['get']['center']){e('-'.$GLOBALS['get']['posuv']);} ?>);
        $('#map_context').css('top',195);
        $('#map_context').css('display','block');
        $(function(){$.get('?token=<?php e($_GET['token']); ?>&e=minimenu&w=&contextid='+<?php e($GLOBALS['get']['center']); ?>, function(vystup){$('#map_context').html(vystup);});});
		}
		);
        },23);/**/
        <?php } ?>   
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
                $("#expandarea").css("display","block");
                $("#create-build").css("display","block");
		
                $("#create-build").draggable({ distance:<?php e($GLOBALS['dragdistance']); ?>});
                $( "#create-build" ).bind( "dragstop", function(event, ui){
                    bx=parseFloat($("#create-build").css("left"));
                    by=parseFloat($("#create-build").css("top"));
                    offset =  $("#tabulkamapy").offset();
                    xt=(bx-offset.left);/*pozice myši px*/
                    yt=(by-offset.top);
                    tmp=pos2pos(xt,yt);
                    xxc=xxc+4.57;
                    yyc=yyc+3.67;
                    build_x=xxc;
                    build_y=yyc;
                    
                    /*$('#build_button').css('display','none');*/
                    
                    
                    $('#create-build_message').html(nacitacihtml);
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
		qbuffer.push(master+'.'+func+' '+id+','+build_x+','+build_y+','+_rot);
		//prompt('qbuffer',master);
		//prompt('qbuffer',qbuffer);

		newhtml=$('#build_model_'+_rot).html();
		newhtml = newhtml.replace('2px', '0px'); 
		   /*alert(newhtml);*/
                    bx=parseFloat($("#create-build").css("left"));
                    by=parseFloat($("#create-build").css("top"));
                    offset =  $("#tabulkamapy").offset();
                    xt=(bx-offset.left);/*pozice myši px*/
                    yt=(by-offset.top);

		newhtml='<div class="clickmap" style="position:absolute;width:0px;height:0px;"><div class="clickmap" style="position:relative;left:'+(xt)+';top:'+(yt)+';z-index:1000;">'+newhtml+'</div></div>';

		$('#units_new').html($('#units_new').html()+newhtml);


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
><?php /* ?><div  id="terrain-build"  name="terrain-build" style="position:absolute;display:none;top:0; left:0;z-index:25;">&nbsp;</div>
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

<?php if(logged() and false){ ?>
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

if(!$_GLOBALS['noxmap']){$_GLOBALS['noxmap']=false;

//subref("map_units",60);


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

$ad=("<table cellspacing=\"0\" cellpadding=\"0\" width=\"".$screen."\" id=\"tabulkamapy\">");
$stream1.=$ad;$stream2.=$ad;$stream3.=$ad;
for($y=$yc; $y<=$ym+$yc; $y++){
    
    //if(connection_aborted()){die();}
    
    $ad=("<tr>");$stream1.=$ad;$stream2.=$ad;$stream3.=$ad;
    for ($x=-$xm+$xc; $x<=$xm+$xc+$xmp; $x++) {
        $ad=(dnln.'<td width="'.round(424/$zoom).'" height="'.round(211/$zoom).'">');$stream1.=$ad;$stream2.=$ad;$stream3.=$ad;
        //r("$x,$y");
	//$stream1.="($x,$y)";

	$q=1;

		$divplace='<div style="width:'.round(424/$zoom).'px;height:'.round(211/$zoom).'px;overflow:hidden;"></div>';

        if($q){$stream1.=movebyr(htmlmap($x,$y,1,NULL,$y-$yc/*,$_GLOBALS['map_night']*/),0,0).$divplace;}else{$stream1.=htmlmap(-5,0,2,NULL,$y-$yc);}
		
		$url=htmlmap($x,$y,1,1);
		$stream1.='<a href="?e=map_tmp&amp;delete='.$url.'">'.($url).'</a>';


        //if($q){$stream2.=movebyr(htmlmap($x,$y,2,NULL,$y-$yc/*,$_GLOBALS['map_night']*/),0,0).$divplace;}else{$stream2.=htmlmap(-5,0,2,NULL,$y-$yc);}
        if($q)$stream3.='<img src="'.mapgrid().'"  width="'.round(424/$zoom).'" class="clickmap">';
        t();
        $ad=("</td>");$stream1.=$ad;$stream2.=$ad;$stream3.=$ad;
    }
    $ad=("</tr>");$stream1.=$ad;$stream2.=$ad;$stream3.=$ad;
}
$ad=("</table>");$stream1.=$ad;$stream2.=$ad;$stream3.=$ad;/**/
//-------------------------------

e('<div style="position:absolute;width:0px;height:0px;"><div style="position:relative;top:'.(htmlbgc/$zoom).'px;left:0px;z-index:100;">'.$stream1.'</div></div>');
e('<div style="position:absolute;width:0px;height:0px;"><div style="position:relative;top:'.(htmlbgc/$zoom).'px;left:0px;z-index:200;">');

    e('<span id="map_units">'.nbsp.'</span>');
    /*subref('map_units');*/
    $GLOBALS['units_stream']='&nbsp;';
    $GLOBALS['attack_stream']='&nbsp;';


/*if(logged()){
    $GLOBALS['units_stream']=$GLOBALS['ss']['units_stream'];
}else{
    $GLOBALS['units_stream']='';
}*/

e('</div></div>');
//e('<div style="position:absolute;width:0px;height:0px;"><div style="position:relative;top:'.(htmlunitc/$zoom).'px;left:0px;z-index:200;display:none;" id="grid">'.$stream3.'</div></div>');
//e('<div style="position:absolute;width:0px;height:0px;"><div style="position:relative;top:'.(htmlunitc/$zoom).'px;left:0px;z-index:300;">'.$stream2.'</div></div>');
//e('<div style="position:absolute;width:0px;height:0px;"><div style="position:relative;top:'.(htmlunitc/$zoom).'px;left:0px;z-index:400;" id="units_stream">'.$GLOBALS['units_stream']/**/.'</div></div>');
e('<div style="position:absolute;width:0px;height:0px;"><div style="position:relative;top:'.(htmlbgc/$zoom).'px;left:0px;z-index:500;" class="clickmap" id="units_new"></div></div>');

}
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
}else{
//echo('to je sračka');
//$url=centerurl($GLOBALS['hl'],$GLOBALS['hl_x'],$GLOBALS['hl_y'],$GLOBALS['hl_ww']);
//click($url,1);
//js('alert(1);');

}
?>
<!--<div style="position:absolute;top:0;left:0;z-index:23;" id="zaloha_u">ahoj</div>-->
