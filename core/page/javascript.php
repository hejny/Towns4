<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/javascript.php

   Javascript hlavně pro mapu
*/
//==============================
?>
<script>
    /*------------------------------drawmap*/

    var drawmaplayers=['','terrain','building','tree','rock'];

    var turnmap=function(wtf,state='x') {

        var i=jQuery.inArray(wtf,drawmaplayers);

        if(i==-1 && state===false)return;
        if(i!=-1 && state===true)return;


        if(i!=-1)
            delete drawmaplayers[i]
        else
            drawmaplayers.push(wtf);


        //alert(i);
        //alert(drawmaplayers);
        drawmap();
    }

    var drawmap=function() {

        //alert(123);
        var imgs_count = all_images.length;
        i=0;while(i<imgs_count){

            if(jQuery.inArray(all_images[i].ll,drawmaplayers)!=-1)
                ctx.drawImage(all_images[i],all_images[i].xx,all_images[i].yy,all_images[i].width,all_images[i].height);


            i++;
        }


    }





/*------------------------------parseMap*/
            xc=0/*<?php echo($GLOBALS['xc']); ?>*/;
            yc=0/*<?php echo($GLOBALS['yc']); ?>*/;
            
            function parseMapF(fff) {
                parseMapx(false,fff);
            }
		
            function parseMap() {
                parseMapx(false,function(){1;});
            }
            function refreshMap() {
                parseMapx(true,function(){1;});
            }
            
            /*setInterval(function(){
                document.title='('+xc+','+yc+','+xx+','+yy+')';//document.parseMapxloading;
            
            },100);*/
            
            document.parseMapxloading=false;
            
            function parseMapx(refresh,fff) {
            /*alert("parse");*/
            xl=424/<?php e($GLOBALS['mapzoom']); ?>;xp=424/<?php e($GLOBALS['mapzoom']); ?>;
            yl=211/<?php e($GLOBALS['mapzoom']); ?>;yp=211/<?php e($GLOBALS['mapzoom']); ?>;
            tt=0.5;ppp=0;
            xxcc=00;
            yycc=00;

                //---------------------------------------Autointeligentni nacitani
                if(document.parseMapxloading==true){
                    inloading=0;freeze=0;
                     xx=xx_abort;
                    yy=yy_abort;
                    xc=xc_abort;
                    yc=yc_abort;
                    document.maploader.abort();
                    /*alert('abort');*/
                
                }
                xx_abort=xx;
                yy_abort=yy;
                xc_abort=xc;
                yc_abort=yc;
                //---------------------------------------

            xx=parseFloat($('#draglayer')./*offset.left*/css("left"));/*alert(xx);*/
            yy=parseFloat($('#draglayer')./*offset.top*/css("top"));
            /*alert(typeof xx_);*/
            /*if(typeof xc_=="undefined")xc_=xc;*/
            if(typeof inloading=="undefined")inloading=0;
            q=1;w=0;
            while(q==1){
                q=0;
                
                if(xx-xxcc<-xp-xl*tt){xx=xx+xl;xc=xc+1;q=1;w=1;}
                if(xx-xxcc>-xp+xl*tt){xx=xx-xl;xc=xc-1;q=1;w=1;}
                if(yy-yycc<-yp-yl*tt+ppp){yy=yy+yl;yc=yc+1;q=1;w=1;}
                if(yy-yycc>-yp+yl*tt+ppp){yy=yy-yl;yc=yc-1;q=1;w=1;}
            }
            if(refresh)w=1;
            if(w==1/* && inloading==0 aby fungovalo abort*/){
                    /*alert("aaa");*/

                    freeze=1;
                    inloading=1;

                    document.parseMapxloading=true;
                    document.maploader = $.get('?token='+token+'&e=map&xc='+xc+'&yc='+yc+'&xx='+xx+'&yy='+yy+'&i='+windows,    function(vystup){
			    
                           
                            //alert(vystup.url);
                            if(true){

                                document.parseMapxloading=false;
                                zaloha_a=$('#create-build').css('display');
                                zaloha_t=$('#terrain-build').css('display');
                                zaloha_e=$('#expandarea').css('display');
				                zaloha_at=$('#attackarea').css('display');
                                zaloha_gr=$('#grid').css('display');
    			                zaloha_mb=$('.mapbox').css('display');
    			                zaloha_sb=$('.mapbox').css('saybox');
                                zaloha_u=$('#units_stream').html();
                                offset=$('#draglayer').offset();

                                //top1=parseInt($('#draglayer').css('top'));
                                //left1=parseInt($('#draglayer').css('left'));
                                zaloha_img=document.getElementById("map_canvas");
                                $('#map').html(vystup);
                                //top2=parseInt($('#draglayer').css('top'));
                                //left2=parseInt($('#draglayer').css('left'));

                                //ctx.drawImage(zaloha_img,left2-left1,top2-top1);
                                //alert(123);
                                //alert(left2+'-'+left1+','+top2+'-'+top1);



                                if(zaloha_a=='block')build(window.build_master,window.build_id,window.build_func);
                                if(zaloha_t=='block')terrain(window.tbuild_master,window.tbuild_id,window.tbuild_func);

       							$('#expandarea').css('display',zaloha_e);
							    $('#attackarea').css('display',zaloha_at);
    							$('#grid').css('display',zaloha_gr);
                                $('.mapbox').css('display',zaloha_mb);
                                $('.saybox').css('display',zaloha_sb);

                                fff();
    
    
                                inloading=0;freeze=0;
    		                    $( "#draglayer" ).draggable( "option", "disabled", false );
                            }


                        /*});*/
                    });
                }else{
					fff();                
                }
        }
/*--------------*/

        /*---------COUNTDOWNTO  */
        /*function countdownto(id,x2){
        q=0;
        x1=parseFloat($("#"+id).html());

        x2=Math.round(x2);
/
        countdowns[countdowns.length]=[id,q,x1,x2];
        }
        setInterval(function(){
        if(typeof countdowns!='undefined'){
        for (var i = 0; i <= countdowns.length; i++){
            
            countdown=countdowns[i];
            if(countdown){
                id=countdown[0];q=countdown[1];x1=countdown[2];x2=countdown[3];
      
                x=Math.round(x1+((x2-x1)*q));
                q=q+(1/fps);
                if(q>1)q=1;
                countdowns[i][1]=q;
                $("#"+id).html(x);
            }else{
                
            }
        }}
        },(connectfps*1000)/fps);*/


	/*---------COUNTUPTO  */
	document.countups= [ ] ;

        function countupto(id,base,x1,x2,bound){/*alert(x2);*/
        //q=0;
        //x1=parseFloat($("#"+id).html());
        x2=Math.round(x2);
	base=Math.round(base);
	bound=Math.round(bound);
	/*alert(document.countups);*/
        document.countups[document.countups.length]=[id,base,x1,x2,bound];
        }
	/*---------LOADUPTO  */
	document.loadups= [ ] ;

        function loadupto(id,base,x1,x2,bound,p){
        //q=0;
        //x1=parseFloat($("#"+id).html());
        x2=Math.round(x2);
	base=Math.round(base);
	bound=Math.round(bound);
        document.loadups[document.loadups.length]=[id,base,x1,x2,bound,p];
        }
	
	//-----------------------------
	


$(document).ready(function(){

	function number_format(number, decimals, dec_point, thousands_sep) {
	  number = (number + '')
	    .replace(/[^0-9+\-Ee.]/g, '');
	  var n = !isFinite(+number) ? 0 : +number,
	    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	    s = '',
	    toFixedFix = function(n, prec) {
	      var k = Math.pow(10, prec);
	      return '' + (Math.round(n * k) / k)
		.toFixed(prec);
	    };
	  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
	  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
	    .split('.');
	  if (s[0].length > 3) {
	    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	  }
	  if ((s[1] || '')
	    .length < prec) {
	    s[1] = s[1] || '';
	    s[1] += new Array(prec - s[1].length + 1)
	      .join('0');
	  }
	  return s.join(dec);
	}

		/*--------------------------------------------COUNTUPTO,LOADUPTO--*/
       //alert(fps);
       setInterval(function(){
		/*--------------------------------------------COUNTUPTO*/
        for (var i = 0; i <= document.countups.length; i++){
            //if(moves[0][1]!=1){
            countup=document.countups[i];
            if(countup){
                id=countup[0];base=countup[1];x1=countup[2];x2=countup[3];bound=countup[4];
                //alert(countup);
		time = (new Date().getTime())/1000;
		//alert((base+','+time));
		//alert(base-time);
                x=Math.round(x1+(x2*(time-base)/3600));
		if(x>bound){ x=bound; }
                //q=q+(1/fps);
                //if(q>1)q=1;
                //document.countups[i][1]=q;

                $("#"+id).html(number_format(x,0,0,'&nbsp;'));
            }else{
                //countdowns.splice(i,1);
            }
        }
		/*--------------------------------------------LOADUPTO*/
        for (var i = 0; i <= document.loadups.length; i++){
            //if(moves[0][1]!=1){
            loadup=document.loadups[i];
            if(loadup){
                id=loadup[0];base=loadup[1];x1=loadup[2];x2=loadup[3];bound=loadup[4];p=loadup[5];
                //alert(countup);
		time = (new Date().getTime())/1000;
		//alert((base+','+time));
		//alert(base-time);
                x=Math.round(x1+(x2*(time-base)/3600));
		if(x>bound){ x=bound; }
                //q=q+(1/fps);
                //if(q>1)q=1;
                //document.countups[i][1]=q;
				
				$("#"+id).attr('width',x+p);
				$("#"+id+'b').attr('width',(bound-x)+p);
                //$("#"+id).html(number_format(x,0,0,'&nbsp;'));
            }else{
                //countdowns.splice(i,1);
            }
        }
		/*--------------------------------------------*/
        },1000/fps);//(connectfps*1000)/fps
	/**/
	

    
        /*===========================================================================AAC*/

	
    rvrao=false;
	qbuffer='';
	windows='';
	setset='';
	nmr=false;


	qii=0;
        setInterval(function(){
	    if(logged==true){
            if(!rvrao/* true*/){
		urlpart='';
		qii=qii+1;
		
		
        if((playingt+(1000*60*2))>=$.now()){
            playing=true;
            //document.title='ANO';
        }else{
            playing=false;
            //document.title='NE';
        }	
		
	    if(qbuffer || windows || setset || qii>40){qii=0;
                 /*if(rvrao){alert('hybaa')}*/
                
                
                if(playing){
					if(nmr){
                    	urlpart='?token='+token+'&e=aac&i='+windows+'&q='+qbuffer+'&set='+setset+'&map_units_time=-1';
						nmr=false;
					}else{
                    	urlpart='?token='+token+'&e=aac&i='+windows+'&q='+qbuffer+'&set='+setset+'&map_units_time='+map_units_time;
					}
    		    qbuffer=''
                    windows='';
                    setset='';
                }
               
              
            }

		if(urlpart!=''){rvrao=true;$.get(urlpart, function(vystup){rvrao=false;eval(vystup);});}
            }
	    }
        },(1000/connectfps));
        
        
        chating=false;
        /*===========================================================================PLAYING*/
        playing=true;
        playingt=$.now();
        //alert(playingt);
        
        $('body').mousemove(function() {
            playingt=$.now();
        });
        /*setInterval(function(){
        },1000/fps);*/
        
        
        
        /*===========================================================================*/
        /*----------------------------------------------*/



        /*---------CHAT CONFIG*/
        chating=false;
        /*---------KEYPRESS CHAT*/
        
        <?php if(chat){ ?>
        
        $(document).keypress(function(e) {
            /*alert(e.which);
            //---------ENTER*/
            if ( e.which == 13 ) {
                
                /*none=false;*/
                /*alert($(':focus').attr('id'));*/
                if(/*chating==false*/!$(':focus').attr('id')){
                    chating=true;/*alert('xxx');*/
                    $('#window_chat').css('display','block');
                    $('#say').focus();
                }else{
                    if($(':focus').attr('id')=='say'){
                        /*alert("focusout");*/
                        chating=false;
                        $('#say').blur();
                    }
                }
                /*}*/
               /* if ($('#saylayer').css('display') == 'none'){
                    
                    //$('#saylayer').css('display','block');
                    $('#say').focus();
                }else{
                    //$('#saylayer').css('display','none');
                }*/
            }
        });
        
        <?php } ?>
        
        
        /*---------KEYDOWN*/
	
        shortcut_pl=0;
        //-------------------
        
        key_up=false;
        key_down=false;
        key_left=false;
        key_right=false;
        key_count=0;
        
        /*-----*/
        $(document).keydown(function(e) {
            //alert(e.which);
            //---------UP,DOWN,LEFT,RIGHT
	    if(logged==true){
            if(chating==false){
                
                //--------------------------
                if ( e.which ==80){ shortcut_pl=1; }
                else if ( e.which ==76 && shortcut_pl==1){ <?php urlx('e=play;noi=1;',false); ?>;shortcut_pl=0; }
                else if ( e.which ==84 && shortcut_pl==1){ <?php urlx('e=terminal;noi=1;',false); ?>;shortcut_pl=0; }
                else{shortcut_pl=0;}
                //-------------------------
                
                
                if ( e.which ==82) {qii=9999;/*parseMap()firstload=4;*/}
                /*if ( e.which ==84) {parseMap()}*/
                /*if ( e.which ==87) {key_up=true;}*/
                /*if ( e.which ==83) {key_down=true;}*/
                /*if ( e.which ==65) {key_left=true;}*/
                /*if ( e.which ==68) {key_right=true;}*/
                
                if ( e.which ==38) {key_up=true;/*firstload=1;*/}
                if ( e.which ==40) {key_down=true;/*firstload=1;*/}
                if ( e.which ==37) {key_left=true;/*firstload=1;*/}
                if ( e.which ==39) {key_right=true;/*firstload=1;*/}              
                
            }
	    }
            /*---------*/
        });  
        $(document).keyup(function(e) {
            /*---------UP,DOWN,LEFT,RIGHT*/
            key_up=false;
            key_down=false;
            key_left=false;
            key_right=false;


            if ( e.which ==80){ shortcut_pl=0; }

            /*if ( e.which ==87) {key_up=false;}
            if ( e.which ==83) {key_down=false;}
            if ( e.which ==65) {key_left=false;}
            if ( e.which ==68) {key_right=false;}*/
            /*---------*/
        });

        /*===========================================================================MAPMOVEBYKEYS*/
		accux=0;
        accuy=0;
        
        act_tmp=0;
        setInterval(function() {
            if(/*document.activeElement.tagName=='BODY'*/ true){
                                /*----------------------------------------?FPS*/
                act_tmpp=act_tmp;
                act_tmp = new Date();/*act_tmp.getMilliseconds()*/
                act_tmp = act_tmp.getTime();
                /*r(act_tmp);*/
                if(act_tmpp==0){
                    act=0;
                }else{
                    act=(act_tmp-act_tmpp)/1000;
                }
                /*----------------------------------------?*/
                if(key_count==0){
                    actx=1;
                }else{
                    actx=key_count;
                    key_count=key_count/Math.pow(1620,act);
                    if(key_count<0.0001){
                        key_up=false;
                        key_down=false;
                        key_left=false;
                        key_right=false;
                        key_count=0; 
                    }
                }
               
                /*-----------------*/
                
                xx=parseFloat($('#draglayer').css("left"));
                yy=parseFloat($('#draglayer').css("top"));
                d=1100*act*actx;q=false;
                xxp=xx;
                yyp=yy;
                if ( key_up==true    ) {yy=yy+d;q=true;}
                if ( key_down==true  ) {yy=yy-d;q=true;}
                if ( key_left==true  ) {xx=xx+d;q=true;}
                if ( key_right==true ) {xx=xx-d;q=true;}
                
				if(typeof freeze=="undefined")freeze=0;                
                
				if((accux!=0 || accuy!=0) && !freeze){
					/*alert('freeze ('+accux+','+accuy+')');  */
					xx=xx-(-accux);
                	yy=yy-(-accuy);
                	accux=0;
                	accuy=0; 
                	q=true;          		
                }
                
                
                if(q==true){
                
                $('#map_context').css('display','none');
                $('#draglayer').css("left",xx+'px');
                $('#draglayer').css("top",yy+'px');
                if(!freeze){
                	/**/
                	parseMap();
                }else{
                	accux=accux-(xxp-xx);
                	accuy=accuy-(yyp-yy);	
                }
                }
            /*---------*/
            }
        },10);
        /*===========================================================================*/
    });
       /*=====================================================================WINDOWS======*/


	/*----------------------------------*/
	function w_close(w_name){
	/*alert(w_name);*/


        if(w_name.substring(0,7)!='window_'){ 
            w_name='window_'+w_name;
        }  
	    
        $('#'+w_name).remove();
        w_name=w_name.split("window_").join("");
        windows=windows+w_name+',none,,;';



    if(w_name=='content' || w_name=='apps-app'){
    <?=permalink(0,0,0)?>
    }

    }

	/*----------------------------------*/
        zi=1001;
	function w_drag(){
		$(".window").draggable({ handle: ".dragbar" });
        $(".window").bind( "dragstart", function(event, ui){
			$(this).parent().css("z-index",zi);
            zi=zi+1;
            /*$(this).parent().css("width",2000);*/
		});
            $(".window").bind( "dragstop", function(event, ui){
			x=parseInt($(this).css("left"))+2;
            y=parseInt($(this).css("top"))+2;
			name= $(this).context.id.split('window_sub_').join('');
			/*alert(name+','+x+','+y);*/
			windows=windows+name+',,'+x+','+y+';';
		});
	}
	/*----------------------------------*/
	w_drag();
	/*----------------------------------*/
	function w_open(w_name,w_content,w_urlpart,xx,yy,ww,hh){

	/*alert(w_name);/**/

        /*if(w_name!='content'){return;}*/
        /*alert('w_open: '+w_name+'('+w_content+')');*/
        if(!w_urlpart)w_urlpart="";
        /*if(!xx)xx=1;
        if(!yy)yy=1;
        if(!ww)ww=449;
        if(!hh)hh=$(document).height()-118;*/
        if(w_name!='content'){
            if(!xx)xx=50;
            if(!yy)yy=50;
            if(!ww)ww=5;
            if(!hh)hh=5;
        }else{
            if(!xx)xx=1;
            if(!yy)yy=1;
        }
        if(!ww)ww=0;
        if(!hh)hh=0;
        /*r(w_name+","+w_content+","+xx+","+yy);*/
		url="?token="+token+"&e="+w_content+w_urlpart+"&i="+w_name+","+w_content+","+xx+","+yy;
		/*--------*/
			stream=$('#window').html();
			stream=stream.split("window_name").join("window_"+w_name);
			stream=stream.split("window_sub_name").join("window_sub_"+w_name);
			stream=(stream.split("window_title_name")).join("window_title_"+w_name);
			   stream=(stream.split("[xx]")).join(xx-2);
            stream=(stream.split("[yy]")).join(yy-2);
            stream=(stream.split("[ww]")).join(ww);
            stream=(stream.split("[hh]")).join(hh);
            /*stream=(stream.split("221")).join(xx);
            stream=(stream.split("223")).join(yy);
            stream=(stream.split("225")).join(ww);
            stream=(stream.split("227")).join(hh);*/
			/*alert("window_title_"+w_name);*/
			loadingstream='<?php include(root.core."/page/loading.php"); ?>';
                stream=stream.split("innercontent").join('<div id="'+w_name+'">'+loadingstream+"</div>");
			$('#windows').append(stream);
			w_drag();
		/*--------*/
			$('#say').focus();
			$('#say').blur();
			<?php $GLOBALS['formid']='form_writewrite'; form_js('write','?e=write&write=1',array('write_text'),false);  ?>
		$.get(url, function(vystup){
            /*if(vystup!=''){*/
                $('#'+w_name).html(vystup);
			$('#say').focus();
			$('#say').blur();
			<?php form_js('write','?e=write&write=1',array('write_text'),false); ?>
            /*}else{
                w_close(w_name);
            }*/
		});
		
	}
	/*$('#loading').css('visibility','visible');/*
	/*----------------------------------*/
	w_drag();
	/*----------------------------------*/
        function r(text){/*alert(text);*/
            contents=$("#output").html();
            /*alert(contents);*/
            if(contents){
                $("#output").html(text+'<br>'+contents);
             }
        }/**/
	/*----------------------------------*/




 
       /*===========================================================================*/
</script>