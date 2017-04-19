<?php
include_once "search.php";
/**
 * Created by PhpStorm.
 * User: Dmitry
 * Date: 19.04.2017
 * Time: 8:28
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Mail task</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet">


    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <script src="https://yastatic.net/jquery/3.1.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>


</head>
<body>

<div class="container theme-showcase" role="main">
    <div class="row">
        <table id="table" class="table">
            <tr>
                <th>From</th>
                <th>To</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Open count</th>
                <th>Click count</th>
            </tr>
            <?php $maxID=1;
                foreach ($arr as $item=>$row){?>
                    <tr>
                        <?php foreach ($row as $key=>$value){
                            if ($key=='id' && $maxID<$value) {
                                $maxID = $value;
                                continue;
                            }
                            
                            ?>
                            <td><?php if ($key=="subject"){ ?>
                                <a href=""><?= $value??0; ?></a>
                                <?php }else{ echo $value??0;
                                } ?>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
        </table>
    </div>
</div>
<input type="hidden" name="maxID" id="maxID" value="<?= $maxID; ?>">

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div class="container theme-showcase" role="main">
    <div class="row">
        <form>
            <div class="form-group">
                <label for="from">From:</label>
                <input type="email" name="from" class="form-control" id="from" placeholder="from">
            </div>
            <div class="form-group">
                <label for="to">To:</label>
                <input type="email" name="to" class="form-control" id="to" placeholder="to">
            </div>
            <div class="form-group">
                <label for="Subject">Subject:</label>
                <input type="text" name="subject" class="form-control" id="subject" placeholder="subject">
            </div>
            <div class="form-group">
                <label for="Body">Body:</label>
                <textarea class="form-control" name="body" id="body" placeholder="body"></textarea>
            </div>

            <button type="button" onclick="ajaxSend()" class="btn btn-primary">Отправить</button>
        </form>
    </div>
</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script>
    function isEmpty(str) {
        return (!str || 0 === str.length);
    }
    function ajaxSend() {
        var from=$('#from').val();
        var to=$('#to').val();
        var subject=$('#subject').val();
        var body=$('#body').val();
        $.ajax({
            type: "POST",
            url: "mail.php",
            data: "from="+from+"&to="+to+"&subject="+subject+"&body="+body,
            success: function(msg){
                alert( "Отправлено!");
                $('#from').val('');
                $('#to').val('');
                $('#subject').val('');
                $('#body').val('');
            }
        });
    }

    function ajaxReceive() {
        var result;
        $.ajax({
            url:"search.php?id="+$('#maxID').val(),
            cache: false,
            success: function(responce){
                console.log(responce);
                if (!isEmpty(responce)) {
                    var arr = JSON.parse(responce.toString());
                    var str = '';//'<tr><th>From</th><th>To</th><th>Subject</th><th>Date</th><th>Open count</th><th>Click count</th></tr>';
                    var array = $.map(arr, function (value, index) {
                        return [value];
                    });

                    for (var row in array) {
                        str = str + "<tr>";
                        for (var property in array[row]) {
//                            console.log(property);
                            if (property.localeCompare('id')==0 && $('#maxID').val()<array[row]["id"]) {
                                $('#maxID').val(array[row]["id"]);
                                continue;//вывод id не нужен, потому отбрасываем
                            }
                            if (property.localeCompare('id')==0)
                                continue;
                            if (array[row].hasOwnProperty(property)) {
                                str = str + "<td>" + array[row][property] + "</td>";
                            }

                        }
                        str = str + "</tr>";
                    }
                    console.log(str);

//                    $('#table tr').remove();
                    $('#table').append(str);
                    //                console.log(responce);
                }
            }
        });
    }

    setInterval(ajaxReceive, 3000);
//    setTimeout(ajaxReceive, 3000);
</script>
</body>
</html>
