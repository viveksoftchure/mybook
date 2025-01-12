<html>
    <head>
        <title>Invitation to Join <?= $data['project_title'] ?></title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 8px;
                background-color: #f9f9f9;
            }
            .header {
                text-align: center;
                background-color: #28a745;
                color: white;
                padding: 10px 0;
                border-radius: 8px 8px 0 0;
            }
            .content {
                padding: 20px;
            }
            .footer {
                text-align: center;
                margin-top: 20px;
                font-size: 0.9em;
                color: #666;
            }
            .btn {
                display: inline-block;
                margin: 10px 0;
                padding: 10px 20px;
                background-color: #28a745;
                color: white;
                text-decoration: none;
                border-radius: 4px;
            }
            .btn:hover {
                background-color: #218838;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Welcome to <?= $data['project_title'] ?></h1>
            </div>
            <div class='content'>