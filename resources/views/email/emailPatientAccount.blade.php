<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
    <title>Document</title> 
</head>
<body> 
    <div style="padding: 10px">
        <h1> WELCOME TO GTC </h1>

        <h3> Credential</h3> 

        <div style=" margin-left: 5px">
            <strong> Username:  </strong> {{ $data['username'] }}<br />
            <strong> Password: </strong> {{ $data['password'] }}<br />
        </div>
 
        <p> Click this  
            <a href="https://globaltelemedicinecorp.net"> https://globaltelemedicinecorp.net </a> 
            link and use your credentials above to access your information.
        </p>

        <br />
        <p>
            <span> Thank You, </span><br />
            <span class="margin-left: 5px;"> Global Temelemedicine Corp </span>
        </p>
    <div>
</body>
</html>