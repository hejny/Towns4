<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/play.php

   Posouvání času
*/
//==============================

window('swrdgtf',300);

ahref('Start','e=map;play=1;noi=1;'.jsa2('maptimesctlref();'));
e(nbsp.'-'.nbsp);
bhp('Timetable','timetable');
e(nbsp.'-'.nbsp);
ahref('Play',js2('mapstart(6);'));//mapstart
e(nbsp.'-'.nbsp);
ahref('PlayX',js2('mapstart(window.prompt(\'FPS? (fps<0 => reverse)\',\'6\')-1+1);'));
e(nbsp.'-'.nbsp);
ahref('PlayXC',js2('mapstart(window.prompt(\'FPS? (fps<0 => reverse)\',\'6\')-1+1);w_close(\'play\');'));
//input_text('ms','200',99,6,'border: 2px solid #222222; background-color: #999999');
e(nbsp.'-'.nbsp);
ahref('Stop',js2('mapstop();'));
e(nbsp.'-'.nbsp);

e('<span id="maptimesctl"></span>');
br();

hydepark('timetable');
e('<span id="timetable"></span>');
br();
ihydepark('timetable');


?>

<script type="text/javascript">
    document.maptimespeed=0;
    //---------------------------------------------------
    maptimectl = function(time){
        document.maptime=time;
        $(".timeplay").each(function( i ) {
            //console.log('start - '+$(this).attr('starttime'));
            //console.log('stop - '+$(this).attr('stoptime'));
            //timestamp = Math.round(+new Date()/1000);
            starttime = $(this).attr('starttime')-1+1;
            stoptime = $(this).attr('stoptime')-1+1;
            
            if((starttime<=time) && (stoptime==0 || stoptime>time)){
                $(this).css('display','block');
                //console.log('block');
            }else{
                $(this).css('display','none');
                //console.log('none');
            }
            
        });
        
    };
    //---------------------------------------------------
    maptimesctlref = function(){
        buffer='';
        
        maptime_prev=false;
        maptime_next=false;
        maptime_i=0;
        
        
        for (i = 0; i < unittimes.length; i++) {
            
            /*if(i<unittimes.length){
                if(document.maptime==unittimes[i+1]){
                    maptime_prev=unittimes[i];
                }
            }
            
            if(i>0){
                if(document.maptime==unittimes[i-1]){
                    maptime_next=unittimes[i];
                }
            }*/

            if(document.maptime==unittimes[i]){
                maptime_i=i;
                buffer += ( '<?php imge('quest/quest_finished.png','',10,10) ?>' );
            }else{
                buffer += ( '<a onclick="maptimectl('+unittimes[i]+');maptimesctlref();"><?php imge('quest/quest_nonefinished.png','',10,10) ?></a>' );
            }
        }
        
        $('#timetable').html(buffer);
        
        buffer='';
        
        /*buffer += '1 ... ';*/
        /*if(maptime_prev!=false)
            buffer += '<a onclick="maptimectl('+maptime_prev+');maptimesctlref();">&lt;</a>';*/
        /*buffer += ' ('+(maptime_i+1)+') ';*/
        
        buffer += (maptime_i+1)+'/'+unittimes.length;
        
        /*if(maptime_next!=false)
            buffer += '<a onclick="maptimectl('+maptime_next+');maptimesctlref();">&gt;</a>';*/
        /*buffer += ' ... '+unittimes.length;*/
        
        $('#maptimesctl').html(buffer);
    };
    //---------------------------------------------------
    
    function mapstart(fps) {
        mapstop();
        document.mapstartonstart=true;
        if(fps==0){
            alert('FPS!');
            return;
        }
        if(fps<0){
            fps=-fps;
            document.mapreverse=true;
        }else{
            document.mapreverse=false;
        }
        //alert('true');
        
        document.mapinterval=setInterval(function(){ 
            
            //alert('interval');
            maptime_next=false;
            for (i = 0; i < unittimes.length; i++) {
                if(document.mapreverse){
                    if(i<unittimes.length){
                        //alert(i);
                        if(document.maptime==unittimes[i+1]){
                            //alert(unittimes[i]);
                            maptime_next=unittimes[i];//maptime_prev
                            break;
                        }
                    }
                }else{
                    if(i>0){
                        //alert(document.maptime+'=='+unittimes[i-1]);
                        if(document.maptime==unittimes[i-1]){
                            maptime_next=unittimes[i];
                            break;
                        }
                    }
                }
            }
            
            if(maptime_next!==false){
                maptimectl(maptime_next);
                maptimesctlref();
            }else{
                
                if(document.mapstartonstart){
                    //alert('replay');
                    if(!document.mapreverse){
                        maptimectl(unittimes[0]);
                        maptimesctlref();
                    }else{
                        maptimectl(unittimes[unittimes.lenght-1]);
                        maptimesctlref();
                    }
                    
                }else{
                    //alert('stop');
                    mapstop();
                }
                
            }
            document.mapstartonstart=false;

        }, 1000/fps);
        
        //alert('false');
        
    }
    
    function mapstop() {
        //alert('stop');
        clearInterval(document.mapinterval);
    }
    
    
    
    
    maptimesctlref();
    
    //---------------------------------------------------
</script>