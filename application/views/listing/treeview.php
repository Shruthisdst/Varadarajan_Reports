<div class="container">
    <div class="row">
        <!-- Column 1 -->
        <div class="col-md-8 clear-paddings">
            <div class="col-padded"><!-- inner custom column -->
                <ul class="list-unstyled clear-margins"><!-- widgets -->
                    <li class="widget-container widget_recent_news"><!-- widgets list -->
                        <ul class="list-unstyled">
                            <li class="journal-article">
                                <p class="journal-article-title">
                                    <?=$data[0]->btitle?>
                                </p><hr />
                            </li>
                        </ul>
                    </li>
                    <li class="widget-container widget_recent_news">
                        <ul class="list-unstyled">
							<li>
<?php
$stack = array();
$p_stack = array();
$first = 1;
$flag = 1;
$li_id = 0;
$ul_id = 0;
$plus_src = PUBLIC_URL . "images/plus.gif";
$link = PUBLIC_URL . "images/";
$minus_src = PUBLIC_URL . "images/bullet_1.gif";
$plus_link = "<img src=\"$plus_src\" alt=\"\" onclick=\"display_block(this)\" />";
$bullet = "<img src=\"$minus_src\" alt=\"\" />";
?>
<div class = "treeview">

<?php foreach($data as $row) { 
	if($first)
        {
            array_push($stack,$row->level);
            $ul_id++;
            echo "<ul id=\"ul_id$ul_id\">\n";
            array_push($p_stack,$ul_id);
            $li_id++;
            $deffer = $viewHelper->display_tabs($row->level) . "<li id=\"li_id$li_id\">:rep:<span class=\"s1\">". $viewHelper->displayTitle($row->bcode, $row->title, $row->page) ."</span>";
            $first = 0;
        }
        elseif($row->level > $stack[sizeof($stack)-1])
        {
            $deffer = preg_replace('/:rep:/',"$plus_link",$deffer);
            echo $deffer;
            $ul_id++;
            $li_id++;
            array_push($stack,$row->level);
            array_push($p_stack,$ul_id);
            $deffer = "\n" . $viewHelper->display_tabs(($row->level-1)) . "<ul class=\"dnone\" id=\"ul_id$ul_id\">\n";
            $deffer = $deffer . $viewHelper->display_tabs($row->level) ."<li id=\"li_id$li_id\">:rep:<span class=\"s2\">". $viewHelper->displayTitle($row->bcode, $row->title, $row->page) . "</span>";
        }
        elseif($row->level < $stack[sizeof($stack)-1])
        {
            $deffer = preg_replace('/:rep:/',"$bullet",$deffer);
            echo $deffer;
            for($k=sizeof($stack)-1;(($k>=0) && ($row->level != $stack[$k]));$k--)
            {
                echo "</li>\n". $viewHelper->display_tabs($row->level) ."</ul>\n";
                $top = array_pop($stack);
                $top1 = array_pop($p_stack);
            }
            $li_id++;
            $deffer = $viewHelper->display_tabs($row->level) . "</li>\n";
            $deffer = $deffer . $viewHelper->display_tabs($row->level) ."<li id=\"li_id$li_id\">:rep:<span class=\"s1\">". $viewHelper->displayTitle($row->bcode, $row->title, $row->page) . "</span>";
        }
        elseif($row->level == $stack[sizeof($stack)-1])
        {
            $deffer = preg_replace('/:rep:/',"$bullet",$deffer);
            echo $deffer;
            $li_id++;
            $deffer = "</li>\n";
            $deffer = $deffer . $viewHelper->display_tabs($row->level) ."<li id=\"li_id$li_id\">:rep:<span class=\"s1\">". $viewHelper->displayTitle($row->bcode, $row->title, $row->page) . "</span>";
        }
	}
	$deffer = preg_replace('/:rep:/',"$bullet",$deffer);
    echo $deffer;

    for($i=0;$i<sizeof($stack);$i++)
    {
        echo "</li>\n". $viewHelper->display_tabs($row->level) ."</ul>\n";
    }
	?>
	 </div>
							</li>
                        </ul>
                    </li><!-- widgets list end -->
                </ul><!-- widgets end -->
            </div>
        </div>
