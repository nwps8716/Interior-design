<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>No Service</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  </head>

    <body>
        <div class="noservice-wrap">
            <!-- 標題 -->
            <div class="title-pic">
                <img src="/images/noservice_title.png"/>
            </div>
            <!-- IP -->
            <div class="ip-text">
                您的IP：{{ session()->get('ip') }}
                <p>IP所在區域不在我們服務範圍內，請與客服人員聯絡，謝謝！</p>
            </div>
        </div>
    </body>

  <style>
    body {
        color: #B2B2B2;
        font-size: 15px;
        text-align: center;
        background: #333;
        margin: 0;
    }
    html, body {
        width: 100%;
        height: 100%;
    }
    .noservice-wrap {
        width: 100%;
        height: 100%;
        position: relative;
    }
    .title-pic {
        position: absolute;
        top: 14%;
        width: 100%;
    }
    .title-pic img {
        max-width: 100%;
        padding-left: 16px;
    }
    .ip-text {
        position: absolute;
        top: 62%;
        width: 100%;
        line-height: 1.67em;
        padding: 0 16px;
        box-sizing: border-box;
    }
    .ip-text p {
        margin: 0;
        color: #FFC500;
    }
  </style>
</html>
