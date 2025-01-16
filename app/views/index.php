<?php
include_once __DIR__ . '/../models/visitorlogs.php';
$visitorLogs = new VisitorLogs();
$count = count($visitorLogs->getVisitorLogs())
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./assets/favicon.ico" type="image/x-icon">
    <title>Personal AI</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        html{
            scroll-behavior: smooth;
        }
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background-color: #2c2c2c;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #d900a3;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #a80070;
            border-radius: 5px;
        }

        body {
            background-color: #1a1a1a;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            background-color: #2c2c2c;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background-color: #3a3a3a;
            border-bottom: none;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .form-control {
            background-color: #444444;
            border: none;
        }

        .form-control:focus {
            background-color: #555555;
            box-shadow: none;
        }

        .idk-button-blue {
            transition: background-color 0.8s;
            border-radius: 20px;
            font-weight: 700;
            background-color: #6082B6;
            border: none;
            max-width: 100px;
            max-height: 30px;
            font-size: 0.8rem;
        }

        .idk-button-blue:hover {
            border: none;
            background-color: #1434A4;
        }

        .idk-button-blue:focus {
            background-color: #6082B6;
        }

        .idk-btn-yellow {
            transition: background-color 0.8s;
            border-radius: 20px;
            font-weight: 700;
            background-color: #E3963E;
            border: none;
            max-width: 100px;
            max-height: 30px;
            font-size: 0.8rem;
        }

        .idk-btn-yellow:hover {
            border: none;
            background-color: #CD7F32;
        }

        .idk-btn-yellow:focus {
            background-color: #E3963E;
        }

        .idk-btn {
            transition: background-color 0.8s;
            border-radius: 20px;
            font-weight: 700;
            background-color: #d900a3;
            border: none;
            max-width: 100px;
            max-height: 30px;
            font-size: 0.8rem;
        }

        .idk-btn:hover {
            border: none;
            background-color: #a80070;
        }

        .idk-btn:focus {
            background-color: #d900a3;
        }

        #response-container {
            overflow-y: auto;
            max-height: 50vh;
            font-family: 'Consolas', 'Menlo', 'Monaco', 'Lucida Console', 'Liberation Mono', 'DejaVu Sans Mono', 'Bitstream Vera Sans Mono', 'Courier New', 'monospace', 'serif';
        }

        .response {
            min-height: 2rem;
            font-family: 'Segoe UI', 'Tahoma', 'Geneva', 'Verdana', 'sans-serif';
        }

        footer {
            color: #777;
        }

        pre {
            background-color: #000000;
            padding: 10px;
            border-radius: 5px;
            font-family: 'Courier New', Courier, monospace;
        }

        #btn-back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 99;
            opacity: 0;
            animation: fadeIn 0.5s ease-in-out forwards;
            animation-play-state: paused;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12 col-lg-12 col-sm-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Personal AI</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <label for="user_prompt" class="text-center" id="label"></label>
                            <textarea type="text" class="form-control mt-2" cols="30" rows="1" id="user_prompt"
                                name="user_prompt"></textarea>
                        </div>
                        <div class="form-group mt-2 mb-2 d-flex gap-2 flex-row align-items-center">
                            <label for="lang" style="font-size: 0.8rem;">AI Response Language?</label>
                            <select class="form-select" style="font-size: 0.8rem; max-width: 8rem; max-height: 2rem"
                                name="lang">
                                <option value="en">English</option>
                                <option value="id">Indonesia</option>
                            </select>
                        </div>
                        <div>
                            <small class="text-muted">Use the keyboard shortcut 'Shift + Enter' to start a new line in the input text.</small>
                        </div>
                        <div class="button-container d-flex flex-row justify-content-end gap-2 mt-2">
                            <button type="button" id="reset" class="idk-button-blue form-control w-25">Reset <i
                                    class="fa fa-arrows-rotate"></i></button>
                            <button type="button" id="cancel" class="idk-btn-yellow form-control w-25"
                                style="display: none;">Cancel <i class="bi-stop-fill"></i></button>
                            <button type="button" id="send" class="idk-btn form-control w-25">Send <i
                                    class="bi-send"></i></button>
                        </div>
                        <div id="response-container" class="mt-2" style="display: none;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <small>
            <p class="text-start text-muted mt-2">Total visitors: <?= $count ?></p>
        </small>
    </div>
    <footer class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>&copy; 2024 Personal AI by Valentino Hariyanto</p>
                </div>
            </div>
        </div>
    </footer>
    <button type="button" class="idk-btn btn-floating btn-lg" id="btn-back-to-top">
        <i class="bi bi-arrow-up"></i>
    </button>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/2.1.0/showdown.min.js"></script>
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="./app/views/js/script.js"></script>
</body>

</html>