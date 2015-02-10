<?php
//data/[freq_]*.html


foreach(glob('data/*.html') as $file){
	list($tmp)=explode('_',basename($file));
	if(is_numeric($tmp)){
		$q=$tmp-1+1;	
	}else{
		$q=0;
	}


}

?>
