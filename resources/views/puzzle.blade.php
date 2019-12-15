<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1">

    <title>Zweedse Puzzel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/app.css')}}"/>
</head>
<body>
<div id="app">
    <div class="columns is-gapless">
        <div class="column"></div>
        <div class="column">
            <grid
                :width="{{ $gridWidth }}"
                :height="{{ $gridHeight }}"
                :cells="{{ $cells }}"
                :clues="{{ $clues }}"
                :words="{{ $words }}"
                keyword="{{ $keyword }}"
            />
        </div>
        <div class="column"></div>
    </div>
    <div class="columns is-gapless">
        <div class="column"></div>
        <div class="column">
            <participant-form/>
        </div>
        <div class="column"></div>
    </div>
</div>
<script src="{{ asset('js/app.js')}}"></script>
</body>
</html>
