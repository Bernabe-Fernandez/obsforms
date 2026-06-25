<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f4f4f4;
                margin: 0;
                padding: 20px;
            }

            .container {
                max-width: 600px;
                margin: auto;
                background: #ffffff;
                border-radius: 8px;
                padding: 20px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }

            h2 {
                color: #2d87f0;
                margin-bottom: 20px;
            }

            .field {
                margin-bottom: 12px;
                font-size: 14px;
            }

            .label {
                font-weight: bold;
                color: #333;
            }

            .value {
                color: #555;
            }

            .message-box {
                background: #f9f9f9;
                padding: 10px;
                border-radius: 6px;
                margin-top: 5px;
            }

            .footer {
                margin-top: 25px;
                font-size: 12px;
                color: #888;
                text-align: center;
            }

            .badge {
                display: inline-block;
                padding: 4px 10px;
                background: #2d87f0;
                color: white;
                font-size: 12px;
                border-radius: 4px;
            }
        </style>
    </head>

    <body>
    <div class="container">

        <h2>Nueva solicitud de vacante</h2>

        <div class="field">
            <span class="label">Nombre:</span>
            <span class="value"><?= htmlspecialchars($nombre) ?></span>
        </div>

        <div class="field">
            <span class="label">Correo:</span>
            <span class="value"><?= htmlspecialchars($correo) ?></span>
        </div>

        <div class="field">
            <span class="label">Teléfono:</span>
            <span class="value"><?= htmlspecialchars($telefono) ?></span>
        </div>

        <div class="field">
            <span class="label">Mensaje:</span>
            <div class="message-box">
                <?= nl2br(htmlspecialchars($mensaje)) ?>
            </div>
        </div>

        <div class="field">
            <span class="badge">Curriculum adjunto en este correo</span>
        </div>

        <div class="footer">
            Sistema automático de reclutamiento - RRHH
        </div>

    </div>
    </body>
</html>