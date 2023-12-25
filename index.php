<!DOCTYPE html>
<!-- Import latest bootstrap and jquery -->
<html>
<head>
    <meta charset="utf-8"/>
    <title>NOTES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>

<?php
require_once("functions.php");
require_once("config.php");
?>

<body data-bs-theme="dark">

<?php include_once("nav.php"); ?>

<!-- NOTE: CONTAINER -->
<div class="container" style="margin-top:15px">

<?php
require_once("formhandler.php");

# Classes
$addCardClass       = "border-success";
$addCardTitleClass  = "bg-success bg-opacity-50 text-white";
$editCardClass      = "border-warning";
$editCardTitleClass = "bg-warning bg-opacity-50 text-white";
$notesCardClass     = "border-info";
$notesCardTitleClass= "bg-info bg-opacity-50 text-white";
?>

<div id="response"></div>

<?php require_once("pages/home.php"); ?>

<br>

<?php require_once("pages/addNote.php"); ?>

<br>

<?php require_once("pages/allNotes.php"); ?>

</div>



</body>

<?php require_once("js.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

</html>