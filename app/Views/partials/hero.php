<?php if (!defined('MASS_APP')) { http_response_code(404); exit; } ?>
	  <!-- ================= HERO CAROUSEL ================= -->
	  <section id="inicio" class="hero-section">
	    <div class="container">
	      <div id="heroCarousel" class="carousel slide hero-slider-container" data-bs-ride="carousel" data-bs-interval="4500">
	        <div class="carousel-indicators">
	          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
	          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
	          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
	        </div>

	        <div class="carousel-inner">
	          <div class="carousel-item active">
	            <a href="#seccion-catalogo">
	              <img src="assets/img/banner-hero-1.svg" class="hero-slide-img" alt="¡Los mejores precios en Mass de La Joya!">
	            </a>
	          </div>
	          <div class="carousel-item">
	            <a href="javascript:void(0)" onclick="openEncarteModal(1)">
	              <img src="assets/img/banner-hero-2.svg" class="hero-slide-img" alt="Encarte Digital Mass La Joya">
	            </a>
	          </div>
	          <div class="carousel-item">
	            <a href="javascript:void(0)" onclick="openCartDrawer()">
	              <img src="assets/img/banner-hero-3.svg" class="hero-slide-img" alt="Delivery Express en La Joya">
	            </a>
	          </div>
	        </div>

	        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
	          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
	          <span class="visually-hidden">Anterior</span>
	        </button>
	        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
	          <span class="carousel-control-next-icon" aria-hidden="true"></span>
	          <span class="visually-hidden">Siguiente</span>
	        </button>
	      </div>
	    </div>
	  </section>
