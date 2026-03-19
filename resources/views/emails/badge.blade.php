<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
</head>
<body style="margin:0; padding:0; background-color:#f4f4f7; font-family: Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f7; padding:30px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.08);">

                    {{-- Header con colores de la bandera de Francia --}}
                    <tr>
                        <td style="height:6px; background: linear-gradient(to right, #002395 33%, #ffffff 33%, #ffffff 66%, #ED2939 66%);"></td>
                    </tr>
                    <tr>
                        <td style="padding:30px 40px 20px; text-align:center;">
                            <h1 style="margin:0; color:#002395; font-size:24px;">Evento Francofonía</h1>
                            <p style="margin:8px 0 0; color:#888; font-size:14px;">Tu gafete digital</p>
                        </td>
                    </tr>

                    {{-- Cuerpo --}}
                    <tr>
                        <td style="padding:10px 40px 30px;">
                            <p style="color:#333; font-size:16px; line-height:1.6;">
                                ¡Hola <strong>{{ $participant->nombre }}</strong>! 👋
                            </p>
                            <p style="color:#333; font-size:15px; line-height:1.6;">
                                Te enviamos tu gafete del <strong>Evento de la Francofonía</strong> en formato PDF.
                                Encontrarás adjunto tu código QR que deberás presentar en cada estand.
                            </p>

                            {{-- Datos de acceso --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0f4ff; border-radius:6px; margin:20px 0;">
                                <tr>
                                    <td style="padding:20px;">
                                        <p style="margin:0 0 10px; color:#002395; font-weight:bold; font-size:14px;">
                                            📋 Tus datos de acceso al dashboard:
                                        </p>
                                        <p style="margin:0; color:#333; font-size:14px; line-height:1.8;">
                                            <strong>Código QR:</strong> {{ $participant->qr_code }}<br>
                                            <strong>Correo:</strong> {{ $loginEmail }}<br>
                                            <strong>Contraseña:</strong> {{ $participant->qr_code }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="color:#666; font-size:14px; line-height:1.6;">
                                Puedes imprimir el PDF adjunto o mostrarlo desde tu celular en cada estand.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 40px; background-color:#f8f8fa; border-top:1px solid #eee; text-align:center;">
                            <p style="margin:0; color:#aaa; font-size:12px;">
                                Este correo fue enviado automáticamente. No responder a esta dirección.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
