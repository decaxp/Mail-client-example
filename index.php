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
                <th><a href="#" onClick="mSort(0)">From</a></th>
                <th><a href="#" onClick="mSort(1)">To</a></th>
                <th><a href="#" onClick="mSort(2)">Subject</a></th>
                <th><a href="#" onClick="mSort(3)">Date</a></th>
                <th><a href="#" onClick="mSort(4)">Open count</a></th>
                <th><a href="#" onClick="mSort(5)">Click count</a></th>

            </tr>
            <?php $maxID=1;
                foreach ($arr as $item=>$row){?>
                    <tr class="dataTr">
                        <?php foreach ($row as $key=>$value){
                            if ($key=='id' && $maxID<$value) {
                                $maxID = $value;
                                continue;
                            }
                            
                            ?>
                            <td class="dataTd"><?php if ($key=="subject"){ ?>
                                    <button class="btn btn-link" onclick="ajaxBody(<?= $row['id']; ?>);"  data-toggle="modal" data-target="#myModal">
                                        <?= $value??0; ?>
                                    </button>
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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalSubject">Название модали</h4>
            </div>
            <div id="modalBody" class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
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

<script>
    var flag=true;

    function swap(tr1,tr2){
        var str='';
        len=tr1.getElementsByClassName('dataTd').length;
        for(var j=0;j<len;j++){
            str1=tr1.getElementsByClassName('dataTd')[j].innerHTML;
            tr1.getElementsByClassName('dataTd')[j].innerHTML=tr2.getElementsByClassName('dataTd')[j].innerHTML;
            tr2.getElementsByClassName('dataTd')[j].innerHTML=str1;
        }
    }

   function mSort(num){
       if (num === undefined) {
           num = 0;
       }
       var tr=document.getElementsByClassName('dataTr');
       for(var i=0;i<tr.length-1;i++){
           for(var z=0;z<tr.length-1-i;z++){
               str1=tr[z].getElementsByClassName('dataTd')[num].innerText;
               str2=tr[z+1].getElementsByClassName('dataTd')[num].innerText;

               if (flag) {
                   if (str1 < str2) {
                       swap(tr[z], tr[z + 1]);
                   }
               }else{
                   if (str1 > str2) {
                       swap(tr[z], tr[z + 1]);
                   }
               }
           }
       }

       flag=!flag;


   }


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
                if (!isEmpty(responce)) {
                    var arr = JSON.parse(responce.toString());
                    var str = '';//'<tr><th>From</th><th>To</th><th>Subject</th><th>Date</th><th>Open count</th><th>Click count</th></tr>';
                    var array = $.map(arr, function (value, index) {
                        return [value];
                    });
//                    console.log(array);
                    for (var row in array) {
                        str = str + "<tr class='dataTr'>";
                        for (var property in array[row]) {

                            $('#maxID').val(array[row]["id"]);

                            if (property.localeCompare('id')==0)
                                continue;
                            if (array[row].hasOwnProperty(property)) {
                                if (property.localeCompare('subject')!=0)
                                    str = str + "<td class='dataTd'>" + array[row][property] + "</td>";
                                else{
                                    str = str + "<td><button class='btn btn-link' onclick='ajaxBody(";
                                    str=str+ array[row]["id"];
                                    str+=")'  data-toggle='modal' data-target='#myModal'>";
                                    str+=array[row]['subject'];
                                    str+="</button>";
                                }
                            }

                        }
                        str = str + "</tr>";
                    }

//                    console.log(str);
                    $('#table').append(str);
                    //                console.log(responce);
                }
            }
        });
    }

    function ajaxBody(id) {
        var result;
        $.ajax({
            url:"body.php?id="+id,
            cache: false,
            success: function(responce){
                if (!isEmpty(responce)) {
                    var arr = JSON.parse(responce.toString());
                    $('#modalBody').html(arr['body']);
                    $('#modalSubject').html(arr['subject']);
                }
            }
        });
    }
    $('#myModal').on('hidden.bs.modal', function (e) {
        location.reload();
    })
    setInterval(ajaxReceive, 5000);

//    setTimeout(ajaxReceive, 3000);
</script>
</body>
</html>
