<!DOCTYPE html>
<html <?php language_attributes(); ?>
<head>
    <meta charset="<?php bloginfo(' charset ') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevFolio — Портфолио разработчика</title>

    <?php wp_head(); ?>
</head>
<body>
<!-- Animated Background -->
<div class="bg-animation">
    <div class="orb"></div>
    <div class="orb"></div>
    <div class="orb"></div>
    <div class="orb"></div>
</div>

<!-- Navigation -->
<nav>
    <div class="logo">&lt;/&gt; DevFolio</div>
    <ul class="nav-links" id="navLinks">
        <li><a href="#home">Главная</a></li>
        <li><a href="#projects">Проекты</a></li>
        <li><a href="#skills">Навыки</a></li>
        <li><a href="#experience">Опыт</a></li>
        <li><a href="#contact">Контакты</a></li>
    </ul>
    <a href="#contact" class="nav-cta">Связаться</a>
    <div class="burger" id="burger">
        <span></span>
        <span></span>
        <span></span>
    </div>
</nav>
