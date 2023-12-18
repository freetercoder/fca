<html>
<head>
    <title>JSON API UI for test</title>
    <style>
        .input_text{
            width:80%;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
    <p><input type='text' id='bearer' placeholder='bearer' class="input_text" /></p>
    <p>
        <select id='method'>
            <option value='get'>GET</option>
            <option value='post' selected='selected'>POST</option>
            <option value='put'>PUT</option>
            <option value='delete'>DELETE</option>
        </select>
        <input type='text' id='url' placeholder='url'  class="input_text" />
    </p>
    <p>
        <textarea id='request_body' placeholder='json request body' rows='20' class="input_text"></textarea>
    </p>
    <p><input type="button" id='send_request' value="send request" /></p>
    <p id="success_alert" style='color:blue;display:none;'>SUCCESS</p>
    <p id="fail_alert" style='color:red;display:none;'>FAIL</p>
    <p>
        <textarea id='response' placeholder='response' rows='20' class="input_text"></textarea>
    </p>
    
        
    
    <script type="text/javascript">
        $(document).ready(function(){
            $("#response").hide();


            $("#send_request").click(function(){
                let header = {};
                if ($("#bearer").val() != ""){
                    header = {"Authorization" : "bearer " + $("#bearer").val()};
                }

                let method = $('#method').val();
                let url =  $('#url').val();
                let body = $('#request_body').val();

                $("#response").show();                
                $.ajax({
                    type: method,
                    accept: "application/json",
                    contentType: "application/json; charset=utf-8",
                    url: url,
                    dataType:"json",                    
                    data: body,
                    headers: header,
                    success: function(data){
                        console.log(data);                        
                        $("#response").val(JSON.stringify(data));
                        $("#success_alert").show();
                        $("#fail_alert").hide();
                    },
                    error: function(data){
                        $("#response").val(data.responseText);
                        $("#success_alert").hide();
                        $("#fail_alert").show();
                    }
                });
            });
        });
    </script>
</body>
</html>