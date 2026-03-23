<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İzibiz API Docs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            background: #ffffff;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "SF Pro Text", sans-serif;
        }

        /* Minimal wrapper like standard Swagger page (no gradients) */
        .topbar {
            padding: 10px 14px;
            background: #1b1b1b;
            border-bottom: 1px solid #2a2a2a;
            color: #f5f5f5;
            font-size: 13px;
        }

        .topbar code {
            color: #a7f3d0;
        }

        /* Use swagger-ui built-in header (light look like default Swagger) */
        .swagger-ui .scheme-container { box-shadow: none; }
        .swagger-ui .opblock { border-radius: 4px; }
        .swagger-ui .btn { box-shadow: none; border-radius: 4px; }
        .swagger-ui .info .title { font-weight: 700; }

        /* Hide our custom bar; Swagger already has its own header */
        .topbar { display: none; }

        /* Force light theme look */
        .swagger-ui {
            background: #ffffff !important;
            color: #111827 !important;
        }

        .swagger-ui .wrapper,
        .swagger-ui .scheme-container,
        .swagger-ui .info,
        .swagger-ui .opblock,
        .swagger-ui .responses-wrapper,
        .swagger-ui .opblock-body,
        .swagger-ui .model-box,
        .swagger-ui .model,
        .swagger-ui table {
            background: #ffffff !important;
            color: #111827 !important;
        }

        .swagger-ui table tbody tr td,
        .swagger-ui table thead tr th {
            color: #111827 !important;
        }

        .swagger-ui .btn.execute {
            background: #22c55e !important;
            border-color: #22c55e !important;
            color: #ffffff !important;
        }

        .swagger-ui input[type="text"],
        .swagger-ui input[type="number"],
        .swagger-ui textarea,
        .swagger-ui select {
            background: #ffffff !important;
            color: #111827 !important;
            border-color: #e5e7eb !important;
        }

        .swagger-ui .parameter__name,
        .swagger-ui .parameter__type,
        .swagger-ui .response-col_status,
        .swagger-ui .response-col_description {
            color: #111827 !important;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css">
</head>
<body>
<div class="topbar">
    Swagger dokümanı: <code>{{ url('/izibiz_api/openapi.json') }}</code>
</div>
<div id="swagger-ui"></div>
<script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        SwaggerUIBundle({
            url: "{{ url('/izibiz_api/openapi.json') }}",
            dom_id: '#swagger-ui',
        });
    });
</script>
</body>
</html>

