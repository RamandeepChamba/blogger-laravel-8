<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Blogger8 | Mail</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"></link>

        <!-- Styles -->
        <style>
            html {
                font-size: 16px;
            }
            body {
                font-family: 'Raleway', sans-serif !important;
                margin: 0;
                padding: 0;
            }

            .mail-container {
                color: #212121;
                background: #ffb333;
                min-height: 100vh;
                position: relative;
            }

            @media screen and (min-width: 768px) {
                .mail-container {
                    background: #f8f8f8;
                }
            }

            .mail-header {
                padding: 8rem 0;
            }

            @media screen and (min-height: 700px) {
                .mail-header {
                    padding: 10rem 0;
                }
            }

            @media screen and (min-width: 768px) {
                .mail-header {
                    background: #ffb333;
                }
            }

            .mail-content {
                position: relative;
                top: -8rem;
                background: #fff;
                padding: 4rem 3rem;
                text-align: center;
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
                margin: 0 auto;
            }

            @media screen and (max-width: 420px) {
                .mail-content {
                    padding: 3rem 1rem;
                }
            }

            @media screen and (min-width: 768px) {
                .mail-content {
                    width: 80%;
                    padding: 6rem 4rem;
                }
            }

            @media screen and (min-width: 1024px) {
                .mail-content {
                    padding: 8rem 4rem;
                }
            }

            .mail-heading {
                font-size: 2rem;
                font-weight: 500;
            }

            @media screen and (min-width: 768px) {
                .mail-heading {
                    font-size: 2.5rem;
                }
            }

            @media screen and (min-width: 1024px) {
                .mail-heading {
                    font-size: 3rem;
                }
            }

            .mail-desc {
                margin: 2rem 0;
            }

            @media screen and (min-width: 768px) {
                .mail-desc {
                    margin: 3rem 0;
                    font-size: 1.2rem;
                }
            }

            @media screen and (min-width: 1024px) {
                .mail-desc {
                    font-size: 1.5rem;
                }
            }

            .mail-btn {
                display: block;
                color: #fff;
                padding: .8rem;
                font-size: 1.2rem;
                background: #ffb333;
                border-radius: 4px;
                margin: 0 auto;
                transition: all .4s;
                text-decoration: none;
            }

            @media screen and (min-width: 420px) {
                .mail-btn {
                    width: 50%;
                }
            }

            @media screen and (min-width: 768px) {
                .mail-btn {
                    width: 30%;
                    font-size: 1.4rem;
                }

                .mail-btn:hover {
                    background: #FFA000;
                    transform: scale(1.1);
                }

                .mail-btn:active {
                    background: #FFA000;
                    transform: scale(0.9);
                }
            }

            @media screen and (min-width: 1024px) {
                .mail-btn {
                    font-size: 1.7rem;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="mail-container">
            <div class="mail-header"></div>
            <div class="mail-content">
                <h1 class="mail-heading">Hello {{ $user->name }}!</h1>
                <p class="mail-desc">Welcome to blogger, feel free to read other user blogs or create your own.</p>
                <a href="#" class="mail-btn">Start here</a>
            </div>
        </div>
    </body>
</html>