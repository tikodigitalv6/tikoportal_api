<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tikoportal API</title>
    <style>
        :root {
            --bg: #0a2d6c;
            --text: #ffffff;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "SF Pro Text", sans-serif;
        }

        .wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .content {
            width: 100%;
            max-width: 900px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 14px;
        }

        .logo {
            width: 56px;
            height: 56px;
            object-fit: contain;
        }

        .title {
            font-size: 30px;
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: 0.01em;
        }

        .version {
            font-size: 16px;
            opacity: 0.95;
            font-weight: 600;
        }

        .sub {
            font-size: 13px;
            opacity: 0.85;
        }

        .links {
            margin-top: 10px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.35);
            color: var(--text);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .link:hover {
            background: rgba(255,255,255,0.08);
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="content">
        <img
            class="logo"
            src="https://tikoportal.com/assets/custom/tek_icon.png"
            alt="tikoportal logo"
        >
        <div class="title">tikoportal API</div>
        <div class="version">V 0.1.2</div>
        <div class="sub">TİKO Entegrasyon Servisleri</div>

       
    </div>
</div>
</body>
</html>

