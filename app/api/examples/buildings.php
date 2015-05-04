<?php
/**
 * Ukázková Towns API Aplikace - Objekty
 *
 * @copyright 2015 Towns.cz
 * @link http://api.towns.cz/
 * @author     Pavol Hejný
 * @version    1.0
 *
 */

if(!isset($_GET['onlypage']))
echo '<div class="pageDescription">Seznam každé vlastnosti u všech budov města</div>';

//----------------------------------------------------------------Rozebrání budovy

if($_GET['dismantle']){
	$result = TownsApi('dismantle',$_GET['dismantle']);
	print_r($result);
}

//----------------------------------------------------------------Načtení objektů

$buildings = TownsApi('list', 'id,name,_name,type,permalink,origin,func,group,expand,block,attack,hold,resurl,res,profile,fp,fs,fc,fr,fx,own,superown,ww,x,y,traceid,starttime,readytime,stoptime',array('mybuildings'), 'y,x');
$buildings = $buildings['objects'];

//----------------------------------------------------------------CSS, JS
?>
    <style type="text/css">
		pre {
			overflow: scroll;
			width: 100px;
			height: 200px;
		}

		table {
			border-width:1px;
			border-color: #666;
			border-style: solid;
			font-size:14px;
			border-spacing:2;
			border-collapse:collapse;
		}

		th{
			color:#ffffff;
			padding:3px;
			background-color:#056597;

		}

		td {
			padding: 3px;
			border: 1px #ddd dotted;
		}

		tr:nth-child(2n+1){
			background:#eee;
		}

		tr:nth-child(2n){
			background:#ddd;
		}
		tr:hover{
			background:#f4f4f4;

		}

    </style>

	<script type="text/javascript">
		    function showres(e){
				var res = window.open("", "Cell", "width=800, height=600");
				res.document.body.innerHTML = "";
				res.document.write('<pre>'+e.innerHTML+'</pre>');
				res.focus();
			}
	</script>

<?php

//----------------------------------------------------------------Hlavička HTML Tabulky
?>

    <table>
        <tr>

        <th>id</th>
        <th>name</th>
        <th>_name</th>
        <th>type</th>
        <th>permalink</th>
        <th>origin</th>
        <th>func</th>
        <th>group</th>
        <th>expand</th>
        <th>block</th>
        <th>attack</th>
        <th>hold</th>
        <th>resurl</th>
        <th>res</th>
        <th>profile</th>
        <th>fp</th>
        <th>fs</th>
        <th>fc</th>
        <th>fr</th>
        <th>fx</th>
        <th>own</th>
        <th>superown</th>
        <th>[x,y,ww]</th>
        <th>traceid</th>
        <th>starttime</th>
        <th>readytime</th>
        <th>stoptime</th>
        <th>akce</th>

        </tr>
        <?php
			//----------------------------------------------------------------Jednotlivé budovy
			if($buildings)
            foreach($buildings as $row){
                echo('<tr>');

				echo('<td>'.$row['id'].'</td>');
				echo('<td><pre>'.$row['name'].'</pre></td>');
				echo('<td><b>'.$row['_name'].'</b></td>');
				echo('<td>'.$row['type'].'</td>');
                echo('<td><a href="'.$_SESSION['townsapi_url'].'/'.$row['permalink'].'" target="_blank">'.$row['permalink'].'</a></td>');
				echo('<td><pre onclick="showres(this);">'.$row['origin'].'</pre></td>');
				echo('<td><pre onclick="showres(this);">'.print_r($row['func'],true).'</pre></td>');
				echo('<td>'.$row['group'].'</td>');
				echo('<td>'.$row['expand'].'</td>');
				echo('<td>'.$row['block'].'</td>');
				echo('<td>'.$row['attack'].'</td>');
				echo('<td><pre onclick="showres(this);">'.print_r($row['hold'],true).'</pre></td>');
				echo('<td><img src="'.$row['resurl'].'" alt="'.$row['name'].'" height="200" /></td>');
				echo('<td><pre onclick="showres(this);">'.$row['res'].'</pre></td>');
				echo('<td><pre onclick="showres(this);">'.print_r($row['profile'],true).'</pre></td>');
				echo('<td>'.$row['fp'].'</td>');
				echo('<td>'.$row['fs'].'</td>');
				echo('<td><pre onclick="showres(this);">'.print_r($row['fc'],true).'</pre></td>');
				echo('<td>'.$row['fr'].'</td>');
				echo('<td>'.$row['fx'].'</td>');
				echo('<td>'.$row['own'].'</td>');
				echo('<td>'.$row['superown'].'</td>');
				echo('<td>['.$row['x'].','.$row['y'].','.$row['ww'].']</td>');
				echo('<td>'.$row['traceid'].'</td>');
				echo('<td>'.$row['starttime'].'<br>'.($row['starttime']?date('d.m.Y H:i:s',$row['starttime']):'').'</td>');
				echo('<td>'.$row['readytime'].'<br>'.($row['readytime']?date('d.m.Y H:i:s',$row['readytime']):'').'</td>');
				echo('<td>'.$row['stoptime'].'<br>'.($row['stoptime']?date('d.m.Y H:i:s',$row['stoptime']):'').'</td>');
                echo('<td><a href="?dismantle='.$row['id'].'">Rozebrat</a></td>');

                echo('</tr>');
            }
			//----------------------------------------------------------------Konec
        ?>
    </table>





