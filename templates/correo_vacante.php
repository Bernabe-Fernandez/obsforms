<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Solicitud de Vacante</title>
</head>

<body style="margin:0;padding:0;background-color:#f4f6f9;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f6f9;padding:30px 0;">
    <tr>
        <td align="center">

            <table width="650" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff;border-radius:8px;overflow:hidden;border:1px solid #e5e7eb;">

                <!-- Encabezado -->
                <tr>
                    <td style="background:#1f4e79;padding:25px 30px;">
                        <h2 style="margin:0;color:#ffffff;font-size:24px;font-weight:600;">
                            Nueva Solicitud de Vacante
                        </h2>
                    </td>
                </tr>

                <!-- Contenido -->
                <tr>
                    <td style="padding:30px;">

                        <p style="margin-top:0;color:#374151;font-size:14px;line-height:1.6;">
                            Se ha recibido una nueva solicitud de empleo a través del portal de reclutamiento.
                        </p>

                        <table width="100%" cellpadding="0" cellspacing="0" border="0">

                            <tr>
                                <td style="padding:12px 0;border-bottom:1px solid #e5e7eb;">
                                    <strong style="color:#111827;">Nombre:</strong><br>
                                    <span style="color:#4b5563;">
                                        <?= htmlspecialchars($nombre) ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 0;border-bottom:1px solid #e5e7eb;">
                                    <strong style="color:#111827;">Correo Electrónico:</strong><br>
                                    <span style="color:#4b5563;">
                                        <?= htmlspecialchars($correo) ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 0;border-bottom:1px solid #e5e7eb;">
                                    <strong style="color:#111827;">Teléfono:</strong><br>
                                    <span style="color:#4b5563;">
                                        <?= htmlspecialchars($telefono) ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 0;">
                                    <strong style="color:#111827;">Mensaje del Candidato:</strong>

                                    <div style="
                                        margin-top:10px;
                                        padding:15px;
                                        background:#f9fafb;
                                        border-left:4px solid #1f4e79;
                                        color:#4b5563;
                                        line-height:1.6;
                                    ">
                                        <?= nl2br(htmlspecialchars($mensaje)) ?>
                                    </div>
                                </td>
                            </tr>

                        </table>

                        <!-- Aviso de CV -->
                        <div style="
                            margin-top:25px;
                            padding:15px;
                            background:#eef6ff;
                            border:1px solid #cfe2ff;
                            border-radius:6px;
                            color:#1f4e79;
                            font-size:14px;
                        ">
                            <strong>Currículum Vitae:</strong> El archivo adjunto se encuentra incluido en este correo para su revisión.
                        </div>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="
                        background:#f9fafb;
                        border-top:1px solid #e5e7eb;
                        padding:18px 30px;
                        text-align:center;
                        color:#6b7280;
                        font-size:12px;
                    ">
                        Este correo fue generado automáticamente por el Sistema de Reclutamiento y Selección de Personal.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
