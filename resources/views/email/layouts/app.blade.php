<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to Car Tub</title>
    <style>
        @media screen {
            @font-face {
                font-family: "Fira Sans";
                font-style: normal;
                font-weight: 400;
                src: local("Fira Sans Regular"), local("FiraSans-Regular"), url(https://fonts.gstatic.com/s/firasans/v8/va9E4kDNxMZdWfMOD5Vvl4jLazX3dA.woff2) format("woff2");
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
            }

            @font-face {
                font-family: "Fira Sans";
                font-style: normal;
                font-weight: 400;
                src: local("Fira Sans Regular"), local("FiraSans-Regular"), url(https://fonts.gstatic.com/s/firasans/v8/va9E4kDNxMZdWfMOD5Vvk4jLazX3dGTP.woff2) format("woff2");
                unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
            }

            @font-face {
                font-family: "Fira Sans";
                font-style: normal;
                font-weight: 500;
                src: local("Fira Sans Medium"), local("FiraSans-Medium"), url(https://fonts.gstatic.com/s/firasans/v8/va9B4kDNxMZdWfMOD5VnZKveRhf6Xl7Glw.woff2) format("woff2");
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
            }

            @font-face {
                font-family: "Fira Sans";
                font-style: normal;
                font-weight: 500;
                src: local("Fira Sans Medium"), local("FiraSans-Medium"), url(https://fonts.gstatic.com/s/firasans/v8/va9B4kDNxMZdWfMOD5VnZKveQhf6Xl7Gl3LX.woff2) format("woff2");
                unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
            }

            @font-face {
                font-family: "Fira Sans";
                font-style: normal;
                font-weight: 700;
                src: local("Fira Sans Bold"), local("FiraSans-Bold"), url(https://fonts.gstatic.com/s/firasans/v8/va9B4kDNxMZdWfMOD5VnLK3eRhf6Xl7Glw.woff2) format("woff2");
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
            }

            @font-face {
                font-family: "Fira Sans";
                font-style: normal;
                font-weight: 700;
                src: local("Fira Sans Bold"), local("FiraSans-Bold"), url(https://fonts.gstatic.com/s/firasans/v8/va9B4kDNxMZdWfMOD5VnLK3eQhf6Xl7Gl3LX.woff2) format("woff2");
                unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
            }

            @font-face {
                font-family: "Fira Sans";
                font-style: normal;
                font-weight: 800;
                src: local("Fira Sans ExtraBold"), local("FiraSans-ExtraBold"), url(https://fonts.gstatic.com/s/firasans/v8/va9B4kDNxMZdWfMOD5VnMK7eRhf6Xl7Glw.woff2) format("woff2");
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
            }

            @font-face {
                font-family: "Fira Sans";
                font-style: normal;
                font-weight: 800;
                src: local("Fira Sans ExtraBold"), local("FiraSans-ExtraBold"), url(https://fonts.gstatic.com/s/firasans/v8/va9B4kDNxMZdWfMOD5VnMK7eQhf6Xl7Gl3LX.woff2) format("woff2");
                unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
            }
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            background-color: #f4f4f4;
            font-family: 'Fira Sans', Helvetica, Arial, sans-serif;
            font-size: 16px;
            width: 100% !important;
            margin: 0 !important;
            padding: 0;
            line-height: 1.5;
        }

        a {
            box-shadow: none;
            color: #00bcd4;
        }

        .container {
            /* width: 100%; */
            padding: 20px;
            background-color: #f4f4f4;
        }

        .email-wrapper {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .header {
            background-color: #00bcd4;
            color: white;
            text-align: center;
            padding: 20px 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px 30px;
        }

        .content h2 {
            color: #333333;
        }

        .details {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 16px;
        }

        .details p {
            margin: 8px 0;
        }

        .cta-button-wrapper {
            text-align: center;
            margin-top: 10px;
        }

        .cta-button {
            display: inline-block;
            padding: 12px 25px;
            margin: 8px 5px;
            background-color: #00bcd4;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #888888;
        }

        @media only screen and (max-width: 600px) {
            .content {
                padding: 20px 15px;
            }

            .header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="email-wrapper">
            <div class="header">
                @yield('header')
            </div>
            <div class="content">
                @yield('content')
            </div>
            <div class="footer">
                @include('email.layouts.footer')
            </div>
        </div>
    </div>
</body>
</html>