<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Platform API</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
            color: #1b5e20;
        }

        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            text-align: center;
            max-width: 550px;
        }

        .badge {
            display: inline-block;
            background: #e8f5e9;
            color: #2e7d32;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 16px;
            color: #4caf50;
            margin-bottom: 30px;
        }

        .desc {
            font-size: 14px;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-primary {
            background: #2e7d32;
            color: white;
        }

        .btn-primary:hover {
            background: #1b5e20;
        }

        .btn-outline {
            border: 1px solid #2e7d32;
            color: #2e7d32;
        }

        .btn-outline:hover {
            background: #2e7d32;
            color: white;
        }

        .github {
            margin-top: 25px;
            font-size: 13px;
        }

        .github a {
            color: #2e7d32;
            text-decoration: none;
            font-weight: 500;
        }

        .github a:hover {
            text-decoration: underline;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #81c784;
        }

        .api-box {
            margin-top: 25px;
            background: #f1f8e9;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }

        .api-label {
            font-size: 12px;
            color: #4caf50;
            margin-bottom: 6px;
        }

        .api-url {
            font-size: 14px;
            font-weight: 600;
            color: #1b5e20;
            word-break: break-all;
            margin-bottom: 10px;
        }

        .copy-btn {
            padding: 6px 12px;
            font-size: 12px;
            border: none;
            border-radius: 6px;
            background: #2e7d32;
            color: white;
            cursor: pointer;
        }

        .copy-btn:hover {
            background: #1b5e20;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card">

            <div class="badge">🚀 UMKM Platform API</div>

            <div class="title">
                Backend for UMKM Marketplace
            </div>

            <div class="subtitle">
                Built with Laravel • Secure • Ready for Integration
            </div>

            <div class="desc">
                RESTful API designed to support digital UMKM ecosystem, including store management, product handling,
                cart system, and order processing.
            </div>

            <div class="buttons">
                <a href="https://github.com/Rydhoff/umkm-platform-api" target="_blank" class="btn btn-outline">
                    View on GitHub
                </a>
            </div>

            <div class="api-box">
                <div class="api-label">Base API URL</div>
                <div style="display: inline;" class="api-url" id="apiUrl">
                    http://umkm-platform.my.id/api
                </div>
                <button style="display: inline; margin-left: 20px;" class="copy-btn" onclick="copyApi()">Copy</button>
            </div>

            <div class="footer">
                Designed for real-world use • Portfolio Project
            </div>

        </div>
    </div>
    <script>
        function copyApi() {
            const text = document.getElementById("apiUrl").innerText;
            navigator.clipboard.writeText(text);
            alert("API URL copied!");
        }
    </script>
</body>

</html>