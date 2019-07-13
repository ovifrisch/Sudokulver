
<?php
$target_dir = "uploads/im.jpeg";
$my_arr = array();

if(isset($_FILES['image'])) {
    $errors= array();

    $file_name = $_FILES['image']['name'];
    $file_size =$_FILES['image']['size'];
    $file_tmp =$_FILES['image']['tmp_name'];
    $file_type=$_FILES['image']['type'];
    // $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));

    // $expensions= array("jpeg","jpg","png");
      
    // if(in_array($file_ext,$expensions)=== false){
    //     $errors[]="extension not allowed, please choose a JPEG or PNG file.";
    // }

    if($file_size > 2097152){
        $errors[]='File size must be excately 2 MB';
    }

    if(empty($errors) == true) {
        if (move_uploaded_file($file_tmp, $target_dir) == true) {
            // $compile_command = escapeshellcmd('g++ -std=c++11 $(pkg-config --cflags --libs opencv) -lboost_system -lboost_filesystem process_image.cpp');
            $exec_command = escapeshellcmd('./process_image');
            // shell_exec($compile_command);
            $my_arr = shell_exec($exec_command);
            // $html_file = "index.html";
            // $dom = new DOMDocument;
            // $dom->loadHTMLFile($html_file);
            // $a = new DOMXPath($dom);
            // $spans = $a->query("//*[contains(@class, 'num_in')]");
            // for ($i = $spans->length - 1; $i > -1; $i--) {
            //     $spans->item($i)->setAttribute('value', "2");
            // }
            // header("Location: http://localhost:8888/index.html");
            // exit;
        }
    }
    else {
        print_r($errors);
        exit;
    }
}
?>

<html>
    <head>
        <script type="text/javascript" src="js/lib/jquery-3.3.1.js"></script>
        <script type="text/javascript" src="js/include/square.js"></script>
        <script type="text/javascript" src="js/include/line.js"></script>
        <script type="text/javascript" src="js/include/box.js"></script>
        <script type="text/javascript" src="js/include/grid.js"></script>
        <script type="text/javascript" src="js/include/solve_grid.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
        <link rel="stylesheet" href="style/style.css">
    </head>
    <body>
        <div id="wrap">
            <div id="title"><span style="color:white">S</span>UD<span style="color:white">O</span>KU<span style="color:white">LVER</span></div>
            <!-- Grid -->
            <table id="grid">
                <!-- 1st Box Row -->
                <tr>
                    <!-- 1st Box -->
                    <td class="box_td">
                        <table class="box" id = "box1">
                            <tr>
                                <td class = "square left top" onclick="square_click(this)">
                                    <input class = "num_in row0 col0" type="text" tabindex = "1" size="2" maxlength="1" autofocus="autofocus" value=<?php echo $my_arr[0] ?>>
                                </td>

                                <td class = "square top" onclick="square_click(this)">
                                    <input class = "num_in row0 col1" type="text" tabindex = "2" size="2" maxlength="1" value=<?php echo $my_arr[1] ?>>
                                </td>

                                <td class = "square top right" onclick="square_click(this)">
                                    <input class = "num_in row0 col2" type="text" tabindex = "3" size="2" maxlength="1" value=<?php echo $my_arr[2] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square left" onclick="square_click(this)">
                                    <input class = "num_in row1 col0" type="text" tabindex = "10" size="2" maxlength="1" value=<?php echo $my_arr[3] ?>>
                                </td>

                                <td class = "square middle" onclick="square_click(this)">
                                    <input class = "num_in row1 col1" type="text" tabindex = "11" size="2" maxlength="1" value=<?php echo $my_arr[4] ?>>
                                </td>

                                <td class = "square right" onclick="square_click(this)">
                                    <input class = "num_in row1 col2" type="text" tabindex = "12" size="2" maxlength="1" value=<?php echo $my_arr[5] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square left bottom" onclick="square_click(this)">
                                    <input class = "num_in row2 col0" type="text" tabindex = "19" size="2" maxlength="1" value=<?php echo $my_arr[6] ?>>
                                </td>

                                <td class = "square bottom" onclick="square_click(this)">
                                    <input class = "num_in row2 col1" type="text" tabindex = "20" size="2" maxlength="1" value=<?php echo $my_arr[7] ?>>
                                </td>

                                <td class = "square bottom right" onclick="square_click(this)">
                                    <input class = "num_in row2 col2" type="text" tabindex = "21" size="2" maxlength="1" value=<?php echo $my_arr[8] ?>>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <!-- 2nd Box -->
                    <td class="box_td">
                        <table class="box" id = "box2">
                            <tr>
                                <td class = "square left top" onclick="square_click(this)">
                                    <input class = "num_in row0 col3" type="text" tabindex = "4" size="2" maxlength="1" value=<?php echo $my_arr[9] ?>>
                                </td>

                                <td class = "square top" onclick="square_click(this)">
                                    <input class = "num_in row0 col4" type="text" tabindex = "5" size="2" maxlength="1" value=<?php echo $my_arr[10] ?>>
                                </td>

                                <td class = "square top right" onclick="square_click(this)">
                                    <input class = "num_in row0 col5" type="text" tabindex = "6" size="2" maxlength="1" value=<?php echo $my_arr[11] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square left" onclick="square_click(this)">
                                    <input class = "num_in row1 col3" type="text" tabindex = "13" size="2" maxlength="1" value=<?php echo $my_arr[12] ?>>
                                </td>

                                <td class = "square middle" onclick="square_click(this)">
                                    <input class = "num_in row1 col4" type="text" tabindex = "14" size="2" maxlength="1" value=<?php echo $my_arr[13] ?>>
                                </td>

                                <td class = "square right" onclick="square_click(this)">
                                    <input class = "num_in row1 col5" type="text" tabindex = "15" size="2" maxlength="1" value=<?php echo $my_arr[14] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square left bottom" onclick="square_click(this)">
                                    <input class = "num_in row2 col3" type="text" tabindex = "22" size="2" maxlength="1" value=<?php echo $my_arr[15] ?>>
                                </td>

                                <td class = "square bottom" onclick="square_click(this)">
                                    <input class = "num_in row2 col4" type="text" tabindex = "23" size="2" maxlength="1" value=<?php echo $my_arr[16] ?>>
                                </td>

                                <td class = "square bottom right" onclick="square_click(this)">
                                    <input class = "num_in row2 col5" type="text" tabindex = "24" size="2" maxlength="1" value=<?php echo $my_arr[17] ?>>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <!-- 3rd Box -->
                    <td class="box_td">
                        <table class="box" id = "box3">
                            <tr>
                                <td class = "square left top" onclick="square_click(this)">
                                    <input class = "num_in row0 col6" type="text" tabindex = "7" size="2" maxlength="1" value=<?php echo $my_arr[18] ?>>
                                </td>

                                <td class = "square top" onclick="square_click(this)">
                                    <input class = "num_in row0 col7" type="text" tabindex = "8" size="2" maxlength="1" value=<?php echo $my_arr[19] ?>>
                                </td>

                                <td class = "square top right" onclick="square_click(this)">
                                    <input class = "num_in row0 col8" type="text" tabindex = "9" size="2" maxlength="1" value=<?php echo $my_arr[20] ?>>
                                </td>
                            </tr>

                            <tr>
                                 <td class = "square left" onclick="square_click(this)">
                                    <input class = "num_in row1 col6" type="text" tabindex = "16" size="2" maxlength="1" value=<?php echo $my_arr[21] ?>>
                                </td>

                                <td class = "square middle" onclick="square_click(this)">
                                    <input class = "num_in row1 col7" type="text" tabindex = "17" size="2" maxlength="1" value=<?php echo $my_arr[22] ?>>
                                </td>

                                <td class = "square right" onclick="square_click(this)">
                                    <input class = "num_in row1 col8" type="text" tabindex = "18" size="2" maxlength="1" value=<?php echo $my_arr[23] ?>>
                                </td>
                            </tr>
                               
                            <tr>
                                 <td class = "square left bottom" onclick="square_click(this)">
                                    <input class = "num_in row2 col6" type="text" tabindex = "25" size="2" maxlength="1" value=<?php echo $my_arr[24] ?>>
                                </td>

                                <td class = "square bottom" onclick="square_click(this)">
                                    <input class = "num_in row2 col7" type="text" tabindex = "26" size="2" maxlength="1" value=<?php echo $my_arr[25] ?>>
                                </td>

                                <td class = "square bottom right" onclick="square_click(this)">
                                    <input class = "num_in row2 col8" type="text" tabindex = "27" size="2" maxlength="1" value=<?php echo $my_arr[26] ?>>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- 2nd Box Row -->
                <tr>
                    
                    <!-- 4th Box -->
                    <td class="box_td">
                        <table class="box" id = "box4">
                            <tr>
                                <td class = "square left top" onclick="square_click(this)">
                                    <input class = "num_in row3 col0" type="text" tabindex = "28" size="2" maxlength="1" value=<?php echo $my_arr[27] ?>>
                                </td>

                                <td class = "square top" onclick="square_click(this)">
                                    <input class = "num_in row3 col1" type="text" tabindex = "29" size="2" maxlength="1" value=<?php echo $my_arr[28] ?>>
                                </td>

                                <td class = "square top right" onclick="square_click(this)">
                                    <input class = "num_in row3 col2" type="text" tabindex = "30" size="2" maxlength="1" value=<?php echo $my_arr[29] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square left" onclick="square_click(this)">
                                    <input class = "num_in row4 col0" type="text" tabindex = "37" size="2" maxlength="1" value=<?php echo $my_arr[30] ?>>
                                </td>

                                <td class = "square middle" onclick="square_click(this)">
                                    <input class = "num_in row4 col1" type="text" tabindex = "38" size="2" maxlength="1" value=<?php echo $my_arr[31] ?>>
                                </td>

                                <td class = "square right" onclick="square_click(this)">
                                    <input class = "num_in row4 col2" type="text" tabindex = "39" size="2" maxlength="1" value=<?php echo $my_arr[32] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square left bottom" onclick="square_click(this)">
                                    <input class = "num_in row5 col0" type="text" tabindex = "46" size="2" maxlength="1" value=<?php echo $my_arr[33] ?>>
                                </td>

                                <td class = "square bottom" onclick="square_click(this)">
                                    <input class = "num_in row5 col1" type="text" tabindex = "47" size="2" maxlength="1" value=<?php echo $my_arr[34] ?>>
                                </td>

                                <td class = "square bottom right" onclick="square_click(this)">
                                    <input class = "num_in row5 col2" type="text" tabindex = "48" size="2" maxlength="1" value=<?php echo $my_arr[35] ?>>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <!-- 5th Box -->
                    <td class="box_td">
                        <table class="box" id = "box5">
                            <tr>
                                <td class = "square left top" onclick="square_click(this)">
                                    <input class = "num_in row3 col3" type="text" tabindex = "31" size="2" maxlength="1" value=<?php echo $my_arr[36] ?>>
                                </td>

                                <td class = "square top" onclick="square_click(this)">
                                    <input class = "num_in row3 col4" type="text" tabindex = "32" size="2" maxlength="1" value=<?php echo $my_arr[37] ?>>
                                </td>

                                <td class = "square top right" onclick="square_click(this)">
                                    <input class = "num_in row3 col5" type="text" tabindex = "33" size="2" maxlength="1" value=<?php echo $my_arr[38] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square left" onclick="square_click(this)">
                                    <input class = "num_in row4 col3" type="text" tabindex = "40" size="2" maxlength="1" value=<?php echo $my_arr[39] ?>>
                                </td>

                                <td class = "square middle" onclick="square_click(this)">
                                    <input class = "num_in row4 col4" type="text" tabindex = "41" size="2" maxlength="1" value=<?php echo $my_arr[40] ?>>
                                </td>

                                <td class = "square right" onclick="square_click(this)">
                                    <input class = "num_in row4 col5" type="text" tabindex = "42" size="2" maxlength="1" value=<?php echo $my_arr[41] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square bottom left" onclick="square_click(this)">
                                    <input class = "num_in row5 col3" type="text" tabindex = "49" size="2" maxlength="1" value=<?php echo $my_arr[42] ?>>
                                </td>

                                <td class = "square bottom" onclick="square_click(this)">
                                    <input class = "num_in row5 col4" type="text" tabindex = "50" size="2" maxlength="1" value=<?php echo $my_arr[43] ?>>
                                </td>

                                <td class = "square bottom right" onclick="square_click(this)">
                                    <input class = "num_in row5 col5" type="text" tabindex = "51" size="2" maxlength="1" value=<?php echo $my_arr[44] ?>>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <!-- 6th Box -->
                    <td class="box_td">
                        <table class="box" id = "box6">
                            <tr>
                                <td class = "square top left" onclick="square_click(this)">
                                    <input class = "num_in row3 col6" type="text" tabindex = "34" size="2" maxlength="1" value=<?php echo $my_arr[45] ?>>
                                </td>

                                <td class = "square top" onclick="square_click(this)">
                                    <input class = "num_in row3 col7" type="text" tabindex = "35" size="2" maxlength="1" value=<?php echo $my_arr[46] ?>>
                                </td>

                                <td class = "square top right" onclick="square_click(this)">
                                    <input class = "num_in row3 col8" type="text" tabindex = "36" size="2" maxlength="1" value=<?php echo $my_arr[47] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square left" onclick="square_click(this)">
                                    <input class = "num_in row4 col6" type="text" tabindex = "43" size="2" maxlength="1" value=<?php echo $my_arr[48] ?>>
                                </td>

                                <td class = "square middle" onclick="square_click(this)">
                                    <input class = "num_in row4 col7" type="text" tabindex = "44" size="2" maxlength="1" value=<?php echo $my_arr[49] ?>>
                                </td>

                                <td class = "square right" onclick="square_click(this)">
                                    <input class = "num_in row4 col8" type="text" tabindex = "45" size="2" maxlength="1" value=<?php echo $my_arr[50] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square bottom left" onclick="square_click(this)">
                                    <input class = "num_in row5 col6" type="text" tabindex = "52" size="2" maxlength="1" value=<?php echo $my_arr[51] ?>>
                                </td>

                                <td class = "square bottom" onclick="square_click(this)">
                                    <input class = "num_in row5 col7" type="text" tabindex = "53" size="2" maxlength="1" value=<?php echo $my_arr[52] ?>>
                                </td>

                                <td class = "square bottom right" onclick="square_click(this)">
                                    <input class = "num_in row5 col8" type="text" tabindex = "54" size="2" maxlength="1" value=<?php echo $my_arr[53] ?>>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- 3rd Box Row -->
                <tr>
                    <!-- 7th Box -->
                    <td class="box_td">
                        <table class="box" id = "box7">
                            <tr>
                                <td class = "square top left" onclick="square_click(this)">
                                    <input class = "num_in row6 col0" type="text" tabindex = "55" size="2" maxlength="1" value=<?php echo $my_arr[54] ?>>
                                </td>

                                <td class = "square top" onclick="square_click(this)">
                                    <input class = "num_in row6 col1" type="text" tabindex = "56" size="2" maxlength="1" value=<?php echo $my_arr[55] ?>>
                                </td>

                                <td class = "square top right" onclick="square_click(this)">
                                    <input class = "num_in row6 col2" type="text" tabindex = "57" size="2" maxlength="1" value=<?php echo $my_arr[56] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square left" onclick="square_click(this)">
                                    <input class = "num_in row7 col0" type="text" tabindex = "64" size="2" maxlength="1" value=<?php echo $my_arr[57] ?>>
                                </td>

                                <td class = "square middle" onclick="square_click(this)">
                                    <input class = "num_in row7 col1" type="text" tabindex = "65" size="2" maxlength="1" value=<?php echo $my_arr[58] ?>>
                                </td>

                                <td class = "square right" onclick="square_click(this)">
                                    <input class = "num_in row7 col2" type="text" tabindex = "66" size="2" maxlength="1" value=<?php echo $my_arr[59] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square bottom left" onclick="square_click(this)">
                                    <input class = "num_in row8 col0" type="text" tabindex = "73" size="2" maxlength="1" value=<?php echo $my_arr[60] ?>>
                                </td>

                                <td class = "square bottom" onclick="square_click(this)">
                                    <input class = "num_in row8 col1" type="text" tabindex = "74" size="2" maxlength="1" value=<?php echo $my_arr[61] ?>>
                                </td>

                                <td class = "square bottom right" onclick="square_click(this)">
                                    <input class = "num_in row8 col2" type="text" tabindex = "75" size="2" maxlength="1" value=<?php echo $my_arr[62] ?>>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <!-- 8th Box -->
                    <td class="box_td">
                        <table class="box" id = "box8">
                            <tr>
                                <td class = "square top left" onclick="square_click(this)">
                                    <input class = "num_in row6 col3" type="text" tabindex = "58" size="2" maxlength="1" value=<?php echo $my_arr[63] ?>>
                                </td>

                                <td class = "square top" onclick="square_click(this)">
                                    <input class = "num_in row6 col4" type="text" tabindex = "59" size="2" maxlength="1" value=<?php echo $my_arr[64] ?>>
                                </td>

                                <td class = "square top right" onclick="square_click(this)">
                                    <input class = "num_in row6 col5" type="text" tabindex = "60" size="2" maxlength="1" value=<?php echo $my_arr[65] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square left" onclick="square_click(this)">
                                    <input class = "num_in row7 col3" type="text" tabindex = "67" size="2" maxlength="1" value=<?php echo $my_arr[66] ?>>
                                </td>

                                <td class = "square middle" onclick="square_click(this)">
                                    <input class = "num_in row7 col4" type="text" tabindex = "68" size="2" maxlength="1" value=<?php echo $my_arr[67] ?>>
                                </td>

                                <td class = "square right" onclick="square_click(this)">
                                    <input class = "num_in row7 col5" type="text" tabindex = "69" size="2" maxlength="1" value=<?php echo $my_arr[68] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square bottom left" onclick="square_click(this)">
                                    <input class = "num_in row8 col3" type="text" tabindex = "76" size="2" maxlength="1" value=<?php echo $my_arr[69] ?>>
                                </td>

                                <td class = "square bottom" onclick="square_click(this)">
                                    <input class = "num_in row8 col4" type="text" tabindex = "77" size="2" maxlength="1" value=<?php echo $my_arr[70] ?>>
                                </td>

                                <td class = "square bottom right" onclick="square_click(this)">
                                    <input class = "num_in row8 col5" type="text" tabindex = "78" size="2" maxlength="1" value=<?php echo $my_arr[71] ?>>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <!-- 9th Box -->
                    <td class="box_td">
                        <table class="box" id = "box9">
                            <tr>
                                <td class = "square top left" onclick="square_click(this)">
                                    <input class = "num_in row6 col6" type="text" tabindex = "61" size="2" maxlength="1" value=<?php echo $my_arr[72] ?>>
                                </td>

                                <td class = "square top" onclick="square_click(this)">
                                    <input class = "num_in row6 col7" type="text" tabindex = "62" size="2" maxlength="1" value=<?php echo $my_arr[73] ?>>
                                </td>

                                <td class = "square top right" onclick="square_click(this)">
                                    <input class = "num_in row6 col8" type="text" tabindex = "63" size="2" maxlength="1" value=<?php echo $my_arr[74] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square left" onclick="square_click(this)">
                                    <input class = "num_in row7 col6" type="text" tabindex = "70" size="2" maxlength="1" value=<?php echo $my_arr[75] ?>>
                                </td>

                                <td class = "square middle" onclick="square_click(this)">
                                    <input class = "num_in row7 col7" type="text" tabindex = "71" size="2" maxlength="1" value=<?php echo $my_arr[76] ?>>
                                </td>

                                <td class = "square right" onclick="square_click(this)">
                                    <input class = "num_in row7 col8" type="text" tabindex = "72" size="2" maxlength="1" value=<?php echo $my_arr[77] ?>>
                                </td>
                            </tr>

                            <tr>
                                <td class = "square bottom left" onclick="square_click(this)">
                                    <input class = "num_in row8 col6" type="text" tabindex = "79" size="2" maxlength="1" value=<?php echo $my_arr[78] ?>>
                                </td>

                                <td class = "square bottom" onclick="square_click(this)">
                                    <input class = "num_in row8 col7" type="text" tabindex = "80" size="2" maxlength="1" value=<?php echo $my_arr[79] ?>>
                                </td>

                                <td class = "square bottom right" onclick="square_click(this)">
                                    <input class = "num_in row8 col8" type="text" tabindex = "81" size="2" maxlength="1" value=<?php echo $my_arr[80] ?>>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <button class="solve" onclick="solve()">SOLVE<br>ALL</button>
            <button class="set" onclick="set()">DONE INPUTTING</button>
            <button class="reset" onclick="reset()">RESET</button>
            <button class="selected" onclick="solve_selected()">SOLVE<br>SELECTED</button>
            <div id = "myform">
                <form action="upload.php" method="POST" enctype="multipart/form-data">
                    <input type="file" id="choose_file" name="image"> 
                    <input id="submit" name="submit" type="submit">
                </form>
            </div>
        </div>

    </body>
</html>




