<?php
?>
    <!DOCTYPE html>
    <html>
        <style>
            div.comment_list {
                width: 100%;
                border: 1px solid gray;
            }
            nav {
                float: left;
                max-width: 160px;
                margin: 0;
                padding: 1em;
            }
        </style>
    <body>
         <div id="record_list">
            </div>

            <div id="menu_list">
                <label >用户功能菜单: </label>
                  <table>
                     <tr>
                         <td><p onclick="showthis()">添加留言</p></td>
                     </tr>
                     <tr>
                        <td>用户留言</td>
                     </tr>
                      <tr>
                        <td> <button>退出登录</button></td>
                      </tr>
                    </table>
                </div>
                <nav>
                <div id="comment_list" style="top:100px;left:100px;right:100px;bottom:100px;background:white;padding:20px;border:3px solid gray; display: none; position:fixed">
                    <p>添加内容</p>
                        标题:<input type="text" name="title" id="title"> <br>
                         <!-- 留言:<input type="text" name="name" id="inputname"> <br>    要取的值-->
                        留言:<br><textarea name="comment" rows="4" cols="25" id="comment"> </textarea><br>
                        添加上传文件<br><input type="file" name="file_upload" id="file_upload"><br>
                        <input type="hidden" name="time" id="time" value="" /><br>
                    <p id="record_error"></p>
                    <button onclick="hidethis()" id="hide_p1">隐藏</button>
                    <button onclick="add_comment()" >提交</button>
                </div>
                </nav>
    </body>
<script>
function record_list(){   				  			 //定义添加留言函数
    var xmlhttp = new XMLHttpRequest();    			 //创建XMLHttpRequest对象
    var url = "/ajaxblog/api.php?do=recordlist"; 		  //建立接口
    console.log(['userid']);
    xmlhttp.onreadystatechange = function(){      //当 xmlhttp 属性改变时，执行函数 function
        //console.log(this.readyState + " " + this.status);
        if (this.readyState == 4 && this.status == 200) {//当 onreadystatechange 的属性readyState 是4，status 属性是200时  执行以下命令
            var obj3 = JSON.parse(this.responseText);           //把 数组转化成 对象格式
                if(obj3.err==0){                          //obj3没有错误提示
                    //console.log(obj3["data"]["records"]);//打印出obj3中data 下的 records
                    var html = "";              //设定一个空变量 定义名称html
                    for (var i = 0; i < obj3.data.records.length; i++) {        // 设定循环条件，当i 值小于记录长度时，遍历
                        var record = obj3.data.records[i];      // 定义 record 值是 对象的值.
                        html += '<div>' +"name:  "+ record.name+ '</div>';          //   输出记录中 name 的值
                        html += '<div>' + "comment:  "+record.comment+ '</div>';        //输出记录中 comment的值
                        html += '<div>' + "entrytime: "+record.entrytime+ '</div>';             //输出记录中 entrytime的值
                        html += '<div>' + "ID: "+ record.ID+ '</div>';              //输出记录中 ID的值
                        html += '<div>' + "userid: "+ record.userid+ '</div>';      //输出记录中 userid的值
                        html += "<br>";	
                    }
                    document.getElementById('record_list').innerHTML = html;        //将元素 record_list 的值改为遍历后的数据表的值
                }else{
                    console.log(obj3);
                    document.getElementById('record_error').innerHTML = obj3["errmsg"];   // 显示错误信息 errmsg
                }
        }
    }
    xmlhttp.open("GET", url);       //打开端口 url
    xmlhttp.send();                         //发送请求
}
record_list();

function add_comment(){    // 定义添加留言函数    //TODO reviwe 2017-9-26
    var xmlhttp = new XMLHttpRequest();   // 创建XMLHttpRequest对象
    var title = document.getElementById("title").value;
    var comment = document.getElementById("inputname").value;
    var url = "/ajaxblog/api.php?do=add_comment&title="+title+"&comment="+comment;  // 创建添加留言函数端口
    xmlhttp.onreadystatechange = function () {      //  当 xmlhttp 属性改变时，执行函数 function  
       if (this.readyState == 4 && this.status == 200) {//  当 onreadystatechange 的属性readyState 是4，status 属性是200时  执行以下命令
           console.log(this.responseText);
          var obj4 = JSON.parse(this.responseText);
          if (obj4.err == 0){
              alert('添加成功');
          }else{
              alert('添加失败'+obj4.errmsg);
              if(obj4.err == 3){
					window.location.href="login.html";
                  }
          }
       }
    }
    xmlhttp.open("GET",url);
    xmlhttp.send();
}



function showthis() {   // 定义函数showthis
    var bbs = document.getElementById('comment_list'); // 找到comment_list这个 div
    if (bbs.style.display === 'none') {    // 如果 div 没有被显示，则显示当前 div
        bbs.style.display = 'block';
    }
}
        function hidethis() {      //定义函数 hidethis
            var bbs=document.getElementById('comment_list');  // 找到comment_list这个 div
            if (bbs.style.display=== 'block'){   //如果当前状态是显示，则隐藏 div
                bbs.style.display = 'none';
            }
        }
        hidethis();  //调用函数

        function postmsgdata(){
        }
    </script>
    </html>
<?
?>