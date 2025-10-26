<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background: #F6FAFB;
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .button {
            background-color: #22D172;
            color: #fff;
            padding: 12px 24px;
            text-align: center;
            border-radius: 7px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            margin-top: 20px;
        }

        .content-box {
            background-color: #ffffff;
            padding: 48px 30px 40px;
            color: #000000;
        }

        .footer {
            text-align: center;
            color: #8B949F;
            font-size: 11px;
            padding: 24px 0 48px;
        }
    </style>
</head>

<body>
    <table align="center" width="600" cellpadding="0" cellspacing="0">
        <tr>
            <td style="text-align: center; padding: 30px 0 30px; font-size: 14px; color: #4C83EE;">
                {{ config('app.name') }}
            </td>
        </tr>

        <tr>
            <td class="content-box">
                <h2 style="margin: 0 0 20px;">Hi {{ $name ?? 'Client' }},</h2>

                <p style="margin-bottom: 10px; font-size: 14px;">
                    {!! $message ?? 'Thanks for sharing your feedback with us.' !!}
                </p>

                <p style="margin-top: 10px; font-size: 14px;">
                    We value your input and will use it to improve.
                </p>

                <p style="margin-top: 10px; font-size: 14px;">
                    For urgent matters, feel free to reply to this email.
                </p>

                <hr style="border: none; border-top: 1px solid #8B949F; margin: 30px 0;" />

                <p style="font-size: 14px;">
                    Thanks,<br>
                    <strong>{{ config('app.name') }}</strong>
                </p>
            </td>
        </tr>

        <tr>
            <td class="footer">
                {{ config('app.name') }}<br />
                {{ env('APP_ADDRESS', '123 Digital Street, Tech City') }}
            </td>
        </tr>
    </table>
</body>

</html>